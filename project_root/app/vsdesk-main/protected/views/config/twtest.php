<html>
<head>
</head>
<body>
<script>
    window.onload = function () {
        var e = window.opener.document.getElementById('TeamViewerForm_access_token');
        e.value = '<?= $access_token ?>';
        window.close();
    };
</script>
</body>
</html>
