<?php
// db info
$hostname="localhost";
$mysql_login="kapdabq5_cella";
$mysql_password="siddha2007";
$database="kapdabq5_cella";

if (!($db = mysql_connect($hostname, $mysql_login , $mysql_password))){
  die("Can't connect to mysql.");    
}else{
  if (!(mysql_select_db("$database",$db)))  {
    die("Can't connect to db.");
  }
}
?> 