<?php
session_start();

include '../config/db.php';

if($_SESSION['role'] != 'admin') {
    exit('Access Denied');
}

$id = $_GET['id'];

mysqli_query($conn, "
    UPDATE users
    SET status='approved'
    WHERE id='$id'
");

header('Location: dashboard.php');
?>