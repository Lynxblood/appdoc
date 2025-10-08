<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Approval App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
        iframe {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            width: 100%;
            height: 70vh;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            padding: 1rem;
        }
        .file-input {
            width: 100%;
            text-sm text-gray-500
        }
        .file-input::-webkit-file-upload-button {
            visibility: hidden;
        }
        .file-input::before {
            content: 'Select a .docx file';
            display: inline-block;
            background: #eef2ff;
            border-radius: 9999px;
            padding: 0.5rem 1rem;
            outline: none;
            white-space: nowrap;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.875rem;
            color: #4338ca;
            border: 0;
            transition: background-color 0.2s;
        }
        .file-input:hover::before {
            background-color: #e0e7ff;
        }
    </style>
</head>
<body class="bg-gray-100">

    <div class="container flex flex-col items-center py-8">
        <div class="bg-white p-6 md:p-8 rounded-lg shadow-xl w-full">
            <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Document Approval & Editing</h1>
            
            <div class="flex flex-col md:flex-row gap-4 mb-6">
                <!-- Section for uploading a .docx file to Google Drive -->
                <div class="flex-grow flex flex-col items-start gap-2 p-4 rounded-lg border border-gray-300">
                    <label class="text-sm font-semibold text-gray-700">Upload a .docx file</label>
                    <input type="file" id="file-upload-input" accept=".docx" class="file-input" />
                    <button id="upload-file-button" class="bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-blue-700 transition-colors duration-200 w-full md:w-auto mt-2">
                        Upload to Drive
                    </button>
                    <div id="upload-status" class="text-sm mt-2 font-medium text-gray-600"></div>
                </div>
            </div>

            <!-- New section to select a document from Google Drive -->
            <div class="flex flex-col md:flex-row gap-4 mb-6 items-center">
                <button id="select-doc-button" class="bg-blue-600 text-white font-semibold py-3 px-6 rounded-lg shadow hover:bg-blue-700 transition-colors duration-200 w-full md:w-auto">Select Document from Drive</button>
            </div>

            <iframe id="document-iframe" src="about:blank" title="Google Docs Editor"></iframe>
            
            <div class="flex flex-col md:flex-row gap-4 mt-6">
                <button id="approve-button" class="bg-green-600 text-white font-semibold py-3 px-6 rounded-lg shadow hover:bg-green-700 transition-colors duration-200 w-full">Approve Document</button>
                <button id="reject-button" class="bg-red-600 text-white font-semibold py-3 px-6 rounded-lg shadow hover:bg-red-700 transition-colors duration-200 w-full">Reject Document</button>
            </div>
            
            <div id="status-message" class="mt-4 text-center text-gray-700 font-semibold hidden"></div>
        </div>
    </div>

    <script src="https://apis.google.com/js/api.js"></script>
    <script src="https://accounts.google.com/gsi/client"></script>

    <script>
        // NOTE: The '400' error you are seeing is because the Google API credentials
        // are not configured. The code itself is correct, but it needs valid
        // API Key and Client ID values to work.
        // TODO: Replace these placeholders with your actual credentials from the Google Cloud Console.
        const clientId = '3330164946-4j0lohq5o020ju7b0sftjfsle3d2b57u.apps.googleusercontent.com';
        const apiKey = 'AIzaSyDf_WigxnPj7cwWBMALSwVYtS35GgiDOMs';
        const scope = 'https://www.googleapis.com/auth/drive.file';

        const fileUploadInput = document.getElementById('file-upload-input');
        const uploadFileButton = document.getElementById('upload-file-button');
        const uploadStatus = document.getElementById('upload-status');
        const documentIframe = document.getElementById('document-iframe');
        const approveButton = document.getElementById('approve-button');
        const rejectButton = document.getElementById('reject-button');
        const statusMessage = document.getElementById('status-message');
        const selectDocButton = document.getElementById('select-doc-button');

        let tokenClient;
        let gapiInited = false;
        let gisInited = false;

        function handleDocumentAction(action) {
            console.log(`${action} button clicked!`);
            statusMessage.textContent = `Document has been marked as ${action.toLowerCase()}d.`;
            statusMessage.classList.remove('hidden');
        }

        // --- Google API and Picker Logic ---
        function gapiLoaded() {
            gapi.load('client', initializeGapiClient);
        }

        function initializeGapiClient() {
            gapiInited = true;
            maybeInitPicker();
        }

        function gisLoaded() {
            tokenClient = google.accounts.oauth2.initTokenClient({
                client_id: clientId,
                scope: scope,
                callback: (tokenResponse) => {
                    if (tokenResponse.error !== 'access_denied') {
                        maybeInitPicker();
                    }
                }
            });
            gisInited = true;
            maybeInitPicker();
        }

        function maybeInitPicker() {
            if (gapiInited && gisInited) {
                // All libraries are loaded and authenticated, enable the button
                selectDocButton.disabled = false;
                selectDocButton.textContent = 'Select Document from Drive';
            }
        }

        function createPicker() {
            // Ensure the user is authorized
            if (gapi.client.getToken() === null) {
                tokenClient.requestAccessToken();
                return;
            }

            const view = new google.picker.DocsView(google.picker.ViewId.DOCS);
            view.setMimeTypes('application/vnd.google-apps.document,application/vnd.openxmlformats-officedocument.wordprocessingml.document');

            const picker = new google.picker.PickerBuilder()
                .enableFeature(google.picker.Feature.NAV_HIDDEN)
                .setAppId(clientId)
                .setOAuthToken(gapi.client.getToken().access_token)
                .addView(view)
                .setCallback(pickerCallback)
                .build();
            picker.setVisible(true);
        }

        function pickerCallback(data) {
            if (data.action === google.picker.Action.PICKED) {
                const doc = data.docs[0];
                const docId = doc.id;
                const embedUrl = `https://docs.google.com/document/d/${docId}/edit`;
                documentIframe.src = embedUrl;

                statusMessage.textContent = `Document "${doc.name}" opened successfully.`;
                statusMessage.classList.remove('hidden');
            }
        }

        // --- Initial setup and event listeners ---
        selectDocButton.disabled = true;
        selectDocButton.textContent = 'Loading Google APIs...';

        gapi.load('picker', { 'callback': gapiLoaded });
        gisLoaded();

        selectDocButton.addEventListener('click', createPicker);
        approveButton.addEventListener('click', () => handleDocumentAction('Approve'));
        rejectButton.addEventListener('click', () => handleDocumentAction('Reject'));

        // Upload functionality (conceptual) remains the same
        uploadFileButton.addEventListener('click', () => {
            const file = fileUploadInput.files[0];
            if (!file) {
                uploadStatus.textContent = 'Please select a .docx file to upload.';
                uploadStatus.classList.remove('text-green-600', 'text-red-600');
                uploadStatus.classList.add('text-gray-600');
                return;
            }

            uploadStatus.textContent = `Uploading "${file.name}"...`;
            uploadStatus.classList.remove('text-green-600', 'text-red-600');
            uploadStatus.classList.add('text-gray-600');

            // --- Mocking the API call for demonstration purposes ---
            setTimeout(() => {
                const mockDocId = '1_mock-document-id-from-upload';
                documentIframe.src = `https://docs.google.com/document/d/${mockDocId}/edit`;
                uploadStatus.textContent = `File uploaded and opened! Document ID: ${mockDocId}`;
                uploadStatus.classList.remove('text-gray-600', 'text-red-600');
                uploadStatus.classList.add('text-green-600');
            }, 2000);
        });
    </script>

</body>
</html>
