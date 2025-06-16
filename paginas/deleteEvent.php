<?php
session_start();
require(__DIR__ . '/../includes/config.php');

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: profile.php");
    exit();
}

$stmt = $conexao->prepare("SELECT imagem FROM eventos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $evento = $resultado->fetch_assoc();
    $imagem = $evento['imagem'];

    $caminhoImagem = __DIR__ . '/../assets/imagens/' . $imagem;
    if (file_exists($caminhoImagem)) {
        unlink($caminhoImagem);
    }

    $stmt = $conexao->prepare("DELETE FROM eventos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: profile.php");
exit();
?>