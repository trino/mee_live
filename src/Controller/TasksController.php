<?php 
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Controller\Controller;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

class TasksController extends AppController {

    
     public function initialize() {
        parent::initialize();
        $this->loadComponent('Trans');
         $this->loadComponent('Settings');
         $this->Settings->verifylogin($this, "schedules");
    }
    
	public function index() {

	}


    function timezone(){
        $offset = date("Z")/3600 ;
        $this->request->session()->write('time',$_GET['time']);
        $this->request->session()->write('timediff',$_GET['time'] - $offset);
    }


	public function view($id = null) {
	    $events = TableRegistry::get('Events');
        $event = $events->find()->where(['id'=>$id])->first();
        $this->set('event',$event);
        $this->set('isdisabled','1');
        $this->render('add');
	}

	public function add() {
	   if(isset($_POST['submit'])) {
        date_default_timezone_set('Canada/Central');
           $offset = -$_POST['offset'];

           $language = $this->request->session()->read('Profile.language');
           $_POST["date"] = $this->Trans->translatedate($language, $_POST["date"]);

            foreach($_POST as $k=>$v) {
                if($k == 'date') {
                    $k = $this->offsettime($k, $offset);
                    $arr[$k] = date('Y-m-d H:i:s', strtotime(trim(str_replace("-", " ", $v)) . ":00"));
                } else if ($k != "timezoneoffset" && $k != "offset" && $k != "submit") {
                    $arr[$k] = addslashes($v);
                }
            }
            $events = TableRegistry::get('Events');
    	    $arr['user_id'] = $this->request->session()->read('Profile.id');

            $event = $events->newEntity($arr);
    
            if ($events->save($event)) {
                $this->Flash->success($this->Trans->getString("flash_eventsaved"));
            } else {
                $this->Flash->error($this->Trans->getString("flash_eventnotsaved"));
            }
            return $this->redirect(['action' => 'calender']);
            
        }
	}


	public function edit($id = null) {
	    $events = TableRegistry::get('Events');
        $event = $events->find()->where(['id'=>$id])->first();
        $this->set('event',$event);
        if(isset($_POST['submit'])) {
           $offset = -$_POST['offset'];
           foreach($_POST as $k=>$v) {
               if($k == 'date') {
                   $arr[$k] = $this->offsettime(date('Y-m-d H:i:s', strtotime(trim(str_replace("-", " ", $v)) . ":00")), $offset);
               } else if ($k != "timezoneoffset" && $k != "offset" && $k != "submit") {
                   $arr[$k] = $v;
               }
           }
            $events = TableRegistry::get('Events');
            
            $query2 = $events->query();
            $query2->update()
                ->set($arr)
                ->where(['id' => $id])
                ->execute();
    	        $this->Flash->success($this->Trans->getString("flash_eventsaved"));
                
            
            return $this->redirect(['action' => 'calender']);
            
        }
       $this->render('add');
	}

	public function delete($id = null) {
	  $event = TableRegistry::get('Events');
      $query = $event->query();
      if($query->delete()
        ->where(['id' => $id])
        ->execute()) {
          $this->Flash->success($this->Trans->getString("flash_eventdeleted"));
      } else {
          $this->Flash->error($this->Trans->getString("flash_eventnotdeleted"));
      }
      return $this->redirect(['action' => 'calender']);
	}
    
    function logout() {
        $this->request->session()->delete('Profile.id');
        $this->redirect('/login');
    }
    
    function todo() {
        
    }

    function picker(){
        $this->layout= 'blank';
    }

    function calender1(){
        $this->layout= 'blank';
    }
    
    function date($date) {
        $events = TableRegistry::get('Events');
        $event = $events->find()->where(['user_id'=>$this->request->session()->read('Profile.id'),'date LIKE "'.$date.'%"'])->order(['date'])->all();
        //debug($event);
        $this->set('events', $event);
    }

    function calender() {
        $events = TableRegistry::get('Events');
        $event = $events->find()->where(['user_id'=>$this->request->session()->read('Profile.id')])->order(['date'=>'DESC'])->all();
        //debug($event);
        $this->set('events', $event);
    }




    function offsettime($date, $offset){
        if ($offset == 0){ return $date;}
        $newdate= date_create($date);
        $hours = floor($offset);
        $minutes = ($offset-$hours)*60;
        if ($minutes > 0) {$newdate->modify("+" . $minutes . " minutes"); }
        if ($hours>0) { $hours = "+" . $hours;}
        $newdate->modify($hours . " hours");
        return $newdate->format('Y-m-d H:i:s');
    }
}
