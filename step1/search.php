

<?php
session_start();
require_once __DIR__ . '/classes/Product.php';
require_once __DIR__ . '/classes/Category.php';

$product = new Product();
$category = new Category();

$name = trim($_GET['name'] ?? '');
$categoryId = isset($_GET['category_id']) && $_GET['category_id'] !== '' ? (int)$_GET['category_id'] : null;
$minPrice = isset($_GET['min_price']) && $_GET['min_price'] !== '' ? (float)$_GET['min_price'] : null;
$maxPrice = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? (float)$_GET['max_price'] : null;
$sort = $_GET['sort'] ?? 'name_asc';

$results = $product->search($name, $categoryId, $minPrice, $maxPrice, $sort);
$categories = $category->getAll();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche de produits</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Recherche de produits</h1>
    <a href="index.php">Retour à l'accueil</a>

    <form action="search.php" method="get">
        <label for="name">Nom du produit</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($name) ?>">

        <label for="category_id">Catégorie</label>
        <select id="category_id" name="category_id">

            <option value="">Toutes</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category['id'] ?>" <?= $categoryId === (int)$category['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($category['name']) ?>
                </option>

            <?php endforeach; ?>

        </select>

        <label for="min_price">Prix minimum</label>
        <input type="number" id="min_price" name="min_price" step="0.01" min="0" value="<?= htmlspecialchars((string)($minPrice ?? '')) ?>">

        <label for="max_price">Prix maximum</label>
        <input type="number" id="max_price" name="max_price" step="0.01" min="0" value="<?= htmlspecialchars((string)($maxPrice ?? '')) ?>">

        <label for="sort">Trier par</label>
        
        <select id="sort" name="sort">
            <option value="name_asc" <?= $sort === 'name_asc' ? 'selected' : '' ?>>Nom (A → Z)</option>
            <option value="name_desc" <?= $sort === 'name_desc' ? 'selected' : '' ?>>Nom (Z → A)</option>
            <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Prix croissant</option>
            <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Prix décroissant</option>
        </select>

        <button type="submit">Rechercher</button>
    </form>

    <?php if (empty($results)): ?>
        <p>Aucun produit ne correspond à votre recherche.</p>
    <?php else: ?>
        <ul class="product-list">
            <?php foreach ($results as $p): ?>
            <li class="product-card">
                <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy" width="300" height="300">
                <h2><?= htmlspecialchars($p['name']) ?></h2>
                <p><?= htmlspecialchars($p['description']) ?></p>
                <p class="price"><?= htmlspecialchars($p['price']) ?> €</p>
            </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>



