<?php
session_start();
require(__DIR__ . '/../includes/config.php');

$email_usuario = $_SESSION['email'];
$id = $_GET['id'] ?? $_POST['id'] ?? null;

if (!isset($_SESSION['email'])) {
    header('Location: ../login.php');
    exit();
}

if (!$id) {
    echo "ID do evento não fornecido.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $data = $_POST['data'];
    $horario = $_POST['horario'];
    $descricao = $_POST['descricao'];
    $categoria = $_POST['categoria'];

    $stmt = $conexao->prepare("SELECT imagem FROM eventos WHERE id = ? AND usuario_email = ?");
    $stmt->bind_param("is", $id, $email_usuario);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        echo "Evento não encontrado";
        exit();
    }

    $evento = $res->fetch_assoc();
    $imagem_antiga = $evento['imagem'];

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === 0) {
        $extensao = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
        $permitidas = ['png', 'jpg', 'jpeg'];

        if (in_array($extensao, $permitidas)) {
            $novoNome = uniqid('img_', true) . '.' . $extensao;
            $caminhoFinal = __DIR__ . '/../assets/imagens/' . $novoNome;

            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoFinal)) {
                $imagem = $novoNome;
            } else {
                $erro = "Erro ao mover a nova imagem.";
            }
        } else {
            $erro = "Formato de imagem inválido.";
        }
    } else {
        $imagem = $imagem_antiga;
    }

    $stmt = $conexao->prepare("UPDATE eventos 
        SET titulo = ?, data_evento = ?, hora_evento = ?, descricao = ?, categoria = ?, imagem = ? 
        WHERE id = ? AND usuario_email = ?");
    $stmt->bind_param("ssssssis", $nome, $data, $horario, $descricao, $categoria, $imagem, $id, $email_usuario);
    $stmt->execute();

    header("Location: ./profile.php");
    exit();
}

$stmt = $conexao->prepare("SELECT * FROM eventos WHERE id = ? AND usuario_email = ?");
$stmt->bind_param("is", $id, $email_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo "Evento não encontrado.";
    exit();
}

$evento = $resultado->fetch_assoc();
?>

<?php include('../includes/head.php'); ?>
<body>
<div class="container w-100" id="Edit">
    <h1>Edite seu evento</h1>

    <?php if (!empty($erro)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form action="editEvent.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= htmlspecialchars($evento['id']) ?>">

        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" class="form-control mb-3" value="<?= htmlspecialchars($evento['titulo']) ?>" required>

        <label for="data">Data:</label>
        <input type="date" name="data" id="data" class="form-control mb-3" value="<?= htmlspecialchars($evento['data_evento']) ?>" required>

        <label for="horario">Horário:</label>
        <input type="time" name="horario" id="horario" class="form-control mb-3" value="<?= htmlspecialchars($evento['hora_evento']) ?>" required>

        <label for="descricao">Descrição:</label>
        <textarea name="descricao" id="descricao" class="form-control mb-3" required><?= htmlspecialchars($evento['descricao']) ?></textarea>

        <label for="categoria">Categoria:</label>
        <select name="categoria" id="categoria" class="form-select mb-3" required>
            <option value="Acadêmico" <?= $evento['categoria'] == 'Acadêmico' ? 'selected' : '' ?>>Acadêmico</option>
            <option value="Esportivo" <?= $evento['categoria'] == 'Esportivo' ? 'selected' : '' ?>>Esportivo</option>
            <option value="Cultural" <?= $evento['categoria'] == 'Cultural' ? 'selected' : '' ?>>Cultural</option>
        </select>

        <label>Imagem atual:</label><br>
        <img src="../assets/imagens/<?= htmlspecialchars($evento['imagem']) ?>" alt="Imagem do Evento" style="max-width: 200px;"><br><br>

        <label for="imagem">Nova imagem (opcional):</label>
        <input type="file" name="imagem" id="imagem" class="form-control mb-3">

        <button type="submit" class="btn btn-primary w-100">Salvar Alterações</button>
    </form>
</div>
<?php include('../includes/footer.php'); ?>
