<?php
/**
 * Created by PhpStorm.
 * User: ewerson
 * Date: 26/07/18
 * Time: 16:24
 */
namespace Ewersonfc\CNABPagamento;

use Ewersonfc\CNABPagamento\Constants\TipoRetorno;
use Ewersonfc\CNABPagamento\Entities\DataFile;
use Ewersonfc\CNABPagamento\Exceptions\FileRetornoException;
use Ewersonfc\CNABPagamento\Services\ServiceRemessa;
use Ewersonfc\CNABPagamento\Services\ServiceRetorno;

/**
 * Class CNABPagamento
 */
class CNABPagamento
{
    /**
     * @var ServiceRemessa
     */
    private $serviceRemessa;

    /**
     * @var ServiceRetorno
     */
    private $serviceRetorno;

    /**
     * CNABPagamento constructor.
     * @param $banco
     * @throws Exceptions\CNABPagamentoException
     */
    function __construct($banco)
    {
        $this->serviceRemessa = new ServiceRemessa(Bancos::getBankData($banco));
        $this->serviceRetorno = new ServiceRetorno(Bancos::getBankData($banco));
    }

    /**
     * @param DataFile $dataFile
     * @return string
     * @throws Exceptions\CNABPagamentoException
     * @throws Exceptions\HeaderYamlException
     * @throws Exceptions\LayoutException
     */
    public function gerarArquivo(DataFile $dataFile)
    {
        $file = $this->serviceRemessa->makeFile($dataFile);
        return json_encode([
            'file' => $file,
        ]);
    }

    /**
     * @param $archivePath
     * @throws \Exception
     */
    public function processarRetorno($archivePath, $tipoRetorno)
    {
        if(!in_array($tipoRetorno, [
            TipoRetorno::CONFIRMACAO_REJEICAO,
            TipoRetorno::LIQUIDACAO,
            TipoRetorno::DDA,
        ])) {
            throw new FileRetornoException("Tipo de retorno definido não existe.");
        };
        $data = $this->serviceRetorno->readFile($archivePath, $tipoRetorno);
        return $data;
    }
}