<?php
// --------------------------------------------------------------------
//
//
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
// Counter
// --------------------------------------------------------------------
$ch_count2 = 0;

// --------------------------------------------------------------------
// Current Date
// --------------------------------------------------------------------
$current_date = date("Y-m-d h:i:s");

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
echo "Connected successfully <br>";
// Debug
error_log("DB Connected \n", 3, "/var/www/***.com/sm-7x/cronjob.log");
// --------------------------------------------------------------------
// Iterate through DB rows
// --------------------------------------------------------------------
$sql = "SELECT * FROM main01";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
    
    // --------------------------------------------------------------------
    // Testing
    // --------------------------------------------------------------------
    //echo "id: ".$row["id"]." name: ".$row["first_name"]." ".$row["last_name"]."<br>";
    
    // --------------------------------------------------------------------
    // Grab & Manipulate Data
    // --------------------------------------------------------------------
    $part_date = strtotime($row["partial_date"]. ' +30 minutes');
    $part_final = date('Y-m-d h:i:s', $part_date);
    $g_ip = $row["ip"];
    $g_sent = $row["partial_sent"];
    $g_id = $row["id"];
    $g_ssn = $row["ssn"];
    $g_gender = $row["gender"];
    if ($g_id === '16') {
        // Debug
        error_log("Partial Date: ".$part_final." Current Date: ".$current_date." Partial Sent: ".$g_sent." Gender: ".$g_gender." \n", 3, "/var/www/***.com/sm-7x/cronjob.log");
    }
    
    // --------------------------------------------------------------------
    // if/else Inside 'while' loop
    // --------------------------------------------------------------------
    if ($part_final < $current_date && $g_sent === '0' && $g_gender === null) {
        // Debug
        error_log("Partial Date: ".$part_final." Current Date: ".$current_date." Partial Sent: ".$g_sent." Gender: ".$g_gender." \n", 3, "/var/www/***.com/sm-7x/cronjob.log");
        
        // --------------------------------------------------------------------
        // Testing
        // --------------------------------------------------------------------
        //echo "id: ".$row["id"]." name: ".$row["first_name"]." ".$row["last_name"]."<br>";
        
        // --------------------------------------------------------------------
        // Counter
        // --------------------------------------------------------------------
        $ch_count2++;
        
        // --------------------------------------------------------------------
        // Send Data to LP
        // --------------------------------------------------------------------
        $url = "https://api.leadprosper.io/ingest";
        $formData = array(
            "lp_campaign_id"=>"***",
            "lp_supplier_id"=>"***",
            "lp_key"=>"***",
            "lp_action"=>"",
            "first_name"=>$row["first_name"],
            "last_name"=>$row["last_name"],
            "email"=>$row["email"],
            "phone"=>$row["phone"],
            "date_of_birth"=>$row["birth"],
            "address"=>$row["street"],
            "city"=>$row["city"],
            "state"=>$row["state"],
            "zip_code"=>$row["zipcode"],
            "ip_address"=>$row["ip"],
            "landing_page_url"=>$row["page_url"],
            "cid"=>$row["cid"],
            "trustform"=>$row["trustform"],
            "give_permission"=>$row["permissions"],
            "medicaid"=>$row["medicare"],
            "medicare"=>$row["medicare"],
            "lp_subid1"=>$row["lp_subid1"],
            "lp_subid2"=>$row["lp_subid2"],
            "dependent"=>$row["dependent"],
            "married"=>$row["married"],
            "income"=>$row["income"]
        );
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $data = json_encode($formData);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $resp = curl_exec($curl);
        curl_close($curl);
        //var_dump($resp);
        $data = array( "status" => "success" );
        $data = json_encode( $data );
        echo $data;
        
        // --------------------------------------------------------------------
        // Update Entry
        // --------------------------------------------------------------------
        $sql = "UPDATE main01 SET partial_sent = '1' WHERE ip = '".$g_ip."'";
        if ($conn->query($sql) === TRUE) {
            echo "Record updated successfully.";
            // Debug
            error_log("Record updated successfully \n", 3, "/var/www/***.com/sm-7x/cronjob.log");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
//$conn->close();
?>