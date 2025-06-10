<?php
session_start();
require(__DIR__ . '/../includes/config.php');

$erroCadastro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Verifica se o email já está cadastrado
    $stmt = $conexao->prepare("SELECT 1 FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result(); // <-- necessário para SELECT 1

    if ($stmt->num_rows > 0) {
        $erroCadastro = "❌ Este email já está cadastrado.";
    } else {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $conexao->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $nome, $email, $senhaHash);

        if ($stmt->execute()) {
            header("Location: ./login.php");
            exit();
        } else {
            $erroCadastro = "Erro ao criar conta. Tente novamente.";
        }
    }
}

include('../includes/head.php');
?>

<body id="login">
    <div class="div-login d-flex justify-content-center align-items-center">
        <div class="login">
            <div>
                <p><a href="./index.php" class="text-decoration-none text-black">AgendaFest</a> | SkyLinkd</p>
            </div>
            <div>
                <h2>Crie uma conta</h2>
                <p>Já tem uma conta? <a href="./login.php" class="text-decoration-none">Clique aqui para fazer login.</a></p>
            </div>

            <?php if (!empty($erroCadastro)): ?>
                <div class="alert alert-danger w-100 text-center mb-3">
                    <?= htmlspecialchars($erroCadastro) ?>
                </div>
            <?php endif; ?>

            <form action="signup.php" method="POST" class="d-flex flex-column justify-content-start align-items-start">
                <label for="nome" class="form-label">Nome:</label>
                <input type="text" name="nome" id="nome" class="form-control mb-3" required>
                <label for="email" class="form-label">Email:</label>
                <input type="email" name="email" id="email" class="form-control mb-3" required>
                <label for="senha" class="form-label">Senha:</label>
                <input type="password" name="senha" id="senha" class="form-control mb-3" required>
                <button type="submit" class="w-100 btn text-white">Criar Conta</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
