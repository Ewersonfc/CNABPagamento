<?php
/**
 * Created by PhpStorm.
 * User: ewerson
 * Date: 02/08/18
 * Time: 10:51
 */
namespace Ewersonfc\CNABPagamento\Services;

use Ewersonfc\CNABPagamento\Entities\DataFile;
use Ewersonfc\CNABPagamento\Exceptions\CNABPagamentoException;
use Ewersonfc\CNABPagamento\Factories\RemessaFactory;
use Ewersonfc\CNABPagamento\Format\Yaml;

/**
 * Class ServiceRemessa
 * @package Ewersonfc\CNABPagamento\Services
 */
class ServiceRemessa
{
    /**
     * @var
     */
    private $banco;

    /**
     * @var
     */
    private $yaml;

    /**
     *
     */
    private $remessaFactory;

    /**
     * ServiceRemessa constructor.
     */
    function __construct($banco)
    {
        $this->banco = $banco;
        $this->yaml = new Yaml($this->banco['path_remessa']);
        $this->remessaFactory = new RemessaFactory;
    }

    /**
     * @return mixed
     * @throws \Ewersonfc\CNABPagamento\Exceptions\HeaderYamlException
     * @throws \Ewersonfc\CNABPagamento\Exceptions\LayoutException
     */
    private function readHeaderYml()
    {
        return $this->yaml->readHeader();
    }

    /**
     * @return mixed
     * @throws \Ewersonfc\CNABPagamento\Exceptions\HeaderYamlException
     * @throws \Ewersonfc\CNABPagamento\Exceptions\LayoutException
     */
    private function readDetailYml()
    {
        return $this->yaml->readDetail();
    }

    /**
     * @param array $headerYmlStructure
     * @param DataFile $dataFile
     * @return array
     * @throws CNABPagamentoException
     */
    private function matchHeaderFileAndHeaderData(array $headerYmlStructure, DataFile $dataFile)
    {
        if(empty($dataFile->header))
            throw new CNABPagamentoException();

        foreach($dataFile->header as $key => $headerData) {
            $messageErro = "Chave passada no array difere do arquivo de configuração yml: {$key}";
            if(!array_key_exists($key, $headerYmlStructure))
                throw new CNABPagamentoException($messageErro);

            $headerYmlStructure[$key]['value'] = $headerData;
        }

        return $headerYmlStructure;
    }

    /**
     * @param array $detailYmlStructure
     * @param DataFile $dataFile
     * @return array
     * @throws CNABPagamentoException
     */
    private function matchDetailFileAndDetailData(array $detailYmlStructure, DataFile $dataFile)
    {
        if(!array_key_exists("0", $dataFile->detail))
            throw new CNABPagamentoException();

        foreach($dataFile->detail as $key => $data) {
            foreach($data as $field => $value) {
                $messageErro = "Chave passada no array difere do arquivo de configuração yml: {$key}";
                if(!array_key_exists($field, $detailYmlStructure))
                    throw new CNABPagamentoException($messageErro);

                $detailYmlStructure[$field]['value'] = $value;
            }
        }

        return $detailYmlStructure;
    }

    /**
     * @param DataFile $dataFile
     * @throws CNABPagamentoException
     * @throws \Ewersonfc\CNABPagamento\Exceptions\HeaderYamlException
     * @throws \Ewersonfc\CNABPagamento\Exceptions\LayoutException
     */
    final public function makeFile(DataFile $dataFile)
    {
        $ymlHeaderToArray = $this->readHeaderYml();
        $ymlDetailToArray = $this->readDetailYml();

        $matchHeader = $this->matchHeaderFileAndHeaderData($ymlHeaderToArray, $dataFile);
        $matchDetail = $this->matchDetailFileAndDetailData($ymlDetailToArray, $dataFile);


        return $this->remessaFactory->generateFile($matchHeader, $matchDetail);
    }
}