<?php

session_start();

if (isset($_SESSION["relogin"])) {
  unset($_SESSION["relogin"]);
} else {
  header('WWW-Authenticate: Basic realm="Dataset Tracker"');
  header('HTTP/1.0 401 Unauthorized', true, 401);
  $_SESSION["relogin"] = 1;
}
?>
<html>

<head>
  <meta http-equiv="REFRESH" content="0;url=?">
</head>

</html>