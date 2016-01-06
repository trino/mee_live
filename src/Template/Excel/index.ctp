<?php
    $Controller = strtolower($this->request->params['controller']);
    $GLOBALS["Controller"] = $Controller;
    if(isset($_GET["embedded"])){$EmbeddedMode=true;}
    if(isset($EmbeddedMode)){
        if(!isset($_GET["table"])){$_GET["table"]=$Table;}
    } else {
        $EmbeddedMode=false;
    }

    function getID($ID){
        return substr($ID, 5, strpos($ID, "]") - 5);
    }

    function getreferences($Manager, $Table, $Reference, $Me ="", $Letters = "", $PrimaryKey = "", $FilterBrackets=true, $RefsOnly = false){
        if(!ismultireference($Manager, $Reference)){
            if ($RefsOnly){return array($Reference);}
            return array(getreference($Manager, $Table, $Reference, $Letters, $PrimaryKey, $FilterBrackets, false));
        }
        $Table = gettablereference($Reference,$Table);
        if(!$PrimaryKey){$PrimaryKey = $Manager->get_primary_key($Table);}
        if(!$Letters){
            $Columns = $Manager->getColumnNames($Table, "", false);
            $Letters = get_column_letters($PrimaryKey, $Columns);
        }
        $Reference1=ismultireference($Manager, $Reference,1, $Me);
        $Reference2=ismultireference($Manager, $Reference,2, $Me);

        $Column1 = $Manager->validate_data($Reference1, "alphabetic");
        $Row1 = $Manager->validate_data($Reference1, "number");
        $Column2 = $Manager->validate_data($Reference2, "alphabetic");
        $Row2 = $Manager->validate_data($Reference2, "number");
        $Column1 = letterToIndex($Column1);
        $Column2 = letterToIndex($Column2);
        if ($Column1 > $Column2){
            $Values = $Column1;
            $Column1 = $Column2;
            $Column2 = $Values;
        }
        if($Row1 > $Row2){
            $Values = $Row1;
            $Row1 = $Row2;
            $Row2 = $Values;
        }

        $Values = array();
        for($RowID = $Row1; $RowID <= $Row2; $RowID++) {
            for ($ColumnID = $Column1; $ColumnID <= $Column2; $ColumnID++) {
                $Column = getcolumnindex($Letters, $ColumnID, false);
                $Reference = $Column . $RowID;
                if ($Reference != $Me) {
                    if ($RefsOnly) {
                        $Values[] = $Reference;
                    } else {
                        $Reference = getreference($Manager, $Table, $Reference, $Letters, $PrimaryKey, $FilterBrackets, false);
                        if($Reference){$Values[] = $Reference;}
                    }
                }
            }
        }
        return $Values;
    }

    function sum($Data){
        $Total = 0;
        foreach($Data as $Value){
            $Total += $Value;
        }
        return $Total;
    }
    function average($Data){
        return sum($Data) / count($Data);
    }

    function getcolumnindex($Letters, $Index, $RetName = false){
        $Index=$Index+1;
        $FirstLetter= floor ($Index/26);
        $SecondLetter = $Index % 26;
        $Index = generateletters(array($FirstLetter,$SecondLetter), ord("A"));
        if ($RetName && isset($Letters[$Index])){
            return $Letters[$Index];
        }
        return $Index;
    }

    function letterToIndex($Letter){
        $Number =0;
        $Int = 0;
        $Start = ord("A");
        for($Temp = strlen($Letter)-1; $Temp>=0; $Temp--){
            $Value = substr($Letter, $Temp, 1);
            $Value = ord($Value) - $Start ;
            if($Int){
                $Number += ($Value+1) * $Int;
                $Int=$Int*26;
            }else {
                $Number = $Value;
                $Int = 26;
            }
        }
        return $Number;
    }

    function gettablereference($Reference, $Table){
        $ExclamationMark = strpos($Reference, "!");
        if ($ExclamationMark) {return substr($Reference, 0, $ExclamationMark);}
        return $Table;
    }

    function getreference($Manager, $Table, $Reference, $Letters = "", $PrimaryKey = "", $FilterBrackets=true, $ReturnIfError=true){
        $Reference = str_replace("$", "", $Reference);
        $ExclamationMark = strpos($Reference, "!");
        if ($ExclamationMark){//reference to another table
            $Table2 = substr($Reference, 0, $ExclamationMark);
            $Reference = substr($Reference, $ExclamationMark + 1, strlen($Reference) - $ExclamationMark - 1);
            if($Table2 != $Table) {
                $Table = $Table2;
                $Letters = "";
                $PrimaryKey = "";
            }
        }
        $Column = $Manager->validate_data($Reference, "alphabetic");
        $Row = $Manager->validate_data($Reference, "number");

        if(!$PrimaryKey){$PrimaryKey = $Manager->get_primary_key($Table);}
        if(!$Letters){
            $Columns = $Manager->getColumnNames($Table, "", false);
            $Letters = get_column_letters($PrimaryKey, $Columns);
        }

        if (!isset($Letters[$Column])){return '[ERROR: Column ' . $Column . ' not found in ' . $Table . ']';}
        $Column = $Letters[$Column];

        $Data = $Manager->get_entry($Table, $Row, $PrimaryKey);
        if($Data){
            $Data = $Data->$Column;
            if($FilterBrackets){$Data = getTag($Data, false);}
            return $Data;
        }
        if($ReturnIfError) {return '[ERROR:' . $Table . "!" . $Reference . " not found]";}
    }

    function isareference($Manager, $Value){
        if(strtoupper($Value) == "ME"){return true;}
        $ExclamationMark = strpos($Value, "!");
        if ($ExclamationMark) {$Value = substr($Value, $ExclamationMark + 1, strlen($Value) - $ExclamationMark - 1);}
        $Column = $Manager->validate_data($Value, "alphabetic");
        $Row = $Manager->validate_data($Value, "number");
        if($Column && $Row && strlen($Column) < 3) {return strpos($Value, $Row) > strpos($Value, $Column);}
        return false;
    }

    function ismultireference($Manager, $Value, $Ret=0, $Me = ""){
        $SemiColon = strpos($Value, ":");
        $Reference1 = strtoupper(substr($Value, 0, $SemiColon));
        $Reference2 = strtoupper(substr($Value, $SemiColon + 1, strlen($Value) - $SemiColon - 1));
        if($Ret == 1 && $Reference1 == "ME"){return $Me;}
        if($Ret == 2 && $Reference2 == "ME"){return $Me;}
        if($Ret == 1){return $Reference1;}
        if($Ret == 2){return $Reference2;}
        return(isareference($Manager, $Reference1) && isareference($Manager, $Reference2));
        return false;
    }

    function editform($Manager, $Table, $Column, $ID=0){//doesn't support embedding yet...
        echo '<FORM METHOD="GET" ACTION="' . $Manager->webroot() . $GLOBALS["Controller"] . '">';
        echo '<INPUT TYPE="HIDDEN" NAME="action" VALUE="saveedit">';
        echo '<INPUT TYPE="HIDDEN" NAME="table" VALUE="' . $Table . '">';
        echo '<INPUT TYPE="HIDDEN" NAME="htmlmode" VALUE="html">';
        echo '<INPUT TYPE="HIDDEN" NAME="column" VALUE="' . $Column . '">';
        $ColumnNames = $Manager->getColumnNames($Table, "", false);
        if($ID){
            $PrimaryKey = $Manager->get_primary_key($Table);
            $Entry = $Manager->get_entry($Table, $ID, $PrimaryKey);
            $Value = $Entry->$Column;
            $Tag = getTag($Value, True);
            if (strpos($Tag, "[") === false){$Tag = "[" . $Tag . "]";}
            $Value = getTag($Value, False);
            echo '<INPUT TYPE="HIDDEN" NAME="id" VALUE="' . $ID . '">';
            echo '<P><LABEL>Value:</LABEL><INPUT TYPE="text" ID="text" NAME="text" VALUE="' . $Value . '" style="width: 100%;" placeholder="Text"></P>';
        } else {//is a column header
            $Tag = $ColumnNames[$Column]["comment"];
        }
        echo '<P><LABEL>Tags:</LABEL><INPUT TYPE="text" ID="tag" NAME="tag" VALUE="' . $Tag . '" readonly style="width: 100%;" placeholder="Tags"></P>';
        ?>
            <SCRIPT>
            var Values = new Array();
            var SelectedKey = "";
            function tagclick(){
                SelectedKey = getinputvalue("tags");
                SelectedValue = Values[SelectedKey];
                if (typeof(SelectedValue) == "undefined"){SelectedValue="";}
                clearselect("values");
                visible("values", false);
                visible("color", false);
                visible("removetag", true);
                switch(SelectedKey){
                    case "format":
                        addoptions("values", ["percent", "uppercase", "lowercase", "number"]);
                        break;
                    case "bgcolor": case "fontcolor":
                        visible("color", true);
                        setvalue("color", SelectedValue);
                        break;
                    case "align":
                        addoptions("values", ["left", "center", "right"]);
                        break;
                    case "fontsize":
                        addoptions("values", [1, 2, 3, 4, 5, 6, 7]);
                        break;
                }
            }

            function valueclick(){
                var SelectedValue = getinputvalue("values");
                Values[SelectedKey] = SelectedValue;
                generatevalues();
            }

            function removeatag(){
                var element = document.getElementById("tags").selectedIndex;
                removeoption("tags", -1);
                Values = removearray(Values,SelectedKey);
                generatevalues();

                visible("values", false);
                visible("removetag", false);
            }

            function removearray(array, index){
                var newarray = new Array();
                for (var key in array) {
                    var value = array[key];
                    if (key != index){
                        newarray[key] = value;
                    }
                }
                return newarray;
            }

            function generatevalues(){
                var tempstr = new Array();
                for (var key in Values) {
                    var value = Values[key];
                    if(value){
                        tempstr.push(key + "=" + value);
                    } else {
                        tempstr.push(key);
                    }
                }
                tempstr = "[" + tempstr.join(",") + "]";
                setvalue("tag", tempstr);
            }
            function setvalue(ID, Value){
                document.getElementById(ID).value = Value;
            }

            function addoptions(ID, Values){
                visible("values", true);
                 for(i=0;i<Values.length;i++) {
                     addoption(ID, Values[i]);
                }
            }
            function addoption(ID, Value, Text){
                var element = document.getElementById(ID);
                var option = document.createElement("option");
                if(Text) {option.text = Text;} else {option.text = Value;}
                option.value = Value;
                element.add(option);
            }
            function clearselect(ID){
                var element = document.getElementById(ID);
                var i;
                for(i=element.options.length-1;i>=0;i--) {
                    element.remove(i);
                }
            }
            function removeoption(ID, Index){
                var element = document.getElementById(ID);
                addoption("options", SelectedKey);
                if(Index==-1){Index = element.selectedIndex;}
                element.remove(Index);
            }
            function optionclick(){
                var element = document.getElementById("options");
                var Value = getinputvalue("options");
                Values[Value] = "";
                addoption("tags", Value);
                element.remove(element.selectedIndex);
                generatevalues();
            }
            function updatecolor(color){
                color = "#" + color;
                Values[SelectedKey] = color;
                generatevalues();
            }
            function visible(ID, Status){
                var element = document.getElementById(ID);
                if(Status){
                    element.setAttribute("style", "display: block;");
                } else {
                    element.setAttribute("style", "display: none;");
                }
            }
        <?php
        if($Tag){
            $Tag = assocsplit(substr($Tag,1, strlen($Tag)-2), ",","=");
            foreach($Tag as $Key => $ValuePair){
                echo "\r\nValues['" . $Key . "'] = '" . $ValuePair . "';";
            }
        }
        echo '</SCRIPT>';

        echo '<P><LABEL>Used tags: </LABEL><BR><SELECT ID="tags" SIZE=10 onclick="tagclick();" style="width: 200px;">';
        if($Tag){
            foreach($Tag as $Key => $ValuePair){
                echo "<OPTION>" . $Key . "</OPTION>";
            }
        }
        echo '</SELECT></P>';
        echo '<P><INPUT TYPE="button" value="Remove Tag" onclick="removeatag();" id="removetag" style="display: none"></P>';

        //input types
        echo '<P><script type="text/javascript" src="assets/global/plugins/jscolor/jscolor.js"></script><input class="color {onImmediateChange:' . "'updatecolor(this);'" . '}" ID="color" style="display: none"></P>';
        echo '<P><INPUT TYPE="text" ID="text" style="display: none"></P>';
        echo '<P><SELECT ID="values" size=10 style="display: none" onclick="valueclick();"></SELECT></P>';
        echo '<P><LABEL>Available tags: </LABEL><BR><SELECT ID="options" size=10 onclick="optionclick();" style="width: 200px;">';
        $options = array("bold", "italic", "underline", "format", "bgcolor", "align", "fontcolor", "fontsize");
        if($ID){$options[] = "colspan";}
        foreach($options as $Key){
            if($Tag){$DoIt = !isset($Tag[$Key]);} else {$DoIt = true;}
            if($DoIt){echo '<OPTION>' . $Key . '</OPTION>';}
        }
        echo '</SELECT></P>';
        echo '<P><INPUT TYPE="SUBMIT" VALUE="Save"> <INPUT TYPE="button" VALUE="Cancel" onclick="window.history.back();"><FORM>';
    }

    if (isset($_GET["action"])){
        switch($_GET["action"]){
            case "clearcache":
                $Manager->clear_cache();
                break;
            case "edit":
                if (strpos($_GET["id"], "Data") !== false) {
                    $ID = explode("][", substr($_GET["id"], 5, strlen($_GET["id"]) - 6));//0=ID, 1=Column
                    editform($Manager, $_GET["table"], $ID[1], $ID[0]);
                } else {
                    editform($Manager, $_GET["table"], $_GET["id"]);
                }
                return;
                break;
            case "saveedit":
                if(isset($_GET["id"])){
                    $PrimaryKey = $Manager->get_primary_key($_GET["table"]);
                    $Value = $_GET["text"];
                    if(strlen($_GET["tag"]>2)){$Value = $_GET["tag"] . $Value;}
                    $Manager->edit_database($_GET["table"], $PrimaryKey, $_GET["id"], array($_GET["column"] => $Value));
                } else {
                    $Manager->change_column_comment($_GET["table"], $_GET["column"], $_GET["tag"]);
                }
                break;
        }
    }
    if (isset($_POST["action"])){
        if(isset($_POST["id"])){$ID = getID($_POST["id"]);}

        switch($_POST["action"]){
            case "delete":
                fixreferences($Manager, $_POST["table"], true, "A", 1, 0, -1);
                $Manager->delete_all($_POST["table"], array($_POST["key"] => $ID));
                echo "Deleted from " . $_POST["table"] . " where " . $_POST["key"] . " = " . $ID;
                break;
            case "save":
                if (isset($_POST["Data"])) {
                    foreach ($_POST["Data"] as $Key => $Data) {
                        if($Key == "new"){
                            $ID = $Manager->edit_database($_POST["table"], $_POST["key"], false, $Data);
                            //echo 'Added: ' . $_POST["key"] . " = " . $ID[$_POST["key"]];
                        } else {
                            $Manager->update_database($_POST["table"], $_POST["key"], $Key, $Data);
                        }
                    }
                    echo "All changed data was saved";
                } else {
                    echo "No data was saved";
                }
                break;
            case "deletetable":
                echo "I'm not deleting " . $_POST["table"] . "...";
                break;
            case "newtable":
                if($Manager->new_table($_POST["table"])){
                    echo "Table made: " . $_POST["table"];
                } else {
                    echo "Unable to create table";
                }
                break;
            case "newcolumn":
                if($_POST["type"] == "DECIMAL" && $_POST["length"] == 0){$_POST["length"] = "10,10";}
                if($_POST["type"] == "VARCHAR" && $_POST["length"] == 0){$_POST["type"] = "TEXT";}
                $Query = $Manager->create_column($_POST["table"], $_POST["name"], $_POST["type"],  $_POST["length"], "", false, false, "", "",  $_POST["position"]);
                if ($_POST["position"]== "FIRST"){
                    fixreferences($Manager, $_POST["table"], true, "A", 1, 1);
                } elseif($_POST["position"]) {
                    fixreferences($Manager, $_POST["table"],  false, $_POST["position"], 1, 1);
                }
                echo $_POST["name"] . " created in " . $_POST["table"] . ' (' . $Query . ")";
                break;
            case "deletecolumn":
                fixreferences($_POST["table"],  getletter($_POST["table"], $_POST["name"], '', '') . "1", -1);
                $Manager->delete_column($_POST["table"], $_POST["name"]);
                echo $_POST["name"] . " deleted from " . $_POST["table"];
                break;
            default:
                debug($_POST);
        }
        die();
    }

    $settings = $this->requestAction('settings/get_settings');
    include_once('subpages/api.php');
    $language = $this->request->session()->read('Profile.language');
    $controller =  $this->request->params['controller'];
    $strings = CacheTranslations($language, array("forms_%" , $controller  . "_%"),$settings);
    if($language == "Debug"){ $Trans = " [Translated]"; } else {$Trans = "";}

    function javascriptarray($Array){
        return '["' . implode('", "', $Array) . '"];';//var cars = ["Saab", "Volvo", "BMW"];
    }

    function ucfirst2($Text, $DoUnderscore = false){
        if($DoUnderscore){$Text = str_replace("_", " ", $Text);}
        $Text = explode(" ", $Text);
        foreach($Text as $Key => $Value){
            $Text[$Key] = ucfirst($Value);
        }
        return implode(" ", $Text);
    }

    function getTag($Text, $GetTag){
        $Start = strpos($Text, "[");
        $End = strpos($Text, "]");
        if($GetTag){
            if($Start !== false && $End !== false){
                if($Start<$End) {return substr($Text, $Start+1, $End-$Start-1);}
            }
        } else {
            if($Start !== false && $End !== false) {
                return substr($Text, 0, $Start) . substr($Text, $End + 1, strlen($Text) - $End - 1);
            }
            return $Text;
        }
    }
    function assocsplit($Text, $PrimaryDelimeter, $SecondaryDelimeter){
        $PrimaryArray = explode($PrimaryDelimeter, $Text);
        $RET = array();
        foreach ($PrimaryArray as $Value) {
            if(strpos($Value, $SecondaryDelimeter) === false){
                $RET[$Value] = "";
            } else {
                $SecondaryArray = explode($SecondaryDelimeter, $Value);
                $RET[$SecondaryArray[0]] = $SecondaryArray[1];
            }
        }
        return $RET;
    }
    function get_column_letters($PrimaryKey, $Columns){
        $FirstLetter = 0;
        $SecondLetter = 1;
        $Letters = array();
        foreach ($Columns as $ColumnName => $ColumnData){
            //if($ColumnName != $PrimaryKey){
                $Letter = generateletters(array($FirstLetter,$SecondLetter), ord("A"));
                $Letters[$Letter] = $ColumnName;
                $Columns[$ColumnName]["letter"] = $Letter;
                $SecondLetter++;
                if($SecondLetter>26){
                    $SecondLetter=1;
                    $FirstLetter++;
                }
            //}
        }
        return $Letters;
    }
    function generateletters($Letters, $Start = 0){
        $Tempstr = "";
        //if(!is_array($Letters)){$Letters = str_split($Letters);}
        foreach($Letters as $Letter){
            if($Letter>0){
                $Letter=$Letter+$Start-1;
                $Tempstr .= chr($Letter);
            }
        }
        return $Tempstr;
    }
    function getletter($Letters, $ColumnName, $Start = '[', $Finish = '] '){
        $Key = array_search($ColumnName, $Letters);
        if($Key){
            return $Start . $Key . $Finish;
        }
    }

    $Columns="";
    $Table="";
    $PrimaryKey="";
    $Tables="";
    if(isset($_GET["table"])){
        $HTMLMode=(isset($_GET["mode"]) && $_GET["mode"] == "html") || $EmbeddedMode || isset($_GET["htmlmode"]);
        $Table = $_GET["table"];
        $GLOBALS["Table"] = $Table;
        $PrimaryKey = $Manager->get_primary_key($Table);
        $Columns = $Manager->getColumnNames($Table, "", false);
        $Letters = get_column_letters($PrimaryKey, $Columns);
        $Conditions = "";
        if (isset($_GET["search"])){
            if (strpos($_GET["search"], "%") !== false){//is a pattern
                $Conditions[] = $_GET["column"] . " like '" . $_GET["search"] . "'";
            } else {
                $Conditions[$_GET["column"]] = $_GET["search"];
            }
        }
        $Data = $Manager->enum_all($Table,$Conditions);
        if($HTMLMode){$Data = $Manager->paginate($Data);}
        if(!$EmbeddedMode){ ?>
            <div class="form-actions" style="height:75px;">
                <div class="row">
                    <div class="col-md-6" align="left">
                        <div id="sample_2_paginate" class="dataTables_paginate paging_simple_numbers" style="margin-top:-10px;">
                            <ul class="pagination sorting">
                                <LI><A HREF="<?= $this->request->webroot . $Controller; ?>">Back</A></LI>
                                <?php if(!$HTMLMode){ ?>
                                    <LI><A ONCLICK="return save(true);">Save</A></LI>
                                    <LI><A HREF="?table=<?= $Table; ?>&action=clearcache">Clear Cache</A></LI>
                                <?php } ?>
                                <LI><A HREF="?table=<?= $Table;?>&mode=<?php
                                    if($HTMLMode){
                                        echo 'sql">SQL';
                                    } else {
                                        echo 'html">HTML';
                                    }
                                    ?></A></LI>
                            </ul>
                        </div>
                    </DIV>
                    <?php if(!$HTMLMode){?>
                        <div class="col-md-6" align="right">
                            <div id="sample_2_paginate" class="dataTables_paginate paging_simple_numbers" style="margin-top:-10px;">
                                <ul class="pagination sorting">
                                    <?= $this->Paginator->prev('< ' . __($strings["dashboard_previous"])); ?>
                                    <?= $this->Paginator->numbers(); ?>
                                    <?= $this->Paginator->next(__($strings["dashboard_next"]) . ' >'); ?>
                                </ul>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php }
        if(!$HTMLMode){?>
            <table class="table table-hover  table-striped table-bordered table-hover dataTable no-footer">
                <TR ID="action_search"><TD>
                    <FORM method="get" action="<?= $this->request->webroot . $Controller; ?>">
                        <LABEL>Search: </LABEL>
                        <INPUT TYPE="hidden" name="table" value="<?= $Table; ?>">
                        <INPUT TYPE="text" name="search" placeholder="Search" value="<?php if(isset($_GET["search"])){echo $_GET["search"];} ?>">
                        <SELECT NAME="column" style="height:24px;">
                            <?php
                            foreach($Columns as $ColumnName => $ColumnData){
                                echo '<OPTION value="' . $ColumnName . '"';
                                if(isset($_GET["column"]) && $_GET["column"] == $ColumnName){echo ' SELECTED';}
                                echo '>' . getletter($Letters, $ColumnName) . ucfirst2($ColumnName, true) . '</OPTION>';
                            }
                            ?>
                        </SELECT>
                        <input type="submit" value="Search">
                    </FORM>
                </TD></TR>
                <TR ID="action_newcol"><TD>
                    <FORM method="post" action="<?= $this->request->webroot . $Controller; ?>">
                        <LABEL>New Column: </LABEL>
                        <INPUT TYPE="hidden" name="action" value="newcolumn">
                        <INPUT TYPE="hidden" name="table" value="<?= $Table; ?>">
                        <INPUT TYPE="text" name="name" placeholder="Name" id="newcol_name">
                        <SELECT name="type" id="newcol_type" style="height:24px;">
                            <OPTION value="INT">Number</OPTION>
                            <OPTION value="DECIMAL">Decimal</OPTION>
                            <OPTION value="TINYINT">Boolean</OPTION>
                            <OPTION value="VARCHAR" SELECTED>Text</OPTION>
                        </SELECT>
                        <LABEL>Length:</LABEL>
                        <INPUT TYPE="text" name="length" value="255" maxlength="4" size="4" id="newcol_length" title="I recommend a VARCHAR with a length of at least 255, to allow for equations">
                        <LABEL>Position:</LABEL>
                        <SELECT name="position" id="newcol_pos" style="height:24px;">
                            <OPTION value="FIRST">At the beginning</OPTION>
                            <?php
                                foreach($Columns as $ColumnName => $ColumnData){
                                    echo '<OPTION VALUE="' . $ColumnName . '">After: ' . getletter($Letters, $ColumnName) . ucfirst2($ColumnName, true) . '</OPTION>';
                                }
                            ?>
                            <OPTION SELECTED value="">At the end</OPTION>
                        </SELECT>
                        <input type="button" value="New Column" onclick="newcol();">
                    </FORM>
                </TD>
            </TR>
        </TABLE>
    <?php } ?>

    <?php } else {
        $Tables = $Manager->enum_tables();
    }?>
<STYLE>
    .nowrap{
        white-space: nowrap;
    }
</STYLE>
<SCRIPT>
    var MyURL = '<?= $Manager->webroot() . $Controller; ?>';
    var Embedded = '<?= $EmbeddedMode; ?>';

    window.onbeforeunload = function (e) {
        if (changed.length > 0) {
            var message = "Are you sure you want to quit without saving?", e = e || window.event;
            if (e) {e.returnValue = message;}// For IE and Firefox
            return message;// For Safari
        }
    };

    var columns = <?php if (is_array($Columns)) { echo javascriptarray(array_keys($Columns));} else { echo "false;"; } ?>
    var tables = <?php if (is_array($Tables)) { echo javascriptarray($Tables);} else { echo "false;"; } ?>
    var changed = new Array();
    var changedNew = false;
    function mychangeevent(ID, DoPush){
        var RET = checktags(ID, "single");
        if(DoPush) {
            var Index = changed.indexOf(ID);
            if (RET["Status"]) {
                if (Index == -1) {
                    changed.push(ID);
                }
            } else if (Index > -1) {
                changed.splice(Index, 1);
            }
        }
    }

    function removeelement(id) {
        return (elem=document.getElementById(id)).parentNode.removeChild(elem);
    }

    function save(DoAlert){
        var ID, Text = "";
        for (index = 0; index < changed.length; ++index) {
            ID = changed[index];
            if(Text){Text = Text + "&";}
            Text = Text + ID + "=" + encodeURIComponent(getinputvalue(ID));
        }
        $.ajax({
            url: MyURL,
            type: "post",
            dataType: "HTML",
            data: "action=save&key=<?= $PrimaryKey; ?>&table=<?= $Table; ?>&" + Text,
            success: function (msg) {
                if(DoAlert){alert(msg);}
                reload("");
            }
        })
        changed = new Array();
        return false;
    }

    function deleterow(ID){
        if (confirm("Are you sure you want to delete '" + ID + "'?")){
            $.ajax({
                url: MyURL,
                type: "post",
                dataType: "HTML",
                data: "action=delete&table=<?= $Table; ?>&key=<?= $PrimaryKey; ?>&id=" + ID,
                success: function (msg) {
                    alert(msg);
                    removeelement(ID);
                }
            })
        }
        return false;
    }

    function reload(URL){
        if(URL){ URL = "&" + URL; }
        if(Embedded){
            var element = document.getElementById(Embedded);
            element.innerHTML = '<TABLE WIDTH="100%;" HEIGHT="100%"><tr><td valign="middle" align="center"><IMG SRC="<?= $this->request->webroot;?>webroot/assets/global/img/loading-spinner-blue.gif"></TD></TR></TABLE>';
             $.ajax({
                url: MyURL,
                type: "get",
                dataType: "HTML",
                data: "table=<?= $Table; ?>&embedded" + URL,
                success: function (msg) {
                    element.innerHTML = msg;
                }
            })
        } else {
            window.open(MyURL + URL,"_self");
        }
    }

    function deletecolumn(Name){
        if (confirm("Are you sure you want to delete '" + Name + "'?")){
            $.ajax({
                url: MyURL,
                type: "post",
                dataType: "HTML",
                data: "action=deletecolumn&table=<?= $Table; ?>&name=" + Name,
                success: function (msg) {
                    alert(msg);
                    reload("");
                }
            })
        }
        return false;
    }

    function deletetable(Name){
        if (confirm("Are you sure you want to delete '" + Name + "'?")){
            $.ajax({
                url: MyURL,
                type: "post",
                dataType: "HTML",
                data: "action=deletetable&table=" + Name,
                success: function (msg) {
                    alert(msg);
                    removeelement("table" + Name);
                }
            })
        }
        return false;
    }

    function newtable(){
        var Name =  prompt("Please enter a table name", "New Table").toLowerCase();
        if(Name) {
            if(tables.indexOf(Name) >-1){
                alert("That table exists already");
                return false;
            }
            $.ajax({
                url: MyURL,
                type: "post",
                dataType: "HTML",
                data: "action=newtable&table=" + Name,
                success: function (msg) {
                    tables.push(Name);
                    alert(msg);
                    window.open(MyURL + "?table=" + Name ,"_self");
                }
            })
        }
        return false;
    }

    function newcol(){
        var Name = getinputvalue("newcol_name");
        if (columns.indexOf(Name) > -1){
            alert("'" + Name + "' exists already");
            return false;
        }

        $.ajax({
            url: MyURL,
            type: "post",
            dataType: "HTML",
            data: "action=newcolumn&table=<?= $Table;?>&name=" + Name + "&type=" + getinputvalue("newcol_type") + "&length=" + getinputvalue("newcol_length") + "&position=" + getinputvalue("newcol_pos"),
            success: function (msg) {
                columns.push(Name);
                alert(msg);
                reload("");
            }
        })
    }

    var downID = "";
    function handleevent(ID, eventtype, eventname){
        var element = document.getElementById(ID);
        var value = getinputvalue(ID);
        switch(eventtype){
            case 0://oncontextmenu
                if(!Embedded){
                    reload("action=edit&id=" + ID);
                    return false;
                }
                break;
            case 1://onclick

                break;
            case 2://ondblclick
                var newvalue = prompt("What would you like the new value of " + ID + " to be?", value);
                if(newvalue && value != newvalue) {
                    element.setAttribute("value", newvalue);
                    mychangeevent(ID, true);
                    save(false);
                }
                break;
            case 3://onmousedown
                downID = ID;
                break;
            case 4://onmousemove

                break;
            case 5://mouseup
                if (downID != ID) {
                    alert("Select: " + downID + " to " + ID);
                }
                break;
        }
    }

    <?php loadreasons("edit", $strings); ?>
</SCRIPT>

<DIV width="100%" height="100%" <?php if(!$EmbeddedMode){ echo 'style="overflow: auto;"';} ?>>
    <table class="table table-hover  table-striped table-bordered table-hover dataTable no-footer" style="margin:0px;">
        <THEAD><TR>
        <?php
            function checknumeric($Value){
                if (!$Value || is_numeric($Value)){return true;}
                echo '[ERROR:isNaN]';
            }

            if(isset($_GET["table"])) {
                if($PrimaryKey){
                    $events = array("oncontextmenu", "onclick", "ondblclick", "onmousedown", "onmousemove", "onmouseup");
                    foreach ($Columns as $ColumnName => $ColumnData) {
                        if(!($HTMLMode && $ColumnName == $PrimaryKey)) {
                            $Me = $ColumnName;
                            $Value = '="return handleevent(' . "'" . $Me . "', ";
                            echo "\r\n" . '<TH class="nowrap" ' . $events[0] . $Value . "0,'');" . '">';//handleevent(ID, eventtype, eventname
                            if ($ColumnName == $PrimaryKey) {
                                echo '<i class="fa fa-key"></i>';
                            }
                            if(!$HTMLMode){
                                $ColumnName = getletter($Letters, $ColumnName) . $ColumnName;
                            }
                            echo $this->Paginator->sort($ColumnName) . ' <A ONCLICK="return deletecolumn(' . "'" . $ColumnName . "'" . ');"><i class="fa fa-times"></i></A></TH>';
                        }
                    }
                    echo '</TR></THEAD><TBODY>';
                    foreach ($Data as $Row) {
                        $ID = "Data[" . $Row->$PrimaryKey . "]";
                        echo "\r\n" . '<TR ID="' . $ID . '">';
                        $First = true;
                        $NullCols=0;
                        $ColIndex=0;
                        $HasPassedPkey =false;
                        foreach ($Columns as $ColumnName => $ColumnData) {
                            $Me = $ID . '[' . $ColumnName . ']';
                            if($HTMLMode) {
                                if($NullCols || $ColumnName == $PrimaryKey){
                                    if($NullCols) {$NullCols = $NullCols-1;}
                                    if($ColumnName == $PrimaryKey){$HasPassedPkey= true;}
                                } else {
                                    $Value = '="return handleevent(' . "'" . $Me . "', ";
                                    echo '<TD ID="' . $Me . '" ';
                                    foreach($events as $index => $event){
                                        echo $event . $Value . $index . ",'" . $event . "'" . ');" ';
                                    }
                                    $Value = $Row->$ColumnName;
                                    $ColKeys = getTag($ColumnData["comment"],true);
                                    $Keys = getTag($Value, true);
                                    if($ColKeys){
                                        if($Keys){$Keys = $ColKeys . "," . $Keys;} else {$Keys = $ColKeys;}
                                    }
                                    if($Keys){
                                        $Keys = assocsplit($Keys, ",", "=");
                                        $Value = getTag($Value, false);
                                    }

                                    $Start = "";
                                    $Finish = "";

                                    echo ' VALUE="' . $Value . '"';
                                    if ($Value && substr($Value,0,1) == "="){
                                        echo ' TITLE="' . $Value . '"';
                                        $Value = substr($Value, 1, strlen($Value)-1);
                                        $Reference = array_search($ColumnName, $Letters) . $Row->$PrimaryKey;
                                        $Value = evaluate($Manager, $Table, $Value, $Reference, $Letters, $PrimaryKey);
                                    }

                                    if(is_array($Keys)) {
                                        foreach ($Keys as $Key => $Data) {
                                            $Key = strtolower(trim($Key));
                                            $Data = trim($Data);

                                            switch ($Key) {
                                                case "colspan":
                                                    if (strtolower($Data) == "all"){
                                                        $Data = count($Columns) - $ColIndex;
                                                        if(!$HasPassedPkey){$Data = $Data-1;}
                                                    }
                                                    echo ' COLSPAN="' . $Data . '"';
                                                    $NullCols = $Data - 1;
                                                    break;
                                                case "align":
                                                    echo ' ALIGN="' . $Data . '"';
                                                    break;
                                                case "bgcolor";
                                                    echo ' BGCOLOR="' . $Data . '"';
                                                    break;
                                                case "validate";
                                                    if($Value) {
                                                        $Value = $Manager->validate_data($Value, $Data);
                                                        if(!$Data){$Value = '[ERROR: Not a valid ' . $Data . ']';}
                                                    }
                                                    break;
                                                case "format";
                                                    switch (strtolower($Data)){
                                                        case "uppercase":
                                                            $Value = strtoupper($Value);
                                                            break;
                                                        case "lowercase":
                                                            $Value = strtolower($Value);
                                                            break;
                                                        case "percent":
                                                            if (checknumeric($Value)) {$Value = number_format($Value * 100, 2) . '%';}
                                                            break;
                                                        case "number":
                                                            if (checknumeric($Value)) {$Value = number_format($Value, 2);}
                                                            break;
                                                    }
                                                    break;

                                                //Inside TD tags
                                                case "bold":
                                                    $Start .= '<B>';
                                                    $Finish = '</B>' . $Finish;
                                                    break;
                                                case "italic":
                                                    $Start .= '<I>';
                                                    $Finish = '</I>' . $Finish;
                                                    break;
                                                case "underline":
                                                    $Start .= '<U>';
                                                    $Finish = '</U>' . $Finish;
                                                    break;
                                                case "fontcolor":
                                                    $Start .= '<FONT COLOR="' . $Data . '">';
                                                    $Finish = '</FONT>' . $Finish;
                                                    break;
                                                case "fontsize":
                                                    $Start .= '<FONT SIZE="' . $Data . '">';
                                                    $Finish = '</FONT>' . $Finish;
                                                    break;
                                            }
                                        }
                                    }

                                    echo '>' . $Start . $Value . $Finish . '</TD>';
                                }
                            } else {
                                $Type = "text";
                                echo '<TD style="padding: 0;" align="center">';
                                if ($ColumnName == $PrimaryKey) {
                                    echo '<A ONCLICK="return deleterow(' . "'" . $ID . "'" . ');"<i class="fa fa-times"></i>' . $Row->$PrimaryKey . '</A>';
                                } else {
                                    echo '<INPUT NAME="' . $Me . '" ID="' . $Me . '" VALUE="' . $Row->$ColumnName . '" CLASS="textinput" onchange="mychangeevent(' . "'" . $Me . "'" . ', true);"';
                                    echo ' PLACEHOLDER="' . $ColumnName . "." . $Row->$PrimaryKey . '"';
                                    switch ($ColumnData["type"]) {
                                        case "string":
                                            break;
                                        case "text":
                                            break;
                                        case "boolean":
                                            $Type = "checkbox";
                                            echo ' VALUE="True"';
                                            if ($Row->$ColumnName) {
                                                echo ' CHECKED';
                                            }
                                        case "integer":
                                            echo ' role="number"';
                                            break;
                                        case "decimal":
                                            echo ' role="number"';
                                            break;

                                        default:
                                            debug($ColumnData);
                                            die();
                                    }
                                    echo 'TYPE="' . $Type . '">';
                                }
                                echo '</TD>';
                            }
                            $ColIndex++;
                        }
                        echo '</TR>';
                    }

                    if(!$HTMLMode){
                        echo '<TR>';
                        foreach ($Columns as $ColumnName => $ColumnData) {
                            echo '<TD style="padding: 0;" align="center" class="nowrap">';
                            $ID = "Data[new]";
                            if ($ColumnName == $PrimaryKey) {
                                echo '<A onclick="return save(true);"><i class="fa fa-floppy-o"></i>New</A>';
                            } else {
                                $Me = $ID . '[' . $ColumnName . ']';
                                $Type = "text";
                                echo '<INPUT NAME="' . $Me . '" ID="' . $Me . '" " CLASS="textinput" onchange="mychangeevent(' . "'" . $Me . "'" . ', true);"';
                                echo ' PLACEHOLDER="' . $ColumnName . '.new"';
                                switch ($ColumnData["type"]) {
                                    case "boolean":
                                        $Type = "checkbox";
                                        echo ' VALUE="True"';
                                        break;
                                    case "integer":
                                        echo ' role="number"';
                                        break;
                                    case "decimal":
                                        echo ' role="number"';
                                        break;
                                }
                                echo 'TYPE="' . $Type . '">';
                            }
                            echo '</TD>';
                        }
                    }
                    echo '</TR>';
                } else {
                    echo '<TH>This table has no primary key and cannot be edited</TH></TR></THEAD><TBODY>';
                }
            } else {
                echo '<TH>Table</TH></TR></THEAD><TBODY>';
                foreach($Tables as $Table){
                    echo '<TR ID="table' . $Table . '"><TD><A onclick="return deletetable(' . "'" . $Table . "'" . ');"><i class="fa fa-times"></i></A> <A HREF="?table=' . $Table . '">' . $Table . '</A></TD></TR>';
                }
                echo '<TR><TD><A onclick="return newtable();"><i class="fa fa-floppy-o"></i> New Table</A>';
            }
        ?>
        </TBODY>
    </table>
</DIV>
<?php
    function evaluate($Manager, $Table, $Equation, $Me, $Letters, $PrimaryKey) {
        $p = new ParensParser();
        $Equation = $p->parse($Equation);
        $Equation = evaluatereferences($p, $Manager, $Table, $Equation, $Me, $Letters, $PrimaryKey);
        $Equation = $p->condense($Equation);
        $Equation = eval('return ' . $Equation . ';');
        return $Equation;
    }

    function evaluatereferences($p, $Manager, $Table, $Equation, $Me, $Letters, $PrimaryKey){
        if(is_array($Equation)){
            foreach($Equation as $Key => $Cell){
                $Equation[$Key] = evaluatereferences($p, $Manager, $Table, $Cell, $Me, $Letters, $PrimaryKey);
            }
        } else {
            $Equation = $p->splitequation($Equation);
            foreach($Equation as $Key => $Cell) {
                if (isareference($Manager, $Cell)) {
                    $Equation[$Key] = getreference($Manager, $Table, $Cell, $Me, $Letters, $PrimaryKey);
                } else if (ismultireference($Manager, $Cell)) {
                    $Data = getreferences($Manager, $Table, $Cell, $Me, $Letters, $PrimaryKey);
                    $Equation[$Key] = '[' . implode(",", $Data) . ']';
                }
            }
            $Equation = implode(" ", $Equation);
        }
        return $Equation;
    }

    function fixreferences($Manager, $Table, $IsLetter, $StartingCellColumn, $StartingCellRow, $OffsetColumn = 0, $OffsetRow = 0){
        echo "Fix: " . $Table . " starting at " . $StartingCellColumn . "(" . $IsLetter . ")," . $StartingCellRow . " Offset: " . $OffsetColumn . ',' . $OffsetRow;
        //$Columns, $Letters,
/*
        $p = new ParensParser();
        $Equation = $p->parse($Equation);

        $Equation = $p->condense($Equation);
        return $Equation;
*/
    }



class ParensParser {//https://gist.github.com/Xeoncross/4710324
    protected $stack = null;
    protected $current = null;
    protected $string = null;
    protected $position = null;
    protected $buffer_start = null;

    public function parse($string) {
        if (!$string) {return array();}
        if ($string[0] == '(') {$string = substr($string, 1, -1);}
        $this->current = array();
        $this->stack = array();
        $this->string = $string;
        $this->length = strlen($this->string);
        $haspushed = false;
        for ($this->position=0; $this->position < $this->length; $this->position++) {
            switch ($this->string[$this->position]) {
                case '(':
                    $this->push();
                    array_push($this->stack, $this->current);
                    $this->current = array();
                    $haspushed = $this->position;
                    break;
                case ')':
                    $this->push();
                    $t = $this->current;
                    $this->current = array_pop($this->stack);
                    $this->current[] = $t;
                    $haspushed = $this->position;
                    break;
                default:
                    if ($this->buffer_start === null) {$this->buffer_start = $this->position;}
            }
        }
        if($haspushed){
            $buffer = substr($string, $haspushed+1, strlen($string)-$haspushed-1);
            $this->current[] = trim($buffer);
        }
        return $this->current;
    }
    protected function push() {
        if ($this->buffer_start !== null) {
            $buffer = substr($this->string, $this->buffer_start, $this->position - $this->buffer_start);
            $this->buffer_start = null;
            $this->current[] = trim($buffer);
        }
    }

    public function condense($array, $IsFirst = true){
        if(is_array($array)){
            foreach($array as $key => $cell){
                $array[$key] = $this->condense($cell, False);
            }
            if($IsFirst){return implode("", $array);}
            return "(" . implode("", $array) . ")";
        }
        return $array;
    }

    public function splitequation($string) {
        $expr = '/[^\d.]|[\d.]++/';
        preg_match_all( $expr, $string, $return );
        $return = $return[0];
        foreach($return as $Key => $Value){
            if(!trim($Value)){unset($return[$Key]);}
        }
        $return = $this->joinLetters($return);
        return $return;
    }

    protected function joinLetters($array){
        $return = array();//a-z,A-Z,!,:,0-9   \w
        $Current = "";
        $IsLetter = false;
        foreach($array as $Value){
            $Cletter = $this->isLetter($Value);
            if ($Current && $IsLetter != $Cletter){
                array_push($return, $Current);
                $Current="";
            }
            $Current.=$Value;
            $IsLetter=$Cletter;
        }
        if($Current) {array_push($return, $Current);}
        return $return;
    }

    protected function isLetter($Text){
        if ($Text == '$') { return true;}
        return preg_replace("/[^a-zA-Z0-9:!]/", "", $Text) == true;
    }

}


    if($EmbeddedMode){
        return;
    }
?>