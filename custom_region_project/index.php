<!DOCTYPE html>
<html lang="en">
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

<?php include "functions.php" ?>
<body>

  <center>

  <h3>ILM Custom Region Configuration (for Lease Analytics ONLY)</h3>
      <h4>What do you need to do?</h4>

      <div id="defaultButtons">
      <form action="" method="post">
        <input class="button" type="submit" name="viewAll" value="View All Custom Display Profile IDs">

        <!-- <button type="button" class="buttonAdd" data-toggle="modal" data-target="#newProfileIDModal">Test text here!</button> -->
        <input class="buttonNew" type="submit" name="viewSpecificCustomRegion" value="View / Edit Existing Custom Region">
        <input class="buttonView" type="submit" name="createNewCustomRegion" value="Configure New Custom Region">
      </div>
      </form>

      <?php
      if(isset($_POST["viewAll"])){

      ?>
      <br>
      You are viewing ALL custom region Display Profile IDs for all ILM properties
        <div id="allCustomRegions">
          <br>
          <br>
          <table class="table" style="width:70%">
            <thead>
              <tr>
              <th>Custom Display Profile ID</th>
              <th>Description</th>
              </tr>
            </thead>
            <tr>
              <?php getAllCustomDisplayProfileIDs(); ?>
            </tr>

          </table>

          <input class="btn btn-primary" type="button" name="createNewID" value="New Custom Display Profile ID" data-toggle="modal" data-target="#newProfileIDModal">

        </div>



  <?php } ?>

        <!-- Modal to add *brand new* custom_display_profile_id-->
      <div class="modal fade" id="newProfileIDModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <!-- <h4 class="modal-title" id="myModalLabel">Modal title</h4> -->
            </div>
              <div class="modal-body">
              <form method="post" id="insert_new_display_id">
              <table class="table">
                  <thead>
                    <tr>
                      <th>Custom Display Profile ID</th>
                      <th>Description</th>
                    </tr>
                    <tr>
                      <td><input class="form-control" id="newId" name="newId"type="text"  readonly="true" value="<?php echo getNextAvailableCustomDisplayID(); ?>">  </td>
                      <td><input class="form-control" id="newDesc" name="newDesc" type="text" placeholder="Enter Description"></td>
                    </tr>
                  </thead>
              </table>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
            </form>
          </div>
        </div>
      </div>

        <?php

        if(isset($_POST['viewSpecificCustomRegion'])){

        ?>

        <br>
        <form name='custCodeForm' action="index.php" method="post">
        <label for "customerCode">Enter 3-letter customer code here:</label>
        <br>
        <input type="text" id="custCode" name="custCode" minlength="3" maxlength="3"></input>
        <br>
        <br>
        <input id="insert" onclick="return validateCustCodeFormInput();" type="submit" class="btn btn-primary" name="viewThisRegion"></input>
        </form>

        <?php

          }

        if(isset($_POST['viewThisRegion'])){


              echo "<br>";
              echo "Use this page if your custom region config <u>already</u> exists and has property associations";
              echo "<br>";
              echo "<br>";
              echo "The <u><b>unique</b></u> custom regions associated with " . mb_strtoupper($_POST['custCode']) . " are:";
              echo "<br>";
              echo "<br>";

              ?>

              <input class="btn btn-primary" type="button" name="newCustomRegion" value="Add a New Custom Region" data-toggle="modal" data-target="#newCustomRegionModal">
              <br>
              <br>
              <div id="deDupedCustomRegions">

                <table class="table" style="width:30%">
                  <thead>
                  <tr>
                    <th>Custom Region Name</th>
                    <th>Custom Display Profile ID</th>
                    <th>Custom Region ID</th>
                  </tr>
                  </thead>
                  <tr>
                      <?php showDedupedCustomRegions() ?>
                  </tr>
                </table>



              </div>


          <!-- Modal to add *brand new* Custom Region -->
        <div class="modal fade" id="newCustomRegionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                 <!-- <h4 class="modal-title" id="myModalLabel">Add New Custom Region</h4> -->
              </div>
                <div class="modal-body">
                <p>Create a new custom region here. You will then need to associate it with a given property / properties</p>
                <form method="post" id="insert_new_custom_region">
                <table class="table">
                    <thead>
                      <tr>
                        <th>Custom Display Profile ID</th>
                        <th>Custom Region Name</th>
                        <th>Custom Region ID</th>
                      </tr>
                      <tr>
                        <td><input class="form-control" readonly="true" id="customDisplayProfileID" name="customDisplayProfileID" type="text" value="<?php echo getCurrentDisplayProfileID(); ?>"</td>
                        <td><input class="form-control" id="newCustomRegionDesc" name="newCustomRegionDesc" type="text" placeholder="Enter Description"></td>
                        <td><input class="form-control" readonly="true" id="newCustomRegionID" name="newCustomRegionID" type="text" value="<?php echo getNextAvailableCustomRegionID(); ?>"></td>
                      </tr>
                    </thead>
                </table>

              </div>

              <div class="modal-footer">

                <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
              </div>
              </form>
            </div>
          </div>
        </div>

          <?php

          echo "<br>";
          echo "<br>";

          echo "Aggregated custom region configuration for " . mb_strtoupper($_POST['custCode']) . " is below:";
          echo "<br>";
          ?>


          <br>
          <input class="btn btn-primary" type="button" name="newCustomRegion" value="Add New Property / Region Association" data-toggle="modal" data-target="#newPropertyRegionAssociation">
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



          </div>

          <!-- Modal to add *brand new* associations of property IDs to a given custom region -->
      <div class="modal fade" id="newPropertyRegionAssociation" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                 <!-- <h4 class="modal-title" id="myModalLabel">Add New Custom Region</h4> -->
              </div>
                <div class="modal-body">

                <form method="post" id="insert_new_property_region_assoc">
                <table class="table">
                    <thead>
                      <tr>
                        <th>Custom Display Profile ID</th>
                        <th>Custom Region Name</th>
                        <th>Property ID</th>
                      </tr>
                      <tr>
                        <td><input class="form-control" readonly="true" id="customDisplayProfileID" name="customDisplayProfileID" type="text" value="<?php echo getCurrentDisplayProfileID(); ?>"</td>
                        <td>
                          <select name="custom_region_selected" id="customRegionSelected" class="form-control" method="post">
                            <?php getCandidateRegions(); ?>
                          </select>
                        </td>
                        <td>
                          <select name="property_id" id="propertyID" class="form-control" method="post">
                            <?php
                            getCandidateProperties(); ?>
                          </select>
                        </td>
                      </tr>
                    </thead>
                </table>

              </div>
              <div class="modal-footer">
              <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
            </form>
          </div>
        </div>
      </div>

        <?php }

        if(isset($_POST['createNewCustomRegion'])){

          echo "<br>";
          echo "Use this page if your custom region config <u>does not</u> exist and has no property associations yet";
          echo "<br>";
          echo "<br>";

        ?>

      <div id="newregionFromScratch">
        <form id="createNewRegionFromScratch" action="index.php" method="post" name="newRegionForm">

          <table class="table" style="width:50%">
            <thead>
              <tr>
              <th>Custom Display Profile ID</th>
              <th>New Custom Region Name</th>
              <th>New Custom Region ID</th>
              <th>Property ID</th>
              </tr>
            </thead>
            <tr>
              <td>
                <select name="displayProfileID" id="displayProfileID" class="form-control" method="post">
                  <?php populateCustomDisplayProfileDropdown(); ?>
                </select>
              </td>
              <td>
                <input class="form-control" id="newRegion" name="newRegion" type="text" placeholder="Enter Region Name">

              </td>
              <td>
                <input class="form-control" id="newRegionID" name="newRegionID" type="text" readonly="true" value="<?php echo getNextAvailableCustomRegionID(); ?>"
              </td>
              <td>
                <select name="propID" id="propID" class="form-control" method="post">
                  <?php getCandidateProperties(); ?>
                </select>
              </td>
            </tr>

          </table>
          <input id="insert" onclick="return validateNewRegionFormInput();" type="submit" class="btn btn-primary" name="newRegionFromScratch"></input>
        </form>
      </div>


      <div>

        <?php

        }

        if(isset($_POST['newRegionFromScratch'])){

          echo "Clicked!";
        }

        ?>




      </div>

</center>

</body>
</html>

  <script>


      $(document).ready(function(){

      $('#insert_new_display_id').on("submit", function(e){

      e.preventDefault();

      var newId = document.getElementById("newId").value;
      var newDesc = document.getElementById("newDesc").value;

      alert("You are adding new Custom Display Profile Name of: " + newDesc);

      $.ajax({
              url:"insertNewDisplayProfileId.php",
              method:"POST",
              data:
                  $('#insert_new_display_id').serialize(),
                  success:function(data){
                  // $('#insert_new_display_id')[0].reset();
                  $('#newProfileIDModal').modal('hide');
                  $('#allCustomRegions').html(data);
              }
            })

      });

      $('#insert_new_custom_region').on("submit", function(e){

      e.preventDefault();

      var customDisplayProfileID = document.getElementById("customDisplayProfileID").value;
      var newCustomRegionDesc = document.getElementById("newCustomRegionDesc").value;
      var newCustomRegionID = document.getElementById("newCustomRegionID").value;


      alert("Id: " + customDisplayProfileID + " newRegion: " + newCustomRegionDesc + " newID " + newCustomRegionID);

      $.ajax({
              url:"insertNewCustomRegion.php",
              method:"POST",
              data:
                  $('#insert_new_custom_region').serialize(),
                  success:function(data){
                  // $('#insert_new_display_id')[0].reset();
                  $('#newCustomRegionModal').modal('hide');
                  $('#deDupedCustomRegions').html(data);
                  //dataType: "text";
              }
            })


      });


      $("#insert_new_property_region_assoc").on("submit", function(e){

      e.preventDefault();

      var customDisplayProfileID = document.getElementById("customDisplayProfileID").value;
      var customRegionName = document.getElementById("customRegionSelected").value;
      var propertyID = document.getElementById("propertyID").value;


      $.ajax({

              url:"insertNewRegionPropAssoc.php",
              method:"POST",
              data:
                  $('#insert_new_property_region_assoc').serialize(),
                  success:function(data){
                  $('#newPropertyRegionAssociation').modal('hide');
                  $('#specificCustomRegion').html(data);
                  }

            })

      })


    });


    $("#createNewRegionFromScratch").on("submit", function(e){

      e.preventDefault();

      var chosenDisplayProfileID = document.getElementById("displayProfileID").value;
      var newRegionNameFromScratch = document.getElementById("newRegion").value;
      var newRegionIdFromScratch = document.getElementById("newRegionID").value;
      var propertyID = document.getElementById("propID").value;

      $.ajax({

            url: "configureNewRegionFromScratch.php",
            method: "POST",
            data:
                $("#createNewRegionFromScratch").serialize(),
                success:function(data){
                alert("You have added " + propertyID + " to custom region id " + chosenDisplayProfileID + "!");
                }

      })


    })

        function validateCustCodeFormInput(){

            if(document.forms['custCodeForm'].custCode.value === ""){

              alert("You must first indicate a customer code!");
              return false;

            }

            return true;
          }

        function validateNewRegionFormInput(){


            if(document.forms['newRegionForm'].newRegion.value === ""){

              alert("Please Enter a Region Name!");
              return false;
            }

            return true;

        }

    </script>
