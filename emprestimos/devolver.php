<?php

require '../config/auth.php';
exigirLogin();

require '../config/database.php';

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: listar.php');
    exit;
}

try {

    $pdo->beginTransaction();

    // Procurar o empréstimo
    $stmt = $pdo->prepare("
        SELECT livro_id
        FROM emprestimos
        WHERE id = ?
        AND estado = 'Emprestado'
        FOR UPDATE
    ");

    $stmt->execute([$id]);

    $livro_id = $stmt->fetchColumn();

    if ($livro_id) {

        // Marcar empréstimo como devolvido
        $update = $pdo->prepare("
            UPDATE emprestimos
            SET
                data_devolucao = ?,
                estado = 'Devolvido'
            WHERE id = ?
        ");

        $update->execute([
            date('Y-m-d'),
            $id
        ]);

        // Aumentar quantidade disponível
        $livro = $pdo->prepare("
            UPDATE livros
            SET quantidade = quantidade + 1
            WHERE id = ?
        ");

        $livro->execute([$livro_id]);
    }

    $pdo->commit();

} catch (Exception $e) {

    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
}

header('Location: listar.php');
exit;

?>