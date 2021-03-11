<?php
echo ("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 3.2 Final//EN\">");
?>
<html>

<head>
  <title>Object List - SBN Dataset Database</title>
</head>
<form action="?" method="post">
  <input type="submit" value="Home" />
</form>

<body>
  <h2>Targets:</h2>
  <p>
  <table>
    <tr>
      <th>Target Name</th>
      <th>Target Type</th>
    </tr>
    <?php
    $targets = mysqli_query($_LINK, "SELECT DISTINCT `TARGET_TYPE`, `TARGET_NAME`" .
      " FROM `TARGET`" .
      " ORDER BY `TARGET_NAME`;");
    while ($row = mysqli_fetch_array($targets)) {
    ?>
      <tr>
        <td><?= htmlspecialchars($row["TARGET_NAME"]) ?></td>
        <td><?= htmlspecialchars($row["TARGET_TYPE"]) ?></td>
      </tr>
    <?php
    }
    ?>
  </table>
  </p>
  <h2>Hosts:</h2>
  <p>
    <?php
    $hosts = mysqli_query($_LINK, "SELECT DISTINCT `HOST_NAME`" .
      " FROM `HOST`" .
      " ORDER BY `HOST_NAME`;");
    while ($row = mysqli_fetch_array($hosts)) {
    ?>
      <?= htmlspecialchars($row["HOST_NAME"]) ?><br />
    <?php
    }
    ?>
  </p>
  <h2>Instruments:</h2>
  <p>
    <?php
    $instruments = mysqli_query($_LINK, "SELECT DISTINCT `INSTRUMENT_NAME`" .
      " FROM `INSTRUMENT`" .
      " ORDER BY `INSTRUMENT_NAME`;");
    while ($row = mysqli_fetch_array($instruments)) {
    ?>
      <?= htmlspecialchars($row["INSTRUMENT_NAME"]) ?><br />
    <?php
    }
    ?>
  </p>
  <h2>Missions:</h2>
  <p>
    <?php
    $missions = mysqli_query($_LINK, "SELECT DISTINCT `MISSION_NAME`" .
      " FROM `MISSION`" .
      " ORDER BY `MISSION_NAME`;");
    while ($row = mysqli_fetch_array($missions)) {
    ?>
      <?= htmlspecialchars($row["MISSION_NAME"]) ?><br />
    <?php
    }
    ?>
  </p>
</body>

</html>