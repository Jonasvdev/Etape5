<?php
require_once __DIR__ . '/includes/admin_check.php';
require_once __DIR__ . '/classes/Product.php';
require_once __DIR__ . '/classes/Category.php';

$category = new Category();
$categories = $category->getAll();

$errors = [];
$old = ['name' => '', 'description' => '', 'price' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $old = ['name' => $name, 'description' => $description, 'price' => $price];

    if ($name === '') {
        $errors[] = "Le nom du produit est requis.";
    }

    if ($description === '') {
        $errors[] = "La description est requise.";
    }

    if ($price === '' || !is_numeric($price) || $price < 0) {
        $errors[] = "Le prix doit être un nombre positif.";
    }

    if (!isset($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
        $errors[] = "Une image est requise.";
        
    } elseif ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "Erreur lors de l'envoi de l'image.";

    } else {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        $fileType = mime_content_type($_FILES['image']['tmp_name']);

        if (!in_array($fileType, $allowedTypes, true)) {
            $errors[] = "L'image doit être au format JPEG, PNG ou WebP.";
        }
    }

    if (empty($errors)) {
        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = uniqid('product_', true) . '.' . $extension;
        move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/uploads/' . $imageName);

        $product = new Product();
        $product->create($name, $description, (float)$price, 'uploads/' . $imageName);

        header('Location: admin.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un produit</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Ajouter un produit</h1>

    <?php if (!empty($errors)): ?>
        <ul class="errors">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form action="product_add.php" method="post" enctype="multipart/form-data">
        <label for="name">Nom</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($old['name']) ?>">

        <label for="description">Description</label>
        <textarea id="description" name="description"><?= htmlspecialchars($old['description']) ?></textarea>

        <label for="price">Prix</label>
        <input type="text" id="price" name="price" value="<?= htmlspecialchars($old['price']) ?>">

        <label for="image">Image</label>
        <input type="file" id="image" name="image" accept="image/jpeg, image/png, image/webp">

        <button type="submit">Ajouter</button>
    </form>
</body>
</html>





