<?php
session_start();

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'organizer') {
    header('Location: ../auth/login.php');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Organizer Dashboard</title>
</head>
<body>

<h1>Welcome <?php echo $_SESSION['fullname']; ?></h1>

<a href="create_event.php">Create Event</a>

</body>
</html>


