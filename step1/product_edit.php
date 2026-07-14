<?php
require_once __DIR__ . '/includes/admin_check.php';
require_once __DIR__ . '/classes/Product.php';

$product = new Product();
$errors = [];

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$productData = $product->findById($id);

if (!$productData) {
    header('Location: admin.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = trim($_POST['price'] ?? '');

    if ($name === '') {
        $errors[] = "Le nom du produit est requis.";
    }

    if ($description === '') {
        $errors[] = "La description est requise.";
    }

    if ($price === '' || !is_numeric($price) || $price < 0) {
        $errors[] = "Le prix doit être un nombre positif.";
    }

    $newImagePath = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = "Erreur lors de l'envoi de l'image.";
            
        } else {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            $fileType = mime_content_type($_FILES['image']['tmp_name']);

            if (!in_array($fileType, $allowedTypes, true)) {
                $errors[] = "L'image doit être au format JPEG, PNG ou WebP.";

            } else {
                $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $imageName = uniqid('product_', true) . '.' . $extension;
                move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/uploads/' . $imageName);
                $newImagePath = 'uploads/' . $imageName;
            }
        }
    }

    if (empty($errors)) {
        $product->update($id, $name, $description, (float)$price, $newImagePath);
        header('Location: admin.php');
        exit;
    }

    $productData['name'] = $name;
    $productData['description'] = $description;
    $productData['price'] = $price;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le produit</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Modifier le produit</h1>

    <?php if (!empty($errors)): ?>
        <ul class="errors">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <img src="<?= htmlspecialchars($productData['image']) ?>" alt="<?= htmlspecialchars($productData['name']) ?>">

    <form action="product_edit.php?id=<?= $id ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $id ?>">

        <label for="name">Nom</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($productData['name']) ?>">

        <label for="description">Description</label>
        <textarea id="description" name="description"><?= htmlspecialchars($productData['description']) ?></textarea>

        <label for="price">Prix</label>
        <input type="text" id="price" name="price" value="<?= htmlspecialchars($productData['price']) ?>">

        <label for="image">Nouvelle image (optionnel)</label>
        <input type="file" id="image" name="image" accept="image/jpeg, image/png, image/webp">

        <button type="submit">Enregistrer</button>
    </form>
</body>
</html>



