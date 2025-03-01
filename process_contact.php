<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    // Set the recipient's email as the user's email (the one they input in the form)
    $to = $email; // Send email to the email the user provides
    $subject = "Thank you for contacting us!"; // Subject for the confirmation email
    $headers = "From: no-reply@yourdomain.com\r\n"; // Replace with your new email address
    $headers .= "Reply-To: no-reply@yourdomain.com\r\n"; // Replace with your new email address
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Email body content
    $body = "Hello " . $name . ",\n\n";
    $body .= "Thank you for reaching out to us. Here are the details of your message:\n\n";
    $body .= "Subject: " . $subject . "\n\n";
    $body .= "Message:\n" . $message . "\n\n";
    $body .= "We will get back to you shortly.\n\n";
    $body .= "Best regards,\nThe STD Notification Service Team";

    // Send email to the user
    if (mail($to, $subject, $body, $headers)) {
        echo "Confirmation email sent to your address!";
    } else {
        echo "There was an error sending the confirmation email. Please try again.";
    }

    // Send an email to you (admin)
    $admin_email = "admin@yourdomain.com"; // Replace with your own admin email
    $admin_subject = "New Contact Form Submission: " . $subject;
    $admin_headers = "From: " . $email . "\r\n"; // Sender's email
    $admin_headers .= "Reply-To: " . $email . "\r\n"; // Reply-to address is the user's email
    $admin_headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Admin email body content
    $admin_body = "You have received a new message from the contact form.\n\n";
    $admin_body .= "Name: " . $name . "\n";
    $admin_body .= "Email: " . $email . "\n";
    $admin_body .= "Subject: " . $subject . "\n\n";
    $admin_body .= "Message:\n" . $message . "\n";

    // Send email to admin (you)
    mail($admin_email, $admin_subject, $admin_body, $admin_headers);
}
?>

