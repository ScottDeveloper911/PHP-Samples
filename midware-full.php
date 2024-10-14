<?php
// --------------------------------------------------------------------
// Allow all domains to submit 'HTTP POST'
// --------------------------------------------------------------------
header('Access-Control-Allow-Origin: *');

// --------------------------------------------------------------------
// Set Timezone (CST)
// --------------------------------------------------------------------
date_default_timezone_set('US/Eastern');

// --------------------------------------------------------------------
// Grab Full Form Data
// --------------------------------------------------------------------
$ch_gender = $_REQUEST['gender'];
$ch_married = $_REQUEST['married'];
$ch_spfname = $_REQUEST['spouce_fname'];
$ch_splname = $_REQUEST['spouce_lname'];
$ch_spgender = $_REQUEST['spouce_gender'];
$ch_spdob = $_REQUEST['spouce_birth'];
$ch_spssn = $_REQUEST['spouce_ssn'];
$ch_dependent = $_REQUEST['dependent'];
$ch_dpfname = $_REQUEST['depone_fname'];
$ch_dplname = $_REQUEST['depone_lname'];
$ch_dpgender = $_REQUEST['depone_gender'];
$ch_dpdob = $_REQUEST['depone_birth'];
$ch_dpssn = $_REQUEST['depone_ssn'];
$ch_incverify = $_REQUEST['income_verification'];
$ch_enroll = $_REQUEST['enrollment'];
$ch_tax = $_REQUEST['tax'];
$ch_acknow = $_REQUEST['acknowledgement'];
$ch_signature = $_REQUEST['signature'];
$ch_date = date('Y-m-d H:i:s');
$ch_ip = $_REQUEST['ip'];

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
        
        // --------------------------------------------------------------------
        // Update Full Form
        // --------------------------------------------------------------------
        $sql = "UPDATE midware3 SET gender = '".$ch_gender."', married = '".$ch_married."', spouse_fname = '".$ch_spfname."', spouse_lname = '".$ch_splname."', spouse_gender = '".$ch_spgender."', spouse_dob = '".$ch_spdob."', spouse_ssn = '".$ch_spssn."', dependent = '".$ch_dependent."', dependent_fname = '".$ch_dpfname."', dependent_lname = '".$ch_dplname."', dependent_gender = '".$ch_dpgender."', dependent_dob = '".$ch_dpdob."', dependent_ssn = '".$ch_dpssn."', income_verify = '".$ch_incverify."', enrollment = '".$ch_enroll."', tax = '".$ch_tax."', consent = '".$ch_acknow."', signature_url = '".$ch_signature."', last_updated = '".$ch_date."' WHERE ip = '".$ch_ip."'";
        if ($conn->query($sql) === TRUE) {
            echo "Record updated successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

?>