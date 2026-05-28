<?php
session_start();
include '../config/db.php';

if(isset($_POST['create'])) {

    $title = $_POST['title'];
    $description = $_POST['description'];
    $location = $_POST['location'];
    $event_date = $_POST['event_date'];

    // ADD THESE NEW LINES
    $regular_price = $_POST['regular_price'];

    $vip_price = $_POST['vip_price'];

    $vvip_price = $_POST['vvip_price'];
   $regular_tickets = $_POST['regular_tickets'];

   $vip_tickets = $_POST['vip_tickets'];

   $vvip_tickets = $_POST['vvip_tickets'];

    $organizer_id = $_SESSION['user_id'];

    $image_name = $_FILES['image']['name'];

$tmp_name = $_FILES['image']['tmp_name'];

$folder = "../assets/uploads/" . $image_name;

move_uploaded_file($tmp_name, $folder);
    // REPLACE OLD SQL WITH THIS
  $sql = "INSERT INTO events(
            organizer_id,
            title,
            description,
            location,
            event_date,
            regular_price,
            vip_price,
            vvip_price,
            regular_tickets,
            vip_tickets,
            vvip_tickets,
            image
        )
        VALUES(
            '$organizer_id',
            '$title',
            '$description',
            '$location',
            '$event_date',
            '$regular_price',
            '$vip_price',
            '$vvip_price',
            '$regular_tickets',
            '$vip_tickets',
            '$vvip_tickets',
            '$image_name'
        )";

    if(mysqli_query($conn, $sql)) {

    header('Location: manage_events.php');

    exit();

} else {

    $error = "Failed to create event";
}
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Event</title>
</head>
<body>

<h2>Create Event</h2>
<div class="container">

    <div class="navbar">
        <a href="../index.php">Home</a>
        <a href="events.php">Events</a>
        <a href="my_tickets.php">My Tickets</a>
        <a href="../auth/logout.php">Logout</a>
    </div>

    <!-- PAGE CONTENT HERE -->

</div>

<form method="POST" enctype="multipart/form-data">
    <input
    type="file"
    name="image"
    accept="image/*"
    required
><br><br>

    <input 
        type="text" 
        name="title" 
        placeholder="Event Title" 
        required
    ><br><br>

    <textarea 
        name="description" 
        placeholder="Description"
    ></textarea><br><br>

    <input 
        type="text" 
        name="location" 
        placeholder="Location"
    >

   <h3>Ticket Pricing</h3>

<input
    type="number"
    step="0.01"
    name="regular_price"
    placeholder="Regular Ticket Price"
    required
><br><br>

<input
    type="number"
    step="0.01"
    name="vip_price"
    placeholder="VIP Ticket Price"
    required
><br><br>

<input
    type="number"
    step="0.01"
    name="vvip_price"
    placeholder="VVIP Ticket Price"
    required
><br><br>

    <h3>Ticket Capacity</h3>

<input
    type="number"
    name="regular_tickets"
    placeholder="Regular Capacity"
    required
><br><br>

<input
    type="number"
    name="vip_tickets"
    placeholder="VIP Capacity"
    required
><br><br>

<input
    type="number"
    name="vvip_tickets"
    placeholder="VVIP Capacity"
    required
><br><br>

    <input 
        type="date" 
        name="event_date" 
        required
    ><br><br>

    <button type="submit" name="create">
        Create Event
    </button>

</form>

</body>
</html>


<!DOCTYPE html>
<html>
<head>
    <title>Event Management System</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>



</body>
</html>