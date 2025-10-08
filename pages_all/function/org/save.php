<?php
require '../../../config/dbcon.php';
require '../../../vendor/autoload.php';

use Mpdf\Mpdf;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filenameInput   = $_POST['filenameInput'] ?? 'document.pdf';
    $editorContent   = $_POST['editorContent'] ?? '';
    $userId          = $_SESSION['user_id'];

    if (empty($editorContent)) {
        $_SESSION['message'] = "No content to save.";
        $_SESSION['msgtype'] = "error";
        $_SESSION['havemsg'] = true;
        header('Location: ../../org/dashboard.php');
        exit;
    }

    // Insert into DB
    $stmt = $conn->prepare("INSERT INTO documents (filename, content, status, user_id) VALUES (?, ?, 'draft', ?)");
    $stmt->bind_param("ssi", $filenameInput, $editorContent, $userId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $doc_id = $stmt->insert_id;

        // ====== Fonts Setup ======
        $defaultConfig = (new ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        try {
            $mpdf = new Mpdf([
                'format'        => 'A4',
                'margin_left'   => 0,
                'margin_right'  => 0,
                'margin_top'    => 35,
                'margin_bottom' => 35,
                'fontDir' => array_merge($fontDirs, ['../../../assets/fonts']),
                'fontdata' => $fontData + [
                    'arial' => [
                        'R' => 'arial.ttf',
                        'B' => 'arialbd.ttf',
                        'I' => 'ariali.ttf',
                        'BI' => 'arialbi.ttf',
                    ],
                    'timesnewroman' => [
                        'R' => 'times.ttf',
                        'B' => 'timesbd.ttf',
                        'I' => 'timesi.ttf',
                        'BI' => 'timesbi.ttf',
                    ],
                    'couriernew' => [
                        'R' => 'cour.ttf',
                        'B' => 'courbd.ttf',
                        'I' => 'couri.ttf',
                        'BI' => 'courbi.ttf',
                    ],
                    'georgia' => [
                        'R' => 'georgia.ttf',
                        'B' => 'georgiab.ttf',
                        'I' => 'georgiai.ttf',
                        'BI' => 'georgiaz.ttf',
                    ],
                    'verdana' => [
                        'R' => 'verdana.ttf',
                        'B' => 'verdanab.ttf',
                        'I' => 'verdanai.ttf',
                        'BI' => 'verdanaz.ttf',
                    ]
                ],
                'default_font' => 'arial'
            ]);

            // ====== Header & Footer ======
            $logoLeft   = '../../../img/logo/basc_logo.png';
            $logoRight  = '../../../img/logo/bits.png';
            $footerLeft = '../../../img/logo/iso9001.png';
            $footerRight= '../../../img/logo/bagongphil.png';

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

            $footerHtml = '
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

            // Body with margins
            $bodyHtml = '<div style="margin-left:1in; margin-right:1in; font-family: Arial, sans-serif; font-size:11pt;">'
                      . $editorContent .
                      '</div>';

            $mpdf->WriteHTML($bodyHtml);

            // ====== Save instead of Download ======
            $pdfDir = __DIR__ . "/uploads/pdfs/";
            if (!is_dir($pdfDir)) {
                mkdir($pdfDir, 0777, true);
            }

            $finalPdf = $pdfDir . $doc_id . "_" . basename($filenameInput);
            $mpdf->Output($finalPdf, 'F'); // 'F' = Save to file

        } catch (\Mpdf\MpdfException $e) {
            $_SESSION['message'] = "PDF generation error: " . $e->getMessage();
            $_SESSION['msgtype'] = "error";
            $_SESSION['havemsg'] = true;
            header('Location: ../../org/dashboard.php');
            exit;
        }

        $_SESSION['message'] = "Document submitted successfully";
        $_SESSION['msgtype'] = "success";
        $_SESSION['havemsg'] = true;
        header('Location: ../../org/dashboard.php');
    } else {
        $_SESSION['message'] = "Error saving document.";
        $_SESSION['msgtype'] = "error";
        $_SESSION['havemsg'] = true;
        header('Location: ../../org/dashboard.php');
    }

    $stmt->close();
    $conn->close();
}
?>
