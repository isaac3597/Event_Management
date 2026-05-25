<?php
session_start();
include '../config/db.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT tickets.*, events.title, events.event_date
        FROM tickets
        JOIN events ON tickets.event_id = events.id
        WHERE tickets.user_id='$user_id'
        ORDER BY tickets.purchase_date DESC";

$result = mysqli_query($conn, $sql);
?>