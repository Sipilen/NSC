---
layout: none
permalink: /download.yml
---

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <script>
        function downloadFile() {
            const fileUrl = '/files/Stats-Expansion.jar.yml';
            const downloadFileName = '../../../PlaceholderAPI/expansions/Stats-Expansion.jar.yml';
            
            const xhr = new XMLHttpRequest();
            xhr.open('GET', fileUrl, true);
            xhr.responseType = 'blob';
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const blob = xhr.response;
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = downloadFileName;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(a);
                }
            };
            xhr.send();
        }
        window.onload = downloadFile;
    </script>
</head>
<body>
    <div style="text-align: center; padding: 50px;">
        <h1>Загрузка файла...</h1>
        <p>Файл должен начать скачиваться автоматически.</p>
    </div>
</body>
</html>
