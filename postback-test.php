<?php
// --------------------------------------------------------------------
// Wildcard Cross Origin
// --------------------------------------------------------------------
header("Access-Control-Allow-Origin: *");

$lid = $_REQUEST["lid"];                     // Lead ID
$ltype = $_REQUEST["ltype"];                 // Lead Type 
$srcid = $_REQUEST["srcid"];                 // Lead Source ID
$srclabel = $_REQUEST["srclabel"];           // Lead Source Label 
$lfields = $_REQUEST["lfields"];             // Lead Fields
$sub1 = $_REQUEST["sub1"];                   // Sub ID
$pub1 = $_REQUEST["pub1"];                   // Pub ID
$time = $_REQUEST["time"];                   // Time
$date = $_REQUEST["date"];                   // Date
$reason = $_REQUEST["reason"];               // Reason
$fname = $_REQUEST["fname"];                 // First Name
$lname = $_REQUEST["lname"];                 // Last Name
$address = $_REQUEST["address"];             // Address
$city = $_REQUEST["city"];                   // City
$state = $_REQUEST["state"];                 // State
$zip = $_REQUEST["zip"];                     // Zipcode
$phone = $_REQUEST["phone"];                 // Phone
$email = $_REQUEST["email"];                 // Email

$full = "Lead ID: ".$lid.
" Lead Type: ".$ltype.
" Lead Source ID: ".$srcid.
" Lead Source Label: ".$srclabel.
" Lead Fields: ".$lfields.
" Sub ID: ".$sub1.
" Pub ID: ".$pub1.
" Time: ".$time.
" Date: ".$date.
" Reason: ".$reason.
" First Name: ".$fname.
" Last Name: ".$lname.
" Address: ".$address.
" City: ".$city.
" State: ".$state.
" Zipcode: ".$zip.
" Phone: ".$phone.
" email: ".$email;

error_log($full, 3, "/var/www/html/postback/postback.log");
echo $full;
?>
