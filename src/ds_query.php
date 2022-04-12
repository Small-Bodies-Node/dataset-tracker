<?php

include("include/include.php");


if (getenv("REMOTE_USER") != "util") {
  ensurePrivileges("edit");
}

$DATA_SET_ID = getParameter("DATA_SET_ID");
$ADS_OVERRIDE = getParameter("ADS_VOLUME");

if ($DATA_SET_ID) {
  if ($ADS_OVERRIDE) {
    $SQL = sprintf(
      "UPDATE DATASET SET ADS_VOLUME = %d WHERE DATA_SET_ID = '%s'",
      $ADS_OVERRIDE,
      $DATA_SET_ID
    );
    mysqli_query($_LINK, $SQL);
  }

  $SQL = sprintf(
    "SELECT ARCHIVE_STATUS, ADS_VOLUME, UUID, DATA_SET_NAME, CERTIFIED_FLAG, ABSTRACT, CITATION_DESC, SIZE, SUPERSEDED_DS_ID, MIGRATION_TO, DOI FROM DATASET WHERE DATA_SET_ID = '%s'",
    $DATA_SET_ID
  );
  $row = mysqli_fetch_array(mysqli_query($_LINK, $SQL));
  $UUID = $row["UUID"];
  $ARCHIVE_STATUS = $row["ARCHIVE_STATUS"];
  $DATA_SET_NAME = $row["DATA_SET_NAME"];
  $CERTIFIED_FLAG = $row["CERTIFIED_FLAG"];
  $ABSTRACT = $row["ABSTRACT"];
  $CITATION_DESC = $row["CITATION_DESC"];
  $SIZE = $row["SIZE"];
  $SUPERSEDED_DS_ID = $row["SUPERSEDED_DS_ID"];
  $MIGRATION_TO_DS = $row["MIGRATION_TO"];
  $DOI = $row["DOI"];

  # Find DATA_SET_ID for data set that supersedes this one
  $SQL = sprintf(
    "SELECT DATA_SET_ID,SUPERSEDED_DS_ID FROM DATASET WHERE SUPERSEDED_DS_ID like '%%%s%%'",
    $DATA_SET_ID
  );
  $sql_que = mysqli_query($_LINK, $SQL);
  while ($row = mysqli_fetch_assoc($sql_que)) {
    $SUPERSEDING_DS_ID .= $row["DATA_SET_ID"] . ";";
  }
  #   $row = mysqli_fetch_array( mysqli_query($_LINK, $SQL));
  #   $SUPERSEDING_DS_ID = $row["DATA_SET_ID"];

  # Find DATA_SET_ID for data set that this data set was migrated from
  $SQL = sprintf(
    "SELECT DATA_SET_ID, MIGRATION_TO FROM DATASET WHERE MIGRATION_TO like '%%%s%%'",
    $DATA_SET_ID
  );
  $sql_que = mysqli_query($_LINK, $SQL);
  while ($row = mysqli_fetch_assoc($sql_que)) {
    $MIGRATION_FROM_DS .= $row["DATA_SET_ID"] . ";";
  }

  if ($row["ADS_VOLUME"] != "") {
    $ADS_VOLUME = $row["ADS_VOLUME"];
  } else {
    $SQL = "SELECT MAX(ADS_VOLUME) AS LAST FROM DATASET";
    $row = mysqli_fetch_array(mysqli_query($_LINK, $SQL));
    $ADS_VOLUME = $row["LAST"] + 1;
    if ($ADS_VOLUME == 1) {
      $ADS_VOLUME = 8000;
    }
    if ($ADS_VOLUME > 9999) {
      $ADS_VOLUME = 0;
    } else {
      $SQL = sprintf(
        "UPDATE DATASET SET ADS_VOLUME = %d WHERE DATA_SET_ID = '%s'",
        $ADS_VOLUME,
        $DATA_SET_ID
      );
      mysqli_query($_LINK, $SQL);
    }
  }
  printf("DATA_SET_ID = %s\r\n", $DATA_SET_ID);
  printf("UUID = %s\r\n", $UUID);
  printf("ADS_VOLUME = %s\r\n", $ADS_VOLUME);
  printf("ARCHIVE_STATUS = %s\r\n", $ARCHIVE_STATUS);
  printf("DATA_SET_NAME = %s\r\n", $DATA_SET_NAME);
  printf("CERTIFIED_FLAG = %s\r\n", $CERTIFIED_FLAG);
  printf("ABSTRACT = %s\r\n", $ABSTRACT);
  printf("CITATION_DESC = %s\r\n", $CITATION_DESC);
  printf("SIZE = %s\r\n", $SIZE);
  printf("SUPERSEDED_DS_ID = %s\r\n", $SUPERSEDED_DS_ID);
  printf("SUPERSEDING_DS_ID = %s\r\n", $SUPERSEDING_DS_ID);
  printf("MIGRATION_TO_DS = %s\r\n", $MIGRATION_TO_DS);
  printf("MIGRATION_FROM_DS = %s\r\n", $MIGRATION_FROM_DS);
  printf("DOI = %s\r\n", $DOI);
}
