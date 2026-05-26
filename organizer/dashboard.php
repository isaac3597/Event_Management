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


<!DOCTYPE html>
<html>
<head>
    <title>Event Management System</title>
    <link rel="icon" type="image/x-icon" href="../assets/favicon.ico">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="navbar">

    <a href="create_event.php">
        Create Event
    </a>

    <a href="generate_report.php">
        Download Ticket Holders Report
    </a>

    <a href="../index.php">
        Home
    </a>

    <a href="../auth/logout.php">
        Logout
    </a>

</div>

</body>
</html>