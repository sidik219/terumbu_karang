<?php
$koneksi = mysqli_connect('localhost','root','') or die ('Koneksi Tidak Ada!');
                mysqli_select_db($koneksi, 'db_terumbu_karang') or die ('Database Tidak Ada!');

$host        = "localhost"; //localhost server
$db_user     = "root"; //database username
$db_password = ""; //database password
$db_name     = "db_terumbu_karang"; //database name

$dsn = 'mysql:host=' . $host . ';dbname=' . $db_name;

global $pdo;
$pdo = new PDO($dsn, $db_user, $db_password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
?> 