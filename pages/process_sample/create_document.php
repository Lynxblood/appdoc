<?php
include '../config/dbcon.php';
// Redirect if not logged in or not an organization user
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'], ['academic_organization', 'non_academic_organization'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create New Document</title>
    <script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>
    <style>
        body { font-family: sans-serif; margin: 2em; }
        .form-group { margin-bottom: 1em; }
        label { display: block; margin-bottom: 0.5em; font-weight: bold; }
        input[type="text"], select { width: 100%; padding: 8px; box-sizing: border-box; }
        .ck-editor__editable { min-height: 200px; }
        .container { max-width: 800px; margin: auto; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create New Document</h2>
        <form action="process_document.php" method="post">
            <div class="form-group">
                <label for="title">Document Title:</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="document_type">Document Type:</label>
                <select id="document_type" name="document_type" required>
                    <option value="">Select a type</option>
                    <option value="accreditation_form">Accreditation Form</option>
                    <option value="off_campus_activity">Off-Campus Activity</option>
                    <option value="proposed_activity">Proposed Activity</option>
                    <option value="recognition_form">Recognition Form</option>
                    <option value="letter_template">Letter Template</option>
                </select>
            </div>
            <div class="form-group">
                <label for="content_html">Document Content:</label>
                <textarea id="content_html" name="content_html"></textarea>
            </div>
            <button type="submit" name="action" value="draft">Save as Draft</button>
            <button type="submit" name="action" value="submit">Submit for Approval</button>
        </form>
    </div>

    <script>
        ClassicEditor
            .create( document.querySelector( '#content_html' ) )
            .catch( error => {
                console.error( error );
            } );
    </script>
</body>
</html>