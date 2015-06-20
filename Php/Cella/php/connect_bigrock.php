<?php
// db info
$hostname="localhost";
$mysql_login="mindnl4r_root";
$mysql_password="bharat1980";
$database="mindnl4r_cella";

if (!($db = mysql_connect($hostname, $mysql_login , $mysql_password))){
  die("Can't connect to mysql.");    
}else{
  if (!(mysql_select_db("$database",$db)))  {
    die("Can't connect to db.");
  }
}
?> 