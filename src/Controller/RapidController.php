<?php
    namespace App\Controller;

    use App\Controller\AppController;
    use Cake\Event\Event;
    use Cake\Controller\Controller;
    use Cake\ORM\TableRegistry;
    use Cake\Network\Email\Email;
    use Cake\Controller\Component\CookieComponent;
    use Cake\Datasource\ConnectionManager;

    class RapidController extends AppController {
        public function initialize() {
            parent::initialize();
            $this->loadComponent('Mailer');
            $this->loadComponent('Document');
        }

        public function index() {
            $this->set('uid', '0');
            $this->set('id', '0');

            $profiles = TableRegistry::get('Profiles');

            $_POST['created'] = date('Y-m-d');
            //var_dump($profile);die();

            if ($this->request->is('post')) {

                if (isset($_POST['profile_type']) && $_POST['profile_type'] == 1) {
                    $_POST['admin'] = 1;
                }

                $_POST['dob'] = $_POST['dob'];//what?
                if ($_POST['title'] == "Mr.") {
                    $_POST["gender"] = "Male";
                } else {
                    $_POST["gender"] = "Female";
                }

                $profile = $profiles->newEntity($_POST);

                $profilesToEmail = array();

                if ($profiles->save($profile)) {
                    if (!$_POST['username']) {//if no username, make one
                        $profile_id = $profile->id;
                        $_POST['username'] = "Driver_" . $profile_id;
                        $this->Update1Column("profiles", "email", $_POST['email'], "username", $_POST['username']);
                    }

                    if ($_POST['client_ids']) {
                        $client_id = explode(",", $_POST['client_ids']);
                        foreach ($client_id as $cid) {//asign to clients
                            $query = TableRegistry::get('clients');
                            $q = $query->find()->where(['id' => $cid])->first();
                            $profile_id = $q->profile_id;
                            $pros = explode(",", $profile_id);
                            $profilesToEmail = array_merge($profilesToEmail, $pros);

                            $p_ids = "";

                            array_push($pros, $profile->id);
                            $pro_id = array_unique($pros);

                            foreach ($pro_id as $k => $p) {
                                if (count($pro_id) == $k + 1) {
                                    $p_ids .= $p;
                                } else {
                                    $p_ids .= $p . ",";
                                }
                            }

                            $query->query()->update()->set(['profile_id' => $p_ids])
                                ->where(['id' => $cid])
                                ->execute();
                        }
                    }
                    //die();
                    $blocks = TableRegistry::get('Blocks');
                    $query2 = $blocks->query();
                    $query2->insert(['user_id'])
                        ->values(['user_id' => $profile->id])
                        ->execute();
                    $side = TableRegistry::get('Sidebar');
                    $query2 = $side->query();
                    $create_que = $query2->insert(['user_id'])
                        ->values(['user_id' => $profile->id])
                        ->execute();

                    $this->emaileveryone($profilesToEmail, $profile->id, $_POST);
                    return $this->redirect('/application/register.php?client=' . $_POST['client_ids'] . '&username=' . $_POST['username'] . '&userid=' . $profile->id);
                } else {
                    return $this->redirect('/application/register.php?client=' . $_POST['client_ids'] . '&error=' . $_POST['username']);
                }
            }
            die();
        }

        function days($type = "", $ClientID = 26) {
            $train = "";
            if (isset($_POST)) {
                $_POST['created'] = date('Y-m-d');
                if ($type == '60') {
                    if (isset($_POST['train']))
                        foreach ($_POST['train'] as $k => $values) {
                            if (($k + 1) == count($_POST['train'])) {
                                $train .= $values;
                            } else
                                $train .= $values . ",";
                        }
                    $_POST['jst13'] = $train;
                }
                $path = $this->Document->getUrl();
                $modal = TableRegistry::get($type . 'days');
                $data = $modal->newEntity($_POST);
                $settings = TableRegistry::get('settings');
                $setting = $settings->find()->first();
                if ($modal->save($data)) {
                    $from = array('info@' . $path => $setting->mee);
                    $pro = TableRegistry::get('profiles')->find()->where(['id' => $_POST['profile_id']])->first();
                    $emails = $this->getallrecruiters($ClientID);
                    $path = LOGIN . "application/" . $type . "days.php?p_id=" . $_POST['profile_id'] . "&form_id=" . $data->id ;
                    $site = TableRegistry::get('settings')->find()->first()->mee;//, "site" => $site

                    foreach($emails as $e) {
                        $this->Mailer->handleevent("surveycomplete", array("email" => $e, "username" => $pro->username, "type" => $type, "path" => $path, "site" => $site));
                    }
                    $this->Mailer->handleevent("surveycomplete", array("email" => "super", "username" => $pro->username, "type" => $type, "path" => $path, "site" => $site));
                    return $this->redirect('/application/' . $type . "days.php?msg=success");
                } else
                    return $this->redirect('/application/' . $type . "days.php?msg=error");

            }
            die();
        }

        function getallrecruiters($cid) {
            $email = array();
            $modal = TableRegistry::get('clients')->find()->where(['id'=>$cid])->first();
            $pros = $modal->profile_id;
            $profiles = TableRegistry::get('profiles')->find('all')->where(['id in('.$pros.')']);
            foreach($profiles as $p) {
                if($p->profile_type == '2' && $p->email != "") {
                    array_push($email, $p->email);
                }
            }
            return $email;
        }

        public function emaileveryone($profilesToEmail, $ProfileID, $POST) {
            //   $settings = $this->Settings->get_settings();
            $emails = array();
            foreach ($profilesToEmail as $Profile) {
                $Profile = $this->getTableByAnyKey("sidebar", "user_id", $Profile);
                if (is_object($Profile) && $Profile->email_profile == 1) {
                    $emails[] = $Profile->email;
                }
            }
            $path = LOGIN . "profiles/view/" . $ProfileID;
            $this->Mailer->handleevent("profilecreated", array("username" => $_POST['username'],"email" => $emails, "path" =>$path, "createdby" => "Application", "type" => "Applicant", "password" => "[Blank]", "id" =>  $ProfileID ));
        }

        public function Update1Column($Table, $PrimaryKey, $PrimaryValue, $Key, $Value) {
            TableRegistry::get($Table)->query()->update()->set([$Key => $Value])->where([$PrimaryKey => $PrimaryValue])->execute();
        }

        public function getTableByAnyKey($Table, $Key, $Value) {
            return TableRegistry::get($Table)->find('all', array('conditions' => array($Key => $Value)))->first();
        }
        
        function checkcron($cid,$date,$pid) {
            $client_crons = TableRegistry::get('client_crons');
            $cnt = $client_crons->find('all')->where(['client_id'=>$cid,'orders_sent'=>'1','cron_date'=>$date,'profile_id'=>$pid])->count();
            return $cnt;
        }

        function cron() {
            $today = date('Y-m-d');
            $msg = "";
            $clients = TableRegistry::get('clients')->find('all')->where(['requalify' => '1','requalify_product <> ""']);
            $marr = array();
            $a = TableRegistry::get('profiles')->find()->where(['super' => '1'])->first();
            $admin_email = $a->email;
            $user_count = 0;

            foreach ($clients as $c) {
                $pro = '';
                $msg .= "<br/><br/><strong>Client:</strong><br/>";
                $msg .= $c->company_name;
                $msg .= "<br/>";
                $message = "Your drivers have been re-qualified." . "</br>";
                $message .= "Re-qualified Date:" . $today . "</br>";
                $em_names = '';
                $pronames = array();
                $em = array();

                if ($c->requalify_re == '0') {
                    $date = $c->requalify_date;
                }
                
                $frequency = $c->requalify_frequency;
                $forms = $c->requalify_product;
                $fname = explode(',',$forms);
                $new_form = "";
                foreach($fname as $n) {
                    if($n=='1') {
                        $nam = '(MVR)';
                    }elseif($n=='14') {
                        $nam = '(CVOR)';
                    }elseif($n=='72') {
                        $nam = '(DL)';
                    }
                    $new_form .=$nam.",";

                }
                $new_form = substr($new_form,0,strlen($new_form)-1);
                $msg .= "Selected Forms:" . $new_form . "<br/>";
                //$nxt_sec = strtotime($today)+($frequency*24*60*60*30);
                //$nxt_date = date('Y-m-d', strtotime('+'.$frequency.' months'));
                $epired_profile = '';
                $p_type = '';
                $p_name = "";
                $emails = '';
                $profile_type = TableRegistry::get("profile_types")->find('all')->where(['placesorders' => 1]);
                foreach ($profile_type as $ty) {
                    $p_type .= $ty->id . ",";
                }
                $p_types = substr($p_type, 0, strlen($p_type) - 1);
                $users = explode(',', $c->profile_id);
                $rec1 = array();

                $crons = TableRegistry::get('client_crons');
                $profile = TableRegistry::get('profiles')->find('all')->where(['id IN(' . $c->profile_id . ')', 'profile_type IN(' . $p_types . ')', 'is_hired' => '1', 'requalify' => '1'])->order('created_by');
                //debug($profile);die();
                $temp = '';
                foreach ($profile as $p) {

                    if($p->expiry_date=='') {
                        $p->expiry_date = '0000-00-00';
                    }
                    //echo $p->expiry_date."<br/>" ;
                    //echo strtotime($p->expiry_date)."<br/>".time();
                    if(($p->profile_type=='5'||$p->profile_type=='7'||$p->profile_type=='8')) {
                        //echo $p->id."</br>";
                        //echo $p->created_by;
                        if(strtotime($p->expiry_date) < strtotime($today)) {
                             $epired_profile .= $p->username.",";
                            
                        } 
                        else 
                        {
                           
                            if ($c->requalify_re == '1') {
                                 $date = $p->hired_date;
                                 if(strtotime($date) <= strtotime($today)) {
                                    
                                    //$date =  $this->getnextdate($date,$frequency); 
                                    if(strtotime($date) == strtotime($today)) {
                                        if($this->checkcron($c->id, $date, $p->id)) {
                                            $date = $this->getnextdate($date, $frequency);
                                        }
                                    } else {
                                        $date =  $this->getnextdate($date,$frequency);
                                          if(strtotime($date) == strtotime($today)) {
                                              if ($this->checkcron($c->id, $date, $p->id)) {
                                                  $date = $this->getnextdate($date, $frequency);
                                              }
                                          }
                                    }
                                } else {
                                     continue;
                                 }
                            }
                            //echo $date;die();
                            $nxt_date = $this->getnextdate($date, $frequency);

                            if ($today == $date || $today == $nxt_date ) {

                                $cron_p = $crons->find()->where(['profile_id' => $p->id, 'client_id' => $c->id, 'orders_sent' => '1', 'cron_date' => $today])->first();
                                if (count($cron_p) == 0) {
                                    $user_count++;
                                    $pro .= $p->id . ",";
                                    if ($temp == $p->created_by) {
                                        $p_name .= $p->username . ",";
                                    }else {
                                        if ($temp != "") {

                                            array_push($pronames, $p_name);
                                            $p_name = "";
                                        }
                                        $temp = $p->created_by;
                                        $p_name .= $p->username . ",";
                                        //echo "<br/>";
                                    }
                                }

                                $rec = TableRegistry::get('profiles')->find()->where(['id' => $p->created_by])->first();
                                if ($rec->email) {
                                    $rec_email = $rec->email;
                                    array_push($em, $rec->email);
                                }

                            }
                        }
                    }
                }
                //die();
                array_push($pronames, $p_name);
                //var_dump($pronames);
                $em = array_unique($em);
                $i = 0;
                $username = substr($pronames[$i], 0, strlen($pronames[$i]) - 1);
                $mesg = "Selected Forms:" . $new_form . "<br/>";    
                $mesg .= "Profile(s): '" . substr($pronames[$i], 0, strlen($pronames[$i]) - 1) . "' have been re-qualified on " . $today . " for client: " . $c->company_name . ".<br /><br />Click <a href='" . LOGIN . "'>here</a> to login to view the reports.<br /><br />Regards,<br />The MEE Team";
                $footer="";
                //echo $epired_profile; die();
                if($epired_profile!="") {
                    $mesg .= "<br/>Expired Profiles:" . $epired_profile;
                }

                foreach ($em as $e) {
                   // $this->Mailer->handleevent("requalification", array("email" => $e, "company_name" => $c->company_name, "username" => $username, "expired" => $epired_profile));
                    //$this->Mailer->sendEmail("", $e, "Driver Re-qualified (" . $c->company_name . ")", $mesg);
                    $emails .= $e . ",";
                    $i++;
                }
                unset($em);
                $epired_profile = "";
                $p_newname = '';
                foreach ($pronames as $p) {
                    $p_newname .= $p . ",";
                }
                //die();
                $em_names = substr($em_names, 0, strlen($em_names) - 1);
                $emails = substr($emails, 0, strlen($emails) - 1);
                $pro = substr($pro, 0, strlen($pro) - 1);
                $p_name = substr($p_newname, 0, strlen($p_newname) - 1);
                //$this->bulksubmit($pro,$forms,$c->id);
                $msg .= "Profiles:" . str_replace(',,', ',', $p_name) . "<br/>";
                $msg .= "Emails Sent to:" . $emails . "<br/>";
                $message .= "Recruited Profiles:" . $em_names . "</br>";

                if ($pro != "") {
                    $drivers = explode(',', $pro);
                    //$forms = $_POST['forms'];
                    $arr['forms'] = $forms;
                    $arr['order_type'] = 'REQ';
                    $arr['draft'] = 0;
                    $arr['title'] = 'order_' . date('Y-m-d H:i:s');

                    $arr['client_id'] = $c->id;
                    $arr['created'] = date('Y-m-d H:i:s');
                    //$arr['division'] = $_POST['division'];
                    //$arr['user_id'] = $this->request->session()->read('Profile.id');
                    $arr['driver'] = '';
                    $arr['order_id'] = '';

                    foreach ($drivers as $driver) {

                        $arr['uploaded_for'] = $driver;
                        $ord = TableRegistry::get('orders');
                        $doc = $ord->newEntity($arr);
                        $ord->save($doc);

                        //$this->webservice('BUL', $arr['forms'], $arr['user_id'], $doc->id);
                        if ($arr['driver']) {
                            $arr['driver'] = $arr['driver'] . ',' . $driver;
                        } else {
                            $arr['driver'] = $driver;
                        }
                        if ($arr['order_id']) {
                            $arr['order_id'] = $arr['order_id'] . ',' . $doc->id;
                        } else {
                            $arr['order_id'] = $doc->id;
                        }

                        $cron['order_id'] = $doc->id;
                        $cron['profile_id'] = $driver;
                        $cron['orders_sent'] = '1';
                        $cron['cron_date'] = $today;
                        $cron['client_id'] = $c->id;

                        $s = $crons->newEntity($cron);
                        $crons->save($s);
                        unset($cron);

                    }
                    array_push($marr, $arr);
                }

                unset($arr);
                unset($rec);

            }

            if ($user_count != 0) {
               // $this->Mailer->sendEmail("", $admin_email, 'Driver Re-qualification Cron', "Cron date:" . $today . "</br>" . $msg);
            }

            $this->set('profiles', $user_count);
            $this->set('arrs', $marr);
            $this->set('msg', $msg);
            $this->set('message', $message);
            
        }

        function getnextdate($date, $frequency) {
            $today = date('Y-m-d');
            $nxt_date = date('Y-m-d', strtotime($date)+ $frequency*30*24*60*60);
            if (strtotime($nxt_date) < strtotime($today)) {
                $d = $this->getnextdate($nxt_date, $frequency);
            } else {
                $d = $nxt_date;
            }
            return $d;
        }

        function getcronProfiles($c_profile) {
            $p_type = "";
            $profile_type = TableRegistry::get("profile_types")->find('all')->where(['placesorders' => 1]);
            foreach ($profile_type as $ty) {
                $p_type .= $ty->id . ",";
            }
            $p_types = substr($p_type, 0, strlen($p_type) - 1);
            $profiles = TableRegistry::get('profiles')->find('all')->where(['id IN(' . $c_profile . ')', 'profile_type IN(' . $p_types . ')']);
            $this->response->body($profiles);
            return $this->response;
        }

        

        function application_employment($Client_ID = 26) {
            if(isset($_POST)) {
                $profile['fname']=$_POST['fname'];
                $profile['lname']=$_POST['lname'];
                $profile['mname']=$_POST['mname'];
                $profile['email']=$_POST['email'];
                $profile['phone'] = $_POST['code']."-".$_POST['phone'];
                $profile['street'] = $_POST['street'];
                $profile['country'] = $_POST['country'];
                $profile['province'] = $_POST['province'];
                $profile['city'] = $_POST['city'];
                $profile['dob'] = $_POST['doby']."-".$_POST['dobm']."-".$_POST['dobd'];
                $profile['placeofbirth'] = $_POST['placeofbirth'];
                $profile['gender'] = $_POST['gender'];
                $profile['title'] = $_POST['title'];
                $profile['postal'] = $_POST['postal'];
                $profile['hear'] = $_POST['hear'];
                $profile["profile_type"] = 0;
                $profile["driver_license_no"] = $_POST["driver_license_no"];
                $profile["driver_province"] = $_POST["driver_province"];
                $profile["expiry_date"] = $_POST["expiry_date"];
                $profile["sin"] = $_POST["sin"];
                $profile["iscomplete"] = 0;

                $modal = TableRegistry::get('profiles');
                $p = $modal->newEntity($profile);
                if ($modal->save($p)) {
                    $p_id = $p->id;
                    $client= TableRegistry::get('clients');
                    $c = $client->find()->where(['id'=>$Client_ID])->first();
                    $p_ids = $c->profile_id;
                    $_POST['profile_id'] = $p_id;
                    if($p_ids) {
                        $profile_ids = $p_ids . "," . $p_id;
                    } else {
                        $profile_ids = $p_id;
                    }
                    $client->query()->update()->set(['profile_id'=>$profile_ids])->where(['id'=>$Client_ID])->execute();

                    //18  GFS Application for Employment  1   application_for_employment_gfs.php  application_for_employment_gfs  1   1   GFS Demande d'emploi    0
                    $docID = $this->Document->constructdocument(0, "GFS Application for Employment", 18, $p_id, $Client_ID, 0);
                    $_POST["document_id"] = $docID;
                    $_POST["address"] = $_POST["street"] . " " . $_POST["city"] . ", " . $_POST["province"] . " " . $_POST["country"];
                    $app = TableRegistry::get('application_for_employment_gfs');

                    $application = $app->newEntity($_POST);

                    $path = $this->Document->getUrl();
                    if($app->save($application)) {
                        $emails = $this->getallrecruiters($Client_ID);//GFS
                        $path = LOGIN . "documents/view/" . $Client_ID . "/" . $docID . "?type=18";//18=document type ID
                        $site = TableRegistry::get('settings')->find()->first()->mee;//, "site" => $site
                    }
                    $this->redirect('/application/index.php?form=9&msg=success&user_id=' . $p_id . '&client_id=' . $Client_ID);
                }
            }
        }

        function cron_client($pid, $cid) {
            $r = "";
            $cronp = TableRegistry::get('client_crons')->find('all')->where(['profile_id' => $pid, 'client_id' => $cid, 'orders_sent' => '1']);
            foreach ($cronp as $c) {
                $r .= $c->cron_date . "&" . $c->order_id . ",";
            }
            $r = substr($r, 0, strlen($r) - 1);
            $this->response->body($r);
            return $this->response;
        }
        

        function getcron($date) {
            $cron = TableRegistry::get('client_crons')->find()->where(['cron_date'=>$date]);
        }
        
        function check_status($date,$client_id,$profile_id) {
            $crons = TableRegistry::get('client_crons');
            $cron_p = $crons->find()->where(['profile_id' => $profile_id, 'client_id' => $client_id, 'cron_date' => $date,'manual'=>'1'])->first();
            $this->response->body(count($cron_p));
            return $this->response;
            die();   
        }

        function cron_user($date,$client_id,$profile_id) {
            $user_count = 0;
            $client = TableRegistry::get('clients')->find()->where(['id'=> $client_id])->first();
            $forms = $client->requalify_product;
            $profile = TableRegistry::get('profiles')->find()->where(['id'=>$profile_id])->first();
                        
            $crons = TableRegistry::get('client_crons');
            $cron_p = $crons->find()->where(['profile_id' => $profile_id, 'client_id' => $client_id, 'orders_sent' => '1', 'cron_date' => $date])->first();
            if (count($cron_p) == 0){
                $user_count++;
                $pro = $profile_id;
                $rec = TableRegistry::get('profiles')->find()->where(['id' => $profile->created_by])->first();
                if ($rec->email) {
                    $e = $rec->email;
                }
                    if ($profile_id != "") {

                    $drivers = explode(',', $profile_id);
                    //$forms = $_POST['forms'];
                    $arr['forms'] = $forms;
                    $arr['order_type'] = 'REQ';
                    $arr['draft'] = 0;
                    $arr['title'] = 'order_' . date('Y-m-d H:i:s');

                    $arr['client_id'] = $client_id;
                    $arr['created'] = date('Y-m-d H:i:s');
                    //$arr['division'] = $_POST['division'];
                    //$arr['user_id'] = $this->request->session()->read('Profile.id');
                    $arr['driver'] = '';
                    $arr['order_id'] = '';

                    foreach ($drivers as $driver) {

                        $arr['uploaded_for'] = $driver;
                        $ord = TableRegistry::get('orders');
                        $doc = $ord->newEntity($arr);
                        $ord->save($doc);

                        //$this->webservice('BUL', $arr['forms'], $arr['user_id'], $doc->id);
                        if ($arr['driver']) {
                            $arr['driver'] = $arr['driver'] . ',' . $driver;
                        } else {
                            $arr['driver'] = $driver;
                        }
                        if ($arr['order_id']) {
                            $arr['order_id'] = $arr['order_id'] . ',' . $doc->id;
                        } else {
                            $arr['order_id'] = $doc->id;
                        }

                        $cron['order_id'] = $doc->id;
                        $cron['profile_id'] = $driver;
                        $cron['orders_sent'] = '1';
                        $cron['cron_date'] = $date;
                        $cron['client_id'] = $client->id;
                        $cron['manual'] = 1;

                        $s = $crons->newEntity($cron);
                        $crons->save($s);
                        unset($cron);

                    }
                    $this->set('arr', $arr);
                }
                 
                $i = 0;
                $setting = TableRegistry::get('settings')->find()->first();
                //$this->Mailer->handleevent("requalification", array("site" => $setting->mee,"email" => $e, "username" => $profile->username, "company_name" => $client->company_name));
            }
            $this->set('profiles', 1);
        }


        function get($Key, $Default = "", $Array = ""){
            if (isset($_GET[$Key])){ return $_GET[$Key];}
            if (isset($_POST[$Key])){ return $_POST[$Key];}
            if (isset($Array[$Key])){ return $Array[$Key];}
            return $Default;
        }
        function unify($Array=""){//for unifying email and debug logging onto 1 server
            $Action = $this->get("action", "", $Array);
            $MergedArray = array_merge($_POST, $_GET);
            if(is_array($Array)){
                $MergedArray = array_merge($MergedArray, $Array);
            }
            switch ($Action){
                case "viewlog":
                    $file = file_get_contents($this->Mailer->debugprint());
                    echo "<PRE>" . $file . "</PRE>";
                    break;
                case "debugprint":
                    $this->Mailer->debugprint("IP Address: " . $this->get("ip") . " Proxy IP: " . $this->get("proxyip", "[N/A]") . "\r\n" . $this->get("text"), $this->get("domain", "ISBMEE"));
                    echo $this->get("text") . " was printed";
                    break;
                case "handleevent":
                    $Event = $this->get("eventname");
                    $Sent=false;
                    if($this->get("domain") != "veritas"){
                        $Event = $this->get("domain") . "_" . $Event;
                        $Sent = $this->Mailer->handleevent($Event, $MergedArray);
                        if(!$Sent){$Sent = $this->get("eventname");}
                    }
                    if(!$Sent){$Sent = $this->Mailer->handleevent($Event, $MergedArray);}
                    return $Sent;
                    break;
                case "placeorder":
                    $this->placerapidorder($MergedArray);
                    break;
                case "orderstatus":
                    echo $this->getorderstatus($MergedArray);
                    die();
                    break;
                case "test":
                    echo "Unify: Success!";
                    break;
                default:
                    echo $Action . " is not handled<BR>";
                    $this->debugall($_POST, "<BR>Post<BR>");
                    $this->debugall($_GET, "<BR>Get<BR>");
            }
            die();
        }
        function debugall($Array, $Name =""){
            $CRLF = "\r\n";
            if($Name) {Echo 'Name: ' . $Name . $CRLF;}
            foreach($Array as $Key => $Value){
                Echo 'Key: ' . $Key . $CRLF;
                Echo 'Value: ' . $Value . $CRLF;
            }
        }

        function testuser($GETPOST, $Key){
            if (is_array($GETPOST)) {
                if (isset($GETPOST[$Key]) && $GETPOST[$Key]) {
                    $GETPOST = $GETPOST[$Key];
                }
            }
            $Entry = $this->Manager->get_entry("profiles", $GETPOST, $Key);
            if($Entry){$this->status(false,$Key . ' is in use');}
            return $GETPOST;
        }


        function getorderstatus($GETPOST = ""){
            if(!$GETPOST){$GETPOST = array_merge($_POST, $_GET);}
            $Entry = $this->Manager->get_entry("orders", $GETPOST["orderid"], "id");
            if(!$Entry){
                $this->status(false, "Order not found");
            }
            $PATH = "webroot/orders/order_" . $GETPOST["orderid"];
            $baseURL = LOGIN . $PATH . "/";
            $basedir = str_replace("//", "/", $_SERVER['DOCUMENT_ROOT'] . $this->Manager->webroot() . $PATH);

            $Files2 = array();
            if(is_dir($basedir)) {
                $files = scandir($basedir);
                unset($files[0]);
                unset($files[1]);
                foreach ($files as $file) {
                    $Files2[] = $baseURL . $file;
                }
            }
            $Entry->Status = true;
            $Entry->Files = $Files2;
            if(isset($GETPOST["test"])) {debug($Entry); die();}
            if(isset($GETPOST["pretty"])){return '<PRE>' . json_encode($Entry, JSON_PRETTY_PRINT) . '</PRE>';}
            return json_encode($Entry);
        }


        function testpost($Action = "postorder", $OrderID = 953){
             switch($Action) {
                 case "postorder":
                     $data = array(
                         'fname' => 'test',
                         'mname' => 'test',
                         'lname' => 'test',
                         'gender' => 'Male',
                         'title' => 'Mr.',
                         'email' => 'info33@tr345inoweb.com',
                         'phone' => '(905) 531-5331',
                         'street' => '123',
                         'city' => '123',
                         'province' => 'AB',
                         'postal' => 'L8L 6Z2',
                         'country' => 'Canada',
                         'dob' => '10/02/2015',
                         'driver_license_no' => '123',
                         'driver_province' => 'ON',
                         'clientid' => '17',
                         'driverphotoBASE' => '',
                         'forms' => '1603,1,14,77,78,1650,1627,72,32,31',
                         'ordertype' => 'CAR',
                         'signatureBASE' => '',
                         'count' => '');
                     break;
                 case "orderstatus":
                     $data = array(
                         "action" => "orderstatus",
                         "orderid" => $OrderID);
                     break;
             }
            $data["username"] = "admin";
            $data["password"] = md5("admin");
            echo $this->placerapidorder($data);
        }

        function status($Status, $Reason, $Text = "Reason"){
            $NewStatus = array("Status" => $Status, $Text => $Reason);
            echo json_encode($NewStatus);
            die();
        }

        function requiredfields($Data, $Fields, $Title = ""){
            if(is_array($Data) && is_array($Fields)){
                foreach($Fields as $Key){
                    if(!isset($Data[$Key]) || !$Data[$Key]){
                        $this->status(false, $Title . $Key . " is required and missing");
                    }
                }
            }
            return true;
        }
        function placerapidorder($GETPOST = ""){
            if(!$GETPOST){$GETPOST = array_merge($_POST, $_GET);}
            //login requirements
            if(!isset($GETPOST["username"])){$this->Status(False, "Username not specified");}
            $Super = $this->Manager->get_entry("profiles", $GETPOST["username"], "username");
            if(!$Super){$this->Status(False, "Username not found");}
            if($GETPOST["password"] != $Super->password){ $this->Status(False,"Password mismatch");}

            if(isset($GETPOST["action"])){
                $this->unify($GETPOST);
                die();
            }

            $Formdata = $this->Manager->validate_data($GETPOST, array("gender" => ["Male", "Female"], "title" => ["Mr.", "Mrs.", "Ms."], "email" => "email", "phone" => "phone", "postal" => "postalcode", "province" => "province", "driver_province" => "province", "clientid" => "number", "driverphotoBASE" => "base64file", "forms" => "csv", 'signatureBASE' => "base64file", 'consentBASE' => "base64file", "dob" => "date"));
            $Required = array("clientid", "forms", "ordertype", "email", "phone", "driver_province" );
            $this->requiredfields($GETPOST, $Required);//required field validation
            if(!is_array($Formdata)){$this->status(False, $Formdata);}

            if(isset($GETPOST["data"])) {
                foreach ($GETPOST["data"] as $Key => $Formdata) {
                    if (isset($Formdata["type"])) {//account for removed forms
                        $FormType = $Formdata["type"];
                        $Replace = false;
                        $Roles = false;
                        $Required = false;
                        switch($FormType){//unfix typos
                            case 9://letter of experience
                                $Roles = array("state_province" => "province", "supervisor_phone" => "phone", "supervisor_email" => "email", "supervisor_secondary_email" => "email", "employment_start_date" => "date", "employment_end_date" => "date", "claims_with_employer" => "bool", "claims_recovery_date" => "date", "signature_datetime" => "date", "equipment_vans" => "bool", "equipment_reefer" => "bool", "equipment_decks " => "bool", "equipment_super" => "bool", "equipment_straight_truck" => "bool", "equipment_others" => "bool", "driving_experince_local" => "bool", "driving_experince_canada" => "bool", "driving_experince_canada_rocky_mountains" => "bool", "driving_experince_usa" => "bool");
                                $Required = array("company_name", "address", "city");
                                break;
                            case 10://education verification
                                $Roles = array("supervisior_name" => "phone", "supervisor_email" => "email", "education_start_date" => "date", "education_end_date" => "date", "claim_tutor" => "bool", "date_claims_occur" => "date", "highest_grade_completed" => "number", "high_school" => "number", "college" => "number", "date_time" => "date");
                                $Replace = array("supervisor_name" => "supervisior_name", "supervisor_phone" => "supervisior_phone", "supervisor_email" => "supervisior_email");
                                //nothing is required
                                break;
                        }
                        if(is_array($Replace)){//masks misspelled columns from the user
                            foreach($Replace as $FROM => $TO){
                                if(isset($Formdata[$FROM])){
                                    $Formdata[$TO] = $Formdata[$FROM];
                                    unset($Formdata[$FROM]);
                                }
                            }
                        }
                        if(is_array($Roles)){//data validation
                            $Formdata = $this->Manager->validate_data($Formdata, $Roles);
                            if(is_array($Formdata)){
                                $GETPOST["data"][$Key] = $Formdata;
                            } else {
                                $this->status(False, 'Form[' . $FormType . '].' . $Formdata);
                            }
                        }
                        if(is_array($Required)){//required field validation
                            $this->requiredfields($GETPOST, $Required, 'Form[' . $FormType . '].');
                        }
                    }
                }
            }

            //construct and/or get driver
            $ClientID = $this->get("clientid", 38);
            if(!$ClientID){$this->Status(False,"Not a valid client ID");}
            if(isset($GETPOST["driverid"])){
                $Driver = $GETPOST["driverid"];
                $this->testuser($Driver, "id");
            } else {
                //$GETPOST["email"] = "a1@gmail.com";
                if(!$this->Manager->validate_data($this->testuser($GETPOST, "email"), "email")){
                    $this->Status(False,"Not a valid email address");
                }
                /* $this->testuser($GETPOST, "username");
                if(isset($GETPOST["password"]) && $GETPOST["password"]){
                    if(!isset($GETPOST["password2"]) || $GETPOST["password"] == $GETPOST["password2"]){
                        $GETPOST["password"] = md5($GETPOST["password"]);
                    } else {
                        Status(False, "Password mismatch");
                    }
                } */
                $Driver = $this->Manager->copyitems($GETPOST, array("profile_type" => 0, "fname", "mname", "lname",  "title", "gender" => "Female", "street", "city", "province", "postal", "dob", "driver_license_no", "driver_province", "email", "phone", "city", "country"));//"password", "username",
                $Driver = $this->Manager->new_entry("profiles", "id", $Driver);
                $Driver = $Driver["id"];
                if(!isset($GETPOST["username"]) || !$GETPOST["username"]) {
                    $this->Manager->update_database("profiles", "id", $Driver, array("username" => "Applicant_" . $Driver));
                }
                $this->Manager->assign_profile_to_client($Driver, $ClientID);
            }

            //get forms list
            if(!$GETPOST["ordertype"]){$GETPOST["ordertype"]="CAR";}
            if(!isset($GETPOST["forms"]) || !$GETPOST["forms"]){
                $Forms = $this->Manager->get_entry("product_types", $GETPOST["ordertype"], "Acronym");
                $GETPOST["forms"] = $Forms->Blocked; //"1603,1,14,77,78,1650,1627,72,32,31,99,500,501";
            }

            //construct order
            $this->loadComponent("Document");
            $OrderID = $this->Document->constructorder("RAPID ORDER " . $this->Manager->now(), $Super->id, $ClientID, $Super->fname . " " . $Super->lname, $this->get("fname") . " " . $this->get("lname"), $GETPOST["forms"], "", $GETPOST["ordertype"], $Driver);

            //attachments
            $Formdata = $this->handleattachments($GETPOST, "driverphotoBASE", 'webroot/attachments', 15, "id_piece1", $Super, $ClientID, $OrderID);//Photo ID (Upload ID)
            if(!$Formdata) {
                $Formdata = $this->Document->constructsubdoc(array(), 15, $Super->id, $ClientID, $OrderID, true);
            }
            $this->handleattachments($GETPOST, "consentBASE", 'webroot/attachments', -15, $Formdata["subdocid"], $Super, $ClientID, $OrderID);//consent form as MEE_ID
            $this->handleattachments($GETPOST, "signatureBASE", 'webroot/canvas', 4, array("criminal_signature_applicant2", "criminal_signature_applicant"), $Super, $ClientID, $OrderID);//signature (consent form)

            //sub-documents
            if(isset($GETPOST["data"])) {
                foreach ($GETPOST["data"] as $Formdata) {
                    if (isset($Formdata["type"])) {//account for removed forms
                        $FormType = $Formdata["type"];
                        unset($Formdata["type"]);
                        $this->Document->constructsubdoc($Formdata, $FormType, $Super->id, $ClientID, $OrderID, true);
                    }
                }
            }

            //call web service
            if(false) {//disable for faster testing
                $this->Manager->callsub("orders", "webservice", array($GETPOST["ordertype"], $GETPOST["forms"], $Driver, $OrderID));
            } else {
                echo "SKIPPING WEB SERVICE FOR TESTING!";
            }
            /* How to call a remote sub without loading the page
            $Orders = new OrdersController;
            $Orders->constructClasses();//Load model, components...
            echo $Orders->webservice($GETPOST["ordertype"], $GETPOST["forms"], $Driver, $OrderID);
            */
            $this->Status(True,$OrderID, "OrderID");
        }

        function handleattachments($GETPOST, $Name, $Path, $SubDocID, $Field, $Super, $ClientID, $OrderID){
            if (isset($GETPOST[$Name]) && strpos($GETPOST[$Name], "data:image/") !== false && strpos($GETPOST[$Name], ";base64,") !== false){
                $GETPOST[$Name] = str_replace("data:image/tmp;base64,", "data:image/png;base64,", $GETPOST[$Name] );
                $Filename = $this->Manager->unbase_64_file($GETPOST[$Name], $Path);
                if($SubDocID>0) {
                    $Data = array();
                    if(is_array($Field)){
                        foreach($Field as $Key){
                            $Data[$Key] = $Filename;
                        }
                    } else {
                        $Data[$Field] = $Filename;
                    }
                    return $this->Document->constructsubdoc($Data, $SubDocID, $Super, $ClientID, $OrderID, true);//MEE Attach (Upload ID)
                } else if ($SubDocID == -15) {//mee_attachments, Field is the MEE_ID
                    $Data = array("mee_id" => $Field, "attachments" => $Filename);
                    return $this->Manager->new_entry("mee_attachments_more", "id", $Data);
                }
            }
            return false;
        }

        function copyarray($SRC, $Cells){
            $To = array();
            foreach($Cells as $Key => $Value){
                $Dest = $Value;
                if(is_numeric($Key)){
                    $Source = $Value;
                } else {
                    $Source = $Key;
                }
                if (isset($SRC[$Source])){
                    $To[$Dest] = $SRC[$Source];
                }
            }
            return $To;
        }
}