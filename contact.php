<?php
// Configuration
$twilioSid = getenv('ACc63b267f9d7f98c6bfe696177de0bf6e');
$twilioAuthToken = getenv('f32f067099a59cb9f4b7d5a63f7d0baa');
$twilioMessagingServiceSid = getenv('MG0c347703127d25c193efa2406254c738');
$twilioPhone = getenv('+18553253928');

// Function to get test centers using OpenStreetMap (Nominatim)
function getTestCenters($location) {
    $url = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($location . " STD Testing Centers") . "&addressdetails=1";

    // Set User-Agent to avoid 403 Forbidden error
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

    // Parse the results (limit to 3 centers)
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

    // Prepare message body
    $messageBody = "Hello,\n\nYou have an important health message that may need action.\n\nNearby STD Test Centers:\n" . implode("\n", $centers) . "\n\nVisit AwareLink for more details.";

    // Twilio API URL
    $url = "https://api.twilio.com/2010-04-01/Accounts/$twilioSid/Messages.json";

    // cURL request
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

// Process form submission
$result = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

            foreach ($phoneNumbers as $number) {
                $number = trim($number);
                if (!empty($number)) {
                    $response = sendSms($number, $centers);
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
    <title>STD Test Center Finder</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <a href="index.php"><img src="LogoHeader.png" alt="Logo" height=50 width=150></a>
        <h1>STD Test Center Finder</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="Learn.php">Get the Facts</a></li>
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

        <h2>Find STD Testing Centers Near You</h2>
        <p>Enter your location and phone number(s) to receive information about nearby STD testing centers.</p>

        <form id="sms-form" method="post" action="contact.php">
            <label for="location">Your Location:</label>
            <input type="text" id="location" name="location" placeholder="City, State or ZIP Code" required>

            <label for="phone_numbers">Phone Number(s):</label>
            <input type="text" id="phone_numbers" name="phone_numbers" placeholder="Separate multiple numbers with commas" required>

            <button type="submit">Find Testing Centers</button>
        </form>
    </section>
    <footer>
        <p>&copy; 2025 STD Notification Service - AwareLink. All rights reserved.</p>
    </footer>
</body>
</html>
