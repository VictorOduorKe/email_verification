<?php
require 'db_config.php'; // Database connection
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hashing the password
    $token = md5(uniqid(rand(), true)); // Generating a unique verification token

    // Insert user into database
    $stmt = $pdo->prepare("INSERT INTO users (username, email, pwd, token) VALUES (:username, :email, :password, :token)");
    $stmt->bindParam(":username", $name, PDO::PARAM_STR);
    $stmt->bindParam(":email", $email, PDO::PARAM_STR);
    $stmt->bindParam(":password", $password, PDO::PARAM_STR); // Fixing the missing password bindParam
    $stmt->bindParam(":token", $token, PDO::PARAM_STR);

    if ($stmt->execute()) {
        sendVerificationEmail($email, $token);
        echo "Registration successful! Check your email to verify your account.";
    } else {
        echo "Error: " . $stmt->errorInfo()[2]; // Displaying error message if SQL fails
    }
}

function sendVerificationEmail($email, $token) {
    $mail = new PHPMailer(true);
    
    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host = 'smtp.outlook.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'victoroduor723@outlook.com'; // Use a secure environment variable
        $mail->Password = 'xtkxvekxnsogspub'; // Never expose this in code
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Email content
        $mail->setFrom('victoroduorxh2@gmail.com', 'Victor Oduor');
        $mail->addAddress($email);
        $mail->Subject = 'Email Verification';
        $mail->Body = "Click the link below to verify your account:\n\n" .
                      "http://localhost/email_verification/verify.php?email=$email&token=$token";
                      $mail->SMTPDebug = 2; // Set to 2 for full debugging
                      $mail->Debugoutput = 'html';
                      
        $mail->send();
    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
}
?>
