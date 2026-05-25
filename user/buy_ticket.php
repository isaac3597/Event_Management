<?php
session_start();
include '../config/db.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

if(!isset($_GET['id'])) {
    die("Event ID missing");
}

$event_id = $_GET['id'];

$query = "SELECT * FROM events WHERE id='$event_id'";
$result = mysqli_query($conn, $query);

$event = mysqli_fetch_assoc($result);

if(!$event) {
    die("Event not found");
}

if(isset($_POST['buy'])) {

    $quantity = $_POST['quantity'];

    // Check available tickets
    if($quantity > $event['available_tickets']) {
        $error = "Not enough tickets available";
    } else {

        $user_id = $_SESSION['user_id'];

        $total_price = $quantity * $event['ticket_price'];

        // Save ticket purchase
        $insert = "INSERT INTO tickets(
                        user_id,
                        event_id,
                        quantity,
                        total_price
                    )
                    VALUES(
                        '$user_id',
                        '$event_id',
                        '$quantity',
                        '$total_price'
                    )";

        if(mysqli_query($conn, $insert)) {

            // Update remaining tickets
            $remaining = $event['available_tickets'] - $quantity;

            $update = "UPDATE events
                       SET available_tickets='$remaining'
                       WHERE id='$event_id'";

            mysqli_query($conn, $update);

            $success = "Ticket purchased successfully";

            // Refresh event data
            $query = "SELECT * FROM events WHERE id='$event_id'";
            $result = mysqli_query($conn, $query);
            $event = mysqli_fetch_assoc($result);

        } else {
            $error = "Error purchasing ticket";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Buy Ticket</title>
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

    <h1>Buy Ticket</h1>

    <?php if(isset($success)) { ?>
        <p style="color:green;"><?php echo $success; ?></p>
    <?php } ?>

    <?php if(isset($error)) { ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php } ?>

    <div class="event-card">

        <h2><?php echo $event['title']; ?></h2>

        <p><?php echo $event['description']; ?></p>

        <p>
            <strong>Location:</strong>
            <?php echo $event['location']; ?>
        </p>

        <p>
            <strong>Date:</strong>
            <?php echo $event['event_date']; ?>
        </p>

        <p>
            <strong>Ticket Price:</strong>
            $<?php echo $event['ticket_price']; ?>
        </p>

        <p>
            <strong>Tickets Left:</strong>
            <?php echo $event['available_tickets']; ?>
        </p>

    </div>

    <form method="POST" action="mpesa_payment.php">

        <label>Number of Tickets</label>

        <input
            type="number"
            name="quantity"
            min="1"
            max="<?php echo $event['available_tickets']; ?>"
            required
        >
        <input
    type="text"
    name="phone"
    placeholder="Enter M-Pesa Number e.g 254712345678"
    required
>
        <button type="submit" name="buy">
    Pay with M-Pesa
        </button>

    </form>

</div>

</body>
</html>