<?php

require "include/include.php";
$query = mysqli_query(
    $_LINK, "SELECT ".
    "DATA_SET_ID,".
    "SUPERSEDED_DS_ID,".
    "MIGRATION_TO,".
    "MIGRATED_FLAG,".
    "DOI,".
    "SIZE,".
    "LOCATION,".
    "DATA_SET_TYPE,".
    "ARCHIVE_STATUS,".
    "NSSDC_UPLOAD_DATE,".
    "NSSDC_ACCEPTED_DATE,".
    "NSSDC_ID,".
    "SIP_ID ".
    "FROM DATASET ".
    "WHERE ARCHIVE_STATUS in ('ARCHIVED','LOCALLY ARCHIVED','SUPERSEDED','SAFED') ".
    "AND SUBNODE_ID='SBN-UMD' ".
    "AND RELEASE_DATE >= '1900-01-01' ".
    "ORDER BY DATASET.DATA_SET_ID ASC"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    .main-table{
      display: grid;
      grid-template-columns: repeat(13, 1fr);
      width: 90vw;
      /* grid-gap: 5px; */
    }
    .main-table span {
      padding: 5px;
      border: rgba(0,0,0,.3) 1px solid;
    }

    .main-table span:nth-child(-n+13) {
      color: red;
      border: black 1px solid;
    }
  </style>
  <title>Document</title>
</head>
<body>
  <h1>NSSDCA UMD</h1>
  <div class="main-table">

  <?php

    echo '<span>'.'DATA_SET_ID'.'</span>';
    echo '<span>'.'SUPERSEDED_DS_ID'.'</span>';
    echo '<span>'.'MIGRATION_TO'.'</span>';
    echo '<span>'.'MIGRATED_FLAG'.'</span>';
    echo '<span>'.'DOI'.'</span>';
    echo '<span>'.'SIZE'.'</span>';
    echo '<span>'.'LOCATION'.'</span>';
    echo '<span>'.'DATA_SET_TYPE'.'</span>';
    echo '<span>'.'ARCHIVE_STATUS'.'</span>';
    echo '<span>'.'NSSDC_UPLOAD_DATE'.'</span>';
    echo '<span>'.'NSSDC_ACCEPTED_DATE'.'</span>';
    echo '<span>'.'NSSDC_ID'.'</span>';
    echo '<span>'.'SIP_ID'.'</span>';

    //

    while ($row = ($query->fetch_assoc())) {
        echo "".
        "<span>".$row["DATA_SET_ID"]."</span>".
        "<span>".$row["SUPERSEDED_DS_ID"]."</span>".
        "<span>".$row["MIGRATION_TO"]."</span>".
        "<span>".$row["MIGRATED_FLAG"]."</span>".
        "<span>".$row["DOI"]."</span>".
        "<span>".$row["SIZE"]."</span>".
        "<span>".$row["LOCATION"]."</span>".
        "<span>".$row["DATA_SET_TYPE"]."</span>".
        "<span>".$row["ARCHIVE_STATUS"]."</span>".
        "<span>".$row["NSSDC_UPLOAD_DATE"]."</span>".
        "<span>".$row["NSSDC_ACCEPTED_DATE"]."</span>".
        "<span>".$row["NSSDC_ID"]."</span>".
        "<span>".$row["SIP_ID"]."</span>" ;
    }

    ?>
  </div>

</body>
</html>

