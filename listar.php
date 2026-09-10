<?php
require '../config/auth.php';
exigirLogin();
require '../config/database.php';

$emprestimos = $pdo->query("
    SELECT 
        e.*,
        l.titulo,
        u.nome AS leitor
    FROM emprestimos e
    JOIN livros l ON l.id = e.livro_id
    JOIN utilizadores u ON u.id = e.utilizador_id
    ORDER BY e.id DESC
")->fetchAll();
?>
<!doctype html>
<html lang="pt">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Empréstimos</title>
<link rel="stylesheet" href="../css/style.css">
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

<div class="top">
<h2>Empréstimos</h2>
<a class="btn" href="adicionar.php">Novo empréstimo</a>
</div>

<table>
<tr>
<th>Livro</th>
<th>Leitor</th>
<th>Empréstimo</th>
<th>Devolução</th>
<th>Estado</th>
<th>Ação</th>
</tr>

<?php foreach($emprestimos as $e): ?>

<tr>
<td><?=htmlspecialchars($e['titulo'])?></td>
<td><?=htmlspecialchars($e['leitor'])?></td>
<td><?=$e['data_emprestimo']?></td>
<td><?=$e['data_devolucao'] ?? '—'?></td>
<td><?=$e['estado']?></td>

<td>
<?php if($e['estado'] === 'Emprestado'): ?>
<a href="devolver.php?id=<?=$e['id']?>">Devolver</a>
<?php endif; ?>
</td>

</tr>

<?php endforeach; ?>

</table>

</main>

</body>
</html>