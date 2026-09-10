<?php
require 'config/database.php';
session_start();
$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare("SELECT * FROM utilizadores WHERE email = ?");
    $stmt->execute([$email]);
    $u = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($u && password_verify($password, $u['senha'])) {
    $_SESSION['utilizador'] = ['id'=>$u['id'],'nome'=>$u['nome'],'perfil'=>$u['tipo']];
        header('Location: dashboard.php'); exit;
    }
    $erro = 'E-mail ou palavra-passe incorretos.';
}
?>
<!doctype html><html lang="pt"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Login - Biblioteca</title><link rel="stylesheet" href="css/style.css"></head>
<body class="login"><form method="post" class="card"><h1>Biblioteca Web</h1>
<?php if($erro): ?><div class="erro"><?=htmlspecialchars($erro)?></div><?php endif; ?>
<label>E-mail</label><input type="email" name="email" required>
<label>Palavra-passe</label><input type="password" name="password" required>
<button>Entrar</button></form></body></html>