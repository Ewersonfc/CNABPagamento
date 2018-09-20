<?php
/**
 * Created by PhpStorm.
 * User: ewerson
 * Date: 29/08/18
 * Time: 14:43
 */

namespace Ewersonfc\CNABPagamento\Services;

use Ewersonfc\CNABPagamento\Bancos;
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
        $tipoArquivo = Bancos::getSafraDetailType($tipoRetorno);
        return $this->yaml->readDetail($tipoArquivo);
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

    private function getTypeReturnByBank($detailCompletely)
    {
        switch ($this->banco['codigo_banco'])
        {
            case Bancos::SAFRA:
                return $this->readDetailYml(substr($detailCompletely, 108, 108));
            break;
            default:
                throw new \Exception("Não foi possivel toma danada");
        }
    }

    private function matchDetailFileAndDetailData()
    {
        $onlyDetails = array_slice($this->dataFile, 1, count(array_filter($this->dataFile)) - 2);
        $detailComplete = [];
        foreach ($onlyDetails as $keyDetail => $detail) {
            $detailYml = $this->getTypeReturnByBank($detail);
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
    final public function readFile($filePath)
    {
        $this->filePath = $filePath;

        $header = $this->matchHeaderFileAndHeaderData();
        $detail = $this->matchDetailFileAndDetailData();

        $retornoFactory = new RetornoFactory($header, $detail);
        if($this->banco = Bancos::SAFRA)
            return $retornoFactory->generateSafraResponse();

        return false;
    }
}