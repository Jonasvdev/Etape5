
// analyse requise 

<?php

session_start();  // Démarrage de la session pour gérer l'état de connexion de l'utilisateur

require_once __DIR__ . '/classes/Product.php';

$product = new Product();

$productsPerPage = 12;
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $productsPerPage;

$products = $product->getPaginated($productsPerPage, $offset);
$totalProducts = $product->count();
$totalPages = (int)ceil($totalProducts / $productsPerPage);


?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - Nos produits</title>
    <meta name="description" content="Découvrez notre catalogue de produits.">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Bienvenue sur mon portfolio professionnel</h1>

        <?php if (isset($_SESSION['user_id'])): ?>
    <?php if (!empty($_SESSION['avatar'])): ?>
        <img src="<?= htmlspecialchars($_SESSION['avatar']) ?>" alt="Mon avatar" width="40" height="40">
    <?php endif; ?>
    <a href="profile.php">Mon profil</a>
    <form action="logout.php" method="post">
        <button type="submit">Se déconnecter</button>
    </form>
    
<?php else: ?>
    <a href="signin.php">Se connecter</a>
<?php endif; ?>

            <form action="logout.php" method="post">

    // Partie de recherche de produits par catégorie

            <a href="search.php">Rechercher un produit</a>  // lien de recherche produit

            <label for="category_id">Catégorie (optionnel)</label>
<select id="category_id" name="category_id">
    <option value="">Aucune</option>
    <?php foreach ($category as $category): ?>
        
        <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
    <?php endforeach; ?>
</select>
               
    </header>

    <main>
        <h2>Nos produits</h2>

      

        <?php if (empty($products)): ?>

            <p>Aucun produit disponible pour le moment.</p>

        <?php else: ?>
            <ul class="product-list">

                <?php foreach ($products as $p): ?>

               <li class="product-card">

            // pANIER PRODUIT

    <a href="product_view.php?id=<?= $p['id'] ?>">
        <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy" width="300" height="300">
        <h3><?= htmlspecialchars($p['name']) ?></h3>
        <p><?= htmlspecialchars($p['description']) ?></p>
        <p class="price"><?= htmlspecialchars($p['price']) ?> €</p>
    </a>
</li>


                    <h3><?= htmlspecialchars($p['name']) ?></h3>
                    <p><?= htmlspecialchars($p['description']) ?></p>
                    <p class="price"><?= htmlspecialchars($p['price']) ?> €</p>
                </li>
                <?php endforeach; ?>
            </ul>

            <?php if ($totalPages > 1): ?>
            <nav aria-label="Pagination des produits">
                <ul class="pagination">
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li>
                            <a href="index.php?page=<?= $i ?>" <?= $i === $page ? 'aria-current="page"' : '' ?>><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
            <?php endif; ?>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> Mon Site Professionnel</p>
    </footer>
</body>
</html>












