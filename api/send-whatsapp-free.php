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

// ========================================
// 🆓 FREE WHATSAPP API CONFIGURATION
// ========================================

// OPTION 1: WhatsApp Business Cloud API (FREE - Up to 1,000 messages/month)
// 🔥 REPLACE THESE WITH YOUR ACTUAL VALUES FROM META DEVELOPERS:
$whatsappAccessToken = 'EAA7DjHpDJD0BPwHZCZANNtsfEgvdqeLuH7eXx4noIIWA3nfQPwcoMNO3UcnIhUthGneTgsIKc23TCE946qKP1w1qEe3XMFj50WtfgNlsQE0M3EjGDfL6bFLJ93gnE8fYGp3Q5I69rCM7RBxDhqR1Q1pdZCQNpJYs82KoBhUDR7pRBKvIg63xRGTAhy0iZAaXDpY3TtfG1iLhZCNtQIfgZB7ePwQzk5YD5lCdqDGluEU9ujxGruMB3wcLDC6ezXtQcZD'; // Replace with EAAxxxxxxxxx...
$phoneNumberId = '869142992942008'; // Replace with your Phone Number ID

// OPTION 2: UltraMsg (FREE - 1,000 messages/month)
// Get these from: https://ultramsg.com/
$ultraMsgToken = 'YOUR_ULTRAMSG_TOKEN';
$ultraMsgInstance = 'YOUR_ULTRAMSG_INSTANCE';

// OPTION 3: Twilio (PAID - But most reliable if you want to pay)
$twilioSid = 'YOUR_TWILIO_ACCOUNT_SID';
$twilioToken = 'YOUR_TWILIO_AUTH_TOKEN';

// ========================================
// 🆓 FREE API FUNCTIONS
// ========================================

// Option 1: WhatsApp Business Cloud API (COMPLETELY FREE)
function sendViaWhatsAppCloudAPI($to, $message, $accessToken, $phoneNumberId) {
    $url = "https://graph.facebook.com/v18.0/$phoneNumberId/messages";
    
    $data = [
        'messaging_product' => 'whatsapp',
        'to' => $to,
        'type' => 'text',
        'text' => ['body' => $message]
    ];
    
    $headers = [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json'
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlError = curl_error($ch);
    curl_close($ch);
    
    // Enhanced error handling
    if ($curlError) {
        return ['success' => false, 'error' => 'CURL Error: ' . $curlError, 'code' => 0];
    }
    
    if ($httpCode == 200) {
        $responseData = json_decode($response, true);
        return ['success' => true, 'response' => $responseData];
    }
    
    // Parse error response for better debugging
    $errorData = json_decode($response, true);
    $errorMessage = $errorData['error']['message'] ?? $response;
    
    return [
        'success' => false, 
        'error' => $errorMessage,
        'code' => $httpCode,
        'full_response' => $response,
        'url' => $url,
        'data_sent' => $data
    ];
}

// Option 2: UltraMsg API (FREE tier available)
function sendViaUltraMsg($to, $message, $token, $instance) {
    $url = "https://api.ultramsg.com/$instance/messages/chat";
    
    $data = [
        'token' => $token,
        'to' => $to,
        'body' => $message
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 200) {
        return ['success' => true, 'response' => json_decode($response, true)];
    }
    
    return ['success' => false, 'error' => $response, 'code' => $httpCode];
}

// Option 3: Twilio (PAID but reliable)
function sendViaTwilio($to, $message, $accountSid, $authToken) {
    $fromNumber = 'whatsapp:+14155238886'; // Twilio sandbox
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
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 200 || $httpCode == 201) {
        return ['success' => true, 'response' => json_decode($response, true)];
    }
    
    return ['success' => false, 'error' => $response, 'code' => $httpCode];
}

// ========================================
// 🚀 MAIN SENDING LOGIC
// ========================================

$result = ['success' => false];
$attempts = [];

// TRY FREE METHODS FIRST

// 1. Try WhatsApp Business Cloud API (FREE)
if (!empty($whatsappAccessToken) && $whatsappAccessToken !== 'YOUR_FREE_WHATSAPP_ACCESS_TOKEN') {
    $result = sendViaWhatsAppCloudAPI($to, $message, $whatsappAccessToken, $phoneNumberId);
    $attempts[] = 'WhatsApp Cloud API (FREE)';
    
    // Debug logging
    $debugData = [
        'timestamp' => date('Y-m-d H:i:s'),
        'method' => 'WhatsApp Cloud API attempt',
        'to' => $to,
        'result' => $result,
        'token_length' => strlen($whatsappAccessToken),
        'phone_number_id' => $phoneNumberId
    ];
    file_put_contents('debug.log', json_encode($debugData) . "\n", FILE_APPEND);
    
    if ($result['success']) {
        // Log successful appointment
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'method' => 'WhatsApp Cloud API (FREE)',
            'to' => $to,
            'form_data' => $formData,
            'status' => 'success'
        ];
        file_put_contents('appointments.log', json_encode($logData) . "\n", FILE_APPEND);
        
        echo json_encode([
            'success' => true,
            'message' => 'WhatsApp message sent successfully via FREE Cloud API!',
            'method' => 'WhatsApp Cloud API (FREE)'
        ]);
        exit;
    }
}

// 2. Try UltraMsg (FREE tier)
if (!$result['success'] && !empty($ultraMsgToken) && $ultraMsgToken !== 'YOUR_ULTRAMSG_TOKEN') {
    $result = sendViaUltraMsg($to, $message, $ultraMsgToken, $ultraMsgInstance);
    $attempts[] = 'UltraMsg (FREE)';
    
    if ($result['success']) {
        // Log successful appointment
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'method' => 'UltraMsg (FREE)',
            'to' => $to,
            'form_data' => $formData,
            'status' => 'success'
        ];
        file_put_contents('appointments.log', json_encode($logData) . "\n", FILE_APPEND);
        
        echo json_encode([
            'success' => true,
            'message' => 'WhatsApp message sent successfully via FREE UltraMsg!',
            'method' => 'UltraMsg (FREE)'
        ]);
        exit;
    }
}

// 3. Try Twilio (PAID fallback)
if (!$result['success'] && !empty($twilioSid) && $twilioSid !== 'YOUR_TWILIO_ACCOUNT_SID') {
    $result = sendViaTwilio($to, $message, $twilioSid, $twilioToken);
    $attempts[] = 'Twilio (PAID)';
    
    if ($result['success']) {
        // Log successful appointment
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'method' => 'Twilio (PAID)',
            'to' => $to,
            'form_data' => $formData,
            'status' => 'success'
        ];
        file_put_contents('appointments.log', json_encode($logData) . "\n", FILE_APPEND);
        
        echo json_encode([
            'success' => true,
            'message' => 'WhatsApp message sent successfully via Twilio!',
            'method' => 'Twilio (PAID)'
        ]);
        exit;
    }
}

// All methods failed
$logData = [
    'timestamp' => date('Y-m-d H:i:s'),
    'to' => $to,
    'form_data' => $formData,
    'attempts' => $attempts,
    'status' => 'failed',
    'error' => $result['error'] ?? 'All methods failed',
    'last_result' => $result,
    'debug_info' => [
        'token_configured' => !empty($whatsappAccessToken) && $whatsappAccessToken !== 'YOUR_FREE_WHATSAPP_ACCESS_TOKEN',
        'phone_id_configured' => !empty($phoneNumberId) && $phoneNumberId !== 'YOUR_FREE_PHONE_NUMBER_ID',
        'ultramsg_configured' => !empty($ultraMsgToken) && $ultraMsgToken !== 'YOUR_ULTRAMSG_TOKEN',
        'twilio_configured' => !empty($twilioSid) && $twilioSid !== 'YOUR_TWILIO_ACCOUNT_SID'
    ]
];
file_put_contents('appointments.log', json_encode($logData) . "\n", FILE_APPEND);

// Return failure so frontend falls back to redirect
http_response_code(500);
echo json_encode([
    'success' => false,
    'error' => 'All WhatsApp methods failed. Will use redirect fallback.',
    'attempts' => $attempts,
    'fallback' => true,
    'debug' => $result // Include the actual error for debugging
]);
?>