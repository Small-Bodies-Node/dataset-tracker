<?php

# Created by: Tilden Barnes
# Created on: 2012-02-07
# Purpose:    To read NSSDC xman generated files and update SIP_ID, SIZE, and NSSDC_UPLOAD_DATE.

include("include/include.php");

if (getenv("REMOTE_USER") != "util") {
  ensurePrivileges("edit");
}

$DATA_SET_ID = getParameter("DATA_SET_ID");
$SIP_ID = getParameter("SIP_ID");
$SIZE = getParameter("SIZE") + 0;
$NSSDC_UPLOAD_DATE = getParameter("NSSDC_UPLOAD_DATE");

#printf ("<p>DATA_SET_ID: %s<br />\nSIP_ID: %s<br />\nSIZE: %s<br />\nNSSDC_UPLOAD_DATE: %s\n<p/>",$DATA_SET_ID,$SIP_ID,$SIZE,$NSSDC_UPLOAD_DATE);

$changes = "SIP_ID='" . mysqli_real_escape_string($_LINK, $SIP_ID) . "'";
if ($SIZE > 0) {
  $changes .= ",SIZE=" . $SIZE;
}
if (!($NSSDC_UPLOAD_DATE === null)) {
  $changes .= ",NSSDC_UPLOAD_DATE='" . mysqli_real_escape_string($_LINK, $NSSDC_UPLOAD_DATE) . "'";
}

#print "update DATASET set ".$changes." where DATA_SET_ID='".mysqli_real_escape_string($_LINK, $DATA_SET_ID)."';"."\n";

mysqli_query($_LINK, "update DATASET set " . $changes . " where DATA_SET_ID='" . mysqli_real_escape_string($_LINK, $DATA_SET_ID) . "';");
