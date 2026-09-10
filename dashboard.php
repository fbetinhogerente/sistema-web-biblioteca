<?php
require 'config/auth.php';
exigirLogin();
require 'config/database.php';

$livros = $pdo->query("SELECT COUNT(*) FROM livros")->fetchColumn();

$disponiveis = $pdo->query(
    "SELECT COUNT(*) FROM livros WHERE quantidade > 0"
)->fetchColumn();

$leitores = $pdo->query(
    "SELECT COUNT(*) FROM leitores"
)->fetchColumn();

$emprestimos = $pdo->query(
    "SELECT COUNT(*) FROM emprestimos WHERE estado = 'Emprestado'"
)->fetchColumn();
?>
<!doctype html>
<html lang="pt">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Dashboard</title>
<link rel="stylesheet" href="css/style.css">
</head>

<body>

<nav>
<b>Biblioteca Web</b>
<a href="dashboard.php">Dashboard</a>
<a href="livros/listar.php">Livros</a>
<a href="leitores/listar.php">Leitores</a>
<a href="emprestimos/listar.php">Empréstimos</a>
<a href="logout.php">Sair</a>
</nav>

<main>

<h2>Bem-vindo, <?=htmlspecialchars($_SESSION['utilizador']['nome'])?></h2>

<div class="grid">

<div class="card">
<h3>Livros</h3>
<p><?=$livros?></p>
</div>

<div class="card">
<h3>Disponíveis</h3>
<p><?=$disponiveis?></p>
</div>

<div class="card">
<h3>Leitores</h3>
<p><?=$leitores?></p>
</div>

<div class="card">
<h3>Empréstimos ativos</h3>
<p><?=$emprestimos?></p>
</div>

</div>

</main>

</body>
</html>