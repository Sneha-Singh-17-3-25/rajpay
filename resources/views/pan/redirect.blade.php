<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>PAN API</title>
    <script type="text/javascript">
        // Submit the form as soon as the page loads
        window.onload = function() {
            document.forms['panForm'].submit();
        }
    </script>
</head>
<body id="pageBody">
    <form id="panForm" name="panForm" action="{{ $url }}" method="post">
        <input type="hidden" name="req" id="req" value='{{ $reqJson }}'/>
        <center>
            <p>Please wait, redirecting to PAN service...</p>
           
        </center>
    </form>
</body>
</html>