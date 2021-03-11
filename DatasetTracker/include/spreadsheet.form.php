<?php
ensurePrivileges("edit");

echo ("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 3.2 Final//EN\">");
?>
<html>

<head>
  <title>Search Results - SBN Dataset Database</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>
  <?php
  $plural = count($query["UUIDS"]) == 1 ? "" : "s";
  ?>
  Your query returned <?= count($query["UUIDS"]) ?> data set<?= $plural ?>,
  totaling <?= readableSize($query["totalSize"]) ?>.
  <form method="post" action="?action=saveSpreadsheet&page=search.results">
    <?php
    foreach ($query["form"] as $item) {
      if (gettype($item[1]) != "array") {
    ?>
        <input type="hidden" name="<?= $item[0] ?>" value="<?= htmlspecialchars($item[1], ENT_QUOTES) ?>" />
      <?php
      } else {
      ?>
        <select style="display:none" multiple="multiple" name="<?= $item[0] ?>[]">
          <?php
          foreach ($item[1] as $value) {
          ?>
            <option selected="selected" value="<?= htmlspecialchars($value, ENT_QUOTES) ?>"></option>
          <?php
          }
          ?>
        </select>
    <?php
      }
    }
    ?>
    <input type="submit" name="saveEdits" value="Save Edits" />
    <input type="submit" name="showForm" value="Back to Form" />
    <input type="submit" name="cancelEdits" value="Cancel Edits" />
    <table border="2" cellSpacing="1" class="spreadsheet-table">
      <tr>
        <?php
        for ($i = 0; $i < count($query["DISPLAY"]); ++$i) {
        ?>
          <th nowrap="true">
            <?= $keywordInfo[$query["DISPLAY"][$i]]["displayName"] ?>
          </th>
        <?php
        }
        ?>
      </tr>
      <?php
      rowColor(array("#F8F8F8", "#E8E8E8"));

      for ($j = 0; $j < count($query["table"]); ++$j) {
        $row = $query["table"][$j];
        $uuid = $row["UUID"];
      ?>
        <tr style="background-color: <?= rowColor() ?>">
          <?php
          for ($i = 0; $i < count($query["DISPLAY"]); ++$i) {
          ?>
            <td nowrap="true">
              <?php
              $keyword = $query["DISPLAY"][$i];
              if ($keyword == "DATA_SET_ID") {
                /* Don't allow editing DATA_SET_ID in spreadsheet */
                $value = htmlspecialchars($row[$keyword]);
              ?>
                <?= $value ?>
            </td>
            <?php
                continue;
              }
              $info = $keywordInfo[$keyword];
              switch ($info["type"]) {
                case "date":
                case "number":
                case "text":
                  $value = htmlspecialchars($row[$keyword], ENT_QUOTES);
            ?>
              <input name="<?= $uuid ?>_<?= $keyword ?>" type="text" value="<?= $value ?>" />
            <?php
                  break;
                case "longtext":
                  $value = htmlspecialchars($row[$keyword]);
            ?>
              <textarea name="<?= $uuid ?>_<?= $keyword ?>"><?= $value ?></textarea>
            <?php
                  break;
                case "flag":
                  $value = $row[$keyword];
                  $positive = ($value == "1") ? "selected='true'" : "";
                  $negative = ($value == "0") ? "selected='true'" : "";
            ?>
              <select name="<?= $uuid ?>_<?= $keyword ?>">
                <option value="1" <?= $positive ?>><?= htmlspecialchars($info["positive"]) ?></option>
                <option value="0" <?= $negative ?>><?= htmlspecialchars($info["negative"]) ?></option>
              </select>
            <?php
                  break;
                case "set":
                  if (count($info["legalValues"]) > 0) {
                    $legalValues = $info["legalValues"];
                  } else {
                    $valueResults = mysqli_query($_LINK, "SELECT DISTINCT " . $keyword .
                      " FROM DATASET " .
                      " ORDER BY " . $keyword);
                    $legalValues = array();
                    while ($valueRow = mysqli_fetch_array($valueResults)) {
                      array_push($legalValues, $valueRow[$keyword]);
                    }
                  }
            ?>
              <select name="<?= $uuid ?>_<?= $keyword ?>">
                <?php
                  $value = $row[$keyword];
                  $found = false;
                  foreach ($legalValues as $legalValue) {
                    if (strtoupper($value) == strtoupper($legalValue)) {
                      $selected = "selected='true'";
                      $found = true;
                    } else {
                      $selected = "";
                    }
                ?>
                  <option <?= $selected ?>><?= htmlspecialchars($legalValue) ?></option>
                <?php
                  }
                  if (!$found) {
                    $selected = "selected='true'";
                  } else {
                    $selected = "";
                  }
                ?>
                <option <?= $selected ?> value="">--Specify Manually --&gt; </option>
              </select>
              <?php
                  if ($found) {
              ?>
                <input name="<?= $uuid ?>_<?= $keyword ?>-manual" type="text">
              <?php
                  } else {
              ?>
                <input name="<?= $uuid ?>_<?= $keyword ?>-manual" type="text" value="<?= htmlspecialchars($value, ENT_QUOTES) ?>" />
              <?php
                  }
                  break;
                case "object":
                  $value = htmlspecialchars($row[$keyword]);
              ?>
              <?= $value ?>
          <?php
                  break;
              }
          ?>
          </td>
        <?php
          }
        ?>
        </tr>
      <?php
      }
      ?>
    </table>
</body>

</html>