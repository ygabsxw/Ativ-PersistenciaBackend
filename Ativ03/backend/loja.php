<?php
header('Content-Type: application/json');

function responder($sucesso, $mensagem, $dados = null) {
    $resposta = [
        'sucesso' => $sucesso,
        'mensagem' => $mensagem
    ];
    
    if ($sucesso && $dados) {
        $resposta['dados'] = $dados;
    }

    echo json_encode($resposta);
    exit; 
}

if (!isset($_GET['codigo']) || !isset($_GET['moedas'])) {
    responder(false, "Erro: Parâmetros 'codigo' ou 'moedas' ausentes.");
}

$codigo_item = $_GET['codigo'];
$moedas_jogador = (int)$_GET['moedas']; 

$caminho_json = 'carros.json';

if (!file_exists($caminho_json)) {
    responder(false, "Erro interno: Arquivo de itens não encontrado.");
}

$json_str = file_get_contents($caminho_json);
$todos_itens = json_decode($json_str, true);


if (!isset($todos_itens[$codigo_item])) {
    responder(false, "Erro: Item com o código '{$codigo_item}' não existe.");
}

$item_selecionado = $todos_itens[$codigo_item];
$preco_item = (int)$item_selecionado['preco'];


if ($moedas_jogador >= $preco_item) {
    responder(true, "Compra autorizada!", $item_selecionado);
} else {
    responder(false, "Moedas insuficientes. Você tem {$moedas_jogador}, mas o item custa {$preco_item}.");
}