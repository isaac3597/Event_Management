<?php
include '../config/db.php';

$sql = "SELECT events.*, users.fullname
        FROM events
        JOIN users ON events.organizer_id = users.id
        ORDER BY event_date ASC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Events</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="container">

<h1>Upcoming Events</h1>

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<div class="event-card">
    <img
    src="../assets/uploads/<?php echo $row['image']; ?>"
    class="event-image"
>

    <h2><?php echo $row['title']; ?></h2>

    <p><?php echo $row['description']; ?></p>

    <p><strong>Location:</strong> <?php echo $row['location']; ?></p>

    <p><strong>Date:</strong> <?php echo $row['event_date']; ?></p>

    <p><strong>Organizer:</strong> <?php echo $row['fullname']; ?></p>

    <p><strong>Ticket Price:</strong> $<?php echo $row['ticket_price']; ?></p>

    <p><strong>Tickets Left:</strong> <?php echo $row['available_tickets']; ?></p>

    <a href="buy_ticket.php?id=<?php echo $row['id']; ?>">
        <button>Buy Ticket</button>
    </a>

</div>

<?php } ?>

</div>

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
        <a href="../index.php">Home</a>
        <a href="events.php">Events</a>
        <a href="my_tickets.php">My Tickets</a>
        <a href="../auth/logout.php">Logout</a>
    </div>

    <!-- PAGE CONTENT HERE -->

</div>

</body>
</html>