<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit STD Proof</title>
      <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <a href="homepage.php"><img src="LogoHeader.png" alt="Logo" height=50 width=150></a>
        <h1>STD Status Verification Form</h1>
        <nav>
            <ul>
                <li><a href="homepage.php">Home</a></li>
                <li><a href="index.php">Start a Form</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Get Tested</a></li>
                <li><a href="Learn.php">Get the Facts</a></li>
            </ul>
        </nav>
    </header>
    <section>
        <h2> Please provide your information below.</h2>
        <p>This is the verification process for the notification program. After you are verified, we will help you anonymously notify the individuals you choose to know about your status.</p>
        <form id="proof-form" action="submit-proof.php" method="post">
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required>

            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" required>

            <label for="clinic">Clinic / Test Provider Company Name:</label>
            <input type="text" id="clinic" name="clinic" required>

            <label for="address">Clinic Address (if applicable):</label>
            <input type="text" id="address" name="address" required>

            <label for="city">City (if applicable):</label>
            <input type="text" id="city" name="city" required>

            <label for="state">State (if applicable):</label>
            <input type="text" id="state" name="state" required>
            
            <sub>By submitting you agree that you are consenting to AwareLink's <a href="privacy_policy.php">privacy policy</a> and that you are 18 years or                         older.</sub>
            <button type="submit">Submit Information</button>
        </form>
        
    </section>
    <footer>
        <p>&copy; 2025 STD Notification Service - AwareLink. All rights reserved.</p>
        <p><a href="privacy_policy.php">Privacy Policy</a></p>

    </footer>

   
    <script>
        const form = document.getElementById('proof-form');
        form.onsubmit = function(event) {
            event.preventDefault(); 
            alert("Thank you for submitting your information.");
            form.reset(); 
        }
    </script>
</body>
</html>
