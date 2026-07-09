// Supprimer un utilisateur 

<?php
require_once __DIR__ . '/includes/admin_check.php';
require_once __DIR__ . '/classes/User.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $user = new User();
    $user->delete($id);
}

header('Location: admin.php');
exit;




