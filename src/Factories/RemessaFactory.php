<?php
/**
 * Created by PhpStorm.
 * User: ewerson
 * Date: 02/08/18
 * Time: 16:05
 */

namespace Ewersonfc\CNABPagamento\Factories;
use Dompdf\Exception;
use Ewersonfc\CNABPagamento\Helpers\FunctionsHelper;

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
     *
     */
    private $content;

    /**
     * RemessaFactory constructor.
     * @param array $header
     * @param array $detail
     */
    function __construct(array $header, array $detail)
    {
        $this->header = $header;
        $this->detail = $detail;
    }

    /**
     * @param array $fieldData
     * @param $nameField
     * @return string
     * @throws \Exception
     */
    private function makeField(array $fieldData, $nameField)
    {
        $defaultValue = !isset($fieldData['default'])?:$fieldData['default'];

        if(!isset($fieldData['value']))
            $valueDefined = ' ';
        else if($defaultValue)
            $valueDefined = $defaultValue;
        else
            $valueDefined = $fieldData['value'];

        $pictureData = FunctionsHelper::explodePicture($fieldData['picture']);

        if($pictureData['firstType'] == 9)
            return str_pad($valueDefined, $pictureData['firstQuantity'], "0", STR_PAD_LEFT);
        if($pictureData['firstType'] == 'X') {
            if(strlen($valueDefined) > $pictureData['firstQuantity'])
                throw new \Exception("O Valor Passado no campo {$nameField} está maior.");

            return str_pad($valueDefined, $pictureData['firstQuantity'], " ", STR_PAD_LEFT);
        }
    }

    /**
     *
     */
    private function makeHeader()
    {
        if(!is_array($this->header))
            throw new Exception();

        foreach($this->header as $nameField => $fieldData)
        {
            $this->content .= $this->makeField($fieldData, $nameField);

            if(strlen($this->content) > $fieldData['pos'][1])
                throw new \Exception("O Campo {$nameField} deve conter caracteres neste padrão: {$fieldData['picture']}");
        }

        print_r($this->content);
        exit();
    }

    /**
     *
     */
    public function generateFile()
    {
         $header = $this->makeHeader();


    }


}