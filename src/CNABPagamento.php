<?php
/**
 * Created by PhpStorm.
 * User: ewerson
 * Date: 26/07/18
 * Time: 16:24
 */
namespace Ewersonfc\CNABPagamento;

use Ewersonfc\CNABPagamento\Entities\DataFile;
use Ewersonfc\CNABPagamento\Services\ServiceRemessa;

/**
 * Class CNABPagamento
 */
class CNABPagamento
{
    /**
     * @var Bancos
     */
    private $banco;

    private $serviceRemessa;

    /**
     * CNABPagamento constructor.
     * @param $banco
     * @throws Exceptions\CNABPagamentoException
     */
    function __construct($banco)
    {
        $this->serviceRemessa = new ServiceRemessa(Bancos::getBankData($banco));
    }

    /**
     * @param DataFile $dataFile
     * @throws Exceptions\CNABPagamentoException
     * @throws Exceptions\HeaderYamlException
     * @throws Exceptions\LayoutException
     */
    public function gerarArquivo(DataFile $dataFile)
    {
        $this->serviceRemessa->makeFile($dataFile);
    }

}