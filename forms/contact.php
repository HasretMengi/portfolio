<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '..\vendor\autoload.php';

// Load environment variables from .env file
$envFile = '..\.env';
if (file_exists($envFile)) {
    $env = parse_ini_file($envFile);
    foreach ($env as $key => $value) {
        putenv("$key=$value");
    }
}
// Définir votre adresse e-mail administrative
$to = getenv('EMAIL');
$senderEmail = getenv('EMAIL');

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $name = $_POST["name"];
    $email = $_POST["email"];
    $subject = $_POST["subject"];
    $message = $_POST["message"];

    // Créer une instance de PHPMailer
    $mail = new PHPMailer(true);

    // Configuration du serveur SMTP
    $mail->isSMTP();
    $mail->Host = "smtp.office365.com";
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->SMTPAuth = true;
    $mail->Username = $to;
    $mail->Password = getenv('PW');

    // Réglage de l'expéditeur et du destinataire
    $mail->setFrom($senderEmail, $name);
    $mail->addAddress($to);
    $mail->addReplyTo($email, $name);

    // Contenu de l'e-mail
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = "<h2>Nouveau message sur le portfolio</h2>
           <p><strong>Nom:</strong> $name</p>
           <p><strong>Email:</strong> $email</p>
           <p><strong>Sujet:</strong> $subject</p>
           <p><strong>Message:</strong></p>
           <p>$message</p>";

    try {
        // Envoi de l'e-mail
        $mail->send();
        echo "OK";
    } catch (Exception $e) {
        echo "Sorry, could not send the message."; // Custom error message
    }
}
