<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgendaFest</title>
    <!-- Bootstrap Basico -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Bootstrap Bundle com Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- CSS Personalizado -->
    <link rel="stylesheet" href="../assets/css/style.css">
    
    <?php
        $usernameJS = isset($_SESSION['nome']) ? json_encode($_SESSION['nome']) : 'null';
    ?>
    <script>
        window.username = <?= $usernameJS ?>;
    </script>
</head>
