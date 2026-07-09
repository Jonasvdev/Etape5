<?php

// LA PAGE D'HISTORIQUE DES PRODUITS VUS

session_start();
require_once __DIR__ . '/classes/Product.php';

$product = new Product();
$historyIds = $_SESSION['history'] ?? [];

$products = [];

foreach ($historyIds as $id) {
    $p = $product->findById($id);
    if ($p) {
        $products[] = $p;
    }
}


?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produits consultés récemment</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <h1>Produits consultés récemment</h1>
    <a href="index.php">Retour à l'accueil</a>

    <?php if (empty($products)): ?>
        <p>Vous n'avez consulté aucun produit pour le moment.</p>
    <?php else: ?>

        <ul class="product-list">
            <?php foreach ($products as $p): ?>
            <li class="product-card">
                <a href="product_view.php?id=<?= $p['id'] ?>">
                    <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy" width="300" height="300">
                    <h2><?= htmlspecialchars($p['name']) ?></h2>
                    <p class="price"><?= htmlspecialchars($p['price']) ?> €</p>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
        
    <?php endif; ?>
</body>
</html>




