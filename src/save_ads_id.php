<?php

include("include/include.php");

if (getenv("REMOTE_USER") != "util") {
  ensurePrivileges("edit");
}

$VID = getParameter("ID") + 0;
$UUID = getParameter("UUID") + 0;
$ADS_ID = getParameter("ADS_ID");

if ($VID > 0) {
  mysqli_query($_LINK, "UPDATE DATASET SET ADS_ID = '" . mysqli_real_escape_string($_LINK, $ADS_ID) . "' WHERE ADS_VOLUME = " . $VID . ";");
} else {
  mysqli_query($_LINK, "UPDATE DATASET SET ADS_ID = '" . mysqli_real_escape_string($_LINK, $ADS_ID) . "' WHERE UUID = " . $UUID . ";");
}
