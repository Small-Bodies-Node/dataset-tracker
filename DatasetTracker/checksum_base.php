<?php
include("include/include.php");

$query = mysqli_query($_LINK, "SELECT LOCATION FROM CHECKSUM");

while ($row = mysqli_fetch_array($query)) {
  echo $row["LOCATION"] . "\n";
}
