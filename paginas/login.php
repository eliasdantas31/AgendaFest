<?php
    session_start();

    require(__DIR__ . '/../includes/config.php');

    $erroLogin = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        $stmt = $conexao->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();

            if (password_verify($senha, $usuario['senha'])) {
                $_SESSION['nome'] = $usuario['nome'];

                header("Location: index.php");
                exit();
            } else {
                $erroLogin = '❌ Senha incorreta.';
            }
        } else {
            $erroLogin = '❌ Usuário não encontrado.';
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
                <h2>Acesse sua conta</h2>
                <p>Ainda não tem uma conta? <a href="./signup.php" class="text-decoration-none">Clique aqui para criar uma.</a></p>
            </div>
            <form action="login.php" method="POST" class="d-flex flex-column justify-content-start align-items-start">
                <label for="email" class="form-label">Email:</label>
                <input type="text" name="email" id="email" class="form-control mb-3">
                <label for="senha" class="form-label">Senha:</label>
                <input type="password" name="senha" id="senha" class="form-control mb-3">
                <button type="submit" class="w-100 btn text-white">Entrar</button>
            </form>
            <?php if (!empty($erroLogin)): ?>
                <div class="alert alert-danger w-100 text-center">
                    <?= htmlspecialchars($erroLogin) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php 
    include('../includes/footer.php'); 
?>
