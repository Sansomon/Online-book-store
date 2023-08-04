<?php 

#  Nombre Servidor
$sName = "localhost";
# Nombre de usuario
$uName = "root";
# clave
$pass = "";

# nombre de la base de datos
$db_name = "online_book_store_db";

try {
    $conn = new PDO("mysql:host=$sName;dbname=$db_name", 
                    $uName, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
  echo "Connection failed : ". $e->getMessage();
}