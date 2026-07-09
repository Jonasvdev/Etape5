<?php
require_once __DIR__ . '/includes/admin_check.php';
require_once __DIR__ . '/classes/Category.php';

$category = new Category();
$errors = [];
$old = ['name' => '', 'parent_id' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $parentId = $_POST['parent_id'] ?? '';
    $old = ['name' => $name, 'parent_id' => $parentId];

    if ($name === '') {
        $errors[] = "Le nom de la catégorie est requis.";
    }

    $parentIdValue = $parentId === '' ? null : (int)$parentId;

    if (empty($errors)) {
        $category->create($name, $parentIdValue);
        header('Location: admin.php');
        exit;
    }
}
 $categories = $category->getAll();

 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une catégorie</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Ajouter une catégorie</h1>

    <?php if (!empty($errors)): ?>
        <ul class="errors">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form action="category_add.php" method="post">
        <label for="name">Nom</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($old['name']) ?>">

        <label for="parent_id">Catégorie parente (optionnel)</label>
        <select id="parent_id" name="parent_id">
            <option value="">Aucune (catégorie racine)</option>

            <?php foreach ($categories as $c): ?>
                <option value="<?= $c['id'] ?>" <?= (string)$old['parent_id'] === (string)$c['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Ajouter</button>
    </form>
</body>
</html>


