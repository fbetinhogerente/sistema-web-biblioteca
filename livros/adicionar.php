<?php
require '../config/auth.php'; exigirLogin(); require '../config/database.php';
$autores=$pdo->query("SELECT * FROM autores ORDER BY nome")->fetchAll();
$categorias=$pdo->query("SELECT * FROM categorias ORDER BY nome")->fetchAll();
$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    try{
        $s=$pdo->prepare("INSERT INTO livros(titulo,isbn,editora,ano_publicacao,autor_id,categoria_id) VALUES(?,?,?,?,?,?)");
        $s->execute([trim($_POST['titulo']),trim($_POST['isbn']),trim($_POST['editora']),$_POST['ano']?:null,$_POST['autor_id'],$_POST['categoria_id']]);
        header('Location: listar.php'); exit;
    }catch(PDOException $e){$msg='Não foi possível registar o livro. Verifique os dados.';}
}
?>
<!doctype html><html lang="pt"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Novo livro</title><link rel="stylesheet" href="../css/style.css"></head><body>
<nav><b>Biblioteca</b><a href="listar.php">Voltar</a></nav><main><div class="card form"><h2>Novo livro</h2><?php if($msg):?><div class="erro"><?=$msg?></div><?php endif;?>
<form method="post"><label>Título *</label><input name="titulo" required><label>ISBN</label><input name="isbn"><label>Editora</label><input name="editora"><label>Ano</label><input type="number" name="ano" min="1000" max="2100">
<label>Autor *</label><select name="autor_id" required><?php foreach($autores as $a):?><option value="<?=$a['id']?>"><?=htmlspecialchars($a['nome'])?></option><?php endforeach;?></select>
<label>Categoria *</label><select name="categoria_id" required><?php foreach($categorias as $c):?><option value="<?=$c['id']?>"><?=htmlspecialchars($c['nome'])?></option><?php endforeach;?></select>
<button>Guardar</button></form></div></main></body></html>