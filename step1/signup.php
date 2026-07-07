<?php
session_start();
require_once __DIR__ . '/classes/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $errors = [];

    if ($username === '') {
        $errors[] = "Le nom d'utilisateur est requis.";
    }

    if ($email === '') {
        $errors[] = "L'adresse e-mail est requise.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'adresse e-mail n'est pas valide.";
    }

    if ($password === '') {
        $errors[] = "Le mot de passe est requis.";
    }

    if (empty($errors)) { 
        $user = new User();
        if ($user->emailExists($email)) {
            $errors[] = "Cette adresse e-mail est déjà utilisée.";
        }
    }

    if (!empty($errors)) {
        $_SESSION['signup_errors'] = $errors;
        $_SESSION['signup_old'] = ['username' => $username, 'email' => $email];
        header('Location: signup.php');
        exit;
    }

    $user->register($username, $email, $password);
    header('Location: signin.php');
    exit;
}

$errors = $_SESSION['signup_errors'] ?? [];
$old = $_SESSION['signup_old'] ?? ['username' => '', 'email' => ''];
unset($_SESSION['signup_errors'], $_SESSION['signup_old']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="css/style.css">    
</head>
<body>

    <h1>Inscription</h1>

    <?php if (!empty($errors)): ?>
        <ul class="errors">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form action="signup.php" method="post">

        <label for="username">Nom d'utilisateur</label>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($old['username']) ?>">

        <label for="email">Adresse e-mail</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($old['email']) ?>">

        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password">

        <button type="submit">S'inscrire</button>
    </form>
</body>
</html>



