<?php
echo ("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 3.2 Final//EN\">");
?>
<html>

<head>
  <title>SBN Dataset Database</title>
</head>

<body>
  <a href="?page=search.form">Search</a><br />
  <a href="?page=list">View Target, Mission, Host, and Instrument lists</a><br />
  <?php
  if (true || hasPrivileges("admin")) {
  ?>
    <a href="?page=edit&new=1">Insert Dataset</a><br />
  <?php
  }
  ?>
  <a href="?page=description">Field Descriptions</a><br />
  <a href="?page=help">Help</a><br />
  <br />
  <a href="?action=logout">Logout</a><br />
</body>

</html>