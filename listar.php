<?php
require '../config/auth.php';
exigirLogin();
require '../config/database.php';

$q = trim($_GET['q'] ?? '');

$stmt = $pdo->prepare("
    SELECT l.*, a.nome AS autor, c.nome AS categoria
    FROM livros l
    JOIN autores a ON a.id = l.autor_id
    JOIN categorias c ON c.id = l.categoria_id
    WHERE l.titulo LIKE ?
    ORDER BY l.id DESC
");

$stmt->execute(["%$q%"]);
$livros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html lang="pt">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Livros</title>
<link rel="stylesheet" href="../css/style.css">
</head>

<body>

<nav>
<b>Biblioteca</b>
<a href="../dashboard.php">Dashboard</a>
<a href="listar.php">Livros</a>
<a href="../leitores/listar.php">Leitores</a>
<a href="../emprestimos/listar.php">Empréstimos</a>
<a href="../logout.php">Sair</a>
</nav>

<main>

<div class="top">
<h2>Livros</h2>
<a class="btn" href="adicionar.php">Novo livro</a>
</div>

<form class="search">
<input name="q" value="<?=htmlspecialchars($q)?>" placeholder="Pesquisar por título">
<button>Pesquisar</button>
</form>

<table>
<tr>
<th>Título</th>
<th>Autor</th>
<th>Categoria</th>
<th>ISBN</th>
<th>Editora</th>
<th>Ano</th>
<th>Estado</th>
<th>Ações</th>
</tr>

<?php foreach($livros as $l): ?>

<tr>

<td><?=htmlspecialchars($l['titulo'])?></td>

<td><?=htmlspecialchars($l['autor'])?></td>

<td><?=htmlspecialchars($l['categoria'])?></td>

<td><?=htmlspecialchars($l['isbn'])?></td>

<td><?=htmlspecialchars($l['editora'] ?? '')?></td>

<td><?=htmlspecialchars($l['ano_publicacao'] ?? '')?></td>

<td>
<?php
if (isset($l['quantidade']) && $l['quantidade'] > 0) {
    echo 'Disponível';
} else {
    echo 'Indisponível';
}
?>
</td>

<td>
<a href="editar.php?id=<?=$l['id']?>">Editar</a> |
<a href="excluir.php?id=<?=$l['id']?>"
   onclick="return confirm('Tem certeza que deseja eliminar este livro?');">
   Eliminar
</a>
</td>

</tr>

<?php endforeach; ?>

</table>

</main>

</body>
</html>