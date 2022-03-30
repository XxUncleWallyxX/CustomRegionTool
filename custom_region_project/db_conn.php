<?php

/*
    Establish database connectivity (using PHP MyAdmin)
    Database Name: ref_advertising_demo
    Table Name: ref_advertising
    ** This table is a mock-up of actual ref_advertising in production (except "id" column is PK) **

*/
$connection = mysqli_connect('localhost','root','','custom_region_project');
//
//	if(!$connection){
//
//        echo "Connection to database failed!";
//
//    } else
//    {
//
//       echo "Connected to database successfully!" . "<br>";
//    }

?>
