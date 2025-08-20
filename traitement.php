<?php
// Inclure PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Charger les fichiers de PHPMailer
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Connexion base de données
$conn = new mysqli("localhost", "root", "", "eco_buulding");
if ($conn->connect_error) {
    die("Échec connexion : " . $conn->connect_error);
}

// Récupération des données
$nom = htmlspecialchars($_POST['nom']);
$prenom = htmlspecialchars($_POST['prenom']);
$email = htmlspecialchars($_POST['email']);
$telephone = htmlspecialchars($_POST['telephone']);
$message = htmlspecialchars($_POST['message']);

// Enregistrer en base
$stmt = $conn->prepare("INSERT INTO contacts (nom, prenom, email, telephone, message) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $nom, $prenom, $email, $telephone, $message);
$stmt->execute();
$stmt->close();

// Envoi e-mail
$mail = new PHPMailer(true);

try {
    // Paramètres SMTP (exemple avec Gmail)
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';           // serveur SMTP
    $mail->SMTPAuth = true;
    $mail->Username = 'alexbisui89@gmail.com';   // TON adresse Gmail
    $mail->Password = 'uuyrlnsjrowsztxs';        // mot de passe d’application (voir en bas)
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Destinataire
    $mail->setFrom($email, $nom);             // expéditeur (l'utilisateur)
    $mail->addAddress('alexbisui89@gmail.com');  // destinataire (toi)

    // Contenu du mail
    $mail->isHTML(true);
    $mail->Subject = "Nouveau message";
    $mail->Body = "Nom      : $nom\n" . 
              "Prénom   : $prenom\n" .
              "Email    : $email\n" .
              "Téléphone: $telephone\n\n" .
              "Message  :\n$message";

    $mail->send();
    echo "Message envoyé et enregistré avec succès !";
} catch (Exception $e) {
    echo "Erreur lors de l'envoi du mail : " . $mail->ErrorInfo;
}

$conn->close();
?>
