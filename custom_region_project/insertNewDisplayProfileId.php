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
$newID = mysqli_real_escape_string($connection, $_POST['newId']);
$newDesc = mysqli_real_escape_string($connection, $_POST['newDesc']);

$query = "INSERT INTO custom_display_profile(custom_display_profile_id, description) VALUES ($newID, '{$newDesc}')";

$result = mysqli_query($connection, $query);

if(!$result){

  echo "Query Failed! Reason: " . mysqli_error($connection);
}

$select_query = "SELECT * FROM custom_display_profile";

$selectResult = mysqli_query($connection, $select_query);

if (!$selectResult){

  echo "Query Failed - Reason: " . mysqli_error($connection);
}
$output .=
'
<br>
<table class="table" style:"width:70%">
<thead>
  <tr>
  <th>Custom Display Profile ID</th>
  <th>Description</th>
  </tr>
</thead>
';

while ($row = mysqli_fetch_array($selectResult)){

$output .=
'
<tr>
<td>' . $row['custom_display_profile_id'] . '</td>
<td>' . $row['description'] . '</td>
</tr>
';
}
echo $output;

?>
