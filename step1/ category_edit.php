<?php
require_once __DIR__ . '/includes/admin_check.php';
require_once __DIR__ . '/classes/Category.php';

$category = new Category();
$errors = [];

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$categoryData = $category->findById($id);

if (!$categoryData) {
    header('Location: admin.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $parentId = $_POST['parent_id'] ?? '';

    if ($name === '') {
        $errors[] = "Le nom de la catégorie est requis.";
    }

    $parentIdValue = $parentId === '' ? null : (int)$parentId;

    if ($parentIdValue === $id) {
        $errors[] = "Une catégorie ne peut pas être sa propre catégorie parente.";
    }

    if (empty($errors)) {
        $category->update($id, $name, $parentIdValue);
        header('Location: admin.php');
        exit;
    }

    $categoryData['name'] = $name;
    $categoryData['parent_id'] = $parentIdValue;
}

$categories = array_filter($category->getAll(), fn($c) => $c['id'] !== $id);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier la catégorie</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Modifier la catégorie</h1>

    <?php if (!empty($errors)): ?>
        <ul class="errors">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form action="category_edit.php?id=<?= $id ?>" method="post">
        <input type="hidden" name="id" value="<?= $id ?>">

        <label for="name">Nom</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($categoryData['name']) ?>">

        <label for="parent_id">Catégorie parente (optionnel)</label>
        <select id="parent_id" name="parent_id">
            <option value="">Aucune (catégorie racine)</option>
            <?php foreach ($categories as $c): ?>
                <option value="<?= $c['id'] ?>" <?= (string)$categoryData['parent_id'] === (string)$c['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Enregistrer</button>
    </form>
</body>
</html>


