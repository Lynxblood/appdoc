<body>
<?php
// document.php (located at /auth/document.php)

// ⚠️ Configuration and Autoload
// Ensure these paths are correct relative to document.php
require '../config/dbcon.php'; 
require '../vendor/autoload.php';

use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output;

// --- GET DOCUMENT ID ---
// ⚠️ MODIFICATION: Change from 'id' to 'document_id' to match the requested URL 'domain.com/auth/document.php?document_id=44'
$document_id = isset($_GET['document_id']) ? intval($_GET['document_id']) : 0; 
if ($document_id <= 0) {
    // ⚠️ Security/Clarity enhancement: Add a simple HTML wrapper
    header("Content-Type: text/html");
    die("
        <html>
            <head>
                <title>Document Not Found</title>
                <style>body { font-family: Arial, sans-serif; text-align: center; padding-top: 50px; }</style>
            </head>
            <body>
                <h1>Invalid or Missing Document ID</h1>
                <p>Please ensure you are accessing the page with a valid parameter, e.g., domain.com/auth/document.php?document_id=44</p>
            </body>
        </html>
    ");
}

// --- FETCH DOCUMENT CONTENT, FILE, AND ORG LOGO ---
$stmt = $conn->prepare("
    SELECT d.pdf_filename, d.content_html, o.logo
    FROM documents d 
    JOIN organizations o ON d.organization_id = o.organization_id 
    WHERE d.document_id = ?
");
$stmt->bind_param("i", $document_id);
$stmt->execute();
$stmt->bind_result($filename, $content, $organization_logo);
$stmt->fetch();
$stmt->close();

if (!$filename) {
    header("Content-Type: text/html");
    die("
        <html>
            <head>
                <title>Document Not Found</title>
                <style>body { font-family: Arial, sans-serif; text-align: center; padding-top: 50px; }</style>
            </head>
            <body>
                <h1>Document Not Found</h1>
                <p>The document with ID: " . htmlspecialchars($document_id) . " could not be retrieved.</p>
            </body>
        </html>
    ");
}

/// --- FETCH SIGNATURES ---
$sql_signatures = "
    SELECT u.user_role, dh.e_signature_code, u.e_signature_path
    FROM document_history dh
    JOIN users u ON dh.modified_by_user_id = u.user_id
    WHERE dh.document_id = ? AND dh.e_signature_code IS NOT NULL
";
$stmt_signatures = $conn->prepare($sql_signatures);
$stmt_signatures->bind_param("i", $document_id);
$stmt_signatures->execute();
$result_signatures = $stmt_signatures->get_result();

$signature_data = [];
while ($row = $result_signatures->fetch_assoc()) {
    $fixedPath = preg_replace('/^\.\.\/\.\.\/\.\.\//', '../', $row['e_signature_path']);

    $signature_data[$row['user_role']] = [
        'code' => $row['e_signature_code'],
        'path' => $fixedPath
    ];

}
$stmt_signatures->close();


// --- GENERATE QR CODE ---
// ⚠️ Placeholder for the base URL. You must define $useURL somewhere in your configuration 
// (e.g., in dbcon.php or before this line). 
// Assuming $useURL = "https://domain.com/"
// This creates the QR code data: https://domain.com/auth/document.php?document_id=44
// If $useURL is not defined, you'll need to define it or hardcode the domain.
$baseURL = isset($useURL) ? $useURL . "auth/document.php" : "https://domain.com/auth/document.php"; 

$qr_data_url = $baseURL . "?document_id=" . $document_id;

$qrCode = new QrCode($qr_data_url);
$output = new Output\Png();
$qrImageData = base64_encode($output->output($qrCode, 150));

// --- INITIALIZE MPDF ---
// ... (mPDF configuration as provided) ...
$defaultConfig = (new ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

try {
    $mpdf = new Mpdf([
        'format' => 'Legal',
        'margin_left' => 0,
        'margin_right' => 0,
        'margin_top' => 35,
        'margin_bottom' => 35,
        'fontDir' => array_merge($fontDirs, ['../assets/fonts']),
        'fontdata' => $fontData + [
            'arial' => ['R' => 'arial.ttf', 'B' => 'arialbd.ttf', 'I' => 'ariali.ttf', 'BI' => 'arialbi.ttf'],
            'timesnewroman' => ['R' => 'times.ttf', 'B' => 'timesbd.ttf', 'I' => 'timesi.ttf', 'BI' => 'timesbi.ttf'],
            'couriernew' => ['R' => 'cour.ttf', 'B' => 'courbd.ttf', 'I' => 'couri.ttf', 'BI' => 'courbi.ttf'],
            'georgia' => ['R' => 'georgia.ttf', 'B' => 'georgiab.ttf', 'I' => 'georgiai.ttf', 'BI' => 'georgiaz.ttf'],
            'verdana' => ['R' => 'verdana.ttf', 'B' => 'verdanab.ttf', 'I' => 'verdanai.ttf', 'BI' => 'verdanaz.ttf']
        ],
        'default_font' => 'arial'
    ]);

    // --- HEADER ---
    $logoLeft  = '../img/logo/basc_logo.png';
    $logoRight = $organization_logo ? '../' . $organization_logo : '../img/logo/bits.png';

    $headerHtml = '
    <table width="80%" style="font-family:Arial, sans-serif; border-collapse: collapse;margin-left:auto;margin-right:auto;">
        <tr>
            <td width="15%" align="center" style="padding-bottom:10px;border-bottom:3px solid #5C8E4E;">
                <img src="' . $logoLeft . '" style="height:80px;">
            </td>
            <td width="70%" align="center" style="line-height:1.4;padding-bottom:10px;border-bottom:3px solid #5C8E4E;">
                <span style="font-size:12px;">Republic of the Philippines</span><br>
                <span style="font-size:16px; font-weight:bold; color:#235016;font-family: Arial Black, Arial, sans-serif;">BULACAN AGRICULTURAL STATE COLLEGE</span><br>
                <span style="font-size:13px; font-weight:bold;">Builders of Information Technology Society</span><br>
                <span style="font-size:12px;">Pinaod, San Ildefonso, Bulacan, Philippines 3010</span>
            </td>
            <td width="15%" align="center" style="padding-bottom:10px;border-bottom:3px solid #5C8E4E;">
                <img src="' . $logoRight . '" style="height:80px;">
            </td>
        </tr>
    </table>
    ';

    // --- FOOTER ---
    $footerLeft  = '../img/logo/iso9001.png';
    $footerRight = '../img/logo/bagongphil.png';

    $footerHtml = '
    <div style="display: inline-block; text-align: right; margin-right: 60px;">
        <div style="font-family: Arial, sans-serif; font-size: 8pt; color: #888; margin-top: 3px;">
            Authenticate:
        </div>
        <img src="data:image/png;base64,' . $qrImageData . '" width="70" alt="QR Code" />
    </div>
    <table width="90%" style="font-family:Arial, sans-serif; font-size:13px; border-collapse: collapse; margin-bottom:0;margin-left:auto;margin-right:auto;">
        <tr>
            <td width="15%" align="right">
                <img src="' . $footerLeft . '" style="height:60px;">
            </td>
            <td width="70%" align="center" style="border-top:1px solid #5C8E4E;">
                <a href="http://www.basc.edu.ph" style="color:#0000EE; text-decoration:none;">www.basc.edu.ph</a> 
                / Email: <a href="mailto:bits2024@gmail.com" style="color:#000000; text-decoration:none;">bits2024@gmail.com</a><br>
                Telefax Nos: (044) 802 - 7719
            </td>
            <td width="15%" align="left">
                <img src="' . $footerRight . '" style="height:90px;">
            </td>
        </tr>
    </table>
    ';

    $mpdf->SetHTMLHeader($headerHtml);
    $mpdf->SetHTMLFooter($footerHtml);

    // --- SIGNATURE HANDLING (same as export.php) ---
    // Adviser Signature
    if (isset($signature_data['adviser'])) {
        $sig = $signature_data['adviser'];
        // ⚠️ Path resolution must be correct. 'realpath(__DIR__ . '/' . $sig['path'])' assumes $sig['path'] 
        // is relative to the directory of document.php (which is 'auth/'). Adjust if needed.
        $path = $sig['path'] ? realpath(__DIR__ . '/' . $sig['path']) : null; 
        $html = '<div style="position: absolute; bottom: 100px; left: 50%; transform: translateX(-50%); width: 200px;">';
        if ($path && file_exists($path)) {
            // Need to convert image to base64 for mPDF to handle complex relative paths reliably
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            $html .= '<img src="' . $base64 . '" style="max-width: 100px; height: 50px;" />';
        }
        $html .= '<br><span style="font-size: 10pt; color: #555; display: block; text-align:center;">' . $sig['code'] . '</span></div>';
        $content = str_replace('[ADVISER_SIGNATURE]', $html, $content);
    } else {
        $content = str_replace('[ADVISER_SIGNATURE]', '', $content);
    }

    // Dean Signature
    if (isset($signature_data['dean'])) {
        $sig = $signature_data['dean'];
        $path = $sig['path'] ? realpath(__DIR__ . '/' . $sig['path']) : null;
        $html = '<div style="position: absolute; bottom: 100px; right: 50%; transform: translateX(50%); width: 200px;">';
        if ($path && file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            $html .= '<img src="' . $base64 . '" style="max-width: 100px; height: 50px;" />';
        }
        $html .= '<br><span style="font-size: 10pt; color: #555; display: block; text-align:center;">' . $sig['code'] . '</span></div>';
        $content = str_replace('[DEAN_SIGNATURE]', $html, $content);
    } else {
        $content = str_replace('[DEAN_SIGNATURE]', '', $content);
    }

    // FSSC Signature
    if (isset($signature_data['fssc'])) {
        $sig = $signature_data['fssc'];
        $path = $sig['path'] ? realpath(__DIR__ . '/' . $sig['path']) : null;
        $html = '<div style="position: absolute; bottom: 100px; right: 50%; transform: translateX(50%); width: 200px;">';
        if ($path && file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            $html .= '<img src="' . $base64 . '" style="max-width: 100px; height: 50px;" />';
        }
        $html .= '<br><span style="font-size: 10pt; color: #555; display: block; text-align:center;">' . $sig['code'] . '</span></div>';
        $content = str_replace('[FSSC_SIGNATURE]', $html, $content);
    } else {
        $content = str_replace('[FSSC_SIGNATURE]', '', $content);
    }

    // --- BODY ---
    $bodyHtml = '<div style="margin-left:1in; margin-right:1in; font-family: Arial, sans-serif; font-size:11pt;">'
                . $content .
                '</div>';

    $mpdf->WriteHTML($bodyHtml);
    
    // Clear output buffer before final output
    ob_clean();
    
    // Output the PDF inline to the browser ('I')
    $mpdf->Output($filename, 'I'); 

} catch (\Mpdf\MpdfException $e) {
    // Send a plain text error if PDF fails
    header("Content-Type: text/plain");
    die("PDF generation error: " . $e->getMessage());
}
?>

<script>
// Remove the query string (?document_id=44) from the address bar
if (window.history.replaceState) {
    const cleanURL = window.location.origin + window.location.pathname;
    window.history.replaceState({}, document.title, cleanURL);
}
</script>
</body>