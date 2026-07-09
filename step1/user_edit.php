<?php
require_once __DIR__ . '/includes/admin_check.php';
require_once __DIR__ . '/classes/User.php';

$user = new User();
$errors = [];

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$userData = $user->findById($id);

if (!$userData) {
    header('Location: admin.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $isAdmin = isset($_POST['is_admin']);

    if ($username === '') {
        $errors[] = "Le nom d'utilisateur est requis.";
    }

    if ($email === '') {
        $errors[] = "L'adresse e-mail est requise.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'adresse e-mail n'est pas valide.";
    }

    if (empty($errors)) {
        $user->update($id, $username, $email, $isAdmin);
        header('Location: admin.php');
        exit;
    }

    $userData['username'] = $username;
    $userData['email'] = $email;
    $userData['is_admin'] = $isAdmin ? 1 : 0;
}

?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un utilisateur</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Modifier l'utilisateur</h1>

    <?php if (!empty($errors)): ?>
        <ul class="errors">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form action="user_edit.php?id=<?= $id ?>" method="post">
        <input type="hidden" name="id" value="<?= $id ?>">

        <label for="username">Nom d'utilisateur</label>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($userData['username']) ?>">

        <label for="email">Adresse e-mail</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($userData['email']) ?>">

        <label for="is_admin">
            <input type="checkbox" id="is_admin" name="is_admin" <?= $userData['is_admin'] ? 'checked' : '' ?>>
            Administrateur
        </label>

        <button type="submit">Enregistrer</button>
    </form>
</body>
</html>






