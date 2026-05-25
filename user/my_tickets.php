<?php
session_start();
include '../config/db.php';

// Check login
if(!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch tickets
$sql = "SELECT 
            tickets.*,
            events.title,
            events.description,
            events.location,
            events.event_date
        FROM tickets
        JOIN events
        ON tickets.event_id = events.id
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

    <div class="navbar">
        <a href="../index.php">Home</a>
        <a href="events.php">Events</a>
        <a href="my_tickets.php">My Tickets</a>
        <a href="../auth/logout.php">Logout</a>
    </div>

    <h1>My Purchased Tickets</h1>

    <?php if(mysqli_num_rows($result) > 0) { ?>

        <?php while($row = mysqli_fetch_assoc($result)) { ?>

            <div class="event-card">

                <h2>
                    <?php echo $row['title']; ?>
                </h2>

                <p>
                    <?php echo $row['description']; ?>
                </p>

                <p>
                    <strong>Location:</strong>
                    <?php echo $row['location']; ?>
                </p>

                <p>
                    <strong>Event Date:</strong>
                    <?php echo $row['event_date']; ?>
                </p>

                <p>
                    <strong>Tickets Bought:</strong>
                    <?php echo $row['quantity']; ?>
                </p>

                <p>
                    <strong>Total Paid:</strong>
                    KSH <?php echo $row['total_price']; ?>
                </p>

                <p>
                    <strong>Purchase Date:</strong>
                    <?php echo $row['purchase_date']; ?>
                </p>
                <p>
    <strong>Access Pass:</strong>
    AUTHORISED
</p>

<img
    src="../assets/qrcodes/<?php echo $row['qr_code']; ?>"
    width="200"
    style="margin-top:20px; border-radius:10px;"
>

            </div>

        <?php } ?>

    <?php } else { ?>

        <div class="event-card">

            <h2>No Tickets Found</h2>

            <p>
                You have not purchased any tickets yet.
            </p>

            <a href="events.php">
                <button>
                    Browse Events
                </button>
            </a>

        </div>

    <?php } ?>

</div>

</body>
</html>