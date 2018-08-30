<?php
/**
 * Created by PhpStorm.
 * User: ewerson
 * Date: 29/08/18
 * Time: 14:43
 */

namespace Ewersonfc\CNABPagamento\Services;

use Ewersonfc\CNABPagamento\Exceptions\FileRetornoException;
use Ewersonfc\CNABPagamento\Factories\RetornoFactory;
use Ewersonfc\CNABPagamento\Format\Yaml;

/**
 * Class ServicoRetorno
 * @package Ewersonfc\CNABPagamento\Services
 */
class ServiceRetorno
{
    /**
     * @var
     */
    private $filePath;

    /**
     * @var
     */
    private $dataFile;

    /**
     * @var
     */
    private $banco;

    /**
     * @var RetornoFactory
     */
    private $retornoFactory;

    /**
     * ServicoRetorno constructor.
     */
    function __construct($banco)
    {
        $this->banco = $banco;
        $this->yaml = new Yaml($this->banco['path_retorno']);
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

    private function readDetailYml($tipoRetorno)
    {
        return $this->yaml->readDetail($tipoRetorno);
    }

    /**
     * @return array
     * @throws FileRetornoException
     */
    private function readFileData()
    {
        try {
            $data = explode("\n", file_get_contents($this->filePath));
        } catch (\Exception $e) {
            throw new FileRetornoException();
        }
        $this->dataFile = $data;
    }



    private function makefield($string, $field)
    {
        $fieldPosition = $field['pos'][0]-1;
        $field['value'] = substr($string, $fieldPosition, $field['pos'][1]-$fieldPosition);

        return $field;
    }

    private function matchHeaderFileAndHeaderData()
    {
        $this->readFileData();

        $header = array_shift($this->dataFile);
        $headerYml = $this->readHeaderYml();

        $headerComplete = [];
        foreach($headerYml as $key => $field )
        {
            $headerComplete[$key] = $this->makefield($header, $field);
        }

        return $headerComplete;
    }


    private function matchDetailFileAndDetailData($tipoRetorno)
    {
        $onlyDetails = array_slice($this->dataFile, 1, count(array_filter($this->dataFile)) - 2);
        $detailYml = $this->readDetailYml($tipoRetorno);

        $detailComplete = [];
        foreach ($onlyDetails as $keyDetail => $detail) {
            foreach ($detailYml as $key => $field) {
                $detailComplete[$keyDetail][$key] = $this->makefield($detail, $field);
            }
        }

        return $detailComplete;
    }

    /**
     * @param $filePath
     * @param $tipoRetorno
     * @return \Ewersonfc\CNABPagamento\Factories\DataFile
     */
    final public function readFile($filePath, $tipoRetorno)
    {
        $this->filePath = $filePath;

        $header = $this->matchHeaderFileAndHeaderData();
        $detail = $this->matchDetailFileAndDetailData($tipoRetorno);

        $retornoFactory = new RetornoFactory($header, $detail);
        return $retornoFactory->generateResponse();
    }
}