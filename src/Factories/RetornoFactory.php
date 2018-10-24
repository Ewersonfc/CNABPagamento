<?php
/**
 * Created by PhpStorm.
 * User: ewerson
 * Date: 29/08/18
 * Time: 15:13
 */

namespace Ewersonfc\CNABPagamento\Factories;


use Ewersonfc\CNABPagamento\Responses\FileResponse;
use Ewersonfc\CNABPagamento\Entities\DataFile;

class RetornoFactory
{
    /**
     * @var
     */
    private $header;

    /**
     * @var
     */
    private $detail;

    /**
     * RetornoFactory constructor.
     * @param $header
     * @param $detail
     * @param $trailer
     */
    function __construct($header, $detail)
    {
        $this->header = $header;
        $this->detail = $detail;
    }

    private function makeRejeicao($dataRejeicao)
    {
        return str_split($dataRejeicao, 3);
    }

    /**
     * @return DataFile
     */
    public function generateSafraResponse()
    {
        $response = [];
        foreach($this->detail as $detail) {
            $approved = false;
            $fileResponse = new FileResponse;

            if(($detail['codigo_operacao']['value'] == 'C' AND trim($detail['codigo_rejeicao']['value']) == null) OR $detail['codigo_operacao']['value'] == 'L')
                $approved = true;


            $fileResponse->aprovado = $approved;
            $fileResponse->tipoAprovacao = $detail['codigo_operacao']['value'];
            if(!$approved) {
                $fileResponse->rejeicao = $this->makeRejeicao(
                    $detail['codigo_rejeicao']['value'].$detail['codigo_motivo_rejeicao']['value']
                );
            }
            $fileResponse->registro = $detail['codigo_registro']['value'];
            $fileResponse->inscricao = $detail['codigo_registro']['value'];
            $fileResponse->agencia = $detail['codigo_agencia']['value'];
            $fileResponse->fornecedor = $detail['codigo_fornecedor']['value'];
            $fileResponse->documento = $detail['tipo_documento']['value'];
            $fileResponse->compromisso = $detail['seu_numero']['value'];
            $fileResponse->dataPagamento = $detail['data_pagamento']['value'];
            $fileResponse->dataVencimento = $detail['data_vencimento']['value'];
            $fileResponse->operacao = $detail['codigo_operacao']['value'];
            $fileResponse->ocorrencia = $detail['codigo_ocorrencia']['value'];
            $fileResponse->dataOcorrencia = $detail['data_ocorrencia']['value'];
            $fileResponse->numeroCompromisso = $detail['negociacao_compromisso']['value'];
            $fileResponse->valorCompromisso = $detail['valor_compromisso']['value'];
            $fileResponse->agenciaDestino = $detail['agencia_destino']['value'];
            $fileResponse->contaDestino = $detail['conta_destino']['value'];

            $response[] = $fileResponse;
        }
        return $response;
    }
}


