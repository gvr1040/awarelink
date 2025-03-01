<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $name = htmlspecialchars($_POST['name']);
  $email = htmlspecialchars($_POST['email']);
  $subject = htmlspecialchars($_POST['subject']);
  $message = htmlspecialchars($_POST['message']);

  
  $to = "recipient@example.com"; // Change this to the recipient's email
  $subject = "New Contact Form Submission: " . $subject;
  $headers = "From: " . $email . "\r\n";
  $headers .= "Reply-To: " . $email . "\r\n";
  $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";


  $body = "You have received a new message from the contact form.\n\n";
  $body .= "Name: " . $name . "\n";
  $body .= "Email: " . $email . "\n";
  $body .= "Subject: " . $subject . "\n\n";
  $body .= "Message:\n" . $message . "\n";

  if (mail($to, $subject, $body, $headers)) {
          echo "Message sent successfully!";
      } else {
          echo "There was an error sending the message. Please try again.";
      }
  }
?>