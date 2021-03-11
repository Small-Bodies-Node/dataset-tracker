<?php

ensurePrivileges("edit");

if (getParameter("showForm")) {

  $overridePage = "search.form";
} else if (!getParameter("cancelEdits")) {

  $fields = preg_grep("/^\\d+_/", array_keys($_POST));
  sort($fields, SORT_NUMERIC);
  $ids = array_unique(array_map('get_uid', $fields));
  foreach ($ids as $id) {
    $id_fields = preg_grep("/^" . $id . "_/", $fields);
    $updates = array();
    foreach ($id_fields as $field) {
      handleField($field);
    }
    $query = ("UPDATE DATASET" .
      " SET " . join(",", $updates) . " WHERE UUID = " . $id . ";");
    mysqli_query($_LINK, $query);
  }
}

function get_uid($field)
{
  $matches = array();
  preg_match("/(\d+)_/", $field, $matches);
  return $matches[1];
}

function handleField($field)
{
  global $keywordInfo, $updates, $_LINK;

  if (!preg_match("/-manual/", $field)) {

    $matches = array();
    preg_match("/^\\d+_(.*)$/", $field, $matches);
    $keyword = $matches[1];

    $info = $keywordInfo[$keyword];

    if ($keyword == "DATA_SET_ID")
      ensurePrivileges("admin");

    switch ($info["type"]) {
      case "text":
      case "longtext":
      case "date":
        $value = $_POST[$field];
        if ($value == "") {
          array_push($updates, $keyword . " = NULL");
        } else {
          array_push($updates, $keyword . " = '"
            . mysqli_real_escape_string($_LINK, $value) . "'");
        }
        break;
      case "flag":
        $value = $_POST[$field];
        $value += 0;
        array_push(
          $updates,
          $keyword . " = " . $value
        );
        break;
      case "number":
        $value = $_POST[$field];
        if ($value == "")
          $value = 0;
        if ($info["units"] == "storage")
          $value = unscaled($value);
        $value += 0;
        array_push(
          $updates,
          $keyword . " = " . $value
        );
        break;
      case "set":
        $value = $_POST[$field];
        if ($value == "")
          $value = $_POST[$field . "-manual"];
        array_push(
          $updates,
          $keyword . " = '" . mysqli_real_escape_string($_LINK, $value) . "'"
        );
        break;
      default:
    }
  }
}
