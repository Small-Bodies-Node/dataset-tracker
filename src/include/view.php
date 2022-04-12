<?php
echo ("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 3.2 Final//EN\">");

$uuid = getParameter("uuid") + 0;

$results = mysqli_query($_LINK, "SELECT * FROM `DATASET` WHERE `UUID` = $uuid");

$row = mysqli_fetch_array($results);

if (!isset($navigation) || $navigation == "")
  $navigation = getParameter("nav");

?>
<html>

<head>
  <title>Dataset Info - SBN Dataset Database</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>
  <table>
    <tr>
      <td>
        <form action="?page=search.form" method="post">
          <input type="submit" value="Search" />
        </form>
      </td>
      <?php
      if ($navigation == "insert") {
      ?>
        <td>
          <form action="?page=edit&new=1" method="post">
            <input type="submit" value="Add another Dataset" />
          </form>
        </td>
      <?php
      } else if ($navigation == "edit") {
      ?>
      <?php
      } else {
      ?>
        <td>
          <form action="?" method="post">
            <button onClick="window.history.go(-1); return false;">Back</button>
          </form>
        </td>
      <?php
      }
      if (hasPrivileges("edit")) {
      ?>
        <td>
          <form method="post" action="?page=edit&uuid=<?= $uuid ?>">
            <input type="submit" value="Edit this Dataset" />
          </form>
        </td>
      <?php
      }
      if (hasPrivileges("admin")) {
      ?>
        <td>
          <form method="post" action="?page=edit&uuid=<?= $uuid ?>&clone=1">
            <input type="submit" value="Clone this Dataset" />
          </form>
        </td>
        <td>
          <form method="post" action="?action=remove&uuid=<?= $uuid ?>&page=search.form">
            <input type="submit" value="Delete this Dataset" />
          </form>
        </td>
      <?php
      }
      ?>
    </tr>
  </table>
  <table class="info-table">
    <?php
    rowColor(array("#F8F8F8", "#E8E8E8"));
    foreach ($viewOrder as $keyword) {
    ?>
      <tr style="background-color: <?= rowColor() ?>">
        <td nowrap="1" style="font-weight: bold"><?= $keywordInfo[$keyword]["displayName"] ?></td>
        <td>&nbsp;</td>
        <?php showValue($keyword) ?>
      </tr>
    <?php
    }

    $comments = mysqli_query($_LINK, "SELECT *" .
      " FROM `COMMENT`" .
      " WHERE `UUID` = " . $uuid .
      " ORDER BY `TIMESTAMP`");
    if (mysqli_num_rows($comments) > 0) {
    ?>
      <tr style="background-color: <?= rowColor() ?>">
        <td nowrap="1" style="font-weight: bold">Comments</td>
        <td>&nbsp;</td>
        <td>
          <table>
            <tr>
              <th>Timestamp</th>
              <th>Flag</th>
              <th>Comment</th>
            </tr>
            <?php
            while ($row = mysqli_fetch_array($comments)) {
            ?>
              <tr>
                <td style="font-size: 0.8em">
                  <?= htmlspecialchars($row["TIMESTAMP"]) ?>
                </td>
                <td style="font-size: 0.8em">
                  <?= $row["FLAG"] ? "Yes" : "No" ?>
                </td>
                <td>
                  <?= htmlspecialchars($row["COMMENT"]) ?>
                </td>
              </tr>
            <?php
            }
            ?>
          </table>
        </td>
      </tr>
    <?php
    }
    ?>
  </table>
</body>

</html>

<?php

function showValue($keyword)
{
  global $keywordInfo, $row, $uuid, $_LINK;

  $info = $keywordInfo[$keyword];

?>
  <td>
    <?php
    switch ($info["type"]) {
      case "date":
        $value = $row[$keyword];
        if ($value == "0000-00-00")
          $value = "";
    ?>
        <?= $value ?>
      <?php
        break;
      case "text":
      case "set":
      case "longtext":
      ?>
        <?= $row[$keyword] ?>
        <?php
        break;
      case "number":
        if ($info["units"] == "storage") {
        ?>
          <?= readableSize($row[$keyword]) ?>
        <?php
        } else {
        ?>
          <?= $row[$keyword] ?>
        <?php
        }
        break;
      case "flag":
        ?>
        <?= $info[$row[$keyword] ? "positive" : "negative"] ?>
        <?php
        break;
      case "object":
        $objResults = mysqli_query($_LINK, "SELECT " . $keyword .
          " FROM " . $info["dbTable"] .
          " WHERE UUID=" . $uuid);
        if (mysqli_num_rows($objResults) < 2) {
          if ($objRow = mysqli_fetch_array($objResults)) {
        ?>
            <?= $objRow[$keyword] ?>
          <?php
          }
        } else {
          ?>
          <ul style="margin: 4px; padding-left: 20px">
            <?php
            while ($objRow = mysqli_fetch_array($objResults)) {
            ?>
              <li><?= $objRow[$keyword] ?></li>
            <?php
            }
            ?>
          </ul>
        <?php
        }
        break;
      default:
        ?>
        <?= $info["type"] ?>?
    <?php
        break;
    }
    ?>
  </td>
<?php
}

?>