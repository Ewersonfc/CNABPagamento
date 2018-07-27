<?php
/**
 * Created by PhpStorm.
 * User: ewerson
 * Date: 26/07/18
 * Time: 16:24
 */
namespace Ewersonfc\CNABPagamento;

use Ewersonfc\CNABPagamento\Entities\DataFile;
/**
 * Class CNABPagamento
 */
class CNABPagamento
{
    /**
     * @var Bancos
     */
    private $banco;

    /**
     * CNABPagamento constructor.
     * @param $banco
     * @throws Exceptions\CNABPagamentoException
     */
    function __construct($banco)
    {
        $this->banco = new Bancos($banco);
    }

    /**
     * @param DataFile $dataFile
     */
    public function gerarArquivo(DataFile $dataFile)
    {
        echo '<pre>';
        print_r($dataFile);
        echo '</pre>';
    }

}