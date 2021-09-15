<?php
$host        = "103.82.241.96"; //localhost server
$db_user     = "tkjborid_db_admin1"; //database username
$db_password = "DNwipW^aPBFi"; //database password
$db_name     = "tkjborid_db_terumbu_karang"; //database name
$dsn = 'mysql:host=' . $host . ';dbname=' . $db_name;
global $pdo;
$pdo = new PDO($dsn, $db_user, $db_password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
setlocale(LC_ALL,  'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'id_ID', 'IND.UTF8', 'IND.UTF-8', 'IND.8859-1', 'IND', 'Indonesian.UTF8', 'Indonesian.UTF-8', 'Indonesian.8859-1', 'Indonesian', 'Indonesia', 'id', 'ID', 'en_US.UTF8', 'en_US.UTF-8', 'en_US.8859-1', 'en_US', 'American', 'ENG', 'English');
date_default_timezone_set("Asia/Bangkok");
$conn = mysqli_connect($host, $db_user, $db_password, $db_name);
