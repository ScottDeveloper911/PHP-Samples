<?php
// ----------------------------------------------------------------
// Set Timezone (CST)
// ----------------------------------------------------------------
date_default_timezone_set('US/Eastern');

// ----------------------------------------------------------------
// Grab Data
// ----------------------------------------------------------------
$gCCD = $_REQUEST["CCD"];
$gUPL = $_REQUEST["UPL"];
$gMBI = $_REQUEST["MBI"];
$gTXD = $_REQUEST["TXD"];
$gOTR = $_REQUEST["OTR"];
$gDEBT = $_REQUEST["DEBT"];
$gfname = $_REQUEST["fname"];
$glname = $_REQUEST["lname"];
$gemail = $_REQUEST["email"];
$gphone = $_REQUEST["phoneNum"];

$gaddress = $_REQUEST["sendaddress"];
$gcity = $_REQUEST["sendcity"];
$gstate = $_REQUEST["sendstate"];
$gzip = $_REQUEST["sendzip"];
$gadid = $_REQUEST["adid"];

$gip = $_REQUEST["ip"];
$gtrust = $_REQUEST["xxTrustedFormCertUrl"];
$gjornaya = $_REQUEST["universal_leadid"];
$gurl = $_REQUEST["landingurl"];
$date = date("m/d/Y h:i:s");

// ----------------------------------------------------------------
// 
// ----------------------------------------------------------------
include("checkzip.php");
//echo "City: " .$citystate[0]. " State: " .$citystate[1];


// ----------------------------------------------------------------
// Grab Data
// ----------------------------------------------------------------
function sendRequest($url, $data) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, true);    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: close'));
    $result = curl_exec($ch);
    if ( curl_errno($ch) ) { //error occured
        $result = 'cURL ERROR -> ' . curl_errno($ch) . ': ' . curl_error($ch);
    } else {
        $returnCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        switch($returnCode){
            case 200:
                break;
            default:
                $result = 'HTTP ERROR -> ' . $returnCode;
                break;
        }
    }
    curl_close($ch);
    return $result;
}
$json = '{
    "Request": {
        "Mode": "full",
        "Key": "***",
        "API_Action": "pingPostLead",
        "TYPE": "35",
        "SRC": "***",
        "TCPA_Consent": "Yes",
        "IP_Address": "'.$gip.'",
        "Landing_Page": "'.$gurl.'",
        "First_Name": "'.$gfname.'",
        "Last_Name": "'.$glname.'",
        "Address": "'.$gaddress.'",
        "City": "'.$gcity.'",
        "State": "'.$gstate.'",
        "Zip": "'.$gzip.'",
        "Primary_Phone": "'.$gphone.'",
        "Email": "'.$gemail.'",
        "Total_Debt": "'.$gDEBT.'",
        "Trusted_Form_URL": "'.$gtrust.'",
        "Universal_LeadiD": "'.$gjornaya.'",
        "ad_id": "'.$gadid.'"
    }
}';
$url = "https://***.com/apiJSON.php";
if ($gfname != '') {
    $result = sendRequest($url, $json);
}
//echo $json;
//echo $result;

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
$sql = "SELECT ip FROM main";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
    if ($row["ip"] === $gip) {        
        // --------------------------------------------------------------------
        // Update Entry on IP match
        // --------------------------------------------------------------------
        $sql = "UPDATE main SET sent_full = '1' WHERE ip = '".$gip."'";
        if ($conn->query($sql) === TRUE) {
            echo "Record updated successfully.";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

echo '<script>window.location.replace("https://***.com/thankyou.php");</script>';
?>
