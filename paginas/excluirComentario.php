<?php
session_start();
require(__DIR__ . '/../includes/config.php');

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$comentario_id = $_POST['comentario_id'] ?? null;
$evento_id = $_POST['evento_id'] ?? null;
$usuario_email = $_SESSION['email'];

if ($comentario_id && $evento_id) {
    // Confirma se o comentário pertence ao usuário logado
    $stmt = $conexao->prepare("SELECT * FROM comentarios WHERE id = ? AND usuario_email = ?");
    $stmt->bind_param("is", $comentario_id, $usuario_email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        // Exclui o comentário
        $stmt = $conexao->prepare("DELETE FROM comentarios WHERE id = ?");
        $stmt->bind_param("i", $comentario_id);
        $stmt->execute();
    }
}

header("Location: eventos.php?id=" . urlencode($evento_id));
exit();
