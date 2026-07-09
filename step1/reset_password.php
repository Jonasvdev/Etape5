<?php
require_once __DIR__ . '/classes/User.php';
require_once __DIR__ . '/classes/PasswordReset.php';

$errors = [];
$token = $_GET['token'] ?? $_POST['token'] ?? '';

$reset = new PasswordReset();
$resetData = $reset->findValidToken($token);

if (!$resetData) {
    $errors[] = "Ce lien de réinitialisation est invalide ou a expiré.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errors)) {
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';

    if ($password === '') {
        $errors[] = "Le mot de passe est requis.";
    } elseif ($password !== $passwordConfirm) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }

    if (empty($errors)) {
        $user = new User();
        $user->updatePassword($resetData['user_id'], $password);
        $reset->deleteByUserId($resetData['user_id']);
        header('Location: signin.php');
        exit;
    }
}

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réinitialiser le mot de passe</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Réinitialiser le mot de passe</h1>

    <?php if (!empty($errors)): ?>
        <ul class="errors">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if ($resetData): ?>
        <form action="reset_password.php" method="post">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

            <label for="password">Nouveau mot de passe</label>
            <input type="password" id="password" name="password">

            <label for="password_confirm">Confirmer le mot de passe</label>
            <input type="password" id="password_confirm" name="password_confirm">

            <button type="submit">Réinitialiser</button>
        </form>
    <?php endif; ?>
</body>
</html>





