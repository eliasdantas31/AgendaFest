<?php
session_start();
require(__DIR__ . '/../includes/config.php');
include('../includes/head.php');

setlocale(LC_TIME, 'pt_BR.utf8', 'pt_BR', 'Portuguese_Brazil.1252');
date_default_timezone_set('America/Sao_Paulo');

$categorias = ['Cultural', 'Esportivo', 'Academico'];

function exibirEventos($conexao, $categoria)
{
    $stmt = $conexao->prepare("SELECT * FROM eventos WHERE categoria = ? ORDER BY data_evento ASC LIMIT 3");
    $stmt->bind_param("s", $categoria);
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
        echo '<div class="card h-100 shadow rounded">';
        echo '<img src="../assets/imagens/' . htmlspecialchars($evento['imagem']) . '" class="card-img-top" alt="Imagem do Evento">';
        echo '<div class="card-body">';
        echo '<small class="text-primary fw-bold d-block mb-2">' . $dataFormatada . '</small>';
        echo '<h5 class="card-title fw-bold">' . htmlspecialchars($evento['titulo']) . '</h5>';
        echo '<span class="badge bg-secondary mb-2">' . htmlspecialchars($evento['categoria']) . '</span>';
        echo '<p class="card-text">' . nl2br(htmlspecialchars($evento['descricao'])) . '</p>';
        echo '<p><strong>Horário:</strong> ' . htmlspecialchars($evento['hora_evento']) . '</p>';
        echo '<p><strong>Local:</strong> ' . htmlspecialchars($evento['local']) . '</p>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }

    echo '</div>';
    $stmt->close();
}
?>