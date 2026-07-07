
// Vérifie si l'utilisateur est connecté, sinon redirige vers la page de connexion

<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: signin.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Interface d'Administration</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <h1>Bienvenue, <?= htmlspecialchars($_SESSION['username']) ?></h1>
    <p>Ceci est l'interface d'administration.</p>

</body>
</html>























