
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
        <h1>Submit Your Proof of STD Status</h1>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
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
        <p>&copy; 2025 STD Notification Service - Aware Link. All rights reserved.</p>
    </footer>
</body>
</html>
