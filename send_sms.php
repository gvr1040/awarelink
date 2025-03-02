<?php
// Configuration
$twilioSid = getenv('TWILIO_SID');
$twilioAuthToken = getenv('TWILIO_AUTH_TOKEN');
$twilioPhone = getenv('TWILIO_PHONE');

// Function to get test centers from OpenStreetMap using Nominatim API
function getTestCenters($location) {
    // OpenStreetMap's Nominatim API endpoint
    $url = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($location) . "&addressdetails=1";

    // Fetch the API response
    $response = file_get_contents($url);

    // Check for failed request
    if ($response === false) {
        return ['error' => 'Failed to fetch data from OpenStreetMap API.'];
    }

    $data = json_decode($response, true);

    // Check if results are available
    if (empty($data)) {
        return ['error' => 'No results found for the given location.'];
    }

    // Parse and collect the results
    $centers = [];
    foreach ($data as $place) {
        // You can modify this to search specifically for STD test centers, hospitals, etc.
        if (isset($place['type']) && $place['type'] == 'amenity' && isset($place['address']['hospital'])) {
            $centers[] = $place['display_name'] . " - " . (isset($place['address']['road']) ? $place['address']['road'] : 'Unknown address');
        }
    }

    // Return top 3 results or fewer if not available
    return array_slice($centers, 0, 3);
}

// Function to send SMS using Twilio API
function sendSms($phoneNumber, $message) {
    global $twilioSid, $twilioAuthToken, $twilioPhone;

    // Initialize Twilio API request
    $url = "https://api.twilio.com/2010-04-01/Accounts/" . $twilioSid . "/Messages.json";
    $data = array(
        'From' => $twilioPhone,
        'To' => $phoneNumber,
        'Body' => $message
    );

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_USERPWD, $twilioSid . ":" . $twilioAuthToken);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

// Process form submission
$result = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
    if (empty($_POST['location']) || empty($_POST['phone_numbers'])) {
        $result['message'] = 'Please provide both location and phone numbers.';
    } else {
        $location = $_POST['location'];
        $phoneNumbers = explode(',', $_POST['phone_numbers']);

        // Get test centers from OpenStreetMap
        $centers = getTestCenters($location);

        if (isset($centers['error'])) {
            $result['message'] = $centers['error'];
        } elseif (empty($centers)) {
            $result['message'] = 'No testing centers found for this location.';
        } else {
            $sentCount = 0;
            $errorCount = 0;

            // Send SMS to each phone number
            foreach ($phoneNumbers as $number) {
                $number = trim($number);
                if (!empty($number)) {
                    // Prepare the message body
                    $messageBody = "Nearby STD Test Centers:\n" . implode("\n", $centers);
                    $response = sendSms($number, $messageBody);
                    $responseData = json_decode($response, true);

                    if (isset($responseData['sid'])) {
                        $sentCount++;
                    } else {
                        $errorCount++;
                    }
                }
            }

            if ($sentCount > 0) {
                $result['success'] = true;
                $result['message'] = "Successfully sent messages to $sentCount recipients." . 
                                    ($errorCount > 0 ? " Failed to send to $errorCount recipients." : "");
            } else {
                $result['message'] = 'Failed to send messages. Please check your settings and try again.';
            }
        }
    }
}

// Return JSON response if this is an AJAX request
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMS Sender</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>SMS Sender</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="send_sms.php">Send SMS</a></li>
            </ul>
        </nav>
    </header>
    <section>
        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
            <?php if ($result['success']): ?>
                <div class="success-message">
                    <h2>Success!</h2>
                    <p><?php echo $result['message']; ?></p>
                </div>
            <?php else: ?>
                <div class="error-message">
                    <h2>Error</h2>
                    <p><?php echo $result['message']; ?></p>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <h2>Send SMS to Multiple Recipients</h2>
        <p>Enter your message and phone number(s) to send SMS notifications about nearby STD testing centers.</p>

        <form id="sms-form" method="post" action="send_sms.php">
            <label for="location">Your Location:</label>
            <input type="text" id="location" name="location" placeholder="City, State or ZIP Code" required>

            <label for="phone_numbers">Phone Number(s):</label>
            <input type="text" id="phone_numbers" name="phone_numbers" placeholder="Separate multiple numbers with commas" required>

            <button type="submit">Send SMS</button>
        </form>
    </section>
    <footer>
        <p>&copy; 2025 SMS Notification Service. All rights reserved.</p>
    </footer>
</body>
</html>
