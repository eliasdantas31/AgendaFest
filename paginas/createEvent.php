<?php
session_start();
require(__DIR__ . '/../includes/config.php');

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $data = $_POST['data'] ?? '';
    $horario = $_POST['horario'] ?? '';
    $local = $_POST['local'] ?? '';
    $categoria = $_POST['categoria'] ?? '';
    $usuario_email = $_SESSION['email'];

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
        $extensao = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
        $permitidas = ['png', 'jpg', 'jpeg'];

        if (in_array($extensao, $permitidas)) {
            $novoNome = uniqid('img_', true) . '.' . $extensao;
            $caminhoFinal = __DIR__ . '/../assets/imagens/' . $novoNome;

            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoFinal)) {
                $stmt = $conexao->prepare("INSERT INTO eventos (titulo, descricao, data_evento, hora_evento, local, categoria, imagem, usuario_email)
                                           VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssss", $titulo, $descricao, $data, $horario, $local, $categoria, $novoNome, $usuario_email);
                $stmt->execute();

                header("Location: profile.php");
                exit();
            } else {
                $erro = "Erro ao mover a imagem.";
            }
        } else {
            $erro = "Formato de imagem não permitido. Use PNG ou JPG.";
        }
    } else {
        $erro = "Erro no upload da imagem.";
    }
}

include('../includes/head.php');
?>

<body id="createEvent">
    <div class="container d-flex flex-column justify-content-center align-items-center">
        <h1>Criar Evento</h1>
        <?php if (!empty($erro)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>
        <form action="createEvent.php" method="POST" enctype="multipart/form-data" class="w-100" style="max-width: 600px;">
            <div class="mb-3">
                <label class="form-label">Imagem do Evento (PNG/JPG):</label>
                <input type="file" name="imagem" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Título:</label>
                <input type="text" name="titulo" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Descrição:</label>
                <textarea name="descricao" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Data:</label>
                <input type="date" name="data" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Horário:</label>
                <input type="time" name="horario" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Local:</label>
                <input type="text" name="local" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Categoria:</label>
                <select name="categoria" class="form-select" required>
                    <option value="">Selecione</option>
                    <option value="Acadêmico">Acadêmico</option>
                    <option value="Esportivo">Esportivo</option>
                    <option value="Cultural">Cultural</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary w-100">Criar Evento</button>
        </form>
    </div>
<?php include('../includes/footer.php'); ?>