<?php
session_start();
require_once __DIR__ . '/../../config/db_connect.php';


if (!isset($_SESSION['resultados'])) {
    header('Location: formulario.php');
    exit;
}

$medias = $_SESSION['resultados']['medias'];
$totalPontos = $_SESSION['resultados']['totalPontos'];


$nivel = match(true) {
    $totalPontos <= 10 => 'Excelente',
    $totalPontos <= 20 => 'Bom',
    default => 'Pode melhorar'
};


$areas = [
    'descarte_de_residuos' => 'Descarte de Resíduos',
    'consumo_consciente' => 'Consumo Consciente',
    'economia_de_agua' => 'Economia de Água',
    'economia_de_energia' => 'Economia de Energia',
    'mobilidade_sustentavel' => 'Mobilidade Sustentável'
];


$recomendacoes = [];

foreach ($_SESSION['respostas'] as $indice => $valorResposta) {
    $pergunta = $_SESSION['todas_perguntas'][$indice];
    
    switch ($pergunta['id']) {
        case 1:
            $recomendacoes[] = match($valorResposta) {
                0 => "✅ Excelente! Continue separando todo o lixo reciclável.",
                1 => "👍 Bom trabalho! Tente separar o lixo todos os dias.",
                2 => "✋ Você pode melhorar. Separe pelo menos plástico e papel.",
                3 => "⚠️ Comece separando o lixo 1x por semana. Cada ajuda conta!"
            };
            break;
            
        case 2: 
            $recomendacoes[] = match($valorResposta) {
                0 => "✅ Parabéns! Você é um consumidor consciente exemplar.",
                1 => "👍 Boas escolhas! Continue pesquisando sobre os produtos.",
                2 => "✋ Tente verificar a origem dos produtos antes de comprar.",
                3 => "⚠️ Antes de comprar, pense: 'Eu realmente preciso disso?'"
            };
            break;
            
        case 3:
            $recomendacoes[] = match($valorResposta) {
                0 => "✅ Perfeito! Você descarta eletrônicos corretamente.",
                1 => "👍 Bom trabalho! Continue levando para pontos de coleta.",
                2 => "✋ Procure na sua cidade pontos de coleta de eletrônicos.",
                3 => "⚠️ Pilhas e baterias não devem ir no lixo comum. Descarte corretamente!"
            };
            break;
            
        case 4:
            $recomendacoes[] = match($valorResposta) {
                0 => "✅ Incrível! Você é expert em reutilizar materiais.",
                1 => "👍 Boa! Tente reutilizar mais embalagens e recipientes.",
                2 => "✋ Antes de jogar fora, pense: 'Posso reutilizar isso?'",
                3 => "⚠️ Garrafas e potes podem virar vasos ou organizadores!"
            };
            break;
            
        case 5:
            $recomendacoes[] = match($valorResposta) {
                0 => "✅ Banho rápido! Você economiza muita água assim.",
                1 => "👍 Bom tempo! Tente reduzir mais 1-2 minutos.",
                2 => "✋ Banhos longos gastam água. Tente cronometrar seu banho.",
                3 => "⚠️ Reduza seu banho pela metade e veja a diferença na conta!"
            };
            break;
            
        case 6:
            $recomendacoes[] = match($valorResposta) {
                0 => "✅ Água reaproveitada! Seu jardim deve agradecer.",
                1 => "👍 Boa iniciativa! Tente coletar mais água da chuva.",
                2 => "✋ Você pode colocar baldes para coletar água da chuva.",
                3 => "⚠️ Água da máquina pode regar plantas. Experimente!"
            };
            break;
            
        case 7:
            $recomendacoes[] = match($valorResposta) {
                0 => "✅ Todos selo A! Sua conta de energia agradece.",
                1 => "👍 Boa parte é econômica. Troque os mais antigos quando possível.",
                2 => "✋ Na próxima compra, prefira eletrodomésticos com selo Procel A.",
                3 => "⚠️ Eletrodomésticos econômicos podem reduzir sua conta em 30%!"
            };
            break;
            
        case 8:
            $recomendacoes[] = match($valorResposta) {
                0 => "✅ Todos desligados! Nada de gasto fantasma.",
                1 => "👍 Quase tudo desligado! Só falta um pequeno ajuste.",
                2 => "✋ Use uma régua com botão para desligar vários aparelhos de uma vez.",
                3 => "⚠️ Aparelhos em standby consomem energia. Desligue da tomada!"
            };
            break;
            
        case 9:
            $recomendacoes[] = match($valorResposta) {
                0 => "✅ Bicicleta ou caminhada! Saúde e planeta agradecem.",
                1 => "👍 Transporte público é uma ótima escolha sustentável.",
                2 => "✋ Tente usar transporte público 1x por semana.",
                3 => "⚠️ Carona solidária reduz emissões e trânsito!"
            };
            break;
            
        case 10:
            $recomendacoes[] = match($valorResposta) {
                0 => "✅ Sempre compartilhando! Ótimo para o meio ambiente.",
                1 => "👍 Bom uso de caronas! Continue assim.",
                2 => "✋ Experimente oferecer carona a colegas de trabalho.",
                3 => "⚠️ Compartilhar carro reduz poluição e custos!"
            };
            break;
    }
}


foreach ($medias as $area => $media) {
    if ($media >= 1.5) {
        $recomendacoes[] = match($area) {
            'descarte_de_residuos' => "📌 Área crítica: Descarte de resíduos. Separe melhor seu lixo!",
            'consumo_consciente' => "📌 Área crítica: Consumo. Prefira produtos sustentáveis.",
            'economia_de_agua' => "📌 Área crítica: Água. Reduza tempo no banho e reaproveite.",
            'economia_de_energia' => "📌 Área crítica: Energia. Desligue aparelhos e use lâmpadas LED.",
            'mobilidade_sustentavel' => "📌 Área crítica: Transporte. Ande mais a pé ou de bike."
        };
    }
}


$recomendacoes = array_slice(array_unique($recomendacoes), 0, 6);


if (empty($recomendacoes)) {
    $recomendacoes[] = "🎉 Parabéns! Você é um exemplo de sustentabilidade em todas as áreas!";
}

$porcentagens = [];
foreach ($medias as $area => $media) {
    $porcentagens[$area] = round((1 - ($media / 3)) * 100);
}

$porcentagemGeral = round((1 - ($totalPontos / 30)) * 100);
$status = match(true) {
    $porcentagemGeral >= 80 => 'Excelente',
    $porcentagemGeral >= 60 => 'Sustentável',
    $porcentagemGeral >= 40 => 'Melhore',
    default => 'Insustentável'
};
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Resultado da sua avaliação de sustentabilidade pessoal">
    <meta name="keywords" content="sustentabilidade, meio ambiente, ecologia, avaliação, resultado">
    <meta name="author" content="Eco Avaliação">
    <title>Seu Resultado Sustentável</title>
    <link rel="stylesheet" href="../assets/css/result.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        html, body {
            height: 100%;
            width: 100%;
            overflow: hidden;
            font-family: Arial, sans-serif;
        }
        
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #a8d5a8;
        }
      
        .dashboard {
            background-color:rgb(255, 255, 255);
            width: 100%;
            width: 800px;
            height: 400px;
            border-radius: 8px;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }
              
        .dashboard-content {
            display: flex;
            flex: 1;
            gap: 30px;
        }
        
        .progress-section {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .progress-container {
            position: relative;
            width: 150px;
            height: 150px;
        }
        
        .progress-circle {
            position: relative;
            width: 100%;
            height: 100%;
        }
        
        .progress-labels {
            display: flex;
            flex-direction: column;
            text-align: left;
        }
        
        .status-label {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .nivel-label {
            font-size: 16px;
            margin-top: 5px;
        }
        
        .recomendacoes-container {
            background-color: #a8d5a8;
            border-radius: 15px;
            padding: 15px;
            flex: 1;
            max-width: 400px;
            min-width: 300px; 
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
}

        .recomendacoes-header {
            background-color: #2ecc71;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            display: inline-block;
            margin-bottom: 15px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 14px;
            align-self: flex-start;
        }
        
        .recomendacoes-list {
            list-style-type: none;
            padding: 0;
            overflow-y: auto;
            flex: 1;
        }
        
        .recomendacoes-item {
            margin-bottom: 12px;
            display: flex;
            align-items: flex-start;
            line-height: 1.3;
            position: relative;
            padding-left: 25px;
        }
        
        .recomendacoes-item::before {
            content: "•";
            color: #2ecc71;
            font-size: 24px;
            position: absolute;
            left: 5px;
            top: -2px;
        }
        
        .recomendacoes-button {
            background-color: #2ecc71;
            color: white;
            border: none;
            border-radius: 15px;
            padding: 5px 15px;
            cursor: pointer;
            font-size: 12px;
            align-self: flex-end;
            margin-top: 10px;
        }
        
        @media (max-width: 768px) {
            .dashboard {
                height: auto;
                max-width: 95%;
            }
            
            .dashboard-content {
                flex-direction: column;
            }
            
            .progress-section {
                justify-content: center;
                margin-bottom: 20px;
            }
            
            .recomendacoes-container {
                height: 250px;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <div class="dashboard-content">
            <div class="progress-section">
                <div class="progress-container">
                    <div class="progress-circle">
                        <canvas id="circleChart"></canvas>
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
                            <div style="font-size: 36px; font-weight: bold;"><?= $porcentagemGeral ?>%</div>
                        </div>
                    </div>
                </div>
                
                <div class="progress-labels">
                    <div class="status-label"><?= $status ?>.</div> 
                </div>
            </div>
            
            <div class="recomendacoes-container">
                <div class="recomendacoes-header">RECOMENDAÇÕES</div>
                <ul class="recomendacoes-list">
                    <?php foreach ($recomendacoes as $index => $rec): ?>
                        <?php if ($index < 4): ?>
                            <li class="recomendacoes-item"><?= htmlspecialchars($rec) ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <script>
        
        const ctxCircle = document.getElementById('circleChart').getContext('2d');
        
        new Chart(ctxCircle, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [<?= $porcentagemGeral ?>, 100 - <?= $porcentagemGeral ?>],
                    backgroundColor: [
                        '#4CAF50',
                        '#e0e0e0'
                    ],
                    borderWidth: 0,
                    cutout: '70%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        enabled: false
                    }
                }
            }
        });
    </script>
</body>
</html>
