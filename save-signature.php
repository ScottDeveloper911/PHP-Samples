<?php
$prefix = "https://***.com/all-7y6e3m4d18/";
// Requires php5   
define('UPLOAD_DIR', 'uploads/');   
$img = $_POST['imgBase64'];   
$img = str_replace('data:image/png;base64,', '', $img);   
$img = str_replace(' ', '+', $img);   
$data = base64_decode($img);   
$file = UPLOAD_DIR . uniqid() . '.png';   
$success = file_put_contents($file, $data);   
//print $success ? $file : 'Unable to save the file.';   
if ($success) {
    echo $prefix . $file;
} else {
    echo "Unable to save the file.";
}
?>   
