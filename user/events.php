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

    <div class="navbar">
        <a href="../index.php">Home</a>
        <a href="events.php">Events</a>
        <a href="my_tickets.php">My Tickets</a>
        <a href="../auth/logout.php">Logout</a>
        
    </div>

    <!-- PAGE CONTENT HERE -->

</div>
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

    <div class="ticket-prices">

    <p>
        <strong>Regular:</strong>
        KSH <?php echo $row['regular_price']; ?>
    </p>

    <p>
        <strong>VIP:</strong>
        KSH <?php echo $row['vip_price']; ?>
    </p>

    <p>
        <strong>VVIP:</strong>
        KSH <?php echo $row['vvip_price']; ?>
    </p>

</div>

    <p>
    <strong>Regular Seats:</strong>
    <?php echo $row['regular_tickets']; ?>
</p>

<p>
    <strong>VIP Seats:</strong>
    <?php echo $row['vip_tickets']; ?>
</p>

<p>
    <strong>VVIP Seats:</strong>
    <?php echo $row['vvip_tickets']; ?>
</p>

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
<script>

const darkBtn =
    document.getElementById('darkModeBtn');

// Load saved mode
if(localStorage.getItem('darkMode') === 'enabled') {

    document.body.classList.add('dark-mode');
}

darkBtn.addEventListener('click', () => {

    document.body.classList.toggle('dark-mode');

    // Save mode
    if(document.body.classList.contains('dark-mode')) {

        localStorage.setItem(
            'darkMode',
            'enabled'
        );

    } else {

        localStorage.setItem(
            'darkMode',
            'disabled'
        );
    }
});

</script>


</body>
</html>