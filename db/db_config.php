<?php

$localhost="localhost";
$root_name="root";
$db_name="verifyUser";
$db_pass="";

try {
$pdo=new PDO("mysql:host=$localhost;dbname=$db_name",$root_name,$db_pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

if(!$pdo){
    echo "Dtabse connection failed";
}
} catch (PDOException $e) {
 die("An error has occured ".$e->getMessage());
}