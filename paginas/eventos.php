<?php
session_start();
require(__DIR__ . '/../includes/config.php');

$id = $_GET['id'] ?? $_POST['id'] ?? null;

if (!$id) {
    echo "ID do evento não fornecido.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['email'])) {
    $comentario = trim($_POST['comentario'] ?? '');
    $usuario_email = $_SESSION['email'];

    if (!empty($comentario)) {
        $stmt = $conexao->prepare("INSERT INTO comentarios (evento_id, usuario_email, comentario) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $id, $usuario_email, $comentario);
        $stmt->execute();
    }
}

$stmt = $conexao->prepare("SELECT * FROM eventos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo "Evento não encontrado.";
    exit();
}

$evento = $resultado->fetch_assoc();

include('../includes/head.php');
?>

<body id="eventos">
    <div class="container mt-5">
        <div class="row">
            <img src="../assets/imagens/<?= htmlspecialchars($evento['imagem']) ?>" alt="Imagem do Evento" style="max-width: 300px;" class="mb-4">
        </div>
        <div class="row mb-3">
            <h1 class="fw-bold"><?= htmlspecialchars($evento['titulo']) ?></h1>
            <p><?= nl2br(htmlspecialchars($evento['descricao'])) ?></p>
            <p><strong>Local:</strong> <?= htmlspecialchars($evento['local']) ?></p>
        </div>
        <div class="row mb-5">
            <h3 class="fw-bold">Programação</h3>
            <p><strong>Data:</strong> <?= htmlspecialchars($evento['data_evento']) ?></p>
            <p><strong>Horário:</strong> <?= htmlspecialchars($evento['hora_evento']) ?></p>
        </div>

        <div class="comentarios mb-5">
            <h3 class="fw-bold">Comentários</h3>

            <?php if (isset($_SESSION['email'])): ?>
                <form action="eventos.php" method="POST" class="mb-4">
                    <div class="input-group">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <input type="text" name="comentario" class="form-control" placeholder="Digite seu comentário" required>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                </form>
            <?php else: ?>
                <p>Você precisa <a href="login.php">estar logado</a> para comentar.</p>
            <?php endif; ?>

            <?php
            $stmt = $conexao->prepare("
    SELECT c.id, c.comentario, c.data_comentario, c.usuario_email, u.nome
    FROM comentarios c
    JOIN usuarios u ON c.usuario_email = u.email
    WHERE c.evento_id = ?
    ORDER BY c.data_comentario DESC
");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $comentarios = $stmt->get_result();

            if ($comentarios->num_rows > 0):
                while ($linha = $comentarios->fetch_assoc()):
            ?>
                    <div class="border p-3 mb-2 rounded">
                        <strong><?= htmlspecialchars($linha['nome']) ?></strong>
                        <small class="text-muted"><?= date('d/m/Y H:i', strtotime($linha['data_comentario'])) ?></small>
                        <p class="mb-1"><?= nl2br(htmlspecialchars($linha['comentario'])) ?></p>

                        <?php if (isset($_SESSION['email']) && $_SESSION['email'] === $linha['usuario_email']): ?>
                            <form action="excluirComentario.php" method="POST" style="display:inline;">
                                <input type="hidden" name="comentario_id" value="<?= $linha['id'] ?>">
                                <input type="hidden" name="evento_id" value="<?= $id ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
                            </form>
                        <?php endif; ?>
                    </div>
            <?php
                endwhile;
            else:
                echo "<p>Seja o primeiro a comentar!</p>";
            endif;
            ?>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>