<?php
/**
 * Created by PhpStorm.
 * User: ewerson
 * Date: 02/08/18
 * Time: 16:05
 */

namespace Ewersonfc\CNABPagamento\Factories;

use Ewersonfc\CNABPagamento\Exceptions\CNABPagamentoException;
use Ewersonfc\CNABPagamento\Exceptions\LayoutException;
use Ewersonfc\CNABPagamento\Helpers\CNABHelper;
use mikehaertl\tmp\File;

/**
 * Class RemessaFactory
 * @package Ewersonfc\CNABPagamento\Factories
 */
class RemessaFactory
{
    /**
     * @var array
     */
    private $header;
    /**
     * @var array
     */
    private $detail;

    /**
     * @var array
     */
    private $trailer;

    /**
     * @var string
     */
    private $content;

    /**
     * @var integer
     */
    private $control;

    /**
     * RemessaFactory constructor.
     * @param array $header
     * @param array $detail
     */
    function __construct(array $header, array $detail, array $trailer)
    {
        $this->header = $header;
        $this->detail = $detail;
        $this->trailer = $trailer;
        $this->control = 1;
    }

    /**
     * @param array $fieldData
     * @param $nameField
     * @return string
     * @throws \Exception
     */
    private function makeField(array $fieldData, $nameField, $lastField = false)
    {
        $valueDefined = null;
        if(preg_match('/branco/', $nameField)) {
            $valueDefined = ' ';
        }

        if($lastField) {
            $valueDefined = $this->control;
            $this->control++;
        }

        if(isset($fieldData['value']) && $valueDefined === null) {
            $valueDefined = $fieldData['value'];
        } else if($valueDefined === null && isset($fieldData['default'])){
            $valueDefined = $fieldData['default'];
        }

        $pictureData = CNABHelper::explodePicture($fieldData['picture']);

        if(strlen($valueDefined) > $pictureData['firstQuantity'])
            throw new LayoutException("O Valor Passado no campo {$nameField} está maior que os campos disponíveis..");

        if($pictureData['firstType'] == 9)
            return str_pad($valueDefined, $pictureData['firstQuantity'], "0", STR_PAD_LEFT);
        if($pictureData['firstType'] == 'X') {
            return str_pad(strtr(
                    utf8_decode($valueDefined),
                    utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY')
                , $pictureData['firstQuantity'], " ", STR_PAD_LEFT);
            
        }
    }

    /**
     * @throws \Exception
     */
    private function makeHeader()
    {
        if(!is_array($this->header))
            throw new LayoutException("Header inválido.");

        foreach($this->header as $nameField => $fieldData) {
            $arrayKeys = array_keys($this->header);
            $lastField = end($arrayKeys) == $nameField?true:false;

            $this->content .= $this->makeField($fieldData, $nameField, $lastField);

            $message = "O Campo {$nameField} deve conter caracteres neste padrão: {$fieldData['picture']}";
            if(strlen($this->content) > $fieldData['pos'][1])
                throw new LayoutException($message);
        }
        unset($nameField, $fieldData, $arrayKeys, $lastField);
        $this->content .= PHP_EOL;
    }

    /**
     * @throws LayoutException
     */
    private function makeDetail()
    {
        if(!array_key_exists("0", $this->detail))
            throw new LayoutException("Lista de detalhes está inválida.");

        foreach($this->detail as $keyDetail => $data) {
            $detail = null;
            foreach($data as $nameField => $fieldData) {
                $arrayKeys = array_keys($data);
                $lastField = end($arrayKeys) == $nameField?true:false;

                $detail .= $this->makeField($fieldData, $nameField, $lastField);
                $message = "O Campo {$nameField} deve conter caracteres neste padrão: {$fieldData['picture']}";
                if(strlen($detail) > $fieldData['pos'][1])
                    throw new LayoutException($message);
            }
            unset($nameField, $fieldData, $arrayKeys, $lastField);
            $this->content .= $detail . PHP_EOL;
        }
    }

    /**
     * @throws LayoutException
     */
    private function makeTrailer()
    {
        if(!is_array($this->trailer))
            throw new LayoutException("Trailer inválido.");

        $trailer = null;
        foreach($this->trailer as $nameField => $fieldData) {
            $arrayKeys = array_keys($this->trailer);
            $lastField = end($arrayKeys) == $nameField?true:false;

            $trailer .= $this->makeField($fieldData, $nameField, $lastField);

            if(strlen($trailer) > $fieldData['pos'][1])
                throw new LayoutException("O Campo {$nameField} deve conter caracteres neste padrão: {$fieldData['picture']}");
        }
        unset($nameField, $fieldData);
        $this->content .= $trailer;
    }

    /**
     * @throws \Exception
     */
    public function generateFile()
    {
        $this->makeHeader();
        $this->makeDetail();
        $this->makeTrailer();

        try {
            $file = new File($this->content, '.txt');
            $file->delete = false;
        } catch(\Exception $e) {
            throw new CNABPagamentoException("Não foi possível baixar o arquivo.");
        }

        return $file->getFileName();
    }
}