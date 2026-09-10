<?php

require '../config/auth.php';
exigirLogin();
require '../config/database.php';

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {

    try {

        $stmt = $pdo->prepare("DELETE FROM livros WHERE id = ?");
        $stmt->execute([$id]);

    } catch (PDOException $e) {

        die("Não foi possível eliminar este livro. Verifique se ele possui empréstimos associados.");

    }
}

header('Location: listar.php');
exit;