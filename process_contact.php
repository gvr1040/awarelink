<?php
// Set up Twilio credentials
$twilioSid = getenv('TWILIO_SID');
$twilioAuthToken = getenv('TWILIO_AUTH_TOKEN');
$twilioMessagingServiceSid = getenv('TWILIO_MESSAGING_SERVICE_SID');

// Function to get STD testing centers from OpenStreetMap (Nominatim)
function getTestCenters($location) {
    $url = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($location . " STD Testing Centers") . "&addressdetails=1";

    // Set User-Agent to avoid 403 error
    $opts = [
        "http" => [
            "header" => "User-Agent: MyHealthApp/1.0\r\n"
        ]
    ];
    $context = stream_context_create($opts);

    $response = file_get_contents($url, false, $context);

    if ($response === false) {
        return ['error' => 'Failed to fetch data from OpenStreetMap.'];
    }

    $data = json_decode($response, true);

    if (empty($data)) {
        return ['error' => 'No testing centers found for this location.'];
    }

    // Extract first 3 results
    $centers = [];
    foreach (array_slice($data, 0, 3) as $place) {
        $name = $place['display_name'] ?? 'Unknown Center';
        $centers[] = $name;
    }

    return $centers;
}

// Function to send SMS using Twilio
function sendSms($phoneNumber, $centers) {
    global $twilioSid, $twilioAuthToken, $twilioMessagingServiceSid;

    $messageBody = "Hello,\n\nYou have an important health message that may need action.\n\nNearby STD Test Centers:\n" . implode("\n", $centers) . "\n\nVisit AwareLink for more details.";

    $url = "https://api.twilio.com/2010-04-01/Accounts/$twilioSid/Messages.json";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_USERPWD, "$twilioSid:$twilioAuthToken");
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        "To" => $phoneNumber,
        "MessagingServiceSid" => $twilioMessagingServiceSid,
        "Body" => $messageBody
    ]));

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

// Handle AJAX request
$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $location = $_POST['location'] ?? '';
    $phoneNumbers = $_POST['phone_numbers'] ?? '';

    if (empty($location) || empty($phoneNumbers)) {
        $response['message'] = 'Please provide both location and phone numbers.';
    } else {
        $phoneNumbers = explode(',', $phoneNumbers);
        $centers = getTestCenters($location);

        if (isset($centers['error'])) {
            $response['message'] = $centers['error'];
        } elseif (empty($centers)) {
            $response['message'] = 'No testing centers found.';
        } else {
            $sentCount = 0;
            foreach ($phoneNumbers as $number) {
                $number = trim($number);
                if (!empty($number)) {
                    $twilioResponse = sendSms($number, $centers);
                    $twilioData = json_decode($twilioResponse, true);

                    if (isset($twilioData['sid'])) {
                        $sentCount++;
                    }
                }
            }

            if ($sentCount > 0) {
                $response['success'] = true;
                $response['message'] = "Successfully sent messages to $sentCount recipients.";
            } else {
                $response['message'] = 'Failed to send messages.';
            }
        }
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
