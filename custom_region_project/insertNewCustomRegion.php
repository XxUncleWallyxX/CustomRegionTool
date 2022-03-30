<head>
  <title>Custom Region Configuration</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">


  <!-- Not sure if this is needed -->
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script> -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <!-- use my own personal style sheet - load last -->
  <link href="css/stylesheet.css" rel="stylesheet">
</head>

<?php

include "db_conn.php";
include "functions.php";

$output = '';

$customDisplayProfileID = mysqli_real_escape_string($connection,$_POST['customDisplayProfileID']);
$newCustomRegionName = mysqli_real_escape_string($connection,$_POST['newCustomRegionDesc']);
$newCustomRegionID = mysqli_real_escape_string($connection, $_POST['newCustomRegionID']);


// "short_code" in db is all-lowercase custom_region.description value, with no spaces! Format it as such here:
$lowercaseName = strtolower($newCustomRegionName);
$shortName = str_replace(" ", "", $lowercaseName);


$query = "INSERT INTO custom_region (custom_display_profile_id, description, custom_region_id, short_code) VALUES ('{$customDisplayProfileID}','{$newCustomRegionName}', '{$newCustomRegionID}', '{$shortName}')";

$result = mysqli_query($connection, $query);


if (!$result){

  echo "Query Failed - Reason: " . mysqli_error($connection);

}



$selectQuery = "SELECT description, custom_display_profile_id, custom_region_id FROM custom_region WHERE custom_display_profile_id = $customDisplayProfileID";

$displayQuery = mysqli_query($connection, $selectQuery);

if (!$displayQuery){

  echo "Query Failed - Reason: " . mysqli_error($connection);

  }

$output .=
'
<br>
<table class="table" style="width:30%">
  <thead>
      <tr>
        <th>Custom Region Name</th>
        <th>Custom Display Profile ID</th>
        <th>Custom Region ID</th>
      </tr>
  </thead>
';

while ($row = mysqli_fetch_array($displayQuery)){

$output .=
'
<tr>
<td>' . $row['description'] . '</td>
<td>' . $row['custom_display_profile_id'] . '</td>
<td>' . $row['custom_region_id'] . '</td>
</tr>
';

}


echo $output;
