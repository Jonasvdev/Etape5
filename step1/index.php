<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Bienvenue sur mon site professionnel</h1>
    </header>

     <nav>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="services.php">Services</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="login.php">Connexion</a></li>
        </ul>
    </nav>

    <?php if (isset($_SESSION['user_id'])): ?> // session de connexion active
        
        <p>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?> !</p>
        <p><a href="admin.php">Accéder à l'administration</a></p> 

        <form action="logout.php" method="post">
            <button type="submit">Se déconnecter</button>
        </form>
    <?php else: ?>
        <a href="signin.php">Se connecter</a>
    <?php endif; ?>


     <footer>
        <p>&copy; <?php echo date('Y'); ?> Mon Site Professionnel</p>
    </footer>
</body>
</html>