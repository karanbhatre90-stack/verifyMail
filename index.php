<?php
header('Content-Type: application/json');

// Fix JSON_PRETTY_PRINT constant issue
if (!defined('JSON_PRETTY_PRINT')) {
    define('JSON_PRETTY_PRINT', 128);
}

// === CONFIGURATION ===
$email = isset($_GET['email']) ? trim($_GET['email']) : '';
$index = isset($_GET['index']) ? intval($_GET['index']) : 1;

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(array(
        array(
            "index" => $index,
            "verify" => $email,
            "response" => array(
                "safetosend" => "No",
                "status" => "Invalid",
                "type" => "",
                "v_response" => "Invalid email format",
                "mx_server" => "",
                "mx_found" => false,
                "mx_response" => ""
            ),
            "code" => "400"
        )
    ), JSON_PRETTY_PRINT);
    exit;
}

// === EXTRACT DOMAIN ===
list($user, $domain) = explode('@', $email);

// === CHECK MX RECORD ===
$mx_records = array();
$mx_found = getmxrr($domain, $mx_records);
$mx_server = $mx_found ? $mx_records[0] : "";

// === DEFAULT RESPONSE ===
$response = array(
    "safetosend" => "No",
    "status" => "Invalid",
    "type" => "",
    "v_response" => "Undeliverable",
    "mx_server" => $mx_server,
    "mx_found" => $mx_found,
    "mx_response" => ""
);

// === SMTP VERIFICATION ===
if ($mx_found) {
    $connect = @fsockopen($mx_server, 25, $errno, $errstr, 8);
    if ($connect) {
        stream_set_timeout($connect, 8);
        fgets($connect);
        fputs($connect, "HELO example.com\r\n");
        fgets($connect);
        fputs($connect, "MAIL FROM:<verify@example.com>\r\n");
        fgets($connect);
        fputs($connect, "RCPT TO:<$email>\r\n");
        $rcpt = fgets($connect);
        fputs($connect, "QUIT\r\n");
        fclose($connect);

        if (strpos($rcpt, "250") !== false) {
            $response = array(
                "safetosend" => "Yes",
                "status" => "Valid",
                "type" => "",
                "v_response" => "Deliverable",
                "mx_server" => $mx_server,
                "mx_found" => true,
                "mx_response" => trim($rcpt)
            );
        } else {
            $response["mx_response"] = trim($rcpt);
        }
    } else {
        $response["v_response"] = "MX server connection failed";
    }
}

echo json_encode(array(
    array(
        "index" => $index,
        "verify" => $email,
        "response" => $response,
        "code" => "200"
    )
), JSON_PRETTY_PRINT);
?>
