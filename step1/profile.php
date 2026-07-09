<?php
session_start();
require_once __DIR__ . '/classes/User.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit;
}

$user = new User();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] === UPLOAD_ERR_NO_FILE) {
        $errors[] = "Veuillez sélectionner une image.";
    } elseif ($_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "Erreur lors de l'envoi de l'image.";
    } else {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        $fileType = mime_content_type($_FILES['avatar']['tmp_name']);

        if (!in_array($fileType, $allowedTypes, true)) {
            $errors[] = "L'image doit être au format JPEG, PNG ou WebP.";
        }
    }

    if (empty($errors)) {
        $extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
        $avatarName = 'avatar_' . $_SESSION['user_id'] . '_' . uniqid() . '.' . $extension;
        move_uploaded_file($_FILES['avatar']['tmp_name'], __DIR__ . '/uploads/' . $avatarName);

        $user->updateAvatar($_SESSION['user_id'], 'uploads/' . $avatarName);
        $_SESSION['avatar'] = 'uploads/' . $avatarName;

        header('Location: profile.php');
        exit;
    }
}

$userData = $user->findById($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon profil</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Mon profil</h1>
    <a href="index.php">Retour à l'accueil</a>

    <?php if (!empty($errors)): ?>
        <ul class="errors">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if (!empty($userData['avatar'])): ?>
        <img src="<?= htmlspecialchars($userData['avatar']) ?>" alt="Avatar de <?= htmlspecialchars($userData['username']) ?>" width="120" height="120">
    <?php endif; ?>

    <form action="profile.php" method="post" enctype="multipart/form-data">
        <label for="avatar">Changer d'avatar</label>
        <input type="file" id="avatar" name="avatar" accept="image/jpeg, image/png, image/webp">
        <button type="submit">Enregistrer</button>
    </form>
</body>
</html>




