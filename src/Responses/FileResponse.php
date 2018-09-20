<?php

namespace Ewersonfc\CNABPagamento\Responses;
/**
 * Created by PhpStorm.
 * User: ewerson
 * Date: 20/09/18
 * Time: 11:23
 */

class FileResponse
{
    public $aprovado;

    public $tipoAprovacao;

    public $rejeicao;

    public $registro;

    public $inscricao;

    public $agencia;

    public $fornecedor;

    public $documento;

    public $compromisso;

    public $dataPagamento;

    public $dataVencimento;

    public $operacao;

    public $ocorrencia;

    public $dataOcorrencia;

    public $negociacaoCompromisso;

    public $valorCompromisso;

    public $agenciaDestino;

    public $contaDestino;

    /**
     * @return mixed
     */
    public function getAprovado()
    {
        return $this->aprovado;
    }

    /**
     * @return mixed
     */
    public function getTipoAprovacao()
    {
        return $this->tipoAprovacao;
    }

    /**
     * @return mixed
     */
    public function getRejeicao()
    {
        return $this->rejeicao;
    }

    /**
     * @return mixed
     */
    public function getRegistro()
    {
        return $this->registro;
    }

    /**
     * @return mixed
     */
    public function getInscricao()
    {
        return $this->inscricao;
    }

    /**
     * @return mixed
     */
    public function getAgencia()
    {
        return $this->agencia;
    }

    /**
     * @return mixed
     */
    public function getFornecedor()
    {
        return $this->fornecedor;
    }

    /**
     * @return mixed
     */
    public function getDocumento()
    {
        return $this->documento;
    }

    /**
     * @return mixed
     */
    public function getCompromisso()
    {
        return $this->compromisso;
    }

    /**
     * @return mixed
     */
    public function getDataPagamento()
    {
        return $this->dataPagamento;
    }

    /**
     * @return mixed
     */
    public function getDataVencimento()
    {
        return $this->dataVencimento;
    }

    /**
     * @return mixed
     */
    public function getOperacao()
    {
        return $this->operacao;
    }

    /**
     * @return mixed
     */
    public function getOcorrencia()
    {
        return $this->ocorrencia;
    }

    /**
     * @return mixed
     */
    public function getDataOcorrencia()
    {
        return $this->dataOcorrencia;
    }

    /**
     * @return mixed
     */
    public function getNegociacaoCompromisso()
    {
        return $this->negociacaoCompromisso;
    }

    /**
     * @return mixed
     */
    public function getValorCompromisso()
    {
        return $this->valorCompromisso;
    }

    /**
     * @return mixed
     */
    public function getAgenciaDestino()
    {
        return $this->agenciaDestino;
    }

    /**
     * @return mixed
     */
    public function getContaDestino()
    {
        return $this->contaDestino;
    }

}