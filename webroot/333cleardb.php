<?php
mysql_connect('localhost','root','');
mysql_select_db('veritas3')or die(mysql_error());

$result = mysql_query("show tables"); // run the query and assign the result to $result
while($table = mysql_fetch_array($result)) { // go through each row that was returned in $result
    if($table[0]!= "settings " &&$table[0]!="profiles"&&$table[0]!= "logos"&&$table[0]!="sidebar"&&$table[0]!="subdocuments")
    {
        mysql_query("TRUNCATE TABLE ".$table[0]);
        //echo $table[0]."<br/>";
    } 
}
echo "Cleared";
