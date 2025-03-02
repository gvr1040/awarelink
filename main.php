
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit STD Proof</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="logo-container">
            <a href="homepage.php"><img src="LogoHeader.png" alt="Logo" height=50 width=150></a>
        </div>
        <h1>Submit Your Proof of STD Status</h1>
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
        <form id="proof-form" action="submit-proof.php" method="post" enctype="multipart/form-data">
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" required>
            
            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" required>
            
            <label for="proof">Upload Proof (PDF/Image):</label>
            <input type="file" id="proof" name="proof" accept=".jpg,.jpeg,.png,.pdf" required>
            
            <button type="submit">Submit Proof</button>
        </form>
    </section>
    <footer>
        <p>&copy; 2025 STD Notification Service - AwareLink. All rights reserved.</p>
        <p><a href="privacy_policy.php">Privacy Policy</a></p>

    </footer>
</body>
</html>
