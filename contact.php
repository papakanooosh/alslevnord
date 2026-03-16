<?php
// Modtager-email
$to = 'mail@alslevnord.dk';

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

// Byg email
$subject = "Besked fra alslevnord.dk: $name";
$body    = "Navn: $name\n";
$body   .= "Email: $email\n\n";
$body   .= "Besked:\n$message\n";

$headers  = "From: noreply@alslevnord.dk\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Send
if (mail($to, $subject, $body, $headers)) {
    header('Location: index.html?kontakt=ok#kontakt');
} else {
    header('Location: index.html?kontakt=fejl#kontakt');
}
exit;
