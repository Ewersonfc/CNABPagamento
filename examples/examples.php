<?php

require 'vendor/autoload.php';

use Ewersonfc\CNABPagamento\CNABPagamento;
use Ewersonfc\CNABPagamento\Entities\DataFile;
use Ewersonfc\CNABPagamento\Helpers\Helper;

$header = [
    'numero_conta' => '12345678',
    'codigo_agencia' => '11200',
    'nome_cliente' => 'NOME CLIENT BANCARIO',
    'data_arquivo' => Helper::formatDateToRemessa('20/06/2018'), // (20/06/2018) OPCIONAL
    'origem' => '1',
    'terceiro' => '11',
    'numero_arquivo' => '000025', // SEQUENCIA CRIADA POR VOCÊ.
];

$detail = [];
$detail[] = [
    'tipo_transacao' => 'boleto',
    'codigo_inscricao' => Helper::verifyTipoPessoa('09127271000187'),
    'numero_inscricao' => '09127271000187',
    'numero_conta' => 2887679,
    'codigo_agencia' => 11200,
    'exclusivo' => "Texto livre",
    'codigo_fornecedor' => 41386638862,
    'tipo_documento' => 'DUP',
    'numero_compromisso' => 1, // irá preencher com 0 à esquerda
    'sequencia_compromisso' => 1,
    'codigo_ocorrencia' => 01,
    'seu_numero_compromisso' => 1, // irá preencher com 0 à esquerda
    'vencimento' => Helper::formatDateToRemessa('25/07/18'),
    'valor_pagamento' => Helper::valueToNumber('100.00'), // irá preencher com 0 à esquerda
    'banco_destino' => 001,
    'agencia_pagamento' => '1234567', //Verificar se não houver
    'banco_portador' => 001, // Verificar
    'abatimento' => Helper::valueToNumber(20.00), // // irá preencher com 0 à esquerda
    'nome_fornecedor' => 'Ewerson Ferreira Carvalho',
    'codigo_barras' => '00191761000000113330000003071378005071620317', // linha digitável informada de acordo com a documentação do banco safra
    'juros_de_mora' => Helper::valueToNumber(5.00), // informar se houver | irá preencher com 0 à esquerda
    'data_pagamento' => Helper::formatDateToRemessa('25/07/2018'), // data que deseja liquidar o título
    'valor_autorizado' => Helper::valueToNumber(80.10), // Valor total - abatimento + juros se houver
    'carteira' => '1727',
];


$trailer = [
    'valortotal' => Helper::valueToNumber(100.00),
    'total_abatimento' =>  Helper::valueToNumber(20.00),
    'total_juros_mora' => Helper::valueToNumber(5.00),
    'total_valor_autorizado' => Helper::valueToNumber(80.00),
];

$datafile = new DataFile;
$datafile->header = $header;
$datafile->detail = $detail;
$datafile->trailer = $trailer;

$CNABPagamento = new CNABPagamento(\Ewersonfc\CNABPagamento\Bancos::SAFRA);
$file = $CNABPagamento->gerarArquivo($datafile);


print_r($file);


