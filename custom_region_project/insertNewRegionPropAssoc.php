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

$displayProfileID = mysqli_real_escape_string($connection,$_POST['customDisplayProfileID']);
$customRegionName = mysqli_real_escape_string($connection,$_POST['custom_region_selected']);
$propertyID = mysqli_real_escape_string($connection,$_POST['property_id']);

$query = "INSERT INTO custom_property_region_map (custom_display_profile_id, custom_region_id, property_id) VALUES ($displayProfileID, '{$customRegionName}', '{$propertyID}')";

$result = mysqli_query($connection, $query);

if (!$result){

echo "Query has failed! Error: " . mysqli_error($connection);

}

$selectQuery = "SELECT
`custom_display_profile`.`description` as profileName,
`custom_region`.`custom_display_profile_id`,
`custom_region`.`description`,
`custom_property_region_map`.`property_id`,
`property`.`property_name`
FROM
`custom_display_profile`,
`custom_property_region_map`,
`custom_region`,
`property`
WHERE `custom_display_profile`.`custom_display_profile_id` = `custom_property_region_map`.`custom_display_profile_id`
AND `custom_property_region_map`.`custom_region_id` = `custom_region`.`custom_region_id`
AND `custom_property_region_map`.`property_id` = `property`.`property_id`
AND `custom_property_region_map`.`custom_display_profile_id` = $displayProfileID
ORDER BY `property`.`property_id`
";


$displayQuery = mysqli_query($connection, $selectQuery);

if (!$displayQuery){

  echo "Query Failed - Reason: " . mysqli_error($connection);

}

$output .=
'
<br>
<table class="table" style="width:70%">
  <thead>
    <tr>
      <th>Custom Region Name:</th>
      <th>Custom Display Profile ID:</th>
      <th>Custom Region Description:</th>
      <th>Property ID:</th>
      <th>Property Name:</th>
    </tr>
  </thead>
';

while ($row = mysqli_fetch_array($displayQuery)){

$output .=
'
<tr>
<td>' . $row['profileName'] . '</td>
<td>' . $row['custom_display_profile_id'] . '</td>
<td>' . $row['description'] . '</td>
<td>' . $row['property_id'] . '</td>
<td>' . $row['property_name'] . '</td>
</tr>
';


}

$output .= '<br>';
// $output .= '<input class="btn btn-primary" type="button" name="newCustomRegion" value="Add New Property / Region Association" data-toggle="modal" data-target="#newPropertyRegionAssociation">';

echo $output;


/*
<div id="specificCustomRegion">
  <br>
  <br>
  <table class="table" style="width:70%">
    <thead>
      <tr>
      <th>Custom Region Name:</th>
      <th>Custom Display Profile ID:</th>
      <th>Custom Region Description:</th>
      <th>Property ID:</th>
      <th>Property Name:</th>
      </tr>
    </thead>
    <tr>
      <?php showCustomRegionsByPortfolio(); ?>
    </tr>

  </table>
*/
