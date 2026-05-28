<?php
session_start();

include '../config/db.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$id = $_GET['id'];

// Delete tickets first
mysqli_query($conn, "
    DELETE FROM tickets
    WHERE event_id='$id'
");

// Delete event
mysqli_query($conn, "
    DELETE FROM events
    WHERE id='$id'
");

header('Location: manage_events.php');
?>