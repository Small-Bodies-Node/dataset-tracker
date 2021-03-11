<?php

include_once('include.php');

?>

<?php
echo ("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 3.2 Final//EN\">");
?>
<html>

<head>
  <title>Search Form - SBN Dataset Database</title>
  <link rel="stylesheet" type="text/css" href="style.css" />

  <script>
    function hintShow(x) {
      document.getElementById(x).style.display = "inline";
    }

    function hintHide(x) {
      document.getElementById(x).style.display = "none";
    }
  </script>

</head>

<body>

  <form action="?" method="post">
    <input type="submit" value="Home" />
  </form>

  <div id="instructions">
    <?php
    if (isset($errors) and gettype($errors) == "array" and count($errors) > 0) {
    ?>
      <p class="error">
        There were errors in the form. Please refer to the red text
        below and correct the errors.
      </p>
    <?php
    }
    ?>
    <p>
      For all date fields, please use the format
      <span class="dateFormat">YYYY-MM-DD</span>.
      <a href="?page=help">More help</a>.
    </p>
  </div>

  <form method="post" action="?action=search&page=search.results">
    <!--<form method="post" action="?page=search.form">-->
    <div id="form">
      <table cellspacing="0px" cellpadding="2px">
        <?php

        $rowColors = array("#EEE", "#DDD");

        rowColor($rowColors);

        logicalOperatorOption();

        searchGroup("ID_DESCRIPTION");

        rowColor($rowColors);

        searchTerm("DATA_SET_ID");
        searchTerm("ACTIVE_FLAG");
        searchTerm("DISCREPANCY_FLAG");
        searchTerm("REPORT_TO_EN");
        searchTerm("MIGRATED_FLAG");
        searchTerm("VOLUME_ID");
        searchTerm("DATA_SET_NAME");
        #	  searchTerm("WEBSITE_DS_NAME");
        searchTerm("COLLOQUIAL_ID");
        searchTerm("SBN_RESPONSIBLE_PARTY");
        searchTerm("SUBNODE_ID");
        searchTerm("DATA_PROVIDER");
        searchTerm("OBSERVATION_TYPE");
        searchTerm("SUPERSEDED_DS_ID");
        searchTerm("NSSDC_ID");
        searchTerm("SIP_ID");
        searchTerm("ADS_ID");
        searchTerm("DOI");
        searchTerm("LOCATION");
        searchTerm("SIZE");

        searchTerm("CREATE_DTS");
        searchTerm("EDIT_DTS");

        searchGroup("LONGTEXT");

        rowColor($rowColors);

        searchTerm("ABSTRACT");
        searchTerm("CITATION_DESC");

        searchGroup("TARGET_ETC");

        rowColor($rowColors);

        searchTerm("TARGET_NAME");
        searchTerm("TARGET_TYPE");
        #	  searchTerm("MISSION_ID");
        searchTerm("MISSION_NAME");
        searchTerm("HOST_NAME");
        searchTerm("INSTRUMENT_NAME");

        searchGroup("DEVELOPMENT");

        rowColor($rowColors);

        searchTerm("DEVELOPMENT_STATUS");
        searchTerm("OLAF_DELIVERY_FLAG");
        searchTerm("DRAFT_DATA_RECEIVED_DATE_FIRST");
        searchTerm("DRAFT_DATA_RECEIVED_DATE");
        #	  searchTerm("DRAFT_DATA_ACCEPTED_DATE");
        searchTerm("DRAFT_DATA_POSTED_DATE");
        searchTerm("REVIEW_DATE");
        searchTerm("REVIEW_RESULT");
        searchTerm("CERTIFIED_FLAG");
        searchTerm("FERRET_STATUS");
        searchTerm("FERRET_COMMENT");
        searchTerm("FERRET_INGEST_DATE");
        searchTerm("MIGRATION_STATUS");
        searchTerm("MIGRATION_TO");
        searchTerm("MIGRATION_DATE");
        searchTerm("MIGRATION_COMMENT");
        searchTerm("CATALOG_FILES_TO_EN_DATE");
        #	  searchTerm("REVIEW_EN_INGEST_DATE");
        searchTerm("CATALOG_FILES_TO_NSSDC_DATE");
        searchTerm("NSSDC_ID_RECEIVED_DATE");
        #	  searchTerm("PROFILE_SERVER_INGEST_DATE");

        searchGroup("ARCHIVING");

        rowColor($rowColors);

        searchTerm("ARCHIVE_STATUS");
        searchTerm("DATA_SET_TYPE");
        searchTerm("FINAL_DATA_RECEIVED_DATE");
        #	  searchTerm("FINAL_DATA_ACCEPTED_DATE");
        searchTerm("FINAL_DATA_POSTED_DATE");
        searchTerm("ABSTRACT_TO_ADS_DATE");
        searchTerm("RELEASE_DATE_FIRST");
        searchTerm("RELEASE_DATE");
        #	  searchTerm("HARDCOPY_MADE_DATE");
        #	  searchTerm("LAST_HARD_COPY_REFRESH_DATE");
        #	  searchTerm("REVISED_CATALOG_FILES_TO_EN_DATE");
        searchTerm("FINAL_EN_INGEST_DATE");
        searchTerm("NSSDC_UPLOAD_DATE");
        searchTerm("NSSDC_ACCEPTED_DATE");

        searchGroup(null);

        ?>
        </tr>
      </table>
    </div>
  </form>
</body>

</html>

<?php

function logicalOperatorOption()
{
  $default = "and";
?>
  <tr style="background-color: <?= rowColor() ?>">
    <td colspan="3" style="height: 3em">
      Match
      <?= radioInput("operator", "and", $default) ?>
      <label for="operator-and">all conditions</label>,
      <?= radioInput("operator", "or", $default) ?>
      <label for="operator-or">any condition</label>
    </td>
  </tr>
<?php
}

function searchGroup($group)
{
  global $groups;
?>
  <tr>
    <td colspan="4" class="searchFormGroupHeader">
      <span style="position: relative; float:right">
        <input type="submit">
      </span>
      <?php


      if ($group != "") {
      ?>
        <?= $groups[$group]["name"] ?>
        <span class="searchFormSmall">
          (<label for="search-group-<?= $group ?>">Show All</label>
          <?= checkboxInput("search-group-$group", "") ?>)
        <?php
      }
        ?>
        </span>
    </td>
  </tr>
<?php
}

function partialOption($id)
{
  $html = (checkboxInput($id, "") .
    " <label for='$id' class='searchFormSmall'>partial match</label>");
  return $html;
}

function nullOption($id)
{
  $html = (checkboxInput($id, "") .
    " <label for='$id' class='searchFormSmall'>include nulls</label>");
  return $html;
}

function checkboxInput($id, $extra)
{
  $checked = getParameter($id) ? "checked='true'" : "";
  return "<input type='checkbox' name='$id' id='$id' $checked $extra />";
}

function radioInput($name, $value, $default)
{
  $formValue = getParameter($name, $default);
  $checked = ($formValue == $value) ? "checked='1'" : "";
  return ("<input type='radio' name='$name' value='$value' " .
    "id='$name-$value' $checked />");
}

function textInput($id, $extra)
{
  $formValue = getParameter($id);
  $value = htmlspecialchars($formValue, ENT_QUOTES);
  return "<input type='text' name='$id' id='$id' value='$value' $extra />";
}

function multiselectInput($id, $values, $extra)
{
  $oldValues = getParameter($id, array());
  if (gettype($oldValues) != "array")
    $oldValues = array();
  if (gettype($values) != "array")
    $values = array();
  $size = min(10, count($values));
  $html = "<select name='" . $id . "[]' id='" . $id . "' multiple='true' size='" . $size . "' $extra>";
  foreach ($values as $value) {
    if (in_array($value, $oldValues))
      $selected = "selected='true'";
    else
      $selected = "";
    $html .= "<option " . $selected . " value='" . htmlspecialchars($value, ENT_QUOTES) . "'>" .
      htmlspecialchars($value) . "</option>";
  }
  $html .= "</select>";
  return $html;
}

function searchTerm($keyword)
{
  global $keywordInfo;

  searchTermIntro($keyword, $keywordInfo[$keyword]);

  switch ($keywordInfo[$keyword]["type"]) {
    case "text":
    case "longtext":
      searchTermText($keyword, $keywordInfo[$keyword]);
      break;
    case "flag":
      searchTermFlag($keyword, $keywordInfo[$keyword]);
      break;
    case "number":
      searchTermNumber($keyword, $keywordInfo[$keyword]);
      break;
    case "date":
      searchTermDate($keyword, $keywordInfo[$keyword]);
      break;
    case "object":
      searchTermObject($keyword, $keywordInfo[$keyword]);
      break;
    case "set":
      searchTermSet($keyword, $keywordInfo[$keyword]);
      break;
    default:
      print("<td>??: $keyword</td></tr>");
  }

  searchTermClosing($keyword, $keywordInfo[$keyword]);
}

function searchTermIntro($keyword, $info)
{
?>
  <tr style="background-color: <?= rowColor() ?>">
    <td class="searchFormShow">
      <label for="search-<?= $keyword ?>-show">Show</label>
      <?= checkboxInput(
        "search-" . $keyword . "-show",
        "onfocus=\"hintShow('$keyword" . "hint')\" onblur=\"hintHide('$keyword" . "hint')\""
      ) ?>
    </td>
    <td class="searchFormKeyword">
      <?= $info["displayName"] ?>
    </td>
  <?php
}

function searchTermClosing($keyword, $info)
{
  global $errors;
  ?>
    <td style="vertical-align: top">
      <span class="hint" id="<?= $keyword ?>hint"><?= $info["description"] ?><span class="hint-pointer">&nbsp;</span></span>
    </td>
  </tr>
  <?php
  if (
    isset($errors) and gettype($errors) == "array"
    and array_key_exists($keyword, $errors)
  ) {
  ?>
    <tr style="background-color: <?= rowColor("last") ?>">
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>
        <span class="error"><?= $errors[$keyword] ?></span>
      </td>
      <td></td>
    </tr>
  <?php
  }
}

function searchTermSet($keyword, $info)
{
  global $_LINK;


  if (array_key_exists("legalValues", $info)) {
    $values = $info["legalValues"];
  } else {
    $values = array();
    $results = mysqli_query($_LINK, "SELECT DISTINCT `$keyword` " .
      "FROM DATASET " .
      "WHERE `$keyword` != '' " .
      "ORDER BY `$keyword`");
    while ($row = mysqli_fetch_array($results)) {
      array_push($values, $row[$keyword]);
    }
  }
  ?>
  <td>
    <?= multiselectInput("search-" . $keyword . "-select", $values, "onfocus=\"hintShow('$keyword" . "hint')\" onblur=\"hintHide('$keyword" . "hint')\"") ?>
    <span style="vertical-align: top">
      <?= checkboxInput("search-" . $keyword . "-nonstandard", "") ?>
      <label for="search-<?= $keyword ?>-nonstandard" class='searchFormSmall'>include nonstandard values</label>
    </span>
  </td>
<?php
}

function searchTermObject($keyword, $info)
{
  global $_LINK;
  $values = array();
  $results = mysqli_query($_LINK, "SELECT DISTINCT `$keyword` " .
    "FROM `" . $info["dbTable"] . "` " .
    "WHERE `$keyword` != '' " .
    "ORDER BY `$keyword`");
  while ($row = mysqli_fetch_array($results)) {
    array_push($values, $row[$keyword]);
  }
?>
  <td class="searchFormConstraint">
    <?= textInput("search-" . $keyword . "-text", "onfocus=\"hintShow('$keyword" . "hint')\" onblur=\"hintHide('$keyword" . "hint')\"") ?>
    <?= partialOption("search-" . $keyword . "-partial") ?>
    <br />
    <?= multiselectInput("search-" . $keyword . "-select", $values, "onfocus=\"hintShow('$keyword" . "hint')\" onblur=\"hintHide('$keyword" . "hint')\"") ?>
    </select>
  </td>
<?php
}

function searchTermDate($keyword, $info)
{
?>
  <td>
    <?= textInput("search-" . $keyword . "-min", "onfocus=\"hintShow('$keyword" . "hint')\" onblur=\"hintHide('$keyword" . "hint')\"") ?>
    <label for="search-<?= $keyword ?>-min">(earliest)</label>,
    <?= textInput("search-" . $keyword . "-max", "onfocus=\"hintShow('$keyword" . "hint')\" onblur=\"hintHide('$keyword" . "hint')\"") ?>
    <label for="search-<?= $keyword ?>-max">(latest)</label>
    <?= nullOption("search-" . $keyword . "-null") ?>
  </td>
<?php
}

function searchTermNumber($keyword, $info)
{
?>
  <td>
    <?= textInput("search-" . $keyword . "-min", "onfocus=\"hintShow('$keyword" . "hint')\" onblur=\"hintHide('$keyword" . "hint')\"") ?>
    <label for="search-<?= $keyword ?>-min">(min)</label>,
    <?= textInput("search-" . $keyword . "-max", "onfocus=\"hintShow('$keyword" . "hint')\" onblur=\"hintHide('$keyword" . "hint')\"") ?>
    <label for="search-<?= $keyword ?>-max">(max)</label>
    <?php
    if ($info["hint"]) {
    ?>
      <br /><?= $info["hint"] ?>
    <?php
    }
    ?>
  </td>
<?php
}

function searchTermFlag($keyword, $info)
{
  $value = getParameter("search-" . $keyword . "-flag");
  $positive = ($value == "1") ? "selected='true'" : "";
  $negative = ($value == "0") ? "selected='true'" : "";
?>
  <td>
    <select name="search-<?= $keyword ?>-flag" onfocus="hintShow('<?= $keyword ?>hint')" onblur="hintHide('<?= $keyword ?>hint')">
      <option value="">--</option>
      <option <?= $positive ?> value="1"><?= $info["positive"] ?></option>
      <option <?= $negative ?> value="0"><?= $info["negative"] ?></option>
    </select>
  </td>
<?php
}

function searchTermText($keyword, $info)
{
?>
  <td>
    <?= textInput("search-" . $keyword . "-text", "onfocus=\"hintShow('$keyword" . "hint')\" onblur=\"hintHide('$keyword" . "hint')\"") ?>
    <?= partialOption("search-" . $keyword . "-partial") ?>
    <?= nullOption("search-" . $keyword . "-null") ?>
  </td>
<?php
}
?>