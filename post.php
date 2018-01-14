<?php
ini_set('display_errors', 1);

require("phpMQTT.php");

$key = "XXXXX";			// The API key
$server = "XXXXX";			// change if necessary
$port = XXXXX;						// change if necessary
$username = "XXXXX";				// set your username
$password = "XXXXX";				// set your password
$client_id = "phpMQTT-publisher";	// make sure this is unique for connecting to sever - you could use uniqid()

# Make sure all post information is present
if(!isset($_GET['key']) || !isset($_GET['user']) || !isset($_GET['status']) ) {
	http_response_code(403);
	die("Missing post information");
}

# Make sure the key is correct
if ($_GET['key'] != $key) {
	http_response_code(403);
	die("Wrong authentication");
}

# Check if the status is numeric
$status = $_GET['status'];
if (!is_numeric($status)) {
	http_response_code(400);
	die("Status isn't numeric");
}

# Check if the status is either 0 or 1 (absent/present)
if (intval($status) != 0 && intval($status) != 1) {
	http_response_code(400);
	die("Status should be either 0 or 1");
}

$response = "";
if (intval($status) == 0)
	$response = "not home";
else if (intval($status) == 1)
	$response = "home";

$user = $_GET['user'];

$mqtt = new phpMQTT($server, $port, $client_id);

if ($mqtt->connect(true, NULL, $username, $password)) {
	$mqtt->publish("home/presence/$user", $response, 0, 1);
	$mqtt->close();
} else {
    echo "Time out!\n";
}
