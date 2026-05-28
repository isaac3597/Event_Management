<?php
session_start();

include '../config/db.php';

require('../fpdf/fpdf.php');

if(!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

$event_id = $_GET['id'];

// Event details
$event_query = mysqli_query($conn, "
    SELECT * FROM events
    WHERE id='$event_id'
");

$event = mysqli_fetch_assoc($event_query);

// Ticket holders
$sql = "SELECT
            tickets.*,
            users.fullname,
            users.email
        FROM tickets

        JOIN users
        ON tickets.user_id = users.id

        WHERE tickets.event_id='$event_id'

        ORDER BY tickets.id DESC";

$result = mysqli_query($conn, $sql);

// Revenue
$revenue_query = mysqli_query($conn, "
    SELECT SUM(total_price) AS revenue
    FROM tickets
    WHERE event_id='$event_id'
");

$revenue = mysqli_fetch_assoc($revenue_query);

// PDF
$pdf = new FPDF();

$pdf->AddPage();

$pdf->SetFont('Arial','B',18);

$pdf->Cell(190,10,'Event Report',0,1,'C');

$pdf->Ln(5);

$pdf->SetFont('Arial','B',12);

$pdf->Cell(190,10,'Event: '.$event['title'],0,1);

$pdf->Cell(190,10,'Location: '.$event['location'],0,1);

$pdf->Cell(190,10,'Date: '.$event['event_date'],0,1);

$pdf->Cell(
    190,
    10,
    'Revenue: KSH '.($revenue['revenue'] ?? 0),
    0,
    1
);

$pdf->Ln(10);

// Table Header
$pdf->Cell(10,10,'#',1);

$pdf->Cell(50,10,'Name',1);

$pdf->Cell(60,10,'Email',1);

$pdf->Cell(30,10,'Tickets',1);

$pdf->Cell(40,10,'Amount',1);

$pdf->Ln();

$count = 1;

while($row = mysqli_fetch_assoc($result)) {

    $pdf->Cell(10,10,$count,1);

    $pdf->Cell(50,10,$row['fullname'],1);

    $pdf->Cell(60,10,$row['email'],1);

    $pdf->Cell(30,10,$row['quantity'],1);

    $pdf->Cell(
        40,
        10,
        'KSH '.$row['total_price'],
        1
    );

    $pdf->Ln();

    $count++;
}

$pdf->Output(
    'D',
    $event['title'].'_report.pdf'
);
?>