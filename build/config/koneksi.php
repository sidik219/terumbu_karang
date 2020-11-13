<?php
	$koneksi = mysqli_connect('localhost','root','') or die ('Koneksi Tidak Ada!');
                mysqli_select_db($koneksi, 'db_terumbu_karang') or die ('Database Tidak Ada!');
?>