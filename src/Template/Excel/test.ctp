<DIV ID="exceltest" style="width: 400px; height: 400px; overflow: scroll; border-style: solid; ">
<?php
    $Table = "test";
    $EmbeddedMode="exceltest";
    $DIR = str_replace('\webroot/', '/', getcwd() . "/src/Template/Excel/index.ctp");
    include($DIR);
?>
</DIV>