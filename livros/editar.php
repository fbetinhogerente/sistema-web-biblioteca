<?php
require '../config/auth.php';
exigirLogin();
require '../config/database.php';

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM livros WHERE id = ?");
$stmt->execute([$id]);
$livro = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$livro) {
    die("Livro não encontrado.");
}

$autores = $pdo->query("SELECT id, nome FROM autores ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
$categorias = $pdo->query("SELECT id, nome FROM categorias ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titulo = trim($_POST['titulo'] ?? '');
    $isbn = trim($_POST['isbn'] ?? '');
    $editora = trim($_POST['editora'] ?? '');
    $ano = (int)($_POST['ano_publicacao'] ?? 0);
    $autor_id = (int)($_POST['autor_id'] ?? 0);
    $categoria_id = (int)($_POST['categoria_id'] ?? 0);

    if ($titulo === '' || $autor_id <= 0 || $categoria_id <= 0) {
        $erro = 'Preencha os campos obrigatórios.';
    } else {

        try {

            $stmt = $pdo->prepare("
                UPDATE livros
                SET titulo = ?,
                    isbn = ?,
                    editora = ?,
                    ano_publicacao = ?,
                    autor_id = ?,
                    categoria_id = ?
                WHERE id = ?
            ");

            $stmt->execute([
                $titulo,
                $isbn,
                $editora,
                $ano,
                $autor_id,
                $categoria_id,
                $id
            ]);

            header('Location: listar.php');
            exit;

        } catch (PDOException $e) {
            $erro = 'Não foi possível atualizar o livro.';
        }
    }
}
?>

<!doctype html>
<html lang="pt">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Editar livro</title>
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

<div class="card">

<h2>Editar livro</h2>

<?php if ($erro): ?>
<div class="erro"><?=htmlspecialchars($erro)?></div>
<?php endif; ?>

<form method="post">

<label>Título *</label>
<input type="text" name="titulo"
value="<?=htmlspecialchars($livro['titulo'])?>" required>

<label>ISBN</label>
<input type="text" name="isbn"
value="<?=htmlspecialchars($livro['isbn'] ?? '')?>">

<label>Editora</label>
<input type="text" name="editora"
value="<?=htmlspecialchars($livro['editora'] ?? '')?>">

<label>Ano</label>
<input type="number" name="ano_publicacao"
value="<?=htmlspecialchars($livro['ano_publicacao'] ?? '')?>">

<label>Autor *</label>
<select name="autor_id" required>

<?php foreach ($autores as $autor): ?>

<option value="<?=$autor['id']?>"
<?=($autor['id'] == $livro['autor_id']) ? 'selected' : ''?>>

<?=htmlspecialchars($autor['nome'])?>

</option>

<?php endforeach; ?>

</select>

<label>Categoria *</label>
<select name="categoria_id" required>

<?php foreach ($categorias as $categoria): ?>

<option value="<?=$categoria['id']?>"
<?=($categoria['id'] == $livro['categoria_id']) ? 'selected' : ''?>>

<?=htmlspecialchars($categoria['nome'])?>

</option>

<?php endforeach; ?>

</select>

<button type="submit">Atualizar</button>

<a href="listar.php">Cancelar</a>

</form>

</div>

</main>

</body>
</html>