<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>PDF to HTML with CloudConvert</title>
</head>
<body>
  <h2>Convert PDF → HTML (CloudConvert API)</h2>

  <input type="file" id="pdfFile" accept="application/pdf">
  <button onclick="convertPdf()">Convert</button>

  <h3>Result:</h3>
  <iframe id="resultFrame" style="width:100%; height:600px;"></iframe>

  <script>
    async function convertPdf() {
      const fileInput = document.getElementById("pdfFile");
      if (!fileInput.files.length) {
        alert("Please choose a PDF file first.");
        return;
      }

      const file = fileInput.files[0];

      // ⚠️ Replace with your CloudConvert API key
      const apiKey = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiMGM5YzBlMDUwNTQyMjhlODdlM2FmNjM4NmY5NmIzN2E1ZTJhNDk3ZjIzYzA2ZTcwNmM0ZTk0ODI4OWE1MTE1MjJkODlmN2Q3MTQ5Y2YxYmEiLCJpYXQiOjE3NTkwNDk2NTYuNjc2NTQxLCJuYmYiOjE3NTkwNDk2NTYuNjc2NTQyLCJleHAiOjQ5MTQ3MjMyNTYuNjcxMzQzLCJzdWIiOiI3MzAzNDY0OSIsInNjb3BlcyI6WyJwcmVzZXQud3JpdGUiLCJwcmVzZXQucmVhZCIsIndlYmhvb2sud3JpdGUiLCJ3ZWJob29rLnJlYWQiLCJ0YXNrLndyaXRlIiwidGFzay5yZWFkIl19.c-2rtxz2zEskwwwbi3_jsOuV_6BMnx7roW3jwijRaBbflzcFKG-2BmwlWkPqRQmp8ZGCIuyIOK67m1nm9en8R-ulqVgYS9T0UuqpJjrUz7LY5sSYlnzPxFnCRy4UBddlP6s7EcP8Vc0guA3LAzURlVDu9mTUbHgzqwb2v_XDXxhKrzbOoekkng1XC2_YedT4LPOPwkbnFWEZRQaf0CxTr-1Fu2r52KL4W0ggFdww9IauRtohMPprES3MH9cSNjdSrvUs-RfrgJAOFN154XSAfZ48xV2WgmEURv2bdjzZf4B5gTEMtteKgof1VR63eLczfW1OYJomEbXrlhf3u_b-Vv5iPaIGrBmqk9Jy6G-Qc50GznAn-rkAn4q0x10I1sV6Ges1B6texlAXw4U3joTd6tZbrfkSWPblfj3cQFI4a2Sc-p5VEXtzy2CQShSF2DkIPnVvsqiNA-3SaivDN5oulcOaafERs8J1kBzPOBapqujiin1logHHo7-8GvBn8edjqmXCEkj87xsXAhn70vd77EDwmESmyMmwejG0ZJUIs7KX9QIMg2dZ2RJtgVGVjh3j_h9i5tajL0zTFTWIEQL9ZhMqsYaosSkj74BjWZGRNstA8CxTMrazILPeoEoqwfJvItbfIbmUh7aCTFNM7M4JenJ2--m3oJyHxaK3X9n9N7Y";  

      // Step 1: Create Import Task (upload PDF)
      let res = await fetch("https://api.cloudconvert.com/v2/import/upload", {
        method: "POST",
        headers: {
          "Authorization": "Bearer " + apiKey,
          "Content-Type": "application/json"
        }
      });
      let importTask = await res.json();

      const uploadUrl = importTask.data.result.form.url;
      const formData = new FormData();
      for (const [k, v] of Object.entries(importTask.data.result.form.parameters)) {
        formData.append(k, v);
      }
      formData.append("file", file);

      await fetch(uploadUrl, { method: "POST", body: formData });

      // Step 2: Create Convert Task (pdf → html)
      res = await fetch("https://api.cloudconvert.com/v2/jobs", {
        method: "POST",
        headers: {
          "Authorization": "Bearer " + apiKey,
          "Content-Type": "application/json"
        },
        body: JSON.stringify({
          tasks: {
            "import-my-file": {
              operation: "import/upload"
            },
            "convert-my-file": {
              operation: "convert",
              input: ["import-my-file"],
              output_format: "html"
            },
            "export-my-file": {
              operation: "export/url",
              input: ["convert-my-file"]
            }
          }
        })
      });
      let job = await res.json();

      const jobId = job.data.id;

      // Step 3: Poll Job until finished
      let exportUrl = null;
      while (!exportUrl) {
        await new Promise(r => setTimeout(r, 3000)); // wait 3s
        res = await fetch("https://api.cloudconvert.com/v2/jobs/" + jobId, {
          headers: { "Authorization": "Bearer " + apiKey }
        });
        job = await res.json();

        const exportTask = job.data.tasks.find(t => t.operation === "export/url" && t.status === "finished");
        if (exportTask) {
          exportUrl = exportTask.result.files[0].url;
        }
      }

      // Step 4: Load HTML result into iframe
      document.getElementById("resultFrame").src = exportUrl;
    }
  </script>
</body>
</html>
