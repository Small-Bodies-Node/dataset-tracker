<?php
echo ("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 3.2 Final//EN\">");

?>
<html>

<head>
  <title>Field Descriptions - SBN Dataset Database</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
</head>

<body>

  <form action="?" method="post">
    <input type="submit" value="Home" />
  </form>

  <blockquote>
    <p>
      Below is a list of the fields kept track of in the Dataset Tracker,
      along with their types, descriptions, and in some cases, examples.
      You can find the Field Type descriptions on the <a href="?page=help">Help</a>
      page.
    </p>
    <p>
      As a help, on the 'Search Form' and 'Edit Dataset Info' pages, when
      you click into a field, a pop-up box will appear to the right of the
      form showing the field's description.
    </p>
  </blockquote>

  <table cellspacing="5px" cellpadding="2px">
    <tr class="searchFormGroupHeader">
      <th>Field Display Name</th>
      <th>Field Type</th>
      <th>Field Description</th>
      <th>Example</th>
    </tr>
    <?php
    rowColor(array("#F8F8F8", "#E8E8E8"));

    $displaynames_keywords = array();
    $keyword_names = array_keys($keywordInfo);
    $displaynames = array();
    foreach ($keyword_names as $key) {
      $displaynames_keywords[$keywordInfo[$key]["displayName"]] = $key;
      array_push($displaynames, $keywordInfo[$key]["displayName"]);
    }
    natcasesort($displaynames);

    foreach ($displaynames as $displayname) {
      if ($keywordInfo[$displaynames_keywords[$displayname]]["group"] != "DEBUG") {
        fieldRow($keywordInfo[$displaynames_keywords[$displayname]]);
      }
    }
    ?>
  </table>
</body>

</html>

<?
function fieldRow($keyword)
{
?>
  <tr style="background-color: <?= rowColor() ?>">
    <td nowrap="1" style="font-weight: bold"><?= $keyword["displayName"] ?></td>
    <td><?php echo convertType($keyword["type"]) ?></td>
    <td><?php echo $keyword["description"] ?></td>
    <td><?php echo (isset($keyword["example"])) ? $keyword["example"] : ''; ?></td>
  </tr>
<?
}

function convertType($type)
{
  switch ($type) {
    case "date":
      return "Date";
    case "longtext":
    case "text":
      return "Text";
    case "flag":
      return "Flag";
    case "set":
      return "List";
    case "number":
      return "Numeric";
    case "object":
      return "Object";
    default:
      return "";
  }
}

?>