<?php 

$sName = "sql117.lh.pl";
$uName = "serwer257591_todo";
$pass = "g#P@Q&3L";
$db_name = "serwer257591_todo";

try {
    $conn = new PDO("mysql:host=$sName;dbname=$db_name", 
                    $uName, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
  echo "Connection failed : ". $e->getMessage();
}