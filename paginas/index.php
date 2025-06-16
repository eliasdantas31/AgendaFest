<?php
session_start();
require(__DIR__ . '/../includes/config.php');
include('../includes/head.php');

setlocale(LC_TIME, 'pt_BR.utf8', 'pt_BR', 'Portuguese_Brazil.1252');
date_default_timezone_set('America/Sao_Paulo');

$categorias = ['Cultural', 'Esportivo', 'Academico'];

function exibirEventos($conexao, $categoria)
{
    $usuarioLogado = isset($_SESSION['email']);

    if ($usuarioLogado) {
        // Usuário logado vê todos os eventos
        $sql = "
            SELECT eventos.*, usuarios.nome AS nome_usuario, usuarios.email AS email_usuario
            FROM eventos
            JOIN usuarios ON eventos.usuario_email = usuarios.email
            WHERE eventos.categoria = ?
            ORDER BY eventos.data_evento ASC
        ";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("s", $categoria);
    } else {
        // Visitante só vê eventos públicos
        $sql = "
            SELECT eventos.*, usuarios.nome AS nome_usuario, usuarios.email AS email_usuario
            FROM eventos
            JOIN usuarios ON eventos.usuario_email = usuarios.email
            WHERE eventos.categoria = ? AND eventos.visibilidade = 'publico'
            ORDER BY eventos.data_evento ASC
        ";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("s", $categoria);
    }

    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 0) {
        return;
    }

    echo '<h3 class="mb-3 p-5">' . htmlspecialchars($categoria) . '</h3>';
    echo '<div class="div-eventos row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">';

    while ($evento = $resultado->fetch_assoc()) {
        $data = strtotime($evento['data_evento']);
        $dataFormatada = mb_strtoupper(strftime('%d de %B', $data), 'UTF-8');

        echo '<div class="col">';
        echo '<div class="card h-100 shadow">';
        echo '<img src="../assets/imagens/' . htmlspecialchars($evento['imagem']) . '" class="card-img-top" alt="Imagem do Evento">';
        echo '<div class="card-body">';
        echo '<small class="text-primary fw-bold d-block mb-2">' . $dataFormatada . '</small>';
        echo '<h5 class="card-title fw-bold mb-4">' . htmlspecialchars($evento['titulo']) . '</h5>';
        echo '<button class="btn btn-primary"><a href="./eventos.php?id=' . $evento['id'] . '" class="text-light text-decoration-none">Saiba Mais</a></button>';
        echo '<p class="mt-2 mb-0 text-muted" style="font-size: 0.9em;">Criado por: ' . htmlspecialchars($evento['nome_usuario']) . '</p>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    echo '</div>';
    $stmt->close();
}
?>

<body id="index">
    <section id="home" class="d-flex flex-column justify-content-center align-items-center">
        <div class="container text-center mb-5">
            <h1>Descubra, crie, participe e divirta-se.</h1>
            <p>Encontre ou crie você mesmo, eventos relevantes para você.</p>
        </div>
        <div class="mt-5">
            <a href="#eventos" class="d-flex flex-column justify-content-center align-items-center text-decoration-none">
                <p class="m-0 text-white">Ver Eventos</p>
                <i class="bi bi-arrow-down-short fs-1 text-white"></i>
            </a>
        </div>
    </section>

    <section id="eventos" class="py-5">
        <div class="container">
            <h2 class="mb-4 text-center">Próximos Eventos</h2>
            <?php foreach ($categorias as $categoria): ?>
                <?php exibirEventos($conexao, $categoria); ?>
            <?php endforeach; ?>
        </div>
    </section>

    <?php include('../includes/footer.php'); ?>
</body>
