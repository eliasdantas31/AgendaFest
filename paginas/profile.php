<?php
session_start();
require(__DIR__ . '/../includes/config.php');

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

include('../includes/head.php');
?>

<body id="profile">
    <div class="background-header">
        <!-- Comentário para teste -->
    </div>
    <div class="container d-flex flex-column justify-content-center align-items-center">
        <nav class="nav nav-tabs w-100">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profileDiv" type="button">Perfil</button>
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#eventos" type="button">Eventos</button>
        </nav>
        <div class="tab-content w-100 mt-3">
            <div class="tab-pane fade show active" id="profileDiv">
                <div class="div-profile">
                    <div class="row w-100 d-flex justify-content-center align-items-center">
                        <div class="col-10">
                            <h1>Perfil</h1>
                            <p>Aqui você consegue ver tudo que faz você, ser você</p>
                        </div>
                        <div class="col-2">
                            <a href="logout.php" class="btn btn-danger">Logout</a>
                        </div>
                    </div>
                    <div class="row w-100">
                        <form action="profile.php" method="POST">
                            <label for="nome" class="form-label">Nome:</label>
                            <input type="text" name="nome" id="nome" class="form-control mb-3" required value="<?= htmlspecialchars($usuario['nome']) ?>">

                            <label for="email" class="form-label">Email:</label>
                            <input type="email" name="email" id="email" class="form-control mb-3" required value="<?= htmlspecialchars($usuario['email']) ?>">

                            <label for="senha" class="form-label">Nova Senha:</label>
                            <input type="password" name="senha" id="senha" class="form-control mb-3" placeholder="Digite uma nova senha se desejar">

                            <button type="submit" class="w-100 btn text-white">Salvar</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="eventos">
                <h1>Eventos</h1>
                <!-- Conteúdo da aba Eventos -->
            </div>
        </div>
    </div>
<?php
    include('../includes/footer.php');
?>