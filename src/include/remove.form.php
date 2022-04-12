<?php

ensurePrivileges("admin");
echo ("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 3.2 Final//EN\">");
?>
<html>

<head>
  <title>Search Form - SBN Dataset Database</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>
  <?php
  $uuid = getParameter("uuid");
  $uuid += 0;

  $results = mysqli_query($_LINK, "SELECT DATA_SET_ID" .
    " FROM DATASET" .
    " WHERE UUID=" . $uuid);

  if (mysqli_num_rows($results) == 1) {
    $row = mysqli_fetch_array($results);
  ?>
    Are you sure you want to delete the data set
    '<code><?= $row["DATA_SET_ID"] ?></code>'?<br /><br />
    <form action="?action=remove&uuid=<?= $uuid ?>&page=<?= getParameter("page") ?>&confirm=1" method="post">
      <input name="confirm" type="submit" value="Yes" />
    </form>
    <button onClick="window.history.go(-1); return false;">No</button>
  <?php
  } else {
  ?>
    The data set specified could not be found.
  <?php
  }
  ?>
</body>

</html>