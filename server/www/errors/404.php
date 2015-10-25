<?php header ( 'HTTP/1.0 404 Not Found' );
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>404 Not Found</title>
</head>
<body>
  <h1>Not Found</h1>
  <h2>My 404</h2>
  <p>The requested URL &quot;<?php echo($_SERVER['REQUEST_URI']) ?>&quot; was not found on the server.</p>
</body>
</html>
