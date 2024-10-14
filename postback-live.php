<?php
// --------------------------------------------------------------------
// Wildcard Cross Origin
// --------------------------------------------------------------------
header("Access-Control-Allow-Origin: *");

// --------------------------------------------------------------------
// Set Timezone (CST)
// --------------------------------------------------------------------
date_default_timezone_set('US/Eastern');

// --------------------------------------------------------------------
// Collect Data
// --------------------------------------------------------------------
$date = date("m/d/Y h:s:i A");
$g_rid = $_REQUEST["RecordID"];
$g_date = $_REQUEST["StatusDate"];
$g_status = $_REQUEST["Status"];
$g_pid = $_REQUEST["PackageID"];
$g_type = $_REQUEST["SignerType"];
$g_url = $_REQUEST["MediaUrl"];
$g_file = $_REQUEST["FileName"];
$g_cid = $_REQUEST["CID"];

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
$sql = "SELECT * FROM main01";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
    // --------------------------------------------------------------------
    // Check for 'rid' match in Database
    // --------------------------------------------------------------------
    if ($row["rid"] === $g_rid && $g_status === 'Signed') {
        // Successful 'rid' Match
        
        // --------------------------------------------------------------------
        // Clean Data Checker
        // --------------------------------------------------------------------
        if (is_nan($row['depone_ssn'])) {
            $depone_ssn = '';
        }
        if (is_nan($row['spouce_ssn'])) {
            $spouce_ssn = '';
        }
        if ($row['spouse_dob'] === "//") {
            $spouse_dob = '';
        }
        
        // --------------------------------------------------------------------
        // Send Data to LP
        // --------------------------------------------------------------------
        $url = "https://api.leadprosper.io/ingest";
        $formData3 = array(
            "lp_campaign_id" => "***",
            "lp_supplier_id" => "***",
            "lp_key" => "***",
            "lp_action" => "",
            "first_name" => $row["first_name"],
            "last_name" => $row["last_name"],
            "date_of_birth" => $row["birth"],
            "phone" => $row["phone"],
            "email" => $row["email"],
            "address" => $row['street'],
            "city" => $row['city'],
            "state" => $row['state'],
            "zip_code" => $row['zipcode'],
            "medicaid" => $row['medicare'],
            "medicare" => $row['medicare'],
            "ssn" => $row['ssn'],
            "gender" => $row['gender'],
            "married" => $row['married'],
            "spouce_fname" => $row['spouse_fname'],
            "spouce_lname" => $row['spouse_lname'],
            "spouce_gender" => $row['spouse_gender'],
            "spouce_birth" => $spouse_dob,
            "spouce_ssn" => $spouce_ssn,
            "dependent" => $row['dependent'],
            "depone_fname" => $row['dependent_fname'],
            "depone_lname" => $row['dependent_lname'],
            "depone_gender" => $row['dependent_gender'],
            "depone_birth" => $row['dependent_dob'],
            "depone_ssn" => $depone_ssn,
            "income" => $row['income'],
            "carriers_bestop" => "Best Recommended",
            "acknowledgement" => $row['consent'],
            "cid" => $row['cid'],
            "landing_page_url" => $row['page_url'],
            "trustform" => $row['trustform'],
            "lp_subid1" => $row['lp_subid1'],
            "lp_subid2" => $row['lp_subid2'],
            "rid" => $row['rid'],
            "ip_address" => $row['ip'],
            "c_plan" => $row['c_plan'],
            "c_insured" => $row['c_insured'],
            "c_ends" => $row['c_ends'],
            "c_sugery" => $row['c_sugery'],
            "c_savings" => $row['c_savings'],
            "c_filetax" => $row['c_filetax'],
            "c_tobacco" => $row['c_tobacco'],
            "c_terms" => $row['c_terms'],
            "doctor" => $row['doctor'],
            "perscriptions" => $row['prescriptions'],
            "tobacco" => $row['tobacco'],
            "terms" => "Accept",
            "enrollment" => "yes",
            "tax" => "yes",
            "income_verification" => "yes",
            "enrollment" => "yes",
            "timestamp" => $date
        );
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $data = json_encode($formData3);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $resp = curl_exec($curl);
        if (strlen($resp) > 0) {
            // Success
            // --------------------------------------------------------------------
            // Update 'full_sent' to 1
            // --------------------------------------------------------------------
            $sql = "UPDATE main01 SET full_sent = 1 WHERE rid = '".$g_rid."'";
            if ($conn->query($sql) === TRUE) {
                //echo "Record updated successfully.";
            } else {
                //echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
        curl_close($curl);
        //var_dump($resp);
    }
}

// Debug
error_log($g_rid." - ".$g_status. " \n", 3, "/var/www/***.com/pb/status.log");
?>