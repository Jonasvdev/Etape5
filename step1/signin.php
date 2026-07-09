<?php
session_start();

require_once __DIR__ . '/classes/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $errors = [];

    if ($email === '') {
        $errors[] = "L'adresse e-mail est requise.";
    }

    if ($password === '') {
        $errors[] = "Le mot de passe est requis.";
    }

    if (empty($errors)) {

        $user = new User();
        $userData = $user->findByEmail($email);

        if (!$userData || !$user->verifyPassword($password, $userData['password'])) {
            $errors[] = "Identifiant ou mot de passe incorrect.";
        }
    }

    if (!empty($errors)) {
        $_SESSION['signin_errors'] = $errors;
        header('Location: signin.php');
        exit;
    }

    $_SESSION['user_id'] = $userData['id'];
    $_SESSION['username'] = $userData['username'];
    $_SESSION['is_admin'] = $userData['is_admin'];
    header('Location: admin.php');
    exit;
}

$errors = $_SESSION['signin_errors'] ?? [];
unset($_SESSION['signin_errors']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Connexion</h1>

    <?php if (!empty($errors)): ?>
        <ul class="errors">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form action="signin.php" method="post">
        <label for="email">Adresse e-mail</label>
        <input type="email" id="email" name="email">

        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password">

        <button type="submit">Se connecter</button>
    </form>

    // LIEN DE Modification de mot de passe oublié

    <a href="forgot_password.php">Mot de passe oublié ?</a>


</body>
</html>





