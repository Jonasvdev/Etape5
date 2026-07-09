<?php
require_once __DIR__ . '/includes/admin_check.php';
require_once __DIR__ . '/classes/User.php';
require_once __DIR__ . '/classes/Product.php';
require_once __DIR__ . '/classes/Category.php';

$user = new User();
$product = new Product();
$category = new Category();

$users = $user->getAll();
$products = $product->getAll();
$categories = $category->getAll();

?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Interface d'administration</h1>
    <a href="index.php">Retour à l'accueil</a>


    // section des utilisateurs


    <section>
        <h2>Utilisateurs</h2>
        <table>
            <thead>
                <tr>
                    <th>Nom d'utilisateur</th>
                    <th>E-mail</th>
                    <th>Administrateur</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= $user['is_admin'] ? 'Oui' : 'Non' ?></td>
                    <td>
                        <a href="user_edit.php?id=<?= $user['id'] ?>">Modifier</a>
                        <form action="user_delete.php" method="post">
                            <input type="hidden" name="id" value="<?= $user['id'] ?>">
                            <button type="submit">Supprimer</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>


    // section des catégories 

       <section>
    <h2>Catégories</h2>
    <a href="category_add.php">Ajouter une catégorie</a>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Catégorie parente</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category): ?>
            <tr>
                <td><?= htmlspecialchars($category['name']) ?></td>
                <td>
                    <?php
                    $parent = $category['parent_id'] ? $category->findById($category['parent_id']) : null;
                    echo $parent ? htmlspecialchars($parent['name']) : '—';
                    ?>
                </td>
                <td>
                    <a href="category_edit.php?id=<?= $category['id'] ?>">Modifier</a>
                    <form action="category_delete.php" method="post">
                        <input type="hidden" name="id" value="<?= $category['id'] ?>">
                        <button type="submit">Supprimer</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>


// section des produits


    <section>
        <h2>Produits</h2>
        <a href="product_add.php">Ajouter un produit</a>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prix</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= htmlspecialchars($product['price']) ?> €</td>
                    <td>
                        <a href="product_detail.php?id=<?= $product['id'] ?>">Voir</a>
                        <a href="product_edit.php?id=<?= $product['id'] ?>">Modifier</a>
                        <form action="product_delete.php" method="post">
                            <input type="hidden" name="id" value="<?= $product['id'] ?>">
                            <button type="submit">Supprimer</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
    </section>
</body>
</html>



