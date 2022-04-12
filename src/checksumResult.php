<?php

include("include/include.php");

$location = getParameter("location");
$result = getParameter("result");

if ($result == "OK") {
  mysqli_query($_LINK, "UPDATE CHECKSUM SET LAST_CHECK = now(), LAST_OK = now() WHERE LOCATION = '" . mysqli_real_escape_string($_LINK, $location) . "';");
} else {
  mysqli_query($_LINK, "UPDATE CHECKSUM SET LAST_CHECK = now() WHERE LOCATION = '" . mysqli_real_escape_string($_LINK, $location) . "';");
}
