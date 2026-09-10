<?php
require '../config/auth.php'; exigirLogin(); require '../config/database.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $s=$pdo->prepare("INSERT INTO leitores(nome,email,telefone,endereco) VALUES(?,?,?,?)");
 $s->execute([trim($_POST['nome']),trim($_POST['email']),trim($_POST['telefone']),trim($_POST['endereco'])]);
 header('Location: listar.php'); exit;
}
?>
<!doctype html><html lang="pt"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Novo leitor</title><link rel="stylesheet" href="../css/style.css"></head><body>
<nav><b>Biblioteca</b><a href="listar.php">Voltar</a></nav><main><div class="card form"><h2>Novo leitor</h2><form method="post">
<label>Nome *</label><input name="nome" required><label>E-mail</label><input type="email" name="email"><label>Telefone</label><input name="telefone"><label>Endereço</label><input name="endereco"><button>Guardar</button></form></div></main></body></html>