<?php
    session_start();
    require(__DIR__ . '/../includes/config.php');

    // if (!isset($_SESSION['email'])) {
    //     header("Location: login.php");
    //     exit();
    // }

    $id = $_GET['id'];

    $stmt = $conexao->prepare("DELETE FROM eventos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: profile.php");
    exit();
?>