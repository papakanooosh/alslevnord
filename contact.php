<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'config.php';
require 'PHPMailer.php';
require 'SMTP.php';
require 'Exception.php';

// Honeypot — hvis feltet er udfyldt, er det en bot
if (!empty($_POST['website'])) {
    header('Location: index.html?kontakt=ok#kontakt');
    exit;
}

// Hent og sanitér input
$name    = isset($_POST['name'])    ? strip_tags(trim($_POST['name']))    : '';
$email   = isset($_POST['email'])   ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
$message = isset($_POST['message']) ? strip_tags(trim($_POST['message'])) : '';

// Validering
if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: index.html?kontakt=fejl#kontakt');
    exit;
}

// Send via PHPMailer
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'send.one.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = SMTP_USER;
    $mail->Password   = SMTP_PASS;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->CharSet    = 'UTF-8';

    $mail->setFrom('mail@alslevnord.dk', 'Alslevnord.dk');
    $mail->addAddress('mail@alslevnord.dk');
    $mail->addReplyTo($email, $name);

    $mail->Subject = "Besked fra alslevnord.dk: $name";
    $mail->Body    = "Navn: $name\nEmail: $email\n\nBesked:\n$message";

    $mail->send();
    header('Location: index.html?kontakt=ok#kontakt');
} catch (Exception $e) {
    header('Location: index.html?kontakt=fejl#kontakt');
}
exit;
