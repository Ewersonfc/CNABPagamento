<?php

require 'vendor/autoload.php';

use Ewersonfc\CNABPagamento\CNABPagamento;
use Ewersonfc\CNABPagamento\Entities\DataFile;

$header = [
    'codigo_registro' => 0, // OPCIONAL
    'codigo_arquivo' => 1, // // OPCIONAL
    'identficacao_arquivo' => 'REMESSA', // OPCIONAL
    'codigo_servico' => 11, // OPCIONAL
    'identificacao_servico' => 'PAGTOS FORNECED',
    'numero_conta' => '12345678',
    'validar_trailler' => 'N', // OPCIONAL
    'codigo_agencia' => '11200',
    'nome_cliente' => 'NOME CLIENT BANCARIO',
    'codigo_banco' => '422', // OPCIONAL
    'nome_banco' => 'BANCO SAFRA S/A', // OPCIONAL
    'data_arquivo' => '200618', // (20/06/2018) OPCIONAL
    'origem' => '1',
    'terceiro' => '11',
    'valida_documento' => 'N', // OPCIONAL
    'numero_arquivo' => '000025', // SEQUENCIA CRIADA POR VOCÊ.
    'numero_registro' => '000001', // DEFAULT
];

$detail = [];
$detail[] = [
    'tipo_transacao' => 'boleto',
 //   'codigo_registro' => 1, // OPCIONAL
  //  'codigo_inscricao' => 02, // 01 CNPJ E 02 CPF
    'numero_inscricao' => '09127271000187',
    'numero_conta' => 2887679,
    'codigo_agencia' => 11200,
    'exclusivo' => "Texto livre",
    'codigo_fornecedor' => 41386638862,
    'tipo_documento' => 'DUP',
    'numero_compromisso' => 1, // irá preencher com 0 à esquerda
    'sequencia_compromisso' => 1,
    'codigo_operacao' => 'C', // Opcional
    'codigo_ocorrencia' => 01,
    'seu_numero_compromisso' => 1, // irá preencher com 0 à esquerda
    'vencimento' => 250718,
    'valor_pagamento' => 100.00, // irá preencher com 0 à esquerda
    'tipo_pagamento' => 'COB', // Opcional
    'banco_destino' => 001,
    'agencia_destino' => '1236547', // Opcional
    'conta_corrente_destino' => '1010101010',// Opcional
    'agencia_pagamento' => '1234567', //Verificar se não houver
    'nosso_numero' => '506012548', // opcional
    'banco_portador' => '001', // Verificar
    'abatimento' => 20.00, // // irá preencher com 0 à esquerda
    'nome_fornecedor' => 'Ewerson Ferreira Carvalho',
    'codigo_barras' => '00191761000000113330000003071378005071620317', // linha digitável informada de acordo com a documentação do banco safra
    'juros_de_mora' => 5.00, // informar se houver | irá preencher com 0 à esquerda
    'data_pagamento' => 250718, // data que deseja liquidar o título
    'valor_autorizado' => 80.00, // Valor total - abatimento + juros se houver
    'moeda' => 'REAL', // OPCIONAL
    'carteira' => '1727', // OPCIONAL
    'especie_documento' => 'DUP', // OPCIONAL
    'numero_sequencial_registro' => '000002'
];

$trailer = [
    'valortotal' => 100.00,
    'total_abatimento' =>  20.00,
    'total_juros_mora' => 5.00,
    'total_valor_autorizado' => 80.00,
];

$datafile = new DataFile;
$datafile->header = $header;
$datafile->detail = $detail;
$datafile->trailer = $trailer;

$CNABPagamento = new CNABPagamento(422);
$file = $CNABPagamento->gerarArquivo($datafile);


print_r($file);


