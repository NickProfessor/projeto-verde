<?php
session_start();
require_once __DIR__ . '/../../config/db_connect.php';

// Inicializa a sessão do questionário
if (!isset($_SESSION['formulario_iniciado'])) {
    $_SESSION['indice_pergunta'] = 0;
    $_SESSION['formulario_iniciado'] = true;
    $_SESSION['respostas'] = [];
}

$indice = $_SESSION['indice_pergunta'] ?? 0;

// Processa o POST do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['voltar'])) {
        if ($indice > 0) {
            $_SESSION['indice_pergunta']--;
        }
    } else {
        $resposta = $_POST['resposta'] ?? null;
        if ($resposta !== null) {
            $_SESSION['respostas'][$indice] = (int)$resposta;

            // Verifica se é a última pergunta
            if ($indice + 1 >= count($_SESSION['todas_perguntas'] ?? [])) {
                calcularResultados();
                header('Location: resultados.php');
                exit;
            }
            $_SESSION['indice_pergunta']++;
        }
    }
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Função para calcular os resultados
function calcularResultados() {
    $pontuacaoPorArea = [
        'descarte_de_residuos' => 0,
        'consumo_consciente' => 0,
        'economia_de_agua' => 0,
        'economia_de_energia' => 0,
        'mobilidade_sustentavel' => 0
    ];
    
    $contadorPorArea = [
        'descarte_de_residuos' => 0,
        'consumo_consciente' => 0,
        'economia_de_agua' => 0,
        'economia_de_energia' => 0,
        'mobilidade_sustentavel' => 0
    ];

    foreach ($_SESSION['respostas'] as $indice => $valorResposta) {
        $area = $_SESSION['todas_perguntas'][$indice]['area'];
        $pontuacaoPorArea[$area] += $valorResposta;
        $contadorPorArea[$area]++;
    }

    // Calcula as médias
    $mediaPorArea = [];
    foreach ($pontuacaoPorArea as $area => $pontos) {
        $mediaPorArea[$area] = $contadorPorArea[$area] > 0 
            ? round($pontos / $contadorPorArea[$area], 2) 
            : 0;
    }

    $_SESSION['resultados'] = [
        'medias' => $mediaPorArea,
        'totalPontos' => array_sum($_SESSION['respostas'])
    ];
}

// Busca todas as perguntas do banco
if (!isset($_SESSION['todas_perguntas'])) {
    $perguntas = [];
    $sql = 'SELECT * FROM pergunta';
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $perguntas[] = $row;
        }
        $_SESSION['todas_perguntas'] = $perguntas;
    } else {
        die("Nenhuma pergunta encontrada.");
    }
    $result->free();
}

$pergunta_atual = $_SESSION['todas_perguntas'][$indice];
$id_pergunta = $pergunta_atual['id'];

// Busca as respostas para a pergunta atual
$respostas = [];
$sql = "SELECT * FROM resposta WHERE id_pergunta = $id_pergunta";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $respostas[] = $row;
    }
} else {
    die("Nenhuma resposta encontrada para esta pergunta.");
}
$result->free();

// Define o nome da área para exibição
$nomesAreas = [
    'descarte_de_residuos' => 'Descarte de Resíduos',
    'consumo_consciente' => 'Consumo Consciente',
    'economia_de_agua' => 'Economia de Água',
    'economia_de_energia' => 'Economia de Energia',
    'mobilidade_sustentavel' => 'Mobilidade Sustentável'
];
$area_da_pergunta = $nomesAreas[$pergunta_atual['area']] ?? 'Desconhecido';

$ultimaPergunta = ($indice + 1 >= count($_SESSION['todas_perguntas']));
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Questionário de Sustentabilidade</title>
    <link rel="stylesheet" href="../assets/css/formulario.css">
</head>
<body>
    <div class="container">
        <div class="progress-container">
            <div class="progress-text"><?= $indice + 1 ?>/<?= count($_SESSION['todas_perguntas']) ?></div>
            <div class="progress-bar">
                <?php $percentual = (($indice + 1) / count($_SESSION['todas_perguntas'])) * 100; ?>
                <div class="progress-fill" style="width: <?= $percentual ?>%;"></div>
            </div>
        </div>

        <div class="title-box">
            <h1>Tema: <?= $area_da_pergunta ?></h1>
        </div>

        <div class="question-box">
            <p><strong><?= $indice + 1 ?>.</strong> <?= htmlspecialchars($pergunta_atual['texto_pergunta']) ?></p>

            <form method="POST">
                <?php foreach ($respostas as $resposta): ?>
                    <label>
                        <input type="radio" name="resposta" value="<?= $resposta['valor'] ?>" required
                            <?= (isset($_SESSION['respostas'][$indice])) && $_SESSION['respostas'][$indice] == $resposta['valor'] ? 'checked' : '' ?>>
                        <?= htmlspecialchars($resposta['texto_resposta']) ?>
                    </label>
                <?php endforeach; ?>

                <div class="navigation">
                    <?php if ($indice > 0): ?>
                        <button type="submit" name="voltar" value="1" class="anterior">← Voltar</button>
                    <?php endif; ?>

                    <button type="submit" name="proxima" value="1" class="proxima">
                        <?= $ultimaPergunta ? 'Finalizar' : 'Próxima' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const voltarBtn = document.querySelector('button[name="voltar"]');
        if (voltarBtn) {
            voltarBtn.addEventListener('click', function() {
                document.querySelectorAll('input[required]').forEach(el => el.removeAttribute('required'));
            });
        }
    </script>
</body>
</html>