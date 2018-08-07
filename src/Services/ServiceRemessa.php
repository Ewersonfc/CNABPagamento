<?php
/**
 * Created by PhpStorm.
 * User: ewerson
 * Date: 02/08/18
 * Time: 10:51
 */
namespace Ewersonfc\CNABPagamento\Services;

use Ewersonfc\CNABPagamento\Constants\TipoTransacao;
use Ewersonfc\CNABPagamento\Entities\DataFile;
use Ewersonfc\CNABPagamento\Exceptions\CNABPagamentoException;
use Ewersonfc\CNABPagamento\Exceptions\LayoutException;
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
     * ServiceRemessa constructor.
     */
    function __construct($banco)
    {
        $this->banco = $banco;
        $this->yaml = new Yaml($this->banco['path_remessa']);
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
    private function readDetailYml($typeLayout)
    {
        return $this->yaml->readDetail($typeLayout);
    }

    /**
     * @return mixed
     * @throws \Ewersonfc\CNABPagamento\Exceptions\HeaderYamlException
     */
    private function readTrailerYml()
    {
        return $this->yaml->readTrailer();
    }

    /**
     * @return array
     */
    private function typeOfPayments()
    {
        return [
            TipoTransacao::BOLETO,
            TipoTransacao::CHEQUE,
            TipoTransacao::TRANSFERENCIA
        ];
    }

    /**
     * @param array $headerYmlStructure
     * @param DataFile $dataFile
     * @return array
     * @throws CNABPagamentoException
     */
    private function matchHeaderFileAndHeaderData(DataFile $dataFile)
    {
        if(empty($dataFile->header))
            throw new CNABPagamentoException();

        $ymlHeaderToArray = $this->readHeaderYml();

        foreach($dataFile->header as $key => $headerData) {
            $messageErro = "Chave passada no Header [array] difere do arquivo de configuração yml: {$key}";
            if(!array_key_exists($key, $ymlHeaderToArray))
                throw new CNABPagamentoException($messageErro);

            $ymlHeaderToArray[$key]['value'] = $headerData;
        }

        return $ymlHeaderToArray;
    }

    /**
     * @param DataFile $dataFile
     * @return array
     * @throws CNABPagamentoException
     * @throws LayoutException
     * @throws \Ewersonfc\CNABPagamento\Exceptions\HeaderYamlException
     */
    private function matchDetailFileAndDetailData(DataFile $dataFile)
    {
        if(!array_key_exists("0", $dataFile->detail))
            throw new CNABPagamentoException("O array de detalhes está inválido, consulte a documentação.");

        $detailMadeByYmlStructure = [];
        foreach($dataFile->detail as $key => $data) {
            /**
             * Load layout of detail
             */
            if(!in_array($data['tipo_transacao'], $this->typeOfPayments()))
                throw new LayoutException("Tipo de pagamento inválido ou não informado.");

            $ymlDetailToArray = $this->readDetailYml($data['tipo_transacao']);

            /**
             * Delete key
             */
            unset($data['tipo_transacao']);

            foreach($data as $field => $value) {
                $messageErro = "Chave passada no Detail [array] difere do arquivo de configuração yml: {$field}";
                if(!array_key_exists($field, $ymlDetailToArray))
                    throw new CNABPagamentoException($messageErro);

                $ymlDetailToArray[$field]['value'] = $value;
            }
            $detailMadeByYmlStructure[] = $ymlDetailToArray;
        }

        return $detailMadeByYmlStructure;
    }

    private function matchTrailerFileAndTrailerData(DataFile $dataFile)
    {
        if(empty($dataFile->trailer))
            throw new CNABPagamentoException();

        $ymlTrailerToArray = $this->readTrailerYml();

        foreach($dataFile->trailer as $key => $trailerData) {
            $messageErro = "Chave passada no Trailer [array] difere do arquivo de configuração yml: {$key}";
            if(!array_key_exists($key, $ymlTrailerToArray))
                throw new CNABPagamentoException($messageErro);

            $ymlTrailerToArray[$key]['value'] = $trailerData;
        }
        return $ymlTrailerToArray;
    }
    /**
     * @param DataFile $dataFile
     * @return string
     * @throws CNABPagamentoException
     * @throws \Ewersonfc\CNABPagamento\Exceptions\HeaderYamlException
     * @throws \Ewersonfc\CNABPagamento\Exceptions\LayoutException
     */
    final public function makeFile(DataFile $dataFile)
    {
        $matchHeader = $this->matchHeaderFileAndHeaderData($dataFile);
        $matchDetail = $this->matchDetailFileAndDetailData($dataFile);
        $matchTrailer = $this->matchTrailerFileAndTrailerData($dataFile);

        $remessaFactory = new RemessaFactory($matchHeader, $matchDetail, $matchTrailer);
        return $remessaFactory->generateFile();
    }
}
