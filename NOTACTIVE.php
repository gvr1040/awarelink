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
        <a href="index.php"><img src="LogoHeader.png" alt="Logo" height=50 width=150></a>
        <h1>STD Status Verification Form</h1>
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
        <h2> Please provide proof of your STD status below.</h2>
        <form id="proof-form" action="submit-proof.php" method="post" enctype="multipart/form-data">
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required>

            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" required>

            <label for="proof">Upload Proof (PDF/Image):</label>
            <input type="file" id="proof" name="proof" accept=".jpg,.jpeg,.png,.pdf">

            <button type="submit">Submit Proof</button>
        </form>
    </section>
    <section>
        <h2> "What should I submit for proof?"</h2>
        <p> Please submit an official version of an STD lab test that has at least one positive result. The document should be from a test taken within the last 3 months. </p>
    </section>
    <footer>
        <p>&copy; 2025 STD Notification Service - AwareLink. All rights reserved.</p>
    </footer>
</body>
</html>
