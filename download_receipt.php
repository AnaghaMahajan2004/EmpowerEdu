<?php
// Include FPDF library
require('src/fpdf.php');

// Start the session
session_start();

// Check if the session data exists
if (!isset($_SESSION['username'], $_SESSION['email'], $_SESSION['phone'], $_SESSION['plan'], $_SESSION['amount'])) {
    die("No payment details found. Please complete the payment first.");
}

// Retrieve data from session
$username = htmlspecialchars($_SESSION['username']);
$email = htmlspecialchars($_SESSION['email']);
$phone = htmlspecialchars($_SESSION['phone']);
$plan = htmlspecialchars($_SESSION['plan']);
$amount = number_format(($_SESSION['amount'] / 100), 2); // Convert amount to INR format
$date = date('d-m-Y H:i:s'); // Current date and time

// Create a temporary file to store the PDF
$pdfFile = tempnam(sys_get_temp_dir(), 'receipt') . '.pdf';

// Generate the PDF
$pdf = new FPDF();
$pdf->AddPage();
// $pdf->SetFont('Helvetica', 'B', 16);



// Set the fill color (e.g., light yellow)
$pdf->SetFillColor(234, 152, 255); // RGB values for the color
$pdf->Rect(0, 0, $pdf->GetPageWidth(), $pdf->GetPageHeight(), 'F'); // 'F' for fill

$pdf->Image('borderImage.png', 0, 0, 210, 297);

// Set the image as the header
$pdf->Image('PaymentReceipt.png', 10, 35, 190, 30); // Adjust position and size

$pdf->Image('PaymentReceiptImg.png', 76, 60, 60, 60); // Adjust position and size

$pdf->Ln(110);

// Add receipt details
$pdf->SetFont('Courier', 'B', 14);

$pdf->SetX(30);
$pdf->Cell(40, 10, 'Date:', 0, 0);
$pdf->Cell(0, 10, $date, 0, 1);

$pdf->SetX(30);
$pdf->Cell(40, 10, 'Name:', 0, 0);
$pdf->Cell(0, 10, $username, 0, 1);

$pdf->SetX(30);
$pdf->Cell(40, 10, 'Email:', 0, 0);
$pdf->Cell(0, 10, $email, 0, 1);

$pdf->SetX(30);
$pdf->Cell(40, 10, 'Phone:', 0, 0);
$pdf->Cell(0, 10, $phone, 0, 1);

$pdf->SetX(30);
$pdf->Cell(40, 10, 'Plan:', 0, 0);
$pdf->Cell(0, 10, $plan, 0, 1);

$pdf->SetX(30);
$pdf->Cell(40, 10, 'Amount Paid:', 0, 0);
$pdf->Cell(0, 10, "INR $amount", 0, 1);

$pdf->Ln(10);

// Thank you message
$pdf->Image('ThankYou.png', 25, 180, 160, 30); // Adjust position and size

// Save the PDF to the temporary file
$pdf->Output('F', $pdfFile);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <style>
        body {
            background-color: #504403;
            background-image: url("paymentdonebg.png");
        }

        h1 {
            text-align: center;
            /* Centers the text horizontally */
            font-family: 'Times New Roman', serif;
            /* Set a suitable font */
            font-size: 50px;
            /* Adjust font size */
            margin-top: 20px;
            /* Adds spacing above the header */
            color: white;
        }
    </style>
</head>

<body>
    <h1>𝓟𝓪𝔂𝓶𝓮𝓷𝓽 𝓡𝓮𝓬𝓮𝓲𝓹𝓽</h1>
    <iframe src="data:application/pdf;base64,<?php echo base64_encode(file_get_contents($pdfFile)); ?>" width="70%"
        height="650px" style="border: none; display: block; margin: 0 auto;">
    </iframe>

    <br>
</body>

</html>