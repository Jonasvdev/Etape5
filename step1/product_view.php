<?php

//  hISTORIQUE DES PRODUITS VUS

session_start();
require_once __DIR__ . '/classes/Product.php';

$id = (int)($_GET['id'] ?? 0);
$product = new Product();
$productData = $product->findById($id);

if (!$productData) {
    header('Location: index.php');
    exit;
}

if (!isset($_SESSION['history'])) {
    $_SESSION['history'] = [];
}

$_SESSION['history'] = array_diff($_SESSION['history'], [$id]);

array_unshift($_SESSION['history'], $id);

$_SESSION['history'] = array_slice($_SESSION['history'], 0, 10);


?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($productData['name']) ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <a href="index.php">Retour à l'accueil</a>

    <h1><?= htmlspecialchars($productData['name']) ?></h1>
    <img src="<?= htmlspecialchars($productData['image']) ?>" alt="<?= htmlspecialchars($productData['name']) ?>" width="400" height="400">
    <p><?= htmlspecialchars($productData['description']) ?></p>
    <p class="price"><?= htmlspecialchars($productData['price']) ?> €</p>

    <section>

        <h2>Partager ce produit</h2>

        <?php
        $productUrl = 'https://' . $_SERVER['HTTP_HOST'] . '/product_view.php?id=' . $productData['id'];
        $productName = urlencode($productData['name']);
        $encodedUrl = urlencode($productUrl);
        ?>


        <ul class="share-list">
            <li><a href="mailto:?subject=<?= $productName ?>&body=<?= $encodedUrl ?>">E-mail</a></li>
            <li><a href="https://www.facebook.com/sharer/sharer.php?u=<?= $encodedUrl ?>" target="_blank" rel="noopener noreferrer">Facebook</a></li>
            <li><a href="https://twitter.com/intent/tweet?url=<?= $encodedUrl ?>&text=<?= $productName ?>" target="_blank" rel="noopener noreferrer">Twitter</a></li>
        </ul>


    </section>
</body>
</html>




