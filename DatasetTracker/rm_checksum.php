<?php

include("include/include.php");

$location = getParameter("location");

mysqli_query($_LINK, "DELETE FROM CHECKSUM WHERE LOCATION = '" . mysqli_real_escape_string($_LINK, $location) . "';");
