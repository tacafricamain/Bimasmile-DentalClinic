<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON']);
    exit;
}

$to = $input['to'] ?? '';
$message = $input['message'] ?? '';
$formData = $input['formData'] ?? [];

if (empty($to) || empty($message)) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

// Option 1: Using Twilio WhatsApp API
function sendViaTwilio($to, $message) {
    $accountSid = 'YOUR_TWILIO_ACCOUNT_SID'; // Replace with your Twilio Account SID
    $authToken = 'YOUR_TWILIO_AUTH_TOKEN';   // Replace with your Twilio Auth Token
    $fromNumber = 'whatsapp:+14155238886';   // Twilio WhatsApp sandbox number
    $toNumber = "whatsapp:+$to";
    
    $url = "https://api.twilio.com/2010-04-01/Accounts/$accountSid/Messages.json";
    
    $data = [
        'From' => $fromNumber,
        'To' => $toNumber,
        'Body' => $message
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "$accountSid:$authToken");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return $httpCode === 201;
}

// Option 2: Using WhatsApp Business Cloud API
function sendViaWhatsAppCloudAPI($to, $message) {
    $accessToken = 'YOUR_WHATSAPP_ACCESS_TOKEN'; // Replace with your access token
    $phoneNumberId = 'YOUR_PHONE_NUMBER_ID';     // Replace with your phone number ID
    
    $url = "https://graph.facebook.com/v18.0/$phoneNumberId/messages";
    
    $data = [
        'messaging_product' => 'whatsapp',
        'to' => $to,
        'type' => 'text',
        'text' => [
            'body' => $message
        ]
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return $httpCode === 200;
}

// Option 3: Using a third-party WhatsApp API service
function sendViaThirdPartyAPI($to, $message) {
    // Example using WhatsApp API services like:
    // - UltraMsg.com
    // - CallMeBot
    // - WhatsApp-web.js based services
    
    $apiUrl = 'YOUR_WHATSAPP_API_ENDPOINT';
    $apiToken = 'YOUR_API_TOKEN';
    
    $data = [
        'token' => $apiToken,
        'to' => $to,
        'body' => $message
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLOPT_HTTP_CODE);
    curl_close($ch);
    
    return $httpCode === 200;
}

// Try to send the message using available methods
$success = false;

// Try Twilio first (uncomment and configure)
// $success = sendViaTwilio($to, $message);

// If Twilio fails, try WhatsApp Cloud API (uncomment and configure)
// if (!$success) {
//     $success = sendViaWhatsAppCloudAPI($to, $message);
// }

// If both fail, try third-party API (uncomment and configure)
// if (!$success) {
//     $success = sendViaThirdPartyAPI($to, $message);
// }

// For demo purposes, log the appointment to a file
$logData = [
    'timestamp' => date('Y-m-d H:i:s'),
    'to' => $to,
    'form_data' => $formData,
    'message' => $message
];

file_put_contents('appointments.log', json_encode($logData) . "\n", FILE_APPEND);

if ($success) {
    echo json_encode([
        'success' => true,
        'message' => 'WhatsApp message sent successfully'
    ]);
} else {
    // Return false so frontend falls back to redirect method
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Failed to send WhatsApp message',
        'fallback' => true
    ]);
}
?>