<?php
require __DIR__ . '/vendor/autoload.php';

use Mpdf\Mpdf;
use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output;

// Initialize mPDF
$mpdf = new Mpdf();

// Option 1: Embed a QR code image manually
$qrCode = new QrCode('https://google.com');
$output = new Output\Png(); // use PNG output
$imageData = $output->output($qrCode, 300); // 300px size

// Convert to base64 and embed as image
$html = '
<h2>Testing mPDF QR Code</h2>
<img src="data:image/png;base64,' . base64_encode($imageData) . '" width="150">
<p>This QR code should appear below:</p>
';

$mpdf->WriteHTML($html);
$mpdf->Output();
