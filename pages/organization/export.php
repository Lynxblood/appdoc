<?php

require '../../vendor/autoload.php';
require '../../config/dbcon.php';

use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\QrCode\QrCode;
use Mpdf\QrCode\Output;

$defaultConfig = (new ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

try {
    $document_id = $_POST['document_id'] ?? null;
    $logoRight = '../../img/logo/bits.png'; // Default logo path

    // Check if a document ID is provided
    if ($document_id) {
        $sql_logo = "SELECT o.logo FROM documents d 
                     JOIN organizations o ON d.organization_id = o.organization_id 
                     WHERE d.document_id = ?";
        $stmt_logo = $conn->prepare($sql_logo);
        $stmt_logo->bind_param("i", $document_id);
        $stmt_logo->execute();
        $result_logo = $stmt_logo->get_result();
        
        if ($row = $result_logo->fetch_assoc()) {
            $logoRight = '../../' . $row['logo'];
        }
        $stmt_logo->close();
        
        // --- QR CODE DATA GENERATION ---
        $useURL = $useURL . "auth/document.php"; // *** UPDATE THIS BASE URL ***
        $qr_data_url = $useURL . "?document_id=" . $document_id; 
    } elseif (isset($_SESSION['organization_id'])) {
        $organization_id = $_SESSION['organization_id'];
        $sql_logo = "SELECT logo FROM organizations WHERE organization_id = ?";
        $stmt_logo = $conn->prepare($sql_logo);
        $stmt_logo->bind_param("i", $organization_id);
        $stmt_logo->execute();
        $result_logo = $stmt_logo->get_result();

        if ($row = $result_logo->fetch_assoc()) {
            $logoRight = '../../' . $row['logo'];
        }
        $stmt_logo->close();

        $useURL = $useURL . "auth/document.php";
        $qr_data_url = $useURL . "?organization_id=" . $organization_id; 
    } else {
        $qr_data_url = $useURL;
    }

    // Fetch signatures
    $sql_signatures = "SELECT u.user_role, dh.e_signature_code, u.e_signature_path
                        FROM document_history dh
                        JOIN users u ON dh.modified_by_user_id = u.user_id
                        WHERE dh.document_id = ? AND dh.e_signature_code IS NOT NULL";
    
    $stmt_signatures = $conn->prepare($sql_signatures);
    $stmt_signatures->bind_param("i", $document_id);
    $stmt_signatures->execute();
    $result_signatures = $stmt_signatures->get_result();

    $signature_data = [];
    while ($row = $result_signatures->fetch_assoc()) {
        $signature_data[$row['user_role']] = [
            'code' => $row['e_signature_code'],
            'path' => $row['e_signature_path']
        ];
    }
    $stmt_signatures->close();

    // Initialize mPDF
    $mpdf = new Mpdf([
        'format'        => $_POST['page_size'] ?? 'A4',
        'margin_left'   => 0,
        'margin_right'  => 0,
        'margin_top'    => 35,
        'margin_bottom' => 35,
        'fontDir' => array_merge($fontDirs, ['../../assets/fonts']), 
        'fontdata' => $fontData + [
            'arial' => ['R' => 'arial.ttf', 'B' => 'arialbd.ttf', 'I' => 'ariali.ttf', 'BI' => 'arialbi.ttf'],
            'timesnewroman' => ['R' => 'times.ttf', 'B' => 'timesbd.ttf', 'I' => 'timesi.ttf', 'BI' => 'timesbi.ttf'],
            'couriernew' => ['R' => 'cour.ttf', 'B' => 'courbd.ttf', 'I' => 'couri.ttf', 'BI' => 'courbi.ttf'],
            'georgia' => ['R' => 'georgia.ttf', 'B' => 'georgiab.ttf', 'I' => 'georgiai.ttf', 'BI' => 'georgiaz.ttf'],
            'verdana' => ['R' => 'verdana.ttf', 'B' => 'verdanab.ttf', 'I' => 'verdanai.ttf', 'BI' => 'verdanaz.ttf']
        ],
        'default_font' => 'arial'
    ]);

    $filename = $_POST['filename'] ?? 'page-export.pdf';
    $content  = $_POST['html_content'] ?? '';

   // In pdf_export.php, locate the signature replacement logic

    // For Adviser Signature
    if (isset($signature_data['adviser'])) {
        $signature_code = $signature_data['adviser']['code'];
        $signature_path = $signature_data['adviser']['path'];
        
        // Construct the new HTML signature block
        $signature_html = '<div style="position: absolute; bottom: 100px; left: 50%; transform: translateX(-50%); width: 200px;">';
        if ($signature_path) {
            $full_signature_path = realpath(__DIR__ . $signature_path);
            if (file_exists($full_signature_path)) {
                $signature_html .= '<img src="' . $full_signature_path . '" style="max-width: 100px; height: 50px;" />';
            }
        }
        $signature_html .= '<br><span style="font-family: Arial, sans-serif; font-size: 10pt; color: #555; display: block; margin-top: 5px; text-align: center;">' . $signature_code . '</span>';
        $signature_html .= '</div>';
        
        $content = str_replace('[ADVISER_SIGNATURE]', $signature_html, $content);
    }else{ 
        $content = str_replace('[ADVISER_SIGNATURE]', '', $content);
    }

    // For Dean Signature
    if (isset($signature_data['dean'])) {
        $signature_code = $signature_data['dean']['code'];
        $signature_path = $signature_data['dean']['path'];
        
        $signature_html = '<div style="position: absolute; bottom: 100px; right: 50%; transform: translateX(50%); width: 200px;">';
        if ($signature_path) {
            $full_signature_path = realpath(__DIR__ . $signature_path);
            if (file_exists($full_signature_path)) {
                $signature_html .= '<img src="' . $full_signature_path . '" style="max-width: 100px; height: 50px;" />';
            }
        }
        $signature_html .= '<br><span style="font-family: Arial, sans-serif; font-size: 10pt; color: #555; display: block; margin-top: 5px; text-align: center;">' . $signature_code . '</span>';
        $signature_html .= '</div>';
        
        $content = str_replace('[DEAN_SIGNATURE]', $signature_html, $content);
    }else{ 
        $content = str_replace('[DEAN_SIGNATURE]', '', $content);
    }

     // For Dean Signature
     if (isset($signature_data['fssc'])) {
        $signature_code = $signature_data['fssc']['code'];
        $signature_path = $signature_data['fssc']['path'];
        
        $signature_html = '<div style="position: absolute; bottom: 100px; right: 50%; transform: translateX(50%); width: 200px;">';
        if ($signature_path) {
            $full_signature_path = realpath(__DIR__ . $signature_path);
            if (file_exists($full_signature_path)) {
                $signature_html .= '<img src="' . $full_signature_path . '" style="max-width: 100px; height: 50px;" />';
            }
        }
        $signature_html .= '<br><span style="font-family: Arial, sans-serif; font-size: 10pt; color: #555; display: block; margin-top: 5px; text-align: center;">' . $signature_code . '</span>';
        $signature_html .= '</div>';
        
        $content = str_replace('[FSSC_SIGNATURE]', $signature_html, $content);
    }else{ 
        $content = str_replace('[FSSC_SIGNATURE]', '', $content);
    }

    $logoLeft   = '../../img/logo/basc_logo.png';
    $footerLeft = '../../img/logo/iso9001.png';
    $footerRight= '../../img/logo/bagongphil.png';

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

    // ✅ --- FIXED QR CODE GENERATION (Replaces <qrcode> tag) ---
    $qrCode = new QrCode($qr_data_url);
    $output = new Output\Png();
    $qrImageData = base64_encode($output->output($qrCode, 150)); // 150px QR code

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
    // -----------------------------------------------------------

    $mpdf->SetHTMLHeader($headerHtml);
    $mpdf->SetHTMLFooter($footerHtml);

    $bodyHtml = '<div style="margin-left:1in; margin-right:1in; font-family: Arial, sans-serif; font-size:11pt;">'
                . $content .
                '</div>';

    $mpdf->WriteHTML($bodyHtml);

    ob_clean();
    $mpdf->Output($filename, 'D');

} catch (\Mpdf\MpdfException $e) {
    echo "PDF generation error: " . $e->getMessage();
}
?>
