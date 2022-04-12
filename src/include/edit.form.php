<?php
ensurePrivileges("edit");

echo ("<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 3.2 Final//EN\">");

$clone = getParameter("clone");
$new = getParameter("new");


// echo "New: " . $new . " \n\n\n   ";

if ($new) {

    $uuid = 0;
    $searchUUID = 0;
    $row = array();

    /*Default values*/
    $row['ACTIVE_FLAG'] = 1;
    $row['REPORT_TO_EN'] = 1;
    $row['FERRET_STATUS'] = "Not Decided";
    $row['MIGRATED_FLAG'] = 0;
    $row['MIGRATION_STATUS'] = "Not Decided";
} else {

    $uuid = getParameter("uuid") + 0;
    $searchUUID = $uuid;
    $results = mysqli_query($_LINK, "SELECT * FROM `DATASET` WHERE `UUID`=$uuid");
    $row = mysqli_fetch_array($results);

    if ($clone) {
        $uuid = 0;
    }
}

?>
<html>

<head>
  <title>Edit Dataset Info - SBN Dataset Database</title>
  <link rel="stylesheet" type="text/css" href="style.css" />

  <!-- JAVA Script functions -->
  <script>
    function hintShow(x) {
      document.getElementById(x).style.display = "inline";
    }

    function hintHide(x) {
      document.getElementById(x).style.display = "none";
    }
  </script>
  <!--End JAVA script functions -->

</head>

<body>
  <?php
    if (isset($errors) and gettype($errors) == "array" and count($errors) > 0) {
        ?>
    <p class="error">
      There were errors in the form. Please refer to notes below.
    </p>
        <?php
    }
    if (isset($errors) and gettype($errors) != "array") {
        $errors = array();
        ?>
    <button onClick="window.history.go(-1); return false;">Cancel</button>
        <?php
    } else {
        ?>
    <form action="?page=search.form" method="post">
      <input type="submit" value="Cancel" />
    </form>
        <?php
    }
    ?>
  <form action="?action=edit&page=view&uuid=<?php echo $uuid ?>" method="post">
    <?php
    if ($uuid == 0) {
        ?>
      <input type="submit" value="Insert" />
      <input type="hidden" name="insert" value="1" />
        <?php
    } else {
        ?>
      <input type="submit" value="Save Changes" />
        <?php
    }
    ?>
    <table class="result-table">
      <?php
        rowColor(array("#F8F8F8", "#E8E8E8"));
        foreach ($viewOrder as $keyword) {
            if (!preg_match('/_DTS$/', $keyword)) {
                editValue($keyword);
            }
        }
        ?>
      <tr style="background-color: <?php echo rowColor() ?>">
        <td nowrap="1" style="font-weight: bold">Comments</td>
        <td>&nbsp;</td>
        <td>
          <table>
            <?php
            $comments = mysqli_query(
                $_LINK, "SELECT *" .
                " FROM `COMMENT`" .
                " WHERE `UUID` = " . $searchUUID .
                " ORDER BY `TIMESTAMP`;"
            );
            if (mysqli_num_rows($comments) > 0) {
                ?>
              <tr>
                <th style="font-size: 0.8em">Delete?</th>
                <th>Timestamp</th>
                <th>Flag</th>
                <th>Comment</th>
              </tr>
                <?php
            } else {
                ?>
              <tr>
                <th colspan="2">&nbsp;</th>
                <th>Flag</th>
                <th>Comment</th>
              </tr>
                <?php
            }
            ?>
            <input type="hidden" name="COMMENT-count" value="<?php echo mysqli_num_rows($comments) ?>" />
            <?php
            $i = 0;
            while ($row = mysqli_fetch_array($comments)) {
                ?>
              <tr>
                <td>
                  <input type="checkbox" name="COMMENT-delete-<?php echo $i ?>" />
                  <input type="hidden" name="COMMENT-cid-<?php echo $i ?>" value="<?php echo htmlspecialchars($row["CID"], ENT_QUOTES) ?>" />
                </td>
                <td style="font-size: 0.8em">
                  <?php echo htmlspecialchars($row["TIMESTAMP"]) ?>
                  <input type="hidden" name="COMMENT-timestamp-<?php echo $i ?>" value="<?php echo htmlspecialchars($row["TIMESTAMP"], ENT_QUOTES) ?>">
                </td>
                <td style="font-size: 0.8em">
                  <?php
                    $yes = $row["FLAG"] ? "selected='true'" : "";
                    $no = $row["FLAG"] ? "" : "selected='true'";
                    ?>
                  <select name="COMMENT-flag-<?php echo $i ?>">
                    <option value="1" <?php echo $yes ?>>Flag</option>
                    <option value="0" <?php echo $no ?>>No Flag</option>
                  </select>
                  <input type="hidden" name="COMMENT-oldFlag-<?php echo $i ?>" value="<?php echo htmlspecialchars($row["FLAG"], ENT_QUOTES) ?>" />
                </td>
                <td>
                  <textarea cols="25" rows="5" name="COMMENT-comment-<?php echo $i ?>"><?php echo htmlspecialchars($row["COMMENT"]) ?></textarea>
                  <input type="hidden" name="COMMENT-oldComment-<?php echo $i ?>" value="<?php echo htmlspecialchars($row["COMMENT"], ENT_QUOTES) ?>" />
                </td>
              </tr>
                <?php
                ++$i;
            }
            ?>
            <tr>
              <td colspan="2" style="font-size: 0.8em">New Comment:</td>
              <td>
                <select name="COMMENT-flag-insert">
                  <option value="1">Flag</option>
                  <option value="0">No Flag</option>
                </select>
              </td>
              <td>
                <textarea cols="25" rows="5" name="COMMENT-comment-insert"></textarea>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    <?php
    if ($uuid == 0) {
        ?>
      <input type="submit" value="Insert" />
      <input type="hidden" name="insert" value="1" />
        <?php
    } else {
        ?>
      <input type="submit" value="Save Changes" />
        <?php
    }
    ?>
  </form>
</body>

</html>

<?php

function editValue($keyword)
{
    global $keywordInfo, $row, $uuid, $errors, $searchUUID, $_LINK;

    $info = $keywordInfo[$keyword];

    /*   if ($keyword == "MISSION_NAME") {
      return;
    }
    */
    if ($keyword == "TARGET_TYPE") {
        return;
    }

    ?>
  <tr style="background-color: <?php echo rowColor() ?>">
    <?php
    /*     if ($keyword == "MISSION_ID") {
    ?>
        <td nowrap="1" style="font-weight: bold">Mission</td>
        <td>&nbsp;</td>
    <?php
     } else*/ if ($keyword == "TARGET_NAME") {
        ?>
      <td nowrap="1" style="font-weight: bold">Target</td>
      <td>&nbsp;</td>
        <?php
} elseif (/*$keyword != "MISSION_NAME" and */$keyword != "TARGET_TYPE") {
    ?>
      <td nowrap="1" style="font-weight: bold"><?php echo $info["displayName"] ?></td>
      <td>&nbsp;</td>
    <?php
}
?>
    <td>
      <?php
        if ($keyword == "DATA_SET_ID" and !hasPrivileges("admin")) {
            ?>
            <?php echo htmlspecialchars($row[$keyword]) ?>
        <input type="hidden" name="<?php echo $keyword ?>" value="<?php echo htmlspecialchars($row[$keyword], ENT_QUOTES) ?>" onfocus="hintShow('<?php echo $keyword ?>hint')" onblur="hintHide('<?php echo $keyword ?>hint')" />
            <?php
        } else {
            switch ($info["type"]) {
            case "date":
                $value = getParameter($keyword);
                if ($value == "" and isset($row[$keyword])) {
                    $value = $row[$keyword];
                }
                if ($value == "0000-00-00") {
                    $value = "";
                }
                ?>
            <input name="<?php echo $keyword ?>" size="30" type="text" value="<?php echo htmlspecialchars($value, ENT_QUOTES) ?>" onfocus="hintShow('<?php echo $keyword ?>hint')" onblur="hintHide('<?php echo $keyword ?>hint')" />
                <?php
                break;
            case "text":
                $value = getParameter($keyword);
                if ($value == "" and isset($row[$keyword])) {
                    $value = $row[$keyword];
                }
                ?>
            <input name="<?php echo $keyword ?>" size="80" type="text" value="<?php echo htmlspecialchars($value, ENT_QUOTES) ?>" onfocus="hintShow('<?php echo $keyword ?>hint')" onblur="hintHide('<?php echo $keyword ?>hint')" />
                <?php
                break;
            case "number":
                $value = getParameter($keyword);
                if ($value == "" and isset($row[$keyword])) {
                    $value = $row[$keyword];
                }
                ?>
            <input name="<?php echo $keyword ?>" size="30" type="text" value="<?php echo htmlspecialchars($value, ENT_QUOTES) ?>" onfocus="hintShow('<?php echo $keyword ?>hint')" onblur="hintHide('<?php echo $keyword ?>hint')" />
                <?php
                break;
            case "longtext":
                $value = getParameter($keyword);
                if ($value == "" and isset($row[$keyword])) {
                    $value = $row[$keyword];
                }
                ?>
            <textarea name="<?php echo $keyword ?>" cols="60" rows="10" onfocus="hintShow('<?php echo $keyword ?>hint')" onblur="hintHide('<?php echo $keyword ?>hint')"><?php echo htmlspecialchars($value) ?> </textarea>
                <?php
                break;
            case "set":
                if (count($info["legalValues"]) > 0) {
                    $legalValues = $info["legalValues"];
                } else {
                    $valueResults = mysqli_query(
                        $_LINK, "SELECT DISTINCT `" . $keyword . "`" .
                        " FROM `DATASET` " .
                        " ORDER BY `" . $keyword . "`"
                    );
                    $legalValues = array();
                    while ($valueRow = mysqli_fetch_array($valueResults)) {
                        array_push($legalValues, $valueRow[$keyword]);
                    }
                }
                ?>
            <select name="<?php echo $keyword ?>" onfocus="hintShow('<?php echo $keyword ?>hint')" onblur="hintHide('<?php echo $keyword ?>hint')">
                <?php
                $value = getParameter($keyword);
                if ($value == "" and isset($row[$keyword])) {
                    $value = $row[$keyword];
                }
                $found = false;
                for ($i = 0; $i < count($legalValues); ++$i) {
                    if (strtoupper($value) == strtoupper($legalValues[$i])) {
                        $selected = "selected='true'";
                        $found = true;
                    } else {
                        $selected = "";
                    }
                    ?>
                <option <?php echo $selected ?>><?php echo htmlspecialchars($legalValues[$i]) ?></option>
                    <?php
                }
                if (!$found) {
                    $selected = "selected='true'";
                } else {
                    $selected = "";
                }
                ?>
              <option <?php echo $selected ?> value="">-- Specify Manually --&gt; </option>
            </select>
                <?php
                if ($found) {
                    ?>
              <input name="<?php echo $keyword ?>-manual" type="text" onfocus="hintShow('<?php echo $keyword ?>hint')" onblur="hintHide('<?php echo $keyword ?>hint')">
                    <?php
                } else {
                    $value = getParameter($keyword . "-manual");
                    if ($value == "" and isset($row[$keyword])) {
                        $value = $row[$keyword];
                    }
                    ?>
              <input name="<?php echo $keyword ?>-manual" type="text" value="<?php echo htmlspecialchars($value, ENT_QUOTES) ?>" onfocus="hintShow('<?php echo $keyword ?>hint')" onblur="hintHide('<?php echo $keyword ?>hint')" />
                    <?php
                }
                break;
            case "flag":
                $value = getParameter($keyword);
                if ($value == "" and isset($row[$keyword])) {
                    $value = $row[$keyword];
                }
                $positive = ($value == "1") ? "selected='true'" : "";
                $negative = ($value != "1") ? "selected='true'" : "";
                ?>
            <select name="<?php echo $keyword ?>" onfocus="hintShow('<?php echo $keyword ?>hint')" onblur="hintHide('<?php echo $keyword ?>hint')">
              <option value="1" <?php echo $positive ?>><?php echo htmlspecialchars($info["positive"]) ?></option>
              <option value="0" <?php echo $negative ?>><?php echo htmlspecialchars($info["negative"]) ?></option>
            </select>
                <?php
                break;
            case "object":
                /*        if ($keyword == "MISSION_ID") {
                ?>
                <table>
                <tr>
                <th>Mission ID</th>
                <th>Mission Name</th>
                </tr>
                <?php
                   $results = mysql_query("SELECT `MISSION_ID`, `MISSION_NAME`".
                " FROM `MISSION`".
                " WHERE `UUID`=$searchUUID");
                $n = 0;
                while ($objRow = mysql_fetch_array($results)) {
                $idValue = getParameter("MISSION_ID-".$n);
                if ($idValue == "")
                $idValue = $objRow["MISSION_ID"];
                $nameValue = getParameter("MISSION_NAME-".$n);
                if ($nameValue == "")
                $nameValue = $objRow["MISSION_NAME"];
                ?>
                <tr>
                <td>
                <input type="text" name="MISSION_ID-<?=$n?>" value="<?=htmlspecialchars($idValue, ENT_QUOTES)?>" onfocus="hintShow('MISSION_IDhint')" onblur="hintHide('MISSION_IDhint')" />
                </td>
                <td>
                <input type="text" name="MISSION_NAME-<?=$n?>" value="<?=htmlspecialchars($nameValue, ENT_QUOTES)?>" onfocus="hintShow('MISSION_NAMEhint')" onblur="hintHide('MISSION_NAMEhint')" />
                </td>
                </tr>
                <?php
                ++$n;
                }
                $last = $n - 1;
                $max = getParameter("MISSION-count");
                while ($n < $max) {
                $idValue = getParameter("MISSION_ID-".$n);
                $nameValue = getParameter("MISSION_NAME-".$n);
                if ($idValue != "" or $nameValue != "")
                $last = $n;
                ?>
                <tr>
                <td>
                <input type="text" name="MISSION_ID-<?=$n?>" value="<?=htmlspecialchars($idValue, ENT_QUOTES)?>" onfocus="hintShow('MISSION_IDhint')" onblur="hintHide('MISSION_IDhint')" />
                </td>
                <td>
                <input type="text" name="MISSION_NAME-<?=$n?>" value="<?=htmlspecialchars($nameValue, ENT_QUOTES)?>" onfocus="hintShow('MISSION_NAMEhint')" onblur="hintHide('MISSION_NAMEhint')" />
                </td>
                </tr>
                <?php
                ++$n;
                }
                $max = max($last + 5, 10);
                while ($n < $max) {
                ?>
                <tr>
                <td>
                <input type="text" name="MISSION_ID-<?=$n?>" onfocus="hintShow('MISSION_IDhint')" onblur="hintHide('MISSION_IDhint')" />
                </td>
                <td>
                <input type="text" name="MISSION_NAME-<?=$n?>" onfocus="hintShow('MISSION_NAMEhint')" onblur="hintHide('MISSION_NAMEhint')" />
                </td>
                </tr>
                <?php
                ++$n;
                }
                ?>
                </table>
                <input type="hidden" name="MISSION-count" value="<?=$max?>" />
                <?php
                } else*/
                if ($keyword == "TARGET_NAME") {
                    ?>
              <table>
                <tr>
                  <th>Target Name</th>
                  <th>Target Type</th>
                </tr>
                    <?php
                    $results = mysqli_query(
                        $_LINK, "SELECT `TARGET_NAME`, `TARGET_TYPE`" .
                        " FROM `TARGET`" .
                        " WHERE `UUID`=$searchUUID"
                    );
                    $n = 0;
                    while ($objRow = mysqli_fetch_array($results)) {
                        $nameValue = getParameter("TARGET_NAME-" . $n);
                        if ($nameValue == "") {
                            $nameValue = $objRow["TARGET_NAME"];
                        }
                        $typeValue = getParameter("TARGET_TYPE-" . $n);
                        if ($typeValue == "") {
                            $typeValue = $objRow["TARGET_TYPE"];
                        }
                        ?>
                  <tr>
                    <td>
                      <input type="text" name="TARGET_NAME-<?php echo $n ?>" value="<?php echo htmlspecialchars($nameValue, ENT_QUOTES) ?>" onfocus="hintShow('TARGET_NAMEhint')" onblur="hintHide('TARGET_NAMEhint')" />
                    </td>
                    <td>
                      <input type="text" name="TARGET_TYPE-<?php echo $n ?>" value="<?php echo htmlspecialchars($typeValue, ENT_QUOTES) ?>" onfocus="hintShow('TARGET_TYPEhint')" onblur="hintHide('TARGET_TYPEhint')" />
                    </td>
                  </tr>
                        <?php
                        ++$n;
                    }
                    $last = $n - 1;
                    $max = getParameter("TARGET-count");
                    while ($n < $max) {
                        $nameValue = getParameter("TARGET_NAME-" . $n);
                        $typeValue = getParameter("TARGET_TYPE-" . $n);
                        if ($nameValue != "" or $typeValue != "") {
                            $last = $n;
                        }
                        ?>
                  <tr>
                    <td>
                      <input type="text" name="TARGET_NAME-<?php echo $n ?>" value="<?php echo htmlspecialchars($nameValue, ENT_QUOTES) ?>" onfocus="hintShow('TARGET_NAMEhint')" onblur="hintHide('TARGET_NAMEhint')" />
                    </td>
                    <td>
                      <input type="text" name="TARGET_TYPE-<?php echo $n ?>" value="<?php echo htmlspecialchars($typeValue, ENT_QUOTES) ?>" onfocus="hintShow('TARGET_TYPEhint')" onblur="hintHide('TARGET_TYPEhint')" />
                    </td>
                  </tr>
                        <?php
                        ++$n;
                    }
                    $max = max($last + 5, 10);
                    while ($n < $max) {
                        ?>
                  <tr>
                    <td>
                      <input type="text" name="TARGET_NAME-<?php echo $n ?>" onfocus="hintShow('TARGET_NAMEhint')" onblur="hintHide('TARGET_NAMEhint')" />
                    </td>
                    <td>
                      <input type="text" name="TARGET_TYPE-<?php echo $n ?>" onfocus="hintShow('TARGET_TYPEhint')" onblur="hintHide('TARGET_TYPEhint')" />
                    </td>
                  </tr>
                        <?php
                        ++$n;
                    }
                    ?>
              </table>
              <input type="hidden" name="TARGET-count" value="<?php echo $max ?>" />
                    <?php
                } else if ($keyword != "MISSION_NAME" or $keyword != "TARGET_TYPE") {
                    $results = mysqli_query(
                        $_LINK, "SELECT `" . $keyword . "`" .
                        " FROM `" . $info["dbTable"] . "`" .
                        " WHERE `UUID`=$searchUUID"
                    );
                    $n = 0;
                    while ($objRow = mysqli_fetch_array($results)) {
                        $value = getParameter($keyword . "-" . $n);
                        if ($value == "") {
                            $value = $objRow[$keyword];
                        }
                        ?>
                <input type="text" name="<?php echo $keyword ?>-<?php echo $n ?>" value="<?php echo htmlspecialchars($value, ENT_QUOTES) ?>" onfocus="hintShow('<?php echo $keyword ?>hint')" onblur="hintHide('<?php echo $keyword ?>hint')" /><br />
                        <?php
                        ++$n;
                    }
                    $last = $n - 1;
                    $max = getParameter($keyword . "-count");
                    while ($n < $max) {
                        $value = getParameter($keyword . "-" . $n);
                        if ($value != "") {
                            $last = $n;
                        }
                        ?>
                <input type="text" name="<?php echo $keyword ?>-<?php echo $n ?>" value="<?php echo htmlspecialchars($value, ENT_QUOTES) ?>" onfocus="hintShow('<?php echo $keyword ?>hint')" onblur="hintHide('<?php echo $keyword ?>hint')" /><br />
                        <?php
                        ++$n;
                    }
                    $max = max($last + 5, 10);
                    while ($n < $max) {
                        ?>
                <input type="text" name="<?php echo $keyword ?>-<?php echo $n ?>" onfocus="hintShow('<?php echo $keyword ?>hint')" onblur="hintHide('<?php echo $keyword ?>hint')" /><br />
                        <?php
                        ++$n;
                    }
                    ?>
              <input type="hidden" name="<?php echo $keyword ?>-count" value="<?php echo $max ?>" onfocus="hintShow('<?php echo $keyword ?>hint')" onblur="hintHide('<?php echo $keyword ?>hint')" />
                    <?php
                }
                break;
            default:
                ?>
                <?php echo $info["type"] ?>?
                <?php
                break;
            }
        }
        ?>
    </td>
    <td style="vertical-align: top">
      <?php
        if ($errors[$keyword] != "") {
            ?>
        <p class="error"><?php echo $errors[$keyword] ?></p>
            <?php
            if ($info["example"] != "") {
                ?>
          <p>Examples: <?php echo $info["example"] ?></p>
                <?php
            } else if ($info["type"] == "date") {
                ?>
          <p>Examples: 1999-01-01, 2008-02-29</p>
                <?php
            }
        }

        if ($keyword == "MISSION_ID") {
            ?>
        <span class="hint" id="MISSION_NAMEhint"><?php echo $keywordInfo["MISSION_NAME"]["description"] ?><span class="hint-pointer">&nbsp;</span></span>
            <?php
        } elseif ($keyword == "TARGET_NAME") {
            ?>
        <span class="hint" id="TARGET_TYPEhint"><?php echo $keywordInfo["TARGET_TYPE"]["description"] ?><span class="hint-pointer">&nbsp;</span></span>
            <?php
        }
        ?>
      <span class="hint" id="<?php echo $keyword ?>hint"><?php echo $info["description"] ?><span class="hint-pointer">&nbsp;</span></span>
    </td>
  </tr>
    <?php
}

?>