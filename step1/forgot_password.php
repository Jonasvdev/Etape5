<?php
require_once __DIR__ . '/classes/User.php';
require_once __DIR__ . '/classes/PasswordReset.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if ($email === '') {
        $errors[] = "L'adresse e-mail est requise.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'adresse e-mail n'est pas valide.";
    }

    if (empty($errors)) {
        $user = new User();
        $userData = $user->findByEmail($email);

        if ($userData) {
            $reset = new PasswordReset();
            $token = $reset->createToken($userData['id']);
            $resetLink = 'https://' . $_SERVER['HTTP_HOST'] . '/reset_password.php?token=' . $token;

            $subject = 'Réinitialisation de votre mot de passe';
            $message = "Cliquez sur ce lien pour réinitialiser votre mot de passe :\n$resetLink\n\nCe lien expire dans 1 heure.";
            $headers = 'From: no-reply@' . $_SERVER['HTTP_HOST'];

            mail($email, $subject, $message, $headers);
        }

        $success = true;
    }
}

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Mot de passe oublié</h1>

    <?php if ($success): ?>

        <p>Si cette adresse e-mail existe dans notre système, un lien de réinitialisation vient de vous être envoyé.</p>

    <?php else: ?>
        <?php if (!empty($errors)): ?>
            <ul class="errors">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form action="forgot_password.php" method="post">

            <label for="email">Adresse e-mail</label>
            <input type="email" id="email" name="email">
            <button type="submit">Envoyer</button>

        </form>
    <?php endif; ?>

    <a href="signin.php">Retour à la connexion</a>
</body>
</html>




