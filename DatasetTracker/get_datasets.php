<?php

include("include/include.php");

$query = mysqli_query($_LINK, "SELECT LOCATION FROM CHECKSUM ORDER BY LAST_CHECK ASC LIMIT 20");

while ($row = mysqli_fetch_array($query)) {
  echo $row["LOCATION"] . "\n";
}
