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

    if($quantity > $event['available_tickets']) {

        $error = "Not enough tickets available";

    } else {

        $user_id = $_SESSION['user_id'];

        $total_price = $quantity * $event['ticket_price'];

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

            // Get inserted ticket ID
            $ticket_id = mysqli_insert_id($conn);

            // Generate pass code
            $pass_code = "PASS-" . rand(100000,999999);

            // QR content
            $qr_data = "
EVENT: {$event['title']}
TICKET ID: {$ticket_id}
USER ID: {$user_id}
PASS: {$pass_code}
STATUS: AUTHORISED
";

            // Include QR library
            include '../phpqrcode/qrlib.php';

            // File name
            $file_name = 'ticket_'.$ticket_id.'.png';

            // Save path
            $file_path = '../assets/qrcodes/'.$file_name;

            // Generate QR
            QRcode::png($qr_data, $file_path);

            // Save QR name
            mysqli_query($conn, "
                UPDATE tickets
                SET qr_code='$file_name'
                WHERE id='$ticket_id'
            ");

            // Update ticket count
            $remaining = $event['available_tickets'] - $quantity;

            mysqli_query($conn, "
                UPDATE events
                SET available_tickets='$remaining'
                WHERE id='$event_id'
            ");

            $success = "Ticket purchased successfully";

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

    <form method="POST">

    <label>M-Pesa Number</label>

    <input
        type="text"
        name="phone"
        placeholder="Enter M-Pesa Number e.g 254712345678"
        required
    >

    <label>Number of Tickets</label>

    <input
        type="number"
        id="quantity"
        name="quantity"
        min="1"
        max="<?php echo $event['available_tickets']; ?>"
        required
    >

    <label>Total Amount</label>

    <input
        type="text"
        id="total"
        readonly
    >

    <button type="submit" name="buy">
        Pay with M-Pesa
    </button>

</form>

</div>
<script>

const quantityInput = document.getElementById('quantity');

const totalInput = document.getElementById('total');

const ticketPrice = <?php echo $event['ticket_price']; ?>;

quantityInput.addEventListener('input', function() {

    let quantity = this.value;

    let total = quantity * ticketPrice;

    totalInput.value = "KSH " + total;

});

</script>
</body>
</html>