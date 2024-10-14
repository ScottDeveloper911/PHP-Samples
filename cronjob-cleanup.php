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
// Counter
// --------------------------------------------------------------------
$g_count = 0;

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
    $part_date = strtotime($row["partial_date"]. ' +10 minutes');
    $part_final = date('Y-m-d h:i:s', $part_date);
    $g_ip = $row["ip"];
    $g_sent = number_format($row["partial_sent"]);
    $g_id = $row["id"];
    $g_date = date('Y-m-d H:i:s');
    $g_fname = $row['first_name'];
    $g_lname = $row['last_name'];
    $g_dob = $row['birth'];
    $g_phone = $row['phone'];
    $g_email = $row['email'];
    $g_address = $row['street'];
    $g_city = $row['city'];
    $g_state = $row['state'];
    $g_zip = $row['zipcode'];
    $g_permi = $row['permissions'];
    $g_medi = $row['medicare'];
    $g_ssn = $row["ssn"];
    $g_gender = $row["gender"];
    $g_married = $row["married"];
    $g_spfname = $row["spouse_fname"];
    $g_splname = $row["spouse_lname"];
    $g_spgender = $row["spouse_gender"];
    $g_spdob = $row["spouse_dob"];
    $g_spssn = $row["spouse_ssn"];
    $g_dep = $row["dependent"];
    $g_depfname = $row["dependent_fname"];
    $g_deplname = $row["dependent_lname"];
    $g_depgender = $row["dependent_gender"];
    $g_depdob = $row["dependent_dob"];
    $g_depssn = $row["dependent_ssn"];
    $g_income = $row['income'];
    $g_carriers = $row['carriers'];
    $g_inc_ver = $row['income_verify'];
    $g_enrollment = $row['enrollment'];
    $g_tax = $row['tax'];
    $g_consent = $row['consent'];
    $g_sigurl = $row['signature_url'];
    $g_cid = $row['cid'];
    $get_url = $row['page_url'];
    $g_jornaya = $row['jornaya_leadid'];
    $g_url = str_replace("--","&",$get_url);
    $g_trustform = $row['trustform'];
    $g_sub1 = $row['lp_subid1'];
    $g_sub2 = $row['lp_subid2'];
    $g_part_date = $row['partial_date'];
    $g_last_date = $row['last_updated'];
    $g_rid = $row['rid'];
    $g_full_sent = $row['full_sent'];
    
    // --------------------------------------------------------------------
    // Close connection after 5 enteries transfered
    // --------------------------------------------------------------------
    if ($g_count > 4) {
        $conn->close();
    }
    
    // --------------------------------------------------------------------
    // 10 minutes from 'partial_date'
    // --------------------------------------------------------------------
    if ($part_final < $current_date) {
        
        // --------------------------------------------------------------------
        // Testing
        // --------------------------------------------------------------------
        //echo "id: ".$row["id"]." name: ".$row["first_name"]." ".$row["last_name"]."<br>";
        
        // --------------------------------------------------------------------
        // Add Old Entry to 'archive01'
        // --------------------------------------------------------------------
        $sql = "INSERT INTO archive01 (ip,first_name,last_name,birth,phone,email,street,city,state,zipcode,permissions,medicare,partial_date,ssn,gender,married,spouse_fname,spouse_lname,spouse_gender,spouse_dob,spouse_ssn,dependent,dependent_fname,dependent_lname,dependent_gender,dependent_dob,dependent_ssn,income,carriers,income_verify,enrollment,tax,consent,signature_url,cid,page_url,jornaya_leadid,trustform,lp_subid1,lp_subid2,partial_sent,last_updated,rid,full_sent) VALUES ('".$g_ip."','".$g_fname."','".$g_lname."','".$g_dob."','".$g_phone."','".$g_email."','".$g_address."','".$g_city."','".$g_state."','".$g_zip."','".$g_permi."','".$g_medi."','".$g_date."','".$g_ssn."','".$g_gender."','".$g_married."','".$g_spfname."','".$g_splname."','".$g_spgender."','".$g_spdob."','".$g_spssn."','".$g_dep."','".$g_depfname."','".$g_deplname."','".$g_depgender."','".$g_depdob."','".$g_depssn."','".$g_income."','".$g_carriers."','".$g_inc_ver."','".$g_enrollment."','".$g_tax."','".$g_consent."','".$g_sigurl."','".$g_cid."','".$g_url."','".$g_jornaya."','".$g_trustform."','".$g_sub1."','".$g_sub2."','".$g_sent."','".$g_last_date."','".$g_rid."','".$g_full_sent."')";
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully. <br>";
            // --------------------------------------------------------------------
            // Add Old Entry to 'archive01'
            // --------------------------------------------------------------------
            $sql2 = "DELETE FROM main01 WHERE id=".$g_id.";";
            if ($conn->query($sql2) === TRUE) {
                echo "Old entry deleted successfully. <br>";
                $g_count++;
                // Debug
                error_log("Archived: ".$g_date." IP: ".$g_ip." Name: ".$g_fname." ".$g_lname." \n", 3, "/var/www/***.com/sm-7x/archived.log");
            } else {
                echo "Error: " . $sql2 . "<br>" . $conn->error;
            }
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
//$conn->close();
?>