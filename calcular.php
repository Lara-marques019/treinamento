<?php
// Função para gerar a composição do montante aleatoriamente
function gerarMontante($valorTotal) {
    // Definição das cédulas e moeda disponíveis
    $cedulas = [100, 50, 20, 10, 5, 2];
    $moeda = 1;
    $montante = [];
    $valorRestante = $valorTotal;

    // Enquanto ainda houver valor a ser distribuído...
    while ($valorRestante > 0) {
        // Filtra as opções de notas que podem ser usadas no montante
        $opcoes = array_filter(array_merge($cedulas, [$moeda]), fn($v) => $v <= $valorRestante);

        // Seleciona aleatoriamente uma das opções disponíveis
        $nota = $opcoes[array_rand($opcoes)];

        // Adiciona a nota ou moeda ao montante e reduz o valor restante
        $montante[] = $nota;
        $valorRestante -= $nota;
    }

    return $montante;
}

// Função para organizar as cédulas e moedas em pacotes conforme as regras
function organizarEmPacotes($montante) {
    $pacotes = []; // Lista de pacotes
    $pacoteAtual = []; // Pacote em construção
    $valorPacote = 0; // Valor acumulado no pacote

    // Percorre todas as notas geradas no montante
    foreach ($montante as $nota) {
        // Adiciona ao pacote atual se não ultrapassar as regras
        if (count($pacoteAtual) < 20 && $valorPacote + $nota <= 500) {
            $pacoteAtual[] = $nota;
            $valorPacote += $nota;
        } else {
            // Se ultrapassar as regras, inicia um novo pacote
            $pacotes[] = $pacoteAtual;
            $pacoteAtual = [$nota];
            $valorPacote = $nota;
        }
    }

    // Adiciona o último pacote à lista
    if (!empty($pacoteAtual)) {
        $pacotes[] = $pacoteAtual;
    }

    return $pacotes;
}

// Obtém o valor total informado pelo usuário
$valorTotal = $_POST['valor'];

// Gera o montante aleatório baseado no valor informado
$montante = gerarMontante($valorTotal);

// Organiza o montante em pacotes conforme as regras
$pacotes = organizarEmPacotes($montante);

// Exibe a composição total gerada
echo "<h2>Composição Total Gerada:</h2>";
$contagem = array_count_values($montante);
foreach ($contagem as $nota => $quantidade) {
    echo "R$ $nota,00: $quantidade unidades<br>";
}

// Exibe a quantidade total de pacotes criados
echo "<h2>Pacotes Necessários:</h2>";
echo count($pacotes) . " pacotes<br>";

// Exibe detalhes de cada pacote
echo "<h2>Detalhe de Cada Pacote:</h2>";
foreach ($pacotes as $i => $pacote) {
    $totalPacote = array_sum($pacote);
    echo "<strong>Pacote " . ($i + 1) . ":</strong> Valor total: R$ $totalPacote<br>";

    // Mostra a quantidade de cada cédula e moeda dentro do pacote
    $contagemPacote = array_count_values($pacote);
    foreach ($contagemPacote as $nota => $quantidade) {
        echo "R$ $nota,00: $quantidade unidades<br>";
    }
    echo "<hr>";
}
?>
