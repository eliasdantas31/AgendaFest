<?php
session_start();
require(__DIR__ . '/../includes/config.php');
include('../includes/head.php');

$emailEncontrado = false;
$emailInformado = '';
$mensagem = '';
$etapa = 1;

// Etapa 1: Verifica se o e-mail existe
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['verificar_email'])) {
        $emailInformado = trim($_POST['email'] ?? '');

        $stmt = $conexao->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $emailInformado);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $emailEncontrado = true;
            $etapa = 2;
        } else {
            $mensagem = "E-mail não encontrado no sistema.";
        }
    }

    // Etapa 2: Atualiza a senha
    if (isset($_POST['redefinir_senha'])) {
        $emailInformado = trim($_POST['email'] ?? '');
        $novaSenha = $_POST['nova_senha'] ?? '';
        $confirmarSenha = $_POST['confirmar_senha'] ?? '';

        if ($novaSenha === $confirmarSenha && strlen($novaSenha) >= 6) {
            $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
            $stmt = $conexao->prepare("UPDATE usuarios SET senha = ? WHERE email = ?");
            $stmt->bind_param("ss", $senhaHash, $emailInformado);
            $stmt->execute();

            $mensagem = "Senha redefinida com sucesso. <a href='login.php'>Clique aqui para fazer login</a>.";
            $etapa = 3;
        } else {
            $mensagem = "As senhas não coincidem ou são muito curtas (mín. 6 caracteres).";
            $etapa = 2;
            $emailEncontrado = true;
        }
    }
}
?>

<body id="forgotPass">
    <div class="container d-flex flex-column justify-content-center align-items-center">
        <div>
            <p><a href="./index.php" class="text-decoration-none text-black">AgendaFest</a> | SkyLinkd</p>
            <h1>Recuperar Senha</h1>

            <?php if (!empty($mensagem)): ?>
                <div class="alert alert-info"><?= $mensagem ?></div>
            <?php endif; ?>

            <?php if ($etapa === 1): ?>
                <!-- Formulário de verificação de email -->
                <p>Insira seu email para redefinir sua senha:</p>
                <form method="POST" class="d-flex flex-column">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" name="email" id="email" class="form-control mb-3" required>
                    <button type="submit" name="verificar_email" class="btn text-white">Enviar</button>
                </form>

            <?php elseif ($etapa === 2): ?>
                <!-- Formulário de redefinição de senha -->
                <p>Digite sua nova senha:</p>
                <form method="POST" class="d-flex flex-column">
                    <input type="hidden" name="email" value="<?= htmlspecialchars($emailInformado) ?>">
                    
                    <label for="nova_senha" class="form-label">Nova Senha:</label>
                    <input type="password" name="nova_senha" id="nova_senha" class="form-control mb-3" required minlength="6">

                    <label for="confirmar_senha" class="form-label">Confirmar Senha:</label>
                    <input type="password" name="confirmar_senha" id="confirmar_senha" class="form-control mb-3" required minlength="6">

                    <button type="submit" name="redefinir_senha" class="btn text-white">Redefinir Senha</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
<?php include('../includes/footer.php'); ?>
