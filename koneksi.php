<?php 
$server   = "localhost";
$user     = "root";
$password = "";
$database = "slrt";

// Membuat koneksi
$koneksi = mysqli_connect($server, $user, $password, $database);

// Periksa koneksi
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}



    // Fungsi untuk mengamankan dan memproses nilai input ke dalam query SQL
    if (!function_exists("GetSQLValueString")) {
        function GetSQLValueString($koneksi, $theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
        {
            // Mengamankan input dengan mysqli_real_escape_string
            $theValue = mysqli_real_escape_string($koneksi, $theValue);

            switch ($theType) {
                case "text":
                    $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                    break;
                case "long":
                case "int":
                    $theValue = ($theValue != "") ? intval($theValue) : "NULL";
                    break;
                case "double":
                    $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
                    break;
                case "date":
                    $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                    break;
                case "defined":
                    $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
                    break;
            }
            return $theValue;
        }
    }
?>


