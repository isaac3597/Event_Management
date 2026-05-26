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
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="container">

    <div class="navbar">
        <a href="generate_report.php">
    <button>
        Download Ticket Holders Report
    </button>
</a>
        <a href="../index.php">Home</a>
        <a href="events.php">Events</a>
        <a href="my_tickets.php">My Tickets</a>
        <a href="../auth/logout.php">Logout</a>
    </div>

    <!-- PAGE CONTENT HERE -->

</div>

</body>
</html>