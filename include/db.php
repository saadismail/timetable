<?php

define('DB_USER', "tt"); // db user  
define('DB_PASSWORD', "tt1234"); // db password  
define('DB_DATABASE', "timetable"); // database name  
define('DB_SERVER', "localhost"); // db server/ host name  

$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);

if (!$conn) {
    die("Connection failed: ".$conn->connect_errorno);
}

define('DB_USER', "tt_pwr"); // db user  
define('DB_PASSWORD', "tt_pwr1234"); // db password  
define('DB_DATABASE', "tt_pwr"); // database name  
define('DB_SERVER', "localhost"); // db server/ host name  

$conn_pwr = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);

if (!$conn_pwr) {
    die("Connection failed: ".$conn->connect_errorno);
}

?>