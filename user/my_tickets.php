<?php
session_start();
include '../config/db.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT tickets.*, events.title, events.event_date
        FROM tickets
        JOIN events ON tickets.event_id = events.id
        WHERE tickets.user_id='$user_id'
        ORDER BY tickets.purchase_date DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Tickets</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="container">

<h1>My Tickets</h1>

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<div class="event-card">

    <h2><?php echo $row['title']; ?></h2>

    <p><strong>Event Date:</strong> <?php echo $row['event_date']; ?></p>

    <p><strong>Quantity:</strong> <?php echo $row['quantity']; ?></p>

    <p><strong>Total Paid:</strong> $<?php echo $row['total_price']; ?></p>

    <p><strong>Purchased:</strong> <?php echo $row['purchase_date']; ?></p>

</div>

<?php } ?>

</div>

</body>
</html>