<?php


session_start();
require_once __DIR__ . '/../../config/db_connect.php';

// Inicia o formulário do zero se for a primeira vez
if (!isset($_SESSION['formulario_iniciado'])) {
  $_SESSION['indice_pergunta'] = 0;
  $_SESSION['formulario_iniciado'] = true;
}


$indice = $_SESSION['indice_pergunta'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Se clicou em VOLTAR
  if (isset($_POST['voltar'])) {
    if ($indice > 0) {
      $_SESSION['indice_pergunta']--;
    }
  } else {
    // Clicou em PRÓXIMA ou FINALIZAR
    $resposta = $_POST['resposta'] ?? null;
    if ($resposta !== null) {
      $_SESSION['respostas'][$indice] = $resposta;

      // Se é a última pergunta, redireciona para os resultados
      if ($indice + 1 >= count($_SESSION['todas_perguntas'] ?? [])) {
        header('Location: resultados.php');
        exit;
      }

      $_SESSION['indice_pergunta']++;
    }
  }

  header("Location: " . $_SERVER['PHP_SELF']);
  exit;
}

// Carrega perguntas do banco de dados
$perguntas = [];
$sql = 'SELECT * FROM db_projeto_verde.pergunta';

if ($conn instanceof mysqli) {
  $result = $conn->query($sql);
  if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $perguntas[] = $row;
    }
    $_SESSION['todas_perguntas'] = $perguntas; // armazena para evitar múltiplas consultas
  } else {
    echo "Nenhuma pergunta encontrada.";
    exit;
  }
  $result->free();
} else {
  echo "Erro na conexão com o banco de dados.";
  exit;
}

$pergunta_atual = $perguntas[$indice];
$id_pergunta = $pergunta_atual['id'];

// Carrega respostas possíveis
$respostas = [];
$sql = "SELECT * FROM db_projeto_verde.resposta WHERE id_pergunta = $id_pergunta";
if ($conn instanceof mysqli) {
  $result = $conn->query($sql);
  if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $respostas[] = $row;
    }
  } else {
    echo "Nenhuma resposta foi encontrada.";
    exit;
  }
  $result->free();
}

// Tema da pergunta
switch ($pergunta_atual['area']) {
  case 'descarte_de_residuos':
    $area_da_pergunta = 'Descarte de resíduos';
    break;
  case 'consumo_consciente':
    $area_da_pergunta = 'Consumo consciente';
    break;
  default:
    $area_da_pergunta = 'Desconhecido';
    break;
}

$ultimaPergunta = ($indice + 1 >= count($perguntas));
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Questionário</title>
  <link rel="stylesheet" href="../assets/css/formulario.css" />
</head>

<body>
  <div class="container">
    <div class="progress-container">
      <div class="progress-text"><?= $indice + 1 ?>/<?= count($perguntas) ?></div>

      <div class="progress-bar">
        <?php $percentual = (($indice + 1) / count($perguntas)) * 100; ?>
        <div class="progress-fill" style="width: <?= $percentual ?>%;"></div>
      </div>
    </div>

    <div class="title-box">
      <h1>Tema: <?= $area_da_pergunta ?></h1>
    </div>

    <div class="question-box">
      <p><strong><?= $indice + 1 ?>.</strong> <?= $pergunta_atual['texto_pergunta'] ?></p>

      <form method="POST">
        <?php foreach ($respostas as $resposta): ?>
          <label>
            <input type="radio" name="resposta" value="<?= $resposta['valor'] ?>" required
              <?= (isset($_SESSION['respostas'][$indice]) && $_SESSION['respostas'][$indice] === $resposta['valor']) ? 'checked' : '' ?> />
            <?= $resposta['texto_resposta'] ?>
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
</body>

</html>

<script>
  const voltarBtn = document.querySelector('button[name="voltar"]');
  if (voltarBtn) {
    voltarBtn.addEventListener('click', function () {
      document.querySelectorAll('input[required]').forEach(el => el.removeAttribute('required'));
    });
  }
</script>