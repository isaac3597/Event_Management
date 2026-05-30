<?php
session_start();

include '../config/db.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$event_id = $_GET['id'];

$sql = "SELECT * FROM events WHERE id='$event_id'";

$result = mysqli_query($conn, $sql);

$event = mysqli_fetch_assoc($result);

/* BUY TICKET */

if(isset($_POST['buy'])) {

    $ticket_type = $_POST['ticket_type'];

    $quantity = $_POST['quantity'];

    $user_id = $_SESSION['user_id'];
    $seat_number = $_POST['seat_number'];
    // Determine ticket price
    if($ticket_type == "Regular") {

        $price = $event['regular_price'];

    } elseif($ticket_type == "VIP") {

        $price = $event['vip_price'];

    } else {

        $price = $event['vvip_price'];
    }

    // Total
    $total_price = $price * $quantity;

    // Check ticket availability
  if($ticket_type == "Regular") {

    $available = $event['regular_tickets'];

} elseif($ticket_type == "VIP") {

    $available = $event['vip_tickets'];

} else {

    $available = $event['vvip_tickets'];
}

if($quantity > $available) 
        {

        $error = "Not enough tickets available";

    } else {

        // Insert ticket
        $insert = "INSERT INTO tickets(
                user_id,
                event_id,
                ticket_type,
                quantity,
                total_price,
                seat_number
            )
            VALUES(
                '$user_id',
                '$event_id',
                '$ticket_type',
                '$quantity',
                '$total_price',
                '$seat_number'
            )";

        if(mysqli_query($conn, $insert)) {

            // Ticket ID
            $ticket_id = mysqli_insert_id($conn);

            // Generate pass
            $pass_code = "PASS-" . rand(100000,999999);

            // QR data
            $qr_data = "
EVENT: {$event['title']}
TYPE: {$ticket_type}
TICKET ID: {$ticket_id}
USER ID: {$user_id}
PASS: {$pass_code}
STATUS: AUTHORISED
";

            // QR Library
            include '../phpqrcode/qrlib.php';

            // QR File
            $file_name = 'ticket_'.$ticket_id.'.png';

            $file_path = '../assets/qrcodes/'.$file_name;

            // Generate QR
            QRcode::png($qr_data, $file_path);

            // Save QR code
            mysqli_query($conn, "
                UPDATE tickets
                SET qr_code='$file_name'
                WHERE id='$ticket_id'
            ");

            // Update remaining tickets
            if($ticket_type == "Regular") {

    $remaining =
        $event['regular_tickets'] - $quantity;

    mysqli_query($conn, "
        UPDATE events
        SET regular_tickets='$remaining'
        WHERE id='$event_id'
    ");

} elseif($ticket_type == "VIP") {

    $remaining =
        $event['vip_tickets'] - $quantity;

    mysqli_query($conn, "
        UPDATE events
        SET vip_tickets='$remaining'
        WHERE id='$event_id'
    ");

} else {

    $remaining =
        $event['vvip_tickets'] - $quantity;

    mysqli_query($conn, "
        UPDATE events
        SET vvip_tickets='$remaining'
        WHERE id='$event_id'
    ");
}

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

        <a href="../index.php">
            Home
        </a>

        <a href="events.php">
            Events
        </a>

        <a href="my_tickets.php">
            My Tickets
        </a>

        <a href="../auth/logout.php">
            Logout
        </a>

    </div>

    <h1>Buy Ticket</h1>

    <?php if(isset($success)) { ?>

        <div class="success">
            <?php echo $success; ?>
        </div>

    <?php } ?>

    <?php if(isset($error)) { ?>

        <div class="error">
            <?php echo $error; ?>
        </div>

    <?php } ?>

    <div class="event-card">

        <img
            src="../assets/uploads/<?php echo $event['image']; ?>"
            class="event-image"
        >

        <h2>
            <?php echo $event['title']; ?>
        </h2>

        <p>
            <?php echo $event['description']; ?>
        </p>

        <p>
            <strong>Location:</strong>
            <?php echo $event['location']; ?>
        </p>

        <p>
            <strong>Date:</strong>
            <?php echo $event['event_date']; ?>
        </p>

        <p>
    <strong>Regular Tickets:</strong>
    <?php echo $event['regular_tickets']; ?>
</p>

<p>
    <strong>VIP Tickets:</strong>
    <?php echo $event['vip_tickets']; ?>
</p>

<p>
    <strong>VVIP Tickets:</strong>
    <?php echo $event['vvip_tickets']; ?>
</p>

    </div>

    <form method="POST">

        <label>Ticket Type</label>

        <select
            name="ticket_type"
            id="ticket_type"
            required
        >

            <option value="">
                Select Ticket Type
            </option>

            <option
                value="Regular"
                data-price="<?php echo $event['regular_price']; ?>"
            >
                Regular -
                KSH <?php echo $event['regular_price']; ?>
            </option>

            <option
                value="VIP"
                data-price="<?php echo $event['vip_price']; ?>"
            >
                VIP -
                KSH <?php echo $event['vip_price']; ?>
            </option>

            <option
                value="VVIP"
                data-price="<?php echo $event['vvip_price']; ?>"
            >
                VVIP -
                KSH <?php echo $event['vvip_price']; ?>
            </option>

        </select>

        <label>M-Pesa Number</label>

        <input
            type="text"
            name="phone"
            placeholder="254712345678"
            required
        >

        <label>Number of Tickets</label>
        </select><br><br>
        <input
    type="number"
    id="quantity"
    name="quantity"
    min="1"
    required
>

        <label>Select Seat</label>

<select name="seat_number" required>

    <option value="">
        Choose Seat
    </option>

    <?php

    $seats = [
        "A1","A2","A3","A4","A5",
        "B1","B2","B3","B4","B5",
        "C1","C2","C3","C4","C5"
    ];

    foreach($seats as $seat) {

        // CHECK IF ALREADY BOOKED
        $checkSeat = mysqli_query(
            $conn,
            "SELECT * FROM tickets
             WHERE event_id='$event_id'
             AND seat_number='$seat'"
        );

        if(mysqli_num_rows($checkSeat) == 0) {

            echo "
            <option value='$seat'>
                $seat
            </option>
            ";
        }
    }

    ?>



        <label>Total Amount</label>

        <input
            type="hidden"
            name="amount"
            id="amount"
        >

        <input
            type="text"
            id="total"
            readonly
        >

        <button
            type="submit"
            name="buy"
        >
            Pay with M-Pesa
        </button>

    </form>

</div>

<script>

const quantityInput =
    document.getElementById('quantity');

const totalInput =
    document.getElementById('total');

const ticketType =
    document.getElementById('ticket_type');

function calculateTotal() {

    const selectedOption =
        ticketType.options[ticketType.selectedIndex];

    const price =
        selectedOption.getAttribute('data-price');

    const quantity =
        quantityInput.value;

    const total =
        price * quantity;

    totalInput.value = "KSH " + total;

    document.getElementById('amount').value = total;
}

quantityInput.addEventListener(
    'input',
    calculateTotal
);

ticketType.addEventListener(
    'change',
    calculateTotal
);

</script>

</body>
</html>
```
