<?php
// --------------------------------------------------------------------
//
// Submit New Partial
//
// --------------------------------------------------------------------

// --------------------------------------------------------------------
// Allow all domains to submit 'HTTP POST'
// --------------------------------------------------------------------
header('Access-Control-Allow-Origin: *');

// --------------------------------------------------------------------
// Set Timezone (CST)
// --------------------------------------------------------------------
date_default_timezone_set('US/Eastern');

// --------------------------------------------------------------------
// Grab Partial Data
// --------------------------------------------------------------------
$ch_fname = $_REQUEST['first_name'];
$ch_lname = $_REQUEST['last_name'];
$ch_dob = $_REQUEST['date_of_birth'];
$ch_phone = $_REQUEST['phone'];
$ch_email = $_REQUEST['email'];
$ch_address = $_REQUEST['address'];
$ch_city = $_REQUEST['city'];
$ch_state = $_REQUEST['state'];
$ch_zip = $_REQUEST['zip_code'];
$ch_medi = $_REQUEST['medicare'];
$ch_date = date('Y-m-d H:i:s');
$ch_cid = $_REQUEST['cid'];
$get_url = $_REQUEST['landing_page_url'];
$ch_url = str_replace("--","&",$get_url);
$ch_trustform = $_REQUEST['trustform'];
$ch_sub1 = $_REQUEST['lp_subid1'];
$ch_sub2 = $_REQUEST['lp_subid2'];
$ch_ssn = $_REQUEST['ssn'];
$ch_income = $_REQUEST['income'];
$ch_ip = $_REQUEST['ip'];

// --------------------------------------------------------------------
// Check for IP match
// --------------------------------------------------------------------
$match_ip = false;

// --------------------------------------------------------------------
// Connect to DB
// --------------------------------------------------------------------
$servername = "***";
$username = "***";
$password = "***";
$dbname = "defaultdb";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
        
// --------------------------------------------------------------------
// Iterate through DB rows
// --------------------------------------------------------------------
$sql = "SELECT ip FROM midware3";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
    // --------------------------------------------------------------------
    // Check for IP match in Database
    // --------------------------------------------------------------------
    if ($row["ip"] === $ch_ip) {
        // Successful IP Match
        $match_ip = true;
    }
}

if ($match_ip === false) {
    // --------------------------------------------------------------------
    // Add New Partial to Database
    // --------------------------------------------------------------------
    $sql = "INSERT INTO midware3 (ip, first_name, last_name, birth, phone, email, street, city, state, zipcode, medicare, partial_date, cid, page_url, trustform, lp_subid1, lp_subid2, ssn, income) VALUES ('".$ch_ip."', '".$ch_fname."', '".$ch_lname."', '".$ch_dob."', '".$ch_phone."', '".$ch_email."', '".$ch_address."', '".$ch_city."', '".$ch_state."', '".$ch_zip."', '".$ch_medi."', '".$ch_date."', '".$ch_cid."', '".$ch_url."', '".$ch_trustform."', '".$ch_sub1."', '".$ch_sub2."', '".$ch_ssn."', '".$ch_income."')";
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>