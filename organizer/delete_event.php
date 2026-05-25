<?php
include '../config/db.php';

$id = $_GET['id'];

$sql = "DELETE FROM events WHERE id='$id'";

if(mysqli_query($conn, $sql)) {
    header('Location: dashboard.php');
}
?>