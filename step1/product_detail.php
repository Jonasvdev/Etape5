// Base de la page de modification du produit

<?php
require_once __DIR__ . '/includes/admin_check.php';
require_once __DIR__ . '/classes/Product.php';

$id = (int)($_GET['id'] ?? 0);

$product = new Product();
$productData = $product->findById($id);

if (!$productData) {
    header('Location: admin.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($productData['name']) ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1><?= htmlspecialchars($productData['name']) ?></h1>
    <img src="<?= htmlspecialchars($productData['image']) ?>" alt="<?= htmlspecialchars($productData['name']) ?>">
    <p><?= htmlspecialchars($productData['description']) ?></p>
    <p><?= htmlspecialchars($productData['price']) ?> €</p>
    <a href="product_edit.php?id=<?= $productData['id'] ?>">Modifier</a>
    <a href="admin.php">Retour</a>
</body>
</html>






