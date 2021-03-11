<?php

ensurePrivileges("admin");

$confirm = getParameter("confirm");

if ($confirm) {

  $uuid = getParameter("uuid");
  $uuid += 0;
  mysqli_query($_LINK, "DELETE FROM DATASET WHERE UUID = " . $uuid);

  foreach (array_filter($viewOrder, "isObject") as $keyword) {
    if ($keyword != "MISSION_NAME") {
      mysqli_query($_LINK, "DELETE FROM " . $keywordInfo[$keyword]["dbTable"] .
        " WHERE UUID = " . $uuid);
    }
  }

  mysqli_query($_LINK, "DELETE FROM `COMMENT` WHERE UUID = " . $uuid);
} else {

  $overridePage = "confirmRemove";
}
