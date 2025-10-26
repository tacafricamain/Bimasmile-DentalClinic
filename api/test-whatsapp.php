<?php
// WhatsApp Business Cloud API Test Script
// Use this to test your credentials before integrating with your booking form

header('Content-Type: application/json');

// 🔧 CONFIGURE THESE WITH YOUR ACTUAL VALUES FROM META DEVELOPERS
$whatsappAccessToken = 'YOUR_ACCESS_TOKEN_HERE'; // Replace with EAAxxxxx...
$phoneNumberId = 'YOUR_PHONE_NUMBER_ID_HERE'; // Replace with your Phone Number ID
$testRecipient = '2349031741426'; // Your test phone number (with country code)

// Test message
$testMessage = "🧪 TEST MESSAGE\n\nThis is a test from your Bimasmile Dental Clinic WhatsApp API integration!\n\nIf you receive this, your setup is working perfectly! ✅\n\nTime: " . date('Y-m-d H:i:s');

// Check if credentials are configured
if ($whatsappAccessToken === 'YOUR_ACCESS_TOKEN_HERE' || $phoneNumberId === 'YOUR_PHONE_NUMBER_ID_HERE') {
    echo json_encode([
        'success' => false,
        'error' => 'Please configure your credentials first!',
        'instructions' => [
            '1. Get your Access Token from developers.facebook.com',
            '2. Get your Phone Number ID from your WhatsApp Business setup',
            '3. Replace the placeholder values in this file',
            '4. Run this test again'
        ]
    ]);
    exit;
}

// Function to send WhatsApp message via Cloud API
function testWhatsAppAPI($to, $message, $accessToken, $phoneNumberId) {
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
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    return [
        'httpCode' => $httpCode,
        'response' => $response,
        'error' => $error
    ];
}

// Send test message
echo "🧪 Testing WhatsApp Business Cloud API...\n\n";

$result = testWhatsAppAPI($testRecipient, $testMessage, $whatsappAccessToken, $phoneNumberId);

if ($result['httpCode'] == 200) {
    $response = json_decode($result['response'], true);
    echo json_encode([
        'success' => true,
        'message' => '✅ SUCCESS! WhatsApp API is working perfectly!',
        'details' => [
            'status' => 'Message sent successfully',
            'recipient' => $testRecipient,
            'messageId' => $response['messages'][0]['id'] ?? 'N/A',
            'timestamp' => date('Y-m-d H:i:s')
        ],
        'nextSteps' => [
            '1. Check your WhatsApp to confirm you received the test message',
            '2. Your booking form is ready to use the API',
            '3. Upload send-whatsapp-free.php with the same credentials',
            '4. Test your booking form'
        ]
    ], JSON_PRETTY_PRINT);
} else {
    $errorResponse = json_decode($result['response'], true);
    echo json_encode([
        'success' => false,
        'error' => 'API request failed',
        'httpCode' => $result['httpCode'],
        'details' => $errorResponse ?? $result['response'],
        'troubleshooting' => [
            'Invalid access token' => 'Double-check your access token from Meta developers',
            'Invalid phone number ID' => 'Verify your Phone Number ID from WhatsApp Business setup',
            'Recipient not found' => 'Add your phone number to test recipients in Meta dashboard',
            'Rate limit exceeded' => 'Wait a few minutes and try again',
            'CURL error' => 'Check your server\'s internet connection and SSL settings'
        ]
    ], JSON_PRETTY_PRINT);
}

// Log the test
$logData = [
    'timestamp' => date('Y-m-d H:i:s'),
    'type' => 'API_TEST',
    'result' => $result,
    'success' => $result['httpCode'] == 200
];
file_put_contents('whatsapp_test.log', json_encode($logData, JSON_PRETTY_PRINT) . "\n", FILE_APPEND);

echo "\n\n📝 Test logged to whatsapp_test.log";
?>