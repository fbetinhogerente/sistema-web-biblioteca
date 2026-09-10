<?php
require '../config/auth.php'; exigirLogin(); require '../config/database.php';
$leitores=$pdo->query("SELECT * FROM leitores ORDER BY id DESC")->fetchAll();
?>
<!doctype html><html lang="pt"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Leitores</title><link rel="stylesheet" href="../css/style.css"></head><body>
<nav><b>Biblioteca</b><a href="../dashboard.php">Dashboard</a><a href="../livros/listar.php">Livros</a><a href="listar.php">Leitores</a><a href="../emprestimos/listar.php">Empréstimos</a><a href="../logout.php">Sair</a></nav>
<main><div class="top"><h2>Leitores</h2><a class="btn" href="adicionar.php">Novo leitor</a></div><table><tr><th>Nome</th><th>E-mail</th><th>Telefone</th></tr>
<?php foreach($leitores as $l):?><tr><td><?=htmlspecialchars($l['nome'])?></td><td><?=htmlspecialchars($l['email'])?></td><td><?=htmlspecialchars($l['telefone'])?></td></tr><?php endforeach;?></table></main></body></html>