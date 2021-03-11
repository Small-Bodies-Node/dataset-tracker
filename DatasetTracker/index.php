<?php

/* ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL); */
// error_reporting(-1);
// echo ini_get('display_errors');
// echo "===========<br><br><br>";
// echo
// echo "===========<br><br><br>";

include("include/include.php");

// Determine which action to perform
switch (strtolower(getParameter("action"))) {
  case "savespreadsheet":
    include("include/spreadsheet.action.php");
    // no break
  case "search":
    include("include/search.action.php");
    break;
  case "edit":
    include("include/edit.action.php");
    break;
  case "remove":
    include("include/remove.action.php");
    break;
  case "logout":
    include("include/logout.php");
    break;
}

# Determine what page to show
if (isset($overridePage) == "") {
  $page = getParameter("page");
} else {
  $page = $overridePage;
}



switch (strtolower($page)) {
  case "help":
    include("include/help.php");
    break;
  case "description":
    include("include/descriptions.php");
    break;
  case "list":
    include("include/list.php");
    break;
  case "search.form":
    include("include/search.form.php");
    break;
  case "search.results":
    include("include/search.results.php");
    break;
  case "view":
    include("include/view.php");
    break;
  case "edit":
    include("include/edit.form.php");
    break;
  case "spreadsheet.form":
    include("include/spreadsheet.form.php");
    break;
  case "confirmremove":
    include("include/remove.form.php");
    break;
  case "home":
  default:
    include("include/root.php");
    break;
}
