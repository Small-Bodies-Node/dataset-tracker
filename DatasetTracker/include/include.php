<?php

include("dbSettings.php");

include_once("dotenv.php");

function connectToDB()
{
  $db_link = mysqli_connect( //
    getenv('MYSQL_HOST'),
    getenv('MYSQL_USER'),
    getenv('MYSQL_PASSWORD'),
    getenv('MYSQL_DATABASE')
  );
  return $db_link;
}
// Define Global Constant
$_LINK =  connectToDB();

function hasPrivileges($domain)
{
  global $privileges;

  $users = $privileges[$domain];
  if (gettype($users) != "array") {
    $users = array();
  }

  return in_array(getenv("REMOTE_USER"), $users);
}

function ensurePrivileges($domain)
{
  if (!hasPrivileges($domain)) {
    echo ("access denied");
    echo "domain = " . $domain;
    exit();
    header('HTTP/1.0 401 Unauthorized', true, 401);
    header('WWW-Authenticate: Basic realm="Dataset Tracker"');
    exit();
  }
}

function isObject($keyword)
{
  global $keywordInfo;
  return $keywordInfo[$keyword]["type"] == "object";
}

function isNotObject($keyword)
{
  return !isObject($keyword);
}

function overwriteParameter($name, $value)
{
  $_POST[$name] = $value;
}

function getParameter($name, $default = null)
{
  $value = getParameterHTTP($name, $default);
  return $value;
}

function getParameterFullName($name_starts_with, $default = null)
{
  global $_POST, $_GET;

  $name = $name_starts_with;

  # Default to POST version, if it's provided;
  # otherwise, use the GET version.
  foreach ($_POST as $key => $value) {
    if (substr($key, 0, strlen($name)) === $name) {
      return $key;
    }
  }

  /*
  if ($varName === null) {
    foreach ($_POST as $key => $value) {
      if (substr($key, 0, strlen($name)) === $name) {
        return $key;
      }
    }
  }
 */

  return $default;
}


function getParameterHTTP($name, $default = null)
{
  global $_POST, $_GET;

  # Default to POST version, if it's provided;
  # otherwise, use the GET version.
  if (isset($_POST[$name])) {
    $value = $_POST[$name];
  } elseif (isset($_GET[$name])) {
    $value = $_GET[$name];
  }


  /**
   * TODO: refactor get_magic_quotes_gpc
   * get_magic_quotes_gpc is deprecated as of PHP 7.4 and removed from 8.0
   * Leaving for now, but making a note here since intelephense is not happy
   * See: https://www.php.net/manual/en/function.get-magic-quotes-gpc.php
   */

  if (!isset($value) || $value === null) {
    # $value is still not defined, use the default
    $value = $default;
  } elseif (get_magic_quotes_gpc()) {
    # Strip slashes, necessary when get_magic_quotes_gpc() is true
    if (gettype($value) != "array") {
      $value = stripslashes($value);
    } else {
      $value = array_map("stripslashes", $value);
    }
  }

  return $value;
}

function rowColor($arg = null)
{
  static $colors = array("#FFFFFF");
  static $index = -1;

  if ($arg === null) {
    ++$index;
    $index %= count($colors);
    return $colors[$index];
  } elseif (gettype($arg) == "array") {
    $oldColors = $colors;
    $colors = $arg;
    $index = -1;
    return $oldColors;
  } elseif (gettype($arg) == "string") {
    switch ($arg) {
      case "last":
        return $colors[$index];
        break;
      default:
        return "#FF0000";
    }
  }
}

function readableSize($size, $newStandard = true)
{

  /* Determine which terminology to use. */

  if ($newStandard) {

    /* IEC standard */

    $magnitudes = array("B", "KiB", "MiB", "GiB", "TiB", "PiB", "EiB");
  } else {

    /* Old terminology, ambiguous with exponents of 10 */

    $magnitudes = array("B", "KB", "MB", "GB", "TB", "PB", "EB");
  }

  /* Begin with an order of 0 (1024^0) */

  $order = 0;

  /*
      * Decrease the size as long as two conditions hold:
      *
      * 1. The size is not yet under 1024.
      * 2. The order of magnitude can still increase and not go past
      *    the end of the defined magnitude strings.
      */

  while ($size >= 1024 & $order < count($magnitudes) - 1) {

    /* Decrease the size by a factor of 1024 */

    $size /= 1024.0;

    /* Increase the order of magnitude */

    ++$order;
  }

  /* Return a string representing the size. */

  return sprintf(($order != 0 ? "%.2f" : "%d") . " %s", $size, $magnitudes[$order]);
}

function unscaled($number)
{
  global
    $storageUnitsPattern,
    $storageUnits;

  $matches = array();

  if ($number != "") {
    if (preg_match($storageUnitsPattern, $number, $matches) == 1) {
      $value = $matches[1];
      $units = strtolower($matches[2]);
      if (
        is_numeric($value) and
        array_key_exists($units, $storageUnits)
      ) {
        $number = bcmul($value, $storageUnits[$units]);
      } elseif (!is_numeric($value) or $units != "") {
        $number = null;
      }
    } else {
      $number = null;
    }
  }

  return $number;
}
