<?php
/**
 * Created by PhpStorm.
 * User: ewerson
 * Date: 29/08/18
 * Time: 15:13
 */

namespace Ewersonfc\CNABPagamento\Factories;


use Ewersonfc\CNABPagamento\Entities\DataFile;

class RetornoFactory
{
    /**
     * @var
     */
    private $header;

    /**
     * @var
     */
    private $detail;

    /**
     * RetornoFactory constructor.
     * @param $header
     * @param $detail
     * @param $trailer
     */
    function __construct($header, $detail)
    {
        $this->header = $header;
        $this->detail = $detail;
    }

    /**
     * @return DataFile
     */
    public function generateResponse()
    {
        $dataFile = new DataFile();
        $dataFile->header = $this->header;
        $dataFile->detail = $this->detail;

        return $dataFile;
    }
}