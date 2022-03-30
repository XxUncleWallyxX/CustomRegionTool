<?php

// Define MySQL database connectivity
include "db_conn.php";


// Populate table when "View All Custom Display Profile IDs" is clicked
function getAllCustomDisplayProfileIDs(){

    global $connection;
    $query = "SELECT * FROM custom_display_profile";

    $result = mysqli_query($connection, $query);

    if (!$result){

    echo "Query has failed! Error: " . mysqli_error($connection);

    }

    while ($row = mysqli_fetch_assoc($result)){

      $custom_display_profile_id = $row['custom_display_profile_id'];
      $description = $row['description'];

      echo "<tr>";
      echo "<td>{$custom_display_profile_id}</td>";
      echo "<td>{$description}</td>";
      echo "</tr>";

}

};

// Find next candidate value for "custom_display_profile" table (primary key) to save the user some time
function getNextAvailableCustomDisplayID(){

      global $connection;
      $value = 0;
      $query = "SELECT MAX(custom_display_profile_id) FROM custom_display_profile";

      $result = mysqli_query($connection, $query);

      if (!$result){

        echo "Query has failed! Error: " . mysqli_error($connection);
      }

      $row = $result -> fetch_assoc();
      mysqli_free_result($result);
      $value = $row['MAX(custom_display_profile_id)'] + 1; // Incrememt Primary Key by 1
      return $value;
};


// Count results - currently not being called
function showCount(){

    global $connection;
    global $count;

    // $customerCode = mysqli_real_escape_string($connection, $_POST['custCode']);
    // v2 - Infer the display profile ID from the form input (form data already sanitized in this function)
    $displayProfileID = getCurrentDisplayProfileID();
    $count = 0;

    $query = "SELECT
    DISTINCT(`custom_region`.`description`)
    FROM
    custom_region
    WHERE
    `custom_region`.`custom_display_profile_id` = $displayProfileID";


    $result = mysqli_query($connection, $query);

    if(!$result){

      echo "Query Failed: " . mysqli_error($connection);

    }

    $count = mysqli_num_rows($result);
    return $count;

}

// Show the *UNIQUE* custom region values associated with a given customer code (customer code coming from form input)
// Displayed at top of page when "View / Edit Existing Custom Region" is clicked and valid customer code is provided
function showDedupedCustomRegions(){

      global $connection;
      $count = 0;

      // Capture / sanitize customer code - to be used if no results found from query
      // Otherwise, use $displayProfileID var.
      $customerCode = mysqli_real_escape_string($connection, $_POST['custCode']);

      // v2 - Infer the display profile ID from the form input (form data already sanitized in this function)
      $displayProfileID = getCurrentDisplayProfileID();

      $query = "SELECT description, custom_display_profile_id, custom_region_id FROM custom_region WHERE custom_display_profile_id = '{$displayProfileID}'";

      $result = mysqli_query($connection, $query);
      $count = mysqli_num_rows($result);

      if (!$result){

        echo "Query has failed! Error is: " . mysqli_error($connection);

      }
          // Alert the user that no results were found. Populate table with "N/A" for a single row
          else if ($count == 0){
            ?>
            <p class="highlight">
            <?php echo "NO RESULTS FOUND! USE 'CONFIGURE NEW CUSTOM REGION' BUTTON TO CREATE NEW REGIONS TIED TO " . mb_strtoupper($customerCode);
            echo "<tr>";
            echo "<td>";
            echo "Not Found!";
            echo "</td>";
            echo "<td>";
            echo "Not Found!";
            echo "</td>";
            echo "<td>";
            echo "Not Found!";
            echo "</tr>";

        } else

                // Show actual data if query returns data ($count > 0)
                while ($row = mysqli_fetch_array($result)){

                $uniqueCustomRegionName = $row['description'];
                $customDisplayProfileId = $row['custom_display_profile_id'];
                $customRegionID = $row['custom_region_id'];

                echo "<tr>";
                echo "<td>$uniqueCustomRegionName</td>";
                echo "<td>$customDisplayProfileId</td>";
                echo "<td>$customRegionID</td>";
                echo "</tr>";

      }


}


// Show the user the FULL custom region configuration / property maps based on the form input
// This is the analysis query used by Jason and Corey for analysis currently, just re-configured for a webpage / UI instead
function showCustomRegionsByPortfolio(){

      global $connection;
      $count = 0;

      // Sanitize form input - mitigate MySQL injection
      $customerCode = mysqli_real_escape_string($connection, $_POST['custCode']);


      $query = "SELECT
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
      AND `custom_property_region_map`.`property_id` LIKE '{$customerCode}%'
      ORDER BY `property`.`property_id`
      ";

      $result = mysqli_query($connection, $query);
      $count = mysqli_num_rows($result);

      if (!$result){

        echo "Query has failed! Error: " . mysqli_error($connection);
      }

      // Alert the user that no results were found. Populate table with "N/A" for a single row
      else if ($count == 0){
      ?>
      <p class="highlight">
      <?php echo "NO RESULTS FOUND! USE 'CONFIGURE NEW CUSTOM REGION' BUTTON TO CREATE NEW PROPERTIES / REGIONS TIED TO " . mb_strtoupper($customerCode);;
      echo "<tr>";
      echo "<td>";
      echo "Not Found!";
      echo "</td>";
      echo "<td>";
      echo "Not Found!";
      echo "</td>";
      echo "<td>";
      echo "Not Found!";
      echo "</td>";
      echo "<td>";
      echo "Not Found!";
      echo "</td>";
      echo "<td>";
      echo "Not Found!";
      echo "</td>";
      echo "</tr>";

      }

      // Show actual data if query returns data ($count > 0)
      while ($row = mysqli_fetch_array($result)){

        $customRegionName = $row['profileName'];
        $customDisplayProfileId = $row['custom_display_profile_id'];
        $description = $row['description'];
        $propertyID = $row['property_id'];
        $propertyName = $row['property_name'];

        echo "<tr>";
        echo "<td>{$customRegionName}</td>";
        echo "<td>{$customDisplayProfileId}</td>";
        echo "<td>{$description}</td>";
        echo "<td>{$propertyID}</td>";
        echo "<td>{$propertyName}</td>";
        echo "</tr>";

      }

}

// Determine the custom_display_profile_id associated with a 3-character customer code
// Customer code comes from form input - sanitize form input here and infer display profile ID from that
function getCurrentDisplayProfileID(){

      global $connection;
      $customerCode = mysqli_real_escape_string($connection, $_POST['custCode']);
      $displayProfileId = 0;


      $query = "SELECT DISTINCT(custom_display_profile_id)
      FROM custom_property_region_map
      WHERE `custom_property_region_map`.`property_id` like '{$customerCode}%'";

      $result = mysqli_query($connection, $query);

      if (!$result){

        echo "Query has failed! Error: " . mysqli_error($connection);

      }

      $row = $result -> fetch_assoc();
      $displayProfileId = $row['custom_display_profile_id'];

      return $displayProfileId;

}

// Determine the next available custom_region_id value (primary key from custom_region table) if a new one is being added - increment PK by one
// Saves user some time and avoids manual input errors - value provided by this function fills a read-only field
function getNextAvailableCustomRegionID(){


      global $connection;
      // $customerCode = mysqli_real_escape_string($connection, $_POST['custCode']);
      $value = 0;

      $query = "SELECT MAX(custom_region_id) FROM custom_region";

      $result = mysqli_query($connection, $query);

      if (!$result){

        echo "Query has failed! Error: " . mysqli_error($connection);

      }

      $row = $result -> fetch_assoc();
      $value = $row['MAX(custom_region_id)'] + 1;

      return $value;

}


// Populate "Property ID" drop-down menu in #newPropertyRegionAssociation Bootstrap Modal
// This will be based on form input (3-character customer code) so only appropriate properties are displayed
function getCandidateProperties(){

    global $connection;
    $customerCode = mysqli_real_escape_string($connection, $_POST['custCode']);

    $query = "SELECT property_id, property_name FROM property WHERE active = 1 AND property_id LIKE '{$customerCode}%' ORDER BY property_id";

    $result = mysqli_query($connection, $query);

    if (!$result){

        echo "Query has failed! Error: " . mysqli_error($connection);

    }

    while ($row = $result->fetch_assoc()){

        $propID = $row['property_id'];
        $propName = $row['property_name'];
        $var = $row['propName'] . "/" . $row['propID'];


        echo "<option value='{$propID}'>{$propID}</option>";
      }
}


// Populate the "Custom Region Name" drop-down menu in #newPropertyRegionAssociation Bootstrap Modal
function getCandidateRegions(){

      global $connection;

      // Form input already sanitized within getCurrentDisplayProfileID() function!
      $displayProfileID = getCurrentDisplayProfileID();
      $query = "SELECT custom_region_id, description FROM custom_region WHERE custom_display_profile_id = {$displayProfileID}";

      $result = mysqli_query($connection, $query);

      if (!$result){

          echo "Query has failed! Error: " . mysqli_error($connection);
      }

      while ($row = $result->fetch_assoc()){

          $description = $row['description'];
          $id = $row['custom_region_id'];

          echo "<option value='{$id}'>{$description}</option>";

      }

}

// Show all available custom_display_profile_id values from "custom_display_profile" table
// Will populate the drop-down menu for "Custom Display Profile ID" in the #createNewRegionFromScratch form
function populateCustomDisplayProfileDropdown(){

    global $connection;

    // Exclude the default selection of "0" ("Default region display") from custom_display_profile table
    // That value will not be needed in the #createNewRegionFromScratch form
    $query = "SELECT * FROM custom_display_profile WHERE custom_display_profile_id != 0";

    $result = mysqli_query($connection, $query);

    if (!$result){

        echo "Query has failed! Error: " . mysqli_error($connection);
    }

    while ($row = $result->fetch_assoc()){

        $customDisplayProfileId = $row['custom_display_profile_id'];
        $description = $row['description'];

        echo "<option value='{$customDisplayProfileId}'>{$description}</option>";

    }

}

// Populate "Property ID" drop-down menu in #createNewRegionFromScratch form
// This function is NOT based on form input
function getAllProperties(){


    global $connection;

    $query = "SELECT * FROM property WHERE active = 1";

    $result = mysqli_query($connection, $query);

    if (!$result){

      echo "Query has failed! Error: " . mysqli_error($connection);

    }

    while ($row = $result->fetch_assoc()){

      $propertyID = $row['property_id'];
      $propertyName = $row['property_name'];

      echo "<option value='{$propertyID}'>{$propertyName}</option>";

    }


}

