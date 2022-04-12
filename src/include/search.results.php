<?php
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
  <?php
  if (count($query["UUIDS"]) != count($query["table"])) {
    $plural = count($query["table"]) == 1 ? "" : "s";
  ?>
    <span style="font-size: .8em">
      (Showing <?= count($query["table"]) ?> record<?= $plural ?> due to
      sorting by <?= $keywordInfo[$query["sortKey"]]["displayName"] ?>.)
    </span>
  <?php
  }
  ?>
  <form method="post" action="?action=search&page=search.results">
    <?php
    $savedSort = htmlspecialchars(getParameterFullName("sort"), ENT_QUOTES);
    ?>
    <input type="hidden" name="savedSort" value="<?= $savedSort ?>" />
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
    <input type="submit" name="showForm" value="Back to Form" />
    <?php
    if (hasPrivileges("edit")) {
    ?>
      <input type="submit" name="spreadsheet" value="Edit as Spreadsheet" />
    <?php
    }
    ?>
    <table class="result-table">
      <?php
      $rowsPerHeader = 25;
      for ($j = 0; $j < count($query["table"]); ++$j) {
        if ($j % $rowsPerHeader == 0) {
      ?>
          <tr>
            <th nowrap="true" style="font-size: 0.8em">details</th>
            <?php
            for ($i = 0; $i < count($query["DISPLAY"]); ++$i) {
            ?>
              <th nowrap="true">
                <?= $keywordInfo[$query["DISPLAY"][$i]]["displayName"] ?>
                <input type="image" style="border: 0px none ; margin: 0px; padding: 0px;" title="Sort Ascending" src="images/famfamfam/icons/bullet_arrow_up.png" name="sort_<?= $query["DISPLAY"][$i] ?>+ASC" />
                <input type="image" style="border: 0px none ; margin: 0px; padding: 0px;" title="Sort Decending" src="images/famfamfam/icons/bullet_arrow_down.png" name="sort_<?= $query["DISPLAY"][$i] ?>+DESC" />
              </th>
            <?php
            }
            ?>
          </tr>
        <?php
          rowColor(array("#F8F8F8", "#E8E8E8"));
        }
        $row = $query["table"][$j];
        ?>
        <tr style="background-color: <?= rowColor() ?>">
          <td>
            <a href="?page=view&uuid=<?= $query["table"][$j]["UUID"] ?>"><img title="view" border="0" src="images/famfamfam/icons/magnifier.png"></a>
            <!--<a href="?page=edit&uuid=<?= $query["table"][$j]["UUID"] ?>"><img title="edit" border="0" src="images/famfamfam/icons/pencil.png"></a>-->
          </td>
          <?php
          for ($i = 0; $i < count($query["DISPLAY"]); ++$i) {
            $keyword = $query["DISPLAY"][$i];
            $info = $keywordInfo[$keyword];
            if ($info["type"] == "number") {
          ?>
              <td nowrap="true" style="text-align: right">
                <?php
                if ($info["units"] == "storage") {
                ?>
                  <?= readableSize($row[$keyword]) ?>
                <?php
                } else {
                ?>
                  <?= $row[$keyword] ?>
                <?php
                }
                ?>
              </td>
            <?php
            } else if ($info["type"] == "flag") {
            ?>
              <td nowrap="true">
                <?= $info[($row[$keyword] == "1") ? "positive" : "negative"] ?>
              </td>
            <?php
            } else if (
              $info["type"] == "longtext" or
              $info["type"] == "object"
            ) {
            ?>
              <td><?= $row[$keyword] ?></td>
            <?php
            } else {
            ?>
              <td nowrap="true"><?= $row[$keyword] ?></td>
          <?php
            }
          }
          ?>
        </tr>
      <?php
      }
      ?>
    </table>
</body>

</html>