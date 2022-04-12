<?php

ensurePrivileges("edit");

$uuid = getParameter("uuid") + 0;

$insert = getParameter("insert");

/* Error-check form values */

$errors = array();
foreach ($viewOrder as $keyword) {
    checkValues($keyword);
}

if (getParameter("DATA_SET_ID") == "") {

    $errors["DATA_SET_ID"] = "Dataset ID must not be blank";
}

if (getParameter("cancel")) {

    $overridePage = "home";
} else {

    if ($insert) {

        $navigation = "insert";

        $DATA_SET_ID = mysqli_real_escape_string($_LINK, getParameter("DATA_SET_ID"));
        $results = mysqli_query(
            $_LINK, "SELECT *" .
            " FROM DATASET" .
            " WHERE DATA_SET_ID = '$DATA_SET_ID';"
        );
        if (mysqli_num_rows($results) != 0) {
            $errors["DATA_SET_ID"] = "Dataset ID already in database.";
        }
    } else {

        $navigation = "edit";

        $DATA_SET_ID = mysqli_real_escape_string($_LINK, getParameter("DATA_SET_ID"));
        $results = mysqli_query(
            $_LINK, "SELECT *" .
            " FROM DATASET" .
            " WHERE DATA_SET_ID = '$DATA_SET_ID'" .
            "   AND UUID != " . $uuid
        );

        if (mysqli_num_rows($results) != 0) {
            $errors["DATA_SET_ID"] = "Dataset ID already in database.";
        }
    }

    if (count($errors) > 0) {

        /* There were errors, go back to edit page */

        $overridePage = "edit";
    } else {

        /* No errors, proceed with edits */

        if ($insert) {

            ensurePrivileges("admin");

            $keywords = array();
            $values = array();

            foreach (array_filter($viewOrder, "isNotObject") as $keyword) {
                if (!preg_match('/_DTS$/', $keyword)) {
                    insertValue($keyword);
                }
            }

            $insertSQL = ("INSERT INTO DATASET" .
            "(" . join(",", $keywords) . ")" .
            "VALUES" .
            "(" . join(",", $values) . ");");
            print $insertSQL;

            mysqli_query($_LINK, $insertSQL) or die(mysqli_error($_LINK));

            $uuid = mysqli_insert_id($_LINK);

            if ($uuid == 0) {

                  $errors["DATA_SET_ID"] = "There was a problem creating the data set. Please contact the developer.";
            } else {

                mysqli_query(
                    $_LINK, "UPDATE DATASET" .
                    " SET CREATE_DTS = now()" .
                    " WHERE UUID = " . ($uuid + 0)
                );

                overwriteParameter("uuid", $uuid);


                foreach (array_filter($viewOrder, "isObject") as $keyword) {
                    insertObject($uuid, $keyword);
                }

                $commentCount = getParameter("COMMENT-count");

                for ($i = 0; $i < $commentCount; ++$i) {

                    $timestamp = getParameter("COMMENT-timestamp-" . $i);
                    $flag = getParameter("COMMENT-flag-" . $i) ? "1" : "0";
                    $comment = getParameter("COMMENT-comment-" . $i);

                    if (getParameter("COMMENT-delete-" . $i) != "on") {

                        $oldFlag = getParameter("COMMENT-oldFlag-" . $i) ? "1" : "0";
                        $oldComment = getParameter("COMMENT-oldComment-" . $i);

                        if ($flag != $oldFlag or $comment != $oldComment) {

                                /* Chnages were made, don't preserve the timestamp */

                                mysqli_query(
                                    $_LINK, sprintf(
                                        "INSERT INTO `COMMENT`" .
                                        "(`UUID`, `FLAG`, `COMMENT`)" .
                                        "VALUES (%s, %s, '%s')",
                                        $uuid,
                                        $flag,
                                        mysqli_real_escape_string($_LINK, $comment)
                                    )
                                );
                        } else {

                              /* This comment is identical; preserve the timestamp */

                            mysqli_query(
                                $_LINK, sprintf(
                                    "INSERT INTO `COMMENT`" .
                                    "(`UUID`, `TIMESTAMP`, `FLAG`, `COMMENT`)" .
                                    "VALUES (%s, '%s', %s, '%s')",
                                    $uuid,
                                    mysqli_real_escape_string(
                                        $_LINK,
                                        ($timestamp),
                                        $flag,
                                        mysqli_real_escape_string($_LINK, $comment)
                                    )
                                )
                            );
                        }
                    }
                }


                $newComment = getParameter("COMMENT-comment-insert");
                if ($newComment != "") {
                    $flag = getParameter("COMMENT-flag-insert") ? "1" : "0";
                    mysqli_query(
                        $_LINK, sprintf(
                            "INSERT INTO `COMMENT` " .
                            "(`UUID`, `FLAG`, `COMMENT`)" .
                            "VALUES (%s, %s, '%s');",
                            $uuid,
                            $flag,
                            mysqli_real_escape_string($_LINK, $newComment)
                        )
                    );
                }

                $url = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
                $url = preg_replace("/\?.*/", "", $url);

                header('Location: ' . $url . '?page=view&nav=insert&uuid=' . $uuid);
            }
        } else {

            $updateTerms = array();

            foreach ($viewOrder as $keyword) {
                if (!preg_match('/_DTS$/', $keyword)) {
                    updateValue($keyword);
                }
            }

            mysqli_query(
                $_LINK, "UPDATE DATASET SET" .
                join(",", $updateTerms) .
                " WHERE UUID = $uuid"
            );

            $commentCount = getParameter("COMMENT-count");

            for ($i = 0; $i < $commentCount; ++$i) {

                $cid = getParameter("COMMENT-cid-" . $i);
                $cid += 0;
                $delete = getParameter("COMMENT-delete-" . $i) == "on";
                $timestamp = getParameter("COMMENT-timestamp-" . $i);
                $flag = getParameter("COMMENT-flag-" . $i) ? "1" : "0";
                $oldFlag = getParameter("COMMENT-oldFlag-" . $i) ? "1" : "0";
                $comment = getParameter("COMMENT-comment-" . $i);
                $oldComment = getParameter("COMMENT-oldComment-" . $i);

                if ($delete) {
                    mysqli_query($_LINK, "DELETE FROM `COMMENT` WHERE `CID` = " . $cid);
                } else if ($flag != $oldFlag or $comment != $oldComment) {
                    mysqli_query(
                        $_LINK, sprintf(
                            "UPDATE `COMMENT`" .
                            " SET `FLAG` = %s," .
                            "     `COMMENT` = '%s'" .
                            " WHERE CID = %s",
                            $flag,
                            mysqli_real_escape_string(
                                $_LINK,
                                $comment,
                                $cid
                            )
                        )
                    );
                }
            }

            $comment = getParameter("COMMENT-comment-insert");
            if ($comment != "") {
                $flag = getParameter("COMMENT-flag-insert") ? "1" : "0";
                mysqli_query(
                    $_LINK, sprintf(
                        "INSERT INTO `COMMENT` " .
                        "(`UUID`, `FLAG`, `COMMENT`)" .
                        "VALUES (%s, %s, '%s');",
                        $uuid,
                        $flag,
                        mysqli_real_escape_string(
                            $_LINK,
                            $comment
                        )
                    )
                );
            }
        }
    }
}

function checkValues($keyword)
{

    global $keywordInfo, $errors;

    switch ($keywordInfo[$keyword]["type"]) {
    case "date":
        $value = getParameter($keyword);
        $datePattern = '/^\s*(\d{4}).(\d{1,2}).(\d{1,2})\s*$/';
        if ($value != "") {
            if (!preg_match($datePattern, $value, $matches)) {
                $errors[$keyword] = "Invalid date format.";
            } else {
                if (preg_match('/^0+$/', $matches[3])) {
                    $matches[3] = 1;
                }
                if (!checkdate($matches[2], $matches[3], $matches[1])) {
                    $errors[$keyword] = "Invalid date format.";
                }
            }
        }
        break;
    case "number":
        $value = getParameter($keyword);
        if ($value != "") {
            if ($keywordInfo[$keyword]["units"] == "storage") {
                if (unscaled($value) == null) {
                    $errors[$keyword] = "Invalid number format.";
                }
            } else {
                if (!is_numeric($value)) {
                    $errors[$keyword] = "Invalid number format.";
                }
            }
        }
        break;
    }
}

function insertValue($keyword)
{
    global $keywordInfo, $keywords, $values, $_LINK;

    $info = $keywordInfo[$keyword];

    array_push($keywords, $keyword);
    switch ($info["type"]) {
    case "longtext":
    case "text":
    case "date":
        $value = getParameter($keyword);
        if ($value == "" or $value == null) {
            $value = "NULL";
        } else {
            $value = "'" . mysqli_real_escape_string($_LINK, $value) . "'";
        }
        break;
    case "set":
        $value = getParameter($keyword);
        if ($value == "") {
            $value = getParameter($keyword . "-manual");
        }
        $value = "'" . mysqli_real_escape_string($_LINK, $value) . "'";
        break;
    case "number":
        $value = getParameter($keyword);
        if ($info["units"] == "storage") {
            $value = unscaled($value);
        }
        $value += 0;
        break;
    case "flag":
        $value = getParameter($keyword) + 0;
        break;
    }
    array_push($values, $value);
}

function insertObject($uuid, $keyword)
{
    global $keywordInfo, $_LINK;

    // if ($keyword == "MISSION_NAME")
    // return;

    /* Skip TARGET_TYPE: it's handled with TARGET_NAME */

    if ($keyword == "TARGET_TYPE") {
        return;
    }

    $info = $keywordInfo[$keyword];

    // if ($keyword == "MISSION_ID") {
    // $count = getParameter("MISSION-count");
    // for ($n = 0; $n < $count; ++$n) {
    // $idValue = getParameter("MISSION_ID-".$n);
    // $nameValue = getParameter("MISSION_NAME-".$n);
    // if ($idValue != "" or $nameValue != "") {
    // mysql_query (sprintf ("INSERT INTO MISSION".
    // "(UUID, MISSION_ID, MISSION_NAME)".
    // "VALUES (%s, '%s', '%s')",
    // $uuid,
    // mysqli_real_escape_string($_LINK, $idValue),
    // mysqli_real_escape_string($_LINK, $nameValue)));
    // }
    // }
    // }
    if ($keyword == "TARGET_NAME") {
        $count = getParameter("TARGET-count");
        for ($n = 0; $n < $count; ++$n) {
            $nameValue = getParameter("TARGET_NAME-" . $n);
            $typeValue = getParameter("TARGET_TYPE-" . $n);
            if ($nameValue != "" or $typeValue != "") {
                mysqli_query(
                    $_LINK, sprintf(
                        "INSERT INTO TARGET" .
                        "(UUID, TARGET_NAME, TARGET_TYPE)" .
                        "VALUES (%s, '%s', '%s')",
                        $uuid,
                        mysqli_real_escape_string($_LINK, $nameValue),
                        mysqli_real_escape_string($_LINK, $typeValue)
                    )
                );
            }
        }
    } // end if "TARGET_NAME"
    else {
        $count = getParameter($keyword . "-count");
        for ($n = 0; $n < $count; ++$n) {
            $value = getParameter($keyword . "-" . $n);
            if ($value != "") {
                mysqli_query(
                    $_LINK, sprintf(
                        "INSERT INTO " . $info["dbTable"] .
                        "(UUID, " . $keyword . ")" .
                        "VALUES (%s, '%s')",
                        $uuid,
                        mysqli_real_escape_string($_LINK, $value)
                    )
                );
            }
        }
    } // end else
}

function updateValue($keyword)
{
    global $updateTerms, $keywordInfo, $uuid, $_LINK;

    // /* Skip MISSION_NAME: it's handled with MISSION_ID */
    //
    // if ($keyword == "MISSION_NAME")
    // return;

    /* Skip TARGET_TYPE: it's handled with TARGET_NAME */

    if ($keyword == "TARGET_TYPE") {
        return;
    }


    $info = $keywordInfo[$keyword];

    switch ($info["type"]) {
    case "longtext":
    case "text":
    case "date":
        $value = getParameter($keyword);
        if ($value == "") {
            array_push($updateTerms, "`$keyword` = NULL");
        } else {
            array_push(
                $updateTerms,
                sprintf(
                    "`$keyword` = '%s'",
                    mysqli_real_escape_string($_LINK, $value)
                )
            );
        }
        break;
    case "set":
        $value = getParameter($keyword);
        if ($value == "") {
            $value = getParameter($keyword . "-manual");
        }
        array_push(
            $updateTerms,
            sprintf(
                "`$keyword` = '%s'",
                mysqli_real_escape_string($_LINK, $value)
            )
        );
        break;
    case "number":
        $value = getParameter($keyword);
        if ($info["units"] == "storage") {
            $value = unscaled($value);
        }
        $value = $value + 0;
        array_push($updateTerms, "`$keyword` = $value");
        break;
    case "flag":
        $value = getParameter($keyword);
        $value = $value + 0;
        array_push($updateTerms, "`$keyword` = $value");
        break;
    case "object":
        /*            if ($keyword == "MISSION_ID")
        {
        mysqli_query($_LINK, "DELETE FROM MISSION WHERE UUID=$uuid");
        $count = getParameter("MISSION-count");
        for ($n = 0; $n < $count; ++$n) {
        $idValue = getParameter("MISSION_ID-".$n);
        $nameValue = getParameter("MISSION_NAME-".$n);
        if ($idValue != "" or $nameValue != "") {
        mysql_query (sprintf("INSERT INTO MISSION".
        "(UUID, MISSION_ID, MISSION_NAME)".
        "VALUES (%s, '%s', '%s')",
        $uuid,
        mysqli_real_escape_string($_LINK, $idValue),
        mysqli_real_escape_string($_LINK, $nameValue)));
        }
        }
        }
        */
        if ($keyword == "TARGET_NAME") {
            mysqli_query($_LINK, "DELETE FROM TARGET WHERE UUID=$uuid");
            $count = getParameter("TARGET-count");
            for ($n = 0; $n < $count; ++$n) {
                $nameValue = getParameter("TARGET_NAME-" . $n);
                $typeValue = getParameter("TARGET_TYPE-" . $n);
                if ($nameValue != "" or $typeValue != "") {
                    mysqli_query(
                        $_LINK, sprintf(
                            "INSERT INTO TARGET" .
                            "(UUID, TARGET_NAME,TARGET_TYPE)" .
                            "VALUES (%s, '%s', '%s')",
                            $uuid,
                            mysqli_real_escape_string($_LINK, $nameValue),
                            mysqli_real_escape_string($_LINK, $typeValue)
                        )
                    );
                }
            }
        } else {
            mysqli_query($_LINK, "DELETE FROM " . $info["dbTable"] . " WHERE UUID=$uuid");
            $count = getParameter($keyword . "-count");
            for ($n = 0; $n < $count; ++$n) {
                $value = getParameter($keyword . "-" . $n);
                if ($value != "") {
                    mysqli_query(
                        $_LINK, sprintf(
                            "INSERT INTO " . $info["dbTable"] .
                            "(UUID, " . $keyword . ")" .
                            "VALUES (%s, '%s')",
                            $uuid,
                            mysqli_real_escape_string($_LINK, $value)
                        )
                    );
                }
            }
        }
        break;
    }
} // end function updateValue()
