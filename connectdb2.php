<?php

$server = "localhost";
$username = "root";
$password = "";
$database = "siakad_db";

// Koneksi dan memilih database di server
mysql_connect($server,$username,$password) or die("Koneksi gagal");
mysql_select_db($database) or die("Database tidak bisa dibuka");

$_ProductName = "Sistem Penerimaan Mahasiswa Baru";
$_Institution = 'Universitas Ekasakti';
$_Identitas = "SISFO";
$_Author = "Arisal Yanuarafi";
$_AuthorEmail = "dinal@bunghatta.ac.id";
$_URL = "";

if (!defined('KodeID')) define('KodeID', $_Identitas);

?>
