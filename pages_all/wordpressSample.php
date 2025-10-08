<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Advanced Page Builder</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #5a7dff;
            --primary-dark: #4768e1;
            --background: #f0f4f8;
            --panel-bg: #ffffff;
            --border: #e2e8f0;
            --text-primary: #1a202c;
            --text-secondary: #718096;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            background: var(--background);
            color: var(--text-primary);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        header {
            background: var(--panel-bg);
            padding: 1rem 2rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow);
            position: sticky;
            top: 0;
            z-index: 10;
        }
        h1 {
            font-size: 1.5rem;
            margin: 0;
            color: var(--primary);
        }
        .small {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }
        .toolbar {
            display: flex;
            gap: 0.75rem;
        }
        button, select, input[type="file"], input[type="color"] {
            background: var(--panel-bg);
            border: 1px solid var(--border);
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            font-family: inherit;
        }
        button:hover, select:hover {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
            transform: translateY(-1px);
        }
        button:active {
            transform: translateY(0);
        }
        button.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary-dark);
        }
        main {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 2rem;
            padding: 2rem;
        }
        .panel {
            background: var(--panel-bg);
            padding: 1.5rem;
            border-radius: 1rem;
            border: 1px solid var(--border);
            min-height: 520px;
            box-shadow: var(--shadow);
        }
        .panel h2 {
            font-size: 1rem;
            margin: 0 0 1rem;
            border-bottom: 1px solid var(--border);
            padding-bottom: 0.5rem;
        }
        #editor {
            min-height: 500px;
            border: 2px dashed var(--border);
            padding: 1.5rem;
            border-radius: 0.75rem;
            overflow: auto;
            background: #ffffff;
            line-height: 1.6;
            outline: none;
            transition: border-color 0.2s ease-in-out;
        }
        #editor:focus {
            border-color: var(--primary);
        }
        .controls-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(70px, 1fr));
            gap: 0.5rem;
        }
        .controls-row {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }
        .full-width {
            width: 100%;
        }
        .preview-wrap {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .actions {
            display: flex;
            gap: 0.75rem;
            justify-content: flex-end;
            margin-top: 1rem;
        }
        input[type="text"], input[type="color"] {
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            font-family: inherit;
            flex: 1;
            min-width: 0;
        }
        input[type="color"] {
            width: 40px;
            padding: 0.1rem;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }
        input[type="color"]::-webkit-color-swatch-wrapper { padding: 0; }
        input[type="color"]::-webkit-color-swatch { border: none; border-radius: 0.4rem; }
        .color-control {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .color-input-group {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }
        .color-input-group label {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }
        #editor img {
            max-width: 100%;
            height: auto;
            cursor: grab;
            display: block;
            margin: 0.5rem 0;
        }
        #editor img:hover {
            outline: 2px solid var(--primary);
            outline-offset: 2px;
        }
        .button-group {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }
        .button-group button {
            flex: 1;
            min-width: 0;
        }
        .button-group label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
    </style>
</head>
<body>
    <header>
        <div>
            <h1>🎨 Advanced Page Builder</h1>
            <div class="small">Design a beautiful page visually then download as .docx or .pdf</div>
        </div>
        <div class="toolbar">
            <button id="exportDocxBtn">Export DOCX</button>
            <button id="exportPdfBtn">Export PDF</button>
            <button id="downloadHtmlBtn">Download HTML</button>
            <button id="clearBtn">Clear</button>
        </div>
    </header>

    <main>
        <section class="panel">
            <h2>Controls</h2>
            <div class="button-group">
                <button data-cmd="bold"><b>B</b></button>
                <button data-cmd="italic"><i>I</i></button>
                <button data-cmd="underline"><u>U</u></button>
                <button data-cmd="insertUnorderedList" title="Bulleted List">• List</button>
                <button data-cmd="insertOrderedList" title="Numbered List">1. List</button>
            </div>
            
            <div class="button-group">
                <button id="h1Btn">H1</button>
                <button id="h2Btn">H2</button>
                <button id="pBtn">P</button>
            </div>

            <h2>Text Formatting</h2>
            <div class="button-group">
                <button data-cmd="justifyLeft" title="Align Left">Align Left</button>
                <button data-cmd="justifyCenter" title="Align Center">Align Center</button>
                <button data-cmd="justifyRight" title="Align Right">Align Right</button>
                <button data-cmd="indent" title="Indent">+</button>
                <button data-cmd="outdent" title="Outdent">-</button>
            </div>
            
            <div class="button-group">
                <label for="textColor">Text Color</label>
                <input type="color" id="textColor" value="#1a202c" />
                <label for="highlightColor">Highlight</label>
                <input type="color" id="highlightColor" value="#ffc107" />
            </div>

            <div class="button-group">
                <select id="fontSelect">
                    <option value="Arial">Arial</option>
                    <option value="Times New Roman">Times New Roman</option>
                    <option value="Courier New">Courier New</option>
                    <option value="Georgia">Georgia</option>
                    <option value="Verdana">Verdana</option>
                </select>
                <select id="fontSizeSelect">
                    <option value="1">Small</option>
                    <option value="3" selected>Normal</option>
                    <option value="5">Large</option>
                </select>
            </div>
            
            <h2>Insert</h2>
            <div class="button-group">
                <label>Image <input id="imgInput" type="file" accept="image/*" style="flex:1"></label>
            </div>
            <div class="button-group">
                <input id="linkInput" placeholder="https://example.com" class="full-width" />
                <button id="insertLinkBtn" class="full-width">Insert Link</button>
            </div>

            <h2>Export options</h2>
            <div class="small">Filename:</div>
            <input id="filenameInput" value="page-export.docx" class="full-width" />
        </section>

        <section class="panel preview-wrap">
            <h2>Editor <span class="small">(What will be exported)</span></h2>
            <div id="editor" contenteditable="true">
                <h1>Your page title</h1>
                <p>Start writing your page here. Use the toolbar to format text, add lists, images, and links.</p>
                <p>This is a paragraph with some <b>bold</b>, <i>italic</i>, and <u>underlined</u> text. Try changing the font or color from the control panel.</p>
                
                <img src="https://via.placeholder.com/600x300.png?text=Placeholder+Image" alt="Placeholder" draggable="true" />
            </div>

            <div class="actions">
                <button id="previewBtn">Open Print Preview</button>
                <button id="copyHtmlBtn">Copy HTML</button>
            </div>
        </section>
    </main>
    <footer>This is a simple client-side builder.</footer>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/docx@9.5.1/dist/index.iife.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    
    <script>
        const editor = document.getElementById('editor');

        // Text formatting
        document.querySelectorAll('[data-cmd]').forEach(btn => {
            btn.addEventListener('click', () => {
                const cmd = btn.getAttribute('data-cmd');
                document.execCommand(cmd, false, null);
                editor.focus();
            });
        });
        document.getElementById('h1Btn').addEventListener('click', () => document.execCommand('formatBlock', false, 'h1'));
        document.getElementById('h2Btn').addEventListener('click', () => document.execCommand('formatBlock', false, 'h2'));
        document.getElementById('pBtn').addEventListener('click', () => document.execCommand('formatBlock', false, 'p'));
        
        // Color & Highlight
        document.getElementById('textColor').addEventListener('input', (e) => {
            document.execCommand('foreColor', false, e.target.value);
            editor.focus();
        });
        document.getElementById('highlightColor').addEventListener('input', (e) => {
            document.execCommand('backColor', false, e.target.value);
            editor.focus();
        });

        // Font & Size
        document.getElementById('fontSelect').addEventListener('change', (e) => {
            document.execCommand('fontName', false, e.target.value);
            editor.focus();
        });
        document.getElementById('fontSizeSelect').addEventListener('change', (e) => {
            document.execCommand('fontSize', false, e.target.value);
            editor.focus();
        });

        // Insert Image
        document.getElementById('imgInput').addEventListener('change', e => {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = () => {
                const img = document.createElement('img');
                img.src = reader.result;
                img.style.maxWidth = '100%';
                img.setAttribute('draggable', 'true');
                editor.appendChild(img);
                editor.focus();
            };
            reader.readAsDataURL(file);
        });

        // Insert Link
        document.getElementById('insertLinkBtn').addEventListener('click', () => {
            const url = document.getElementById('linkInput').value.trim();
            if (!url) return;
            const selection = window.getSelection();
            if (selection.toString().length > 0) {
                document.execCommand('createLink', false, url);
            } else {
                const text = prompt('Text for the link', url) || url;
                document.execCommand('insertHTML', false, `<a href="${url}" target="_blank">${text}</a>`);
            }
            editor.focus();
        });
        
        // Drag & Drop for images
        let draggedItem = null;
        editor.addEventListener('dragstart', (e) => {
            if (e.target.tagName === 'IMG') {
                draggedItem = e.target;
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/plain', null); // Required for Firefox
                e.target.style.opacity = '0.5';
            }
        });
        editor.addEventListener('dragover', (e) => {
            e.preventDefault();
            const target = e.target;
            if (draggedItem && (target.tagName === 'IMG' || target.tagName === 'P')) {
                const rect = target.getBoundingClientRect();
                const isAfter = e.clientY > rect.top + rect.height / 2;
                if (isAfter) {
                    target.parentNode.insertBefore(draggedItem, target.nextSibling);
                } else {
                    target.parentNode.insertBefore(draggedItem, target);
                }
            }
        });
        editor.addEventListener('drop', (e) => {
            e.preventDefault();
            if (draggedItem) {
                draggedItem.style.opacity = '1';
                draggedItem = null;
            }
        });
        editor.addEventListener('dragend', () => {
            if (draggedItem) {
                draggedItem.style.opacity = '1';
                draggedItem = null;
            }
        });

        // Export and other functions (DOCX, PDF, HTML, etc.)
        // These remain the same as the original, but are included for completeness.
        document.getElementById('exportDocxBtn').addEventListener('click', async () => {
            const { Document, Packer, Paragraph } = docx;
            const filename = (document.getElementById('filenameInput').value || 'page-export.docx');
            try {
                const doc = new Document({
                    sections: [{ children: [new Paragraph(editor.innerText)] }]
                });
                const blob = await Packer.toBlob(doc);
                saveAs(blob, filename);
            } catch (err) {
                console.error(err);
                alert('DOCX export failed: ' + err.message);
            }
        });

        document.getElementById('exportPdfBtn').addEventListener('click', async () => {
            const { jsPDF } = window.jspdf;
            const filename = (document.getElementById('filenameInput').value.replace(/\.docx$/i, '.pdf') || 'page-export.pdf');
            try {
                const canvas = await html2canvas(editor);
                const imgData = canvas.toDataURL('image/png');
                const pdf = new jsPDF('p', 'pt', 'a4');
                const pageWidth = pdf.internal.pageSize.getWidth();
                const pageHeight = pdf.internal.pageSize.getHeight();
                const imgWidth = pageWidth - 40;
                const imgHeight = canvas.height * imgWidth / canvas.width;
                pdf.addImage(imgData, 'PNG', 20, 20, imgWidth, imgHeight);
                pdf.save(filename);
            } catch (err) {
                console.error(err);
                alert('PDF export failed: ' + err.message);
            }
        });

        document.getElementById('downloadHtmlBtn').addEventListener('click', () => {
            const content = `<!doctype html><html><head><meta charset="utf-8"><title>Exported HTML</title></head><body>${editor.innerHTML}</body></html>`;
            const blob = new Blob([content], { type: 'text/html' });
            saveAs(blob, 'page-export.html');
        });

        document.getElementById('clearBtn').addEventListener('click', () => {
            if (confirm('Clear the editor?')) editor.innerHTML = '<h1>Your page title</h1><p>Start writing your page here.</p>';
        });

        document.getElementById('previewBtn').addEventListener('click', () => {
            const w = window.open('about:blank', '_blank');
            w.document.write('<!doctype html><html><head><meta charset="utf-8"><title>Preview</title></head><body>' + editor.innerHTML + '</body></html>');
            w.document.close();
            w.focus();
        });

        document.getElementById('copyHtmlBtn').addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(editor.innerHTML);
                alert('HTML copied');
            } catch (e) {
                prompt('Copy this HTML', editor.innerHTML);
            }
        });
    </script>
</body>
</html>

