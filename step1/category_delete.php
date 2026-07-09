<?php
require_once __DIR__ . '/includes/admin_check.php';
require_once __DIR__ . '/classes/Category.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $category = new Category();
    $category->delete($id);
}

header('Location: admin.php');
exit;

