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

// Sanitize form input from the #createNewRegionFromScratch form
$displayProfileID = mysqli_real_escape_string($connection, $_POST['displayProfileID']);
$newRegionName = mysqli_real_escape_string($connection, $_POST['newRegion']);
$newRegionID = mysqli_real_escape_string($connection, $_POST['newRegionID']);
$propertyID = mysqli_real_escape_string($connection, $_POST['propID']);


// "short_code" in db is all-lowercase custom_region.description value, with no spaces! Format it as such here:
$lowercaseName = strtolower($newRegionName);
$shortName = str_replace(" ", "", $lowercaseName);

$query = "INSERT INTO custom_region (custom_region_id, custom_display_profile_id, description, short_code)
VALUES ('{$newRegionID}', '{$displayProfileID}', '{$newRegionName}', '{$shortName}')";

$query2 = "INSERT INTO custom_property_region_map (id, custom_display_profile_id, custom_region_id, property_id)
VALUES (null, '{$displayProfileID}', '{$newRegionID}', '{$propertyID}')";

// First, execute $query for custom_region table insert
$result = mysqli_query($connection, $query);

if (!$result){

  echo "Query Failed - Reason: " . mysqli_error($connection);

}
    // If $query was successful, insert into custom_property_region_map table
    $result2 = mysqli_query($connection, $query2);

    if (!$result2){

      echo "Query Failed - Reason: " . mysqli_error($connection);

    }

    echo "Form submission to both tables successful!";




$output = '';


$output .=
'
<br>
<table class="table" style="width:30%">
  <thead>
      <tr>
        <th>Custom Display Profile ID</th>
        <th>Custom Region Name</th>
        <th>Custom Region ID</th>
      </tr>
  </thead>
';

echo "Poop!";
