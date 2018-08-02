<?php
/**
 * Created by PhpStorm.
 * User: ewerson
 * Date: 02/08/18
 * Time: 12:08
 */
namespace Ewersonfc\CNABPagamento\Format;
use Ewersonfc\CNABPagamento\Exceptions\HeaderYamlException;
use Ewersonfc\CNABPagamento\Exceptions\LayoutException;

/**
 * Class Yaml
 * @package Ewersonfc\CNABPagamento\Format
 */
class Yaml extends \Symfony\Component\Yaml\Yaml
{
    /**
     * @var
     */
    private $path;

    /**
     * @var
     */
    private $fields;

    /**
     * Yaml constructor.
     * @param $path
     */
    function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @return mixed
     * @throws HeaderYamlException
     * @throws LayoutException
     */
    public function readHeader()
    {
        $filename = "{$this->path}/header.yml";

        if(!file_exists($filename))
            throw new HeaderYamlException("Arquivo de configuração header.yml não encontrado em: $this->path");

        $this->fields = $this->parse(file_get_contents($filename));

        return $this->validateLayout();
    }

    /**
     * @return mixed
     * @throws HeaderYamlException
     * @throws LayoutException
     */
    public function readDetail()
    {
        $filename = "{$this->path}/detalhe_boleto.yml";

        if(!file_exists($filename))
            throw new HeaderYamlException("Arquivo de configuração detail.yml não encontrado em: $this->path");

        $this->fields = $this->parse(file_get_contents($filename));

        return $this->validateLayout();
    }

    /**
     * @return mixed
     * @throws LayoutException
     */
    private function validateLayout()
    {
        if(empty($this->fields))
            throw new LayoutException("No field found");

        $this->validateCollision();

        return $this->fields;
    }

    /**
     * @throws LayoutException
     */
    private function validateCollision()
    {
        foreach($this->fields as $name => $field){
            $pos_start = $field['pos'][0];
            $pos_end = $field['pos'][1];
            foreach($this->fields as $current_name => $current_field){
                if ($current_name === $name)
                    continue;
                $current_pos_start = $current_field['pos'][0];
                $current_pos_end = $current_field['pos'][1];
                if(!is_numeric($current_pos_start) || !is_numeric($current_pos_end))
                    continue;
                if($current_pos_start > $current_pos_end)
                    throw new LayoutException("In the {$current_name} field the starting position ({$current_pos_start}) must be less than or equal to the final position ({$current_pos_end})");
                if(($pos_start >= $current_pos_start && $pos_start <= $current_pos_end) || ($pos_end <= $current_pos_end && $pos_end >= $current_pos_start))
                    throw new LayoutException("The {$name} field collides with the field {$current_name}");
            }
        }
    }
}