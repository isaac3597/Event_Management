<?php
session_start();

include '../config/db.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$organizer_id = $_SESSION['user_id'];

$sql = "SELECT * FROM events
        WHERE organizer_id='$organizer_id'
        ORDER BY id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>

<head>

    <title>Manage Events</title>

    <link rel="stylesheet" href="../assets/style.css">

</head>

<body>

<div class="container">

    <div class="navbar">

        <a href="create_event.php">
            Create Event
        </a>

        <a href="manage_events.php">
            Manage Events
        </a>

        <a href="../auth/logout.php">
            Logout
        </a>
      
    </div>

    <h1>Manage My Events</h1>

    <?php while($row = mysqli_fetch_assoc($result)) { ?>

        <?php

        // Tickets sold
        $ticket_sql = mysqli_query($conn, "
            SELECT SUM(quantity) AS total_tickets
            FROM tickets
            WHERE event_id='{$row['id']}'
        ");

        $ticket_data = mysqli_fetch_assoc($ticket_sql);

        // Revenue
        $revenue_sql = mysqli_query($conn, "
            SELECT SUM(total_price) AS revenue
            FROM tickets
            WHERE event_id='{$row['id']}'
        ");

        $revenue_data = mysqli_fetch_assoc($revenue_sql);

        ?>

        <div class="event-card">

            <img
                src="../assets/uploads/<?php echo $row['image']; ?>"
                class="event-image"
            >

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
                <strong>Date:</strong>
                <?php echo $row['event_date']; ?>
            </p>

            <p>
    <strong>Regular Tickets:</strong>
    <?php echo $row['regular_tickets']; ?>
</p>

<p>
    <strong>VIP Tickets:</strong>
    <?php echo $row['vip_tickets']; ?>
</p>

<p>
    <strong>VVIP Tickets:</strong>
    <?php echo $row['vvip_tickets']; ?>
</p>

            <p>
                <strong>Tickets Sold:</strong>
                <?php echo $ticket_data['total_tickets'] ?? 0; ?>
            </p>

            <p>
                <strong>Total Revenue:</strong>
                KSH <?php echo $revenue_data['revenue'] ?? 0; ?>
            </p>

            <a href="event_report.php?id=<?php echo $row['id']; ?>">

                <button>
                    Generate Event Report
                </button>

            </a>

            <br><br>

            <a
                href="delete_event.php?id=<?php echo $row['id']; ?>"
                onclick="return confirm('Delete this event?')"
            >

                <button>
                    Delete Event
                </button>

            </a>

        </div>

    <?php } ?>

</div>
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