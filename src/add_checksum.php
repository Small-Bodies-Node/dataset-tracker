<?php

include("include/include.php");

$location = getParameter("location");

mysqli_query($_LINK, "INSERT INTO CHECKSUM (LOCATION) VALUES ('" . mysqli_real_escape_string($_LINK, $location) . "');") or die(mysqli_error($_LINK));
