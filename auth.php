<?php
session_start();
function exigirLogin() {
    if (!isset($_SESSION['utilizador'])) {
        header('Location: /biblioteca-web/login.php');
        exit;
    }
}
?>