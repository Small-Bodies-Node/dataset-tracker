<?php

if (getParameter("showForm")) {

  $overridePage = "search.form";
} else {

  /* Setup some global variables */

  $errors = array();

  if (getParameter("spreadsheet")) {

    $overridePage = "spreadsheet.form";

    $sort = getParameter("savedSort");
    $sortExtract = strpos($sort, 'sort') !== False ? substr($sort, 5, -2) : $sort;
    if ($sortExtract == "") {
      $sortExtract = "DATA_SET_ID+ASC";
    }
    // $sortItems = split('\+', $sortExtract);
    $sortItems = preg_split('/\+/', $sortExtract);

    /*
      * Don't allow sorting by objects, which can produce multiple
      * results for a single dataset.
    */

    if ($keywordInfo[$sortItems[0]]["type"] == "object") {

      $sortItems = array("DATA_SET_ID", "ASC");
    }
  } else {

    $sort = getParameterFullName("sort", "DATA_SET_ID+ASC");
    $sortExtract = strpos($sort, 'sort') !== False ? substr($sort, 5, -2) : $sort;
    // $sortItems = split('\+', $sortExtract);
    $sortItems = preg_split('/\+/', $sortExtract);
  }


  $query = array(

    "operator" => (getParameter("operator", "and") == "and") ? " AND " : " OR ",

    "sortKey"  => $sortItems[0],
    "sortDir"  => $sortItems[1],

    "select"   => array("DATASET.UUID AS UUID"),
    "from"     => array("DATASET"),
    "join"     => array("LEFT JOIN DATASET AS DS ON DS.UUID = DATASET.UUID"),
    "where"    => array(),
    "group"    => array("DATASET.UUID"),
    "order"    => array(),

    "DISPLAY"  => array(),

    "form"     => array(array("operator", getParameter("operator", "and"))),
  );

  # Prevent SQL injection

  # direction must be ASC or DESC

  if ($query["sortDir"] != "ASC" and $query["sortDir"] != "DESC") {
    $query["sortDir"] = "ASC";
  }

  # check syntax of sort keyword

  if (preg_match("/[^A-Za-z_]/", $query["sortKey"])) {
    $query["sortKey"] = "DATA_SET_ID";
  }

  /* Construct the query from the form */

  constructQuery();

  /* If there were any errors encountered in the form, bring back the */
  /* search form so the user can fix the errors. */

  if (count($errors) > 0) {
    $overridePage = "search.form";
  } else {

    # Make sure there's SOMETHING in the where clause

    if (count($query["where"]) == 0)
      array_push($query["where"], "1");

    getUUIDs();
    if (count($query["UUIDS"]) > 0) {
      getTotalSize();
      getResultTable();
    }
  }
}

function getUUIDs()
{
  global $query, $_LINK;
  $countSQL = ("SELECT DISTINCT DATASET.UUID AS UUID " .
    "FROM " . join(",", $query["from"]) . " " .
    join(" ", $query["join"]) . " " .
    "WHERE " . join($query["operator"], $query["where"]));
  $results = mysqli_query($_LINK, $countSQL);
  $query["UUIDS"] = array();
  while ($row = mysqli_fetch_array($results)) {
    array_push($query["UUIDS"], $row["UUID"]);
  }
}

function getTotalSize()
{
  global $query, $_LINK;

  $sizeSQL = ("SELECT SUM(SIZE) AS SIZE " .
    "FROM DATASET " .
    "WHERE DATASET.UUID IN (" . join(",", $query["UUIDS"]) . ")");
  $results = mysqli_query($_LINK, $sizeSQL);
  $row = mysqli_fetch_array($results);
  $query["totalSize"] = $row["SIZE"];
}

function getResultTable()
{
  global $query, $_LINK;
  $tableSQL = ("SELECT " . join(",", $query["select"]) . " " .
    "FROM " . join(",", $query["from"]) . " " .
    join(" ", $query["join"]) . " " .
    "WHERE DATASET.UUID IN (" . join(",", $query["UUIDS"]) . ") " .
    "GROUP BY " . join(",", $query["group"]) . " " .
    "ORDER BY " . $query["sortKey"] . " " . $query["sortDir"]);
  $results = mysqli_query($_LINK, $tableSQL);
  $query["table"] = array();
  while ($row = mysqli_fetch_array($results)) {
    array_push($query["table"], $row);
  }
}

function constructQuery()
{
  global $outputOrder, $keywordInfo, $passThrough, $query, $groups;

  foreach ($groups as $group => $attributes) {
    array_push(
      $query["form"],
      array(
        "search-group-" . $group,
        getParameter("search-group-" . $group)
      )
    );
  }
  foreach ($outputOrder as $keyword) {
    foreach ($passThrough[$keywordInfo[$keyword]["type"]] as $suffix) {
      $realSuffix = preg_replace('/\[\]/', '', $suffix);
      array_push(
        $query["form"],
        array(
          "search-" . $keyword . $realSuffix,
          getParameter("search-" . $keyword . $realSuffix)
        )
      );
    }
    handleKeyword($keyword);
  }
  $query["join"] = array_unique($query["join"]);
}

function handleKeyword($keyword)
{
  global $keywordInfo, $errors, $passThrough;

  foreach ($passThrough[$keywordInfo[$keyword]["type"]] as $suffix) {

    /* passThrough($keyword.$suffix); */
  }

  switch ($keywordInfo[$keyword]["type"]) {
    case "text":
    case "longtext":
      handleKeywordText($keyword, $keywordInfo[$keyword]);
      break;
    case "date":
      handleKeywordDate($keyword, $keywordInfo[$keyword]);
      break;
    case "number":
      handleKeywordNumber($keyword, $keywordInfo[$keyword]);
      break;
    case "flag":
      handleKeywordFlag($keyword, $keywordInfo[$keyword]);
      break;
    case "object":
      handleKeywordObject($keyword, $keywordInfo[$keyword]);
      break;
    case "set":
      handleKeywordSet($keyword, $keywordInfo[$keyword]);
      break;
    default:
      $errors[$keyword] = ("Can't handle keyword type: " .
        $keywordInfo[$keyword]["type"]);
  }
}

function handleKeywordText($keyword, $info)
{
  global $query, $_LINK;

  /* We use it a lot, so fetch the text field once. */

  $text = getParameter("search-" . $keyword . "-text");

  /* Determine if we should include this keyword in the query */

  if (
    $keyword == "DATA_SET_ID"
    or getParameter("search-" . $keyword . "-show")
    or $text != ""
    or getParameter("search-" . $keyword . "-null")
    or getParameter("search-group-" . $info["group"])
  ) {

    /* Add the keyword to the query */

    array_push($query["select"], "DATASET." . $keyword . " AS " . $keyword);
    array_push($query["DISPLAY"], $keyword);

    /* init the where clauses */

    $terms = array();

    /* See how we should constrain the values */

    if ($text) {

      /* text was provided.
	  * differentiate between partial and exact matches
	  */

      if (getParameter("search-" . $keyword . "-partial"))

        array_push(
          $terms,
          sprintf(
            "DATASET.%s LIKE '%%%s%%'",
            $keyword,
            mysqli_real_escape_string($_LINK, $text)
          )
        );

      else

        array_push(
          $terms,
          sprintf(
            "DATASET.%s = '%s'",
            $keyword,
            mysqli_real_escape_string($_LINK, $text)
          )
        );
    }

    if (getParameter("search-" . $keyword . "-null"))

      /* The user is looking for nulls or blanks */

      array_push(
        $terms,
        sprintf("DATASET.%s IS NULL", $keyword),
        sprintf("DATASET.%s = ''", $keyword)
      );

    /* If there are any constraints, add them to the query */

    if (count($terms) > 0)

      array_push($query["where"], "(" . join(" OR ", $terms) . ")");
  }
}

function handleKeywordDate($keyword, $info)
{
  global $query, $errors;

  $min = getParameter("search-" . $keyword . "-min");
  $max = getParameter("search-" . $keyword . "-max");

  if (
    getParameter("search-" . $keyword . "-show")
    or getParameter("search-group-" . $info["group"])
    or getParameter("search-" . $keyword . "-null")
    or $min != ""
    or $max != ""
  ) {

    /* add the column to the query */

    array_push($query["select"], "DATASET." . $keyword . " AS " . $keyword);
    array_push($query["DISPLAY"], $keyword);

    if (
      preg_match("/^\s*(\d{4}).(\d{1,2}).(\d{1,2})\s*$/", $min, $matches)
      and checkdate($matches[2], $matches[3], $matches[1])
    ) {
      $minValid = true;
    } else if (getParameter("search-" . $keyword . "-min") != "") {
      $minError = true;
    }

    if (
      preg_match("/^\s*(\d{4}).(\d{1,2}).(\d{1,2})\s*$/", $max, $matches)
      and checkdate($matches[2], $matches[3], $matches[1])
    ) {
      $maxValid = true;
    } else if (getParameter("search-" . $keyword . "-max") != "") {
      $maxError = true;
    }

    if (isset($minError) or isset($maxError)) {
      $errors[$keyword] = ("Please use a valid date " .
        "in the format YYYY-MM-DD");
      return;
    }

    $terms = array();

    if (isset($minValid) and isset($maxValid)) {
      //       // Any clue why the following doesn't work?
      //	 array_push($terms,
      //		    sprintf("DATASET.".$keyword." BETWEEN ".
      //			    "CAST('%s' AS DATE) AND CAST('%s' AS DATE)",
      //			    $min, $max));
      array_push(
        $terms,
        sprintf(
          "(DATASET." . $keyword . " >= CAST('%s' AS DATE) AND DATASET." . $keyword . " <= CAST('%s' AS DATE))",
          $min,
          $max
        )
      );
    } else if (isset($minValid)) {
      array_push(
        $terms,
        sprintf(
          "DATASET." . $keyword . " >= CAST('%s' AS DATE)",
          $min
        )
      );
    } else if (isset($maxValid)) {
      array_push(
        $terms,
        sprintf(
          "DATASET." . $keyword . " <= CAST('%s' AS DATE)",
          $max
        )
      );
    }

    if (getParameter("search-" . $keyword . "-null")) {
      array_push(
        $terms,
        "DATASET." . $keyword . " IS NULL",
        "DATASET." . $keyword . " = CAST('0000-00-00' AS DATE)"
      );
    }

    if (count($terms) > 0) {

      array_push($query["where"], "(" . join(" OR ", $terms) . ")");
    }
  }
}

function handleKeywordNumber($keyword, $info)
{
  global $query, $errors, $storageUnitsPattern, $storageUnits;

  $min = getParameter("search-" . $keyword . "-min");
  $max = getParameter("search-" . $keyword . "-max");

  if (
    getParameter("search-" . $keyword . "-show")
    or getParameter("search-group-" . $info["group"])
    or getParameter("search-" . $keyword . "-null")
    or $min != ""
    or $max != ""
  ) {

    array_push($query["select"], "DATASET." . $keyword . " AS " . $keyword);
    array_push($query["DISPLAY"], $keyword);

    switch ($info["units"]) {
      case "storage":
        $matches = array();
        if ($min != "") {
          if (
            preg_match($storageUnitsPattern, $min, $matches) == 1
            and is_numeric($matches[1])
            and array_key_exists(
              strtolower($matches[2]),
              $storageUnits
            )
          ) {
            $min = bcmul($matches[1], $storageUnits[$matches[2]]);
          } else {
            $minError = true;
          }
        }
        if ($max != "") {
          if (
            preg_match($storageUnitsPattern, $max, $matches) == 1
            and is_numeric($matches[1])
            and array_key_exists(
              strtolower($matches[2]),
              $storageUnits
            )
          ) {
            $max = bcmul($matches[1], $storageUnits[$matches[2]]);
          } else {
            $maxError = true;
          }
        }
        break;
      default:
        if ($min != "" and !is_numeric($min)) {
          $minError = true;
        }
        if ($max != "" and !is_numeric($max)) {
          $maxError = true;
        }
    }

    if (isset($minError) or isset($maxError)) {
      $errors[$keyword] = "Check format for number.";
      return;
    }

    $terms = array();

    if ($min != "" and $max != "") {
      array_push($terms, "DATASET.$keyword BETWEEN $min AND $max");
    } else if ($min != "") {
      array_push($terms, "DATASET.$keyword >= $min");
    } else if ($max != "") {
      array_push($terms, "DATASET.$keyword <= $max");
    }

    if (getParameter("search-" . $keyword . "-null")) {
      array_push(
        $terms,
        "DATASET.$keyword IS NULL",
        "DATASET.$keyword = 0"
      );
    }

    if (count($terms) > 0) {
      array_push($query["where"], "(" . join(" OR ", $terms) . ")");
    }
  }
}

function handleKeywordFlag($keyword, $info)
{
  global $query, $errors;

  if (
    getParameter("search-" . $keyword . "-show")
    or getParameter("search-group-" . $info["group"])
    or getParameter("search-" . $keyword . "-flag") != ""
  ) {

    array_push($query["select"], "DATASET.$keyword AS $keyword");
    array_push($query["DISPLAY"], $keyword);

    switch (getParameter("search-" . $keyword . "-flag")) {
      case "0":
        array_push($query["where"], "DATASET.$keyword = 0");
        break;
      case "1":
        array_push($query["where"], "DATASET.$keyword = 1");
        break;
    }
  }
}

function handleKeywordObject($keyword, $info)
{
  global $query, $_LINK;

  $table = $info["dbTable"];
  $text = getParameter("search-" . $keyword . "-text");
  $select = getParameter("search-" . $keyword . "-select", array());
  if (gettype($select) != "array")
    $select = array();

  if (
    getParameter("search-" . $keyword . "-show")
    or getParameter("search-group-" . $info["group"])
    or getParameter("search-" . $keyword . "-text") != ""
    or count($select) > 0
  ) {

    array_push(
      $query["join"],
      "LEFT JOIN $table ON $table.UUID = DATASET.UUID"
    );

    if ($query["sortKey"] == $keyword) {
      array_push($query["select"], "$table.$keyword AS $keyword");
      array_push($query["group"], "$table.$keyword");
    } else {
      array_push(
        $query["select"],
        "GROUP_CONCAT(DISTINCT $table.$keyword SEPARATOR ', ')" .
          "AS $keyword"
      );
    }

    array_push($query["DISPLAY"], $keyword);

    $terms = array();
    if ($text != "") {
      if (getParameter("search-" . $keyword . "-partial"))
        array_push($terms, sprintf(
          "$table.$keyword LIKE '%%%s%%'",
          mysqli_real_escape_string($_LINK, $text)
        ));
      else
        array_push($terms, sprintf(
          "$table.$keyword = '%s'",
          mysqli_real_escape_string($_LINK, $text)
        ));
    }
    if (gettype($select) == "array" and count($select) > 0) {
      $values = array();
      foreach ($select as $value)
        array_push(
          $values,
          sprintf("'%s'", mysqli_real_escape_string($_LINK, $value))
        );
      array_push($terms, "$table.$keyword IN (" . join(",", $values) . ")");
    }

    if (count($terms) > 0) {

      array_push($query["where"], "(" . join(" OR ", $terms) . ")");
    }
  }
}

function handleKeywordSet($keyword, $info)
{
  global $query, $_LINK;

  $select = getParameter("search-" . $keyword . "-select", array());
  if (gettype($select) != "array")
    $select = array();

  if (
    getParameter("search-" . $keyword . "-show")
    or getParameter("search-" . $keyword . "-nonstandard")
    or getParameter("search-group-" . $info["group"])
    or count($select) > 0
  ) {

    array_push($query["select"], "DATASET.$keyword AS $keyword");
    array_push($query["DISPLAY"], $keyword);

    $terms = array();
    if (count($select) > 0) {
      $values = array();
      foreach ($select as $value)
        array_push(
          $values,
          sprintf("'%s'", mysqli_real_escape_string($_LINK, $value))
        );
      array_push(
        $terms,
        "DATASET.$keyword IN (" . join(",", $values) . ")"
      );
    }

    if (getParameter("search-" . $keyword . "-nonstandard")) {
      $values = array();
      foreach ($info["legalValues"] as $value)
        array_push(
          $values,
          sprintf("'%s'", mysqli_real_escape_string($_LINK, $value))
        );
      if (count($values) > 0) {
        array_push(
          $terms,
          "DATASET.$keyword NOT IN (" . join(",", $values) . ")"
        );
      }
    }

    if (count($terms) > 0) {
      array_push($query["where"], "(" . join(" OR ", $terms) . ")");
    }
  }
}
