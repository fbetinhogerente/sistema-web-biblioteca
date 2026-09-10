<?php
require '../config/auth.php';
exigirLogin();

require '../config/database.php';

$livros = $pdo->query("
    SELECT *
    FROM livros
    WHERE quantidade > 0
    ORDER BY titulo
")->fetchAll();

$leitores = $pdo->query("
    SELECT *
    FROM leitores
    ORDER BY nome
")->fetchAll();

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $livro_id = (int)($_POST['livro_id'] ?? 0);
    $leitor_id = (int)($_POST['leitor_id'] ?? 0);

    if ($livro_id <= 0 || $leitor_id <= 0) {
        $msg = 'Seleccione o livro e o leitor.';
    } else {

        try {

            $pdo->beginTransaction();

            // Verificar se ainda existe exemplar disponível
            $check = $pdo->prepare("
                SELECT quantidade
                FROM livros
                WHERE id = ?
                FOR UPDATE
            ");

            $check->execute([$livro_id]);

            $quantidade = $check->fetchColumn();

            if ($quantidade === false || $quantidade <= 0) {
                throw new Exception('Livro indisponível.');
            }

            // Registar empréstimo
            $stmt = $pdo->prepare("
                INSERT INTO emprestimos
                (
                    utilizador_id,
                    livro_id,
                    data_emprestimo,
                    estado
                )
                VALUES (?, ?, ?, 'Emprestado')
            ");

            $stmt->execute([
                $_SESSION['utilizador']['id'],
                $livro_id,
                date('Y-m-d')
            ]);

            // Diminuir quantidade disponível
            $update = $pdo->prepare("
                UPDATE livros
                SET quantidade = quantidade - 1
                WHERE id = ?
            ");

            $update->execute([$livro_id]);

            $pdo->commit();

            header('Location: listar.php');
            exit;

        } catch (Exception $e) {

            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }

            $msg = 'Não foi possível registar o empréstimo.';
        }
    }
}
?>

<!doctype html>

<html lang="pt">

<head>

<meta charset="utf-8">

<meta name="viewport"
      content="width=device-width,initial-scale=1">

<title>Novo empréstimo</title>

<link rel="stylesheet"
      href="../css/style.css">

</head>

<body>

<nav>

<b>Biblioteca</b>

<a href="../dashboard.php">Dashboard</a>

<a href="../livros/listar.php">Livros</a>

<a href="../leitores/listar.php">Leitores</a>

<a href="listar.php">Empréstimos</a>

<a href="../logout.php">Sair</a>

</nav>

<main>

<div class="card form">

<h2>Novo empréstimo</h2>

<?php if ($msg): ?>

<div class="erro">
<?=htmlspecialchars($msg)?>
</div>

<?php endif; ?>

<form method="post">

<label>Livro *</label>

<select name="livro_id" required>

<option value="">Seleccione um livro</option>

<?php foreach ($livros as $l): ?>

<option value="<?=$l['id']?>">

<?=htmlspecialchars($l['titulo'])?>

— Disponíveis: <?=$l['quantidade']?>

</option>

<?php endforeach; ?>

</select>


<label>Leitor *</label>

<select name="leitor_id" required>

<option value="">Seleccione um leitor</option>

<?php foreach ($leitores as $l): ?>

<option value="<?=$l['id']?>">

<?=htmlspecialchars($l['nome'])?>

</option>

<?php endforeach; ?>

</select>


<button type="submit">

Registar empréstimo

</button>

</form>

</div>

</main>

</body>

</html>