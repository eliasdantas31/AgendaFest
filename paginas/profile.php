<?php
    session_start();
    require(__DIR__ . '/../includes/config.php');

    if (!isset($_SESSION['email'])) {
        header("Location: login.php");
        exit();
    }

    $email = $_SESSION['email'];
    
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = $_POST['nome'] ?? '';
        $novoEmail = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $conexao->prepare("UPDATE usuarios SET nome = ?, email = ?, senha = ? WHERE email = ?");
        $stmt->bind_param("ssss", $nome, $novoEmail, $senhaHash, $email);
        $stmt->execute();

        $_SESSION['nome'] = $nome;
        $_SESSION['email'] = $novoEmail;

        header("Location: ./profile.php");
        exit();
    }

    $stmt = $conexao->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $usuario = $resultado->fetch_assoc();

    $stmtEventos = $conexao->prepare("SELECT * FROM eventos WHERE usuario_email = ?");
    $stmtEventos->bind_param("s", $email);
    $stmtEventos->execute();
    $eventos = $stmtEventos->get_result();

    include('../includes/head.php');
?>

<body id="profile">
    <div class="container d-flex flex-column justify-content-center align-items-center">
        <nav class="nav nav-tabs w-100">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profileDiv">Perfil</button>
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#eventos">Eventos</button>
        </nav>
        <div class="tab-content w-100 mt-3">
            <div class="tab-pane fade show active" id="profileDiv">
                <div class="div-profile">
                    <div class="row w-100">
                        <div class="col-10">
                            <h1>Perfil</h1>
                            <p>Aqui você consegue ver tudo que faz você, ser você</p>
                        </div>
                        <div class="col-2">
                            <a href="logout.php" class="btn btn-danger">Logout</a>
                        </div>
                    </div>
                    <form action="profile.php" method="POST">
                        <label for="nome" class="form-label">Nome:</label>
                        <input type="text" name="nome" id="nome" class="form-control mb-3" required value="<?= htmlspecialchars($usuario['nome']) ?>">

                        <label for="email" class="form-label">Email:</label>
                        <input type="email" name="email" id="email" class="form-control mb-3" required value="<?= htmlspecialchars($usuario['email']) ?>">

                        <label for="senha" class="form-label">Nova Senha:</label>
                        <input type="password" name="senha" id="senha" class="form-control mb-3">

                        <button type="submit" class="btn btn-primary w-100">Salvar</button>
                    </form>
                </div>
            </div>

            <div class="tab-pane fade" id="eventos">
                <h1 class="mb-3">Meus Eventos</h1>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <?php while ($evento = $eventos->fetch_assoc()): ?>
                        <div class="col">
                            <div class="card h-100">
                                <img src="../assets/imagens/<?= htmlspecialchars($evento['imagem']) ?>" class="card-img-top" alt="Imagem do Evento">
                                <div class="card-header">
                                    <h5 class="card-title"><?= htmlspecialchars($evento['titulo']) ?></h5>
                                    <span class="badge bg-secondary"><?= htmlspecialchars($evento['categoria']) ?></span>
                                </div>
                                <div class="card-body">
                                    <p class="card-text"><?= nl2br(htmlspecialchars($evento['descricao'])) ?></p>
                                    <p><strong>Data:</strong> <?= date('d/m/Y', strtotime($evento['data_evento'])) ?></p>
                                    <p><strong>Horário:</strong> <?= htmlspecialchars($evento['hora_evento']) ?></p>
                                    <p><strong>Local:</strong> <?= htmlspecialchars($evento['local']) ?></p>
                                </div>
                                <div class="card-footer">
                                    <a href="editEvent.php?id=<?= $evento['id'] ?>" class="btn btn-primary">Editar</a>
                                    <a href="deleteEvent.php?id=<?= $evento['id'] ?>" class="btn btn-danger">Excluir</a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
<?php include('../includes/footer.php'); ?>
