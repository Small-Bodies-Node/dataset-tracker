<?php

include("include/include.php");

ensurePrivileges("edit");

function toNumber($x)
{
  return $x + 0;
};

# Protect from SQL injection
// $ids = implode(",", array_map("toNumber", split(",", getParameter("ids"))));
$ids = implode(",", array_map("toNumber", preg_split("/,/", getParameter("ids"))));

# Update the records
mysqli_query($_LINK, "UPDATE DATASET SET ABSTRACT_TO_ADS_DATE = '" . date("Y-m-d") . "' WHERE UUID in (" . $ids . ");");

if (mysqli_affected_rows($_LINK) > 0) {
  printf("update successful.");
} else {
  printf("no affected records.");
}
