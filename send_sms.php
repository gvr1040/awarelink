
<?php
// Configuration
$twilioSid = getenv('TWILIO_SID');
$twilioAuthToken = getenv('TWILIO_AUTH_TOKEN');
$twilioPhone = getenv('TWILIO_PHONE');
$googleMapsApiKey = getenv('GOOGLE_MAPS_API_KEY');

// Function to get test centers
function getTestCenters($location) {
    global $googleMapsApiKey;
    $url = "https://maps.googleapis.com/maps/api/place/textsearch/json?query=STD+Testing+Centers+near+".urlencode($location)."&key=".$googleMapsApiKey;
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    $centers = [];
    
    if (isset($data['results'])) {
        foreach (array_slice($data['results'], 0, 3) as $place) {
            $centers[] = $place['name'] . " - " . $place['formatted_address'];
        }
    }
    
    return $centers;
}

// Function to send SMS
function sendSms($phoneNumber, $centers) {
    global $twilioSid, $twilioAuthToken, $twilioPhone;
    
    // Prepare message body
    $messageBody = "Nearby STD Test Centers:\n" . implode("\n", $centers);
    
    // Initialize Twilio API request
    $url = "https://api.twilio.com/2010-04-01/Accounts/" . $twilioSid . "/Messages.json";
    $data = array(
        'From' => $twilioPhone,
        'To' => $phoneNumber,
        'Body' => $messageBody
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
        
        // Get test centers
        $centers = getTestCenters($location);
        
        if (empty($centers)) {
            $result['message'] = 'No testing centers found for this location.';
        } else {
            $sentCount = 0;
            $errorCount = 0;
            
            // Send SMS to each phone number
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
        <h1>STD Test Center Finder</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
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
        
        <form id="sms-form" method="post" action="send_sms.php">
            <label for="location">Your Location:</label>
            <input type="text" id="location" name="location" placeholder="City, State or ZIP Code" required>
            
            <label for="phone_numbers">Phone Number(s):</label>
            <input type="text" id="phone_numbers" name="phone_numbers" placeholder="Separate multiple numbers with commas" required>
            
            <button type="submit">Find Testing Centers</button>
        </form>
    </section>
    <footer>
        <p>&copy; 2025 STD Notification Service - Aware Link. All rights reserved.</p>
    </footer>
</body>
</html>
