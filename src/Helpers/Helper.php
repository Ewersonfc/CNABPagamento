<?php
/**
 * Created by PhpStorm.
 * User: ewerson
 * Date: 07/08/18
 * Time: 12:29
 */

namespace Ewersonfc\CNABPagamento\Helpers;

use Ewersonfc\CNABPagamento\Constants\TipoInscricao;

/**
 * Class Helper
 * @package Ewersonfc\CNABPagamento\Helper
 */
class Helper
{
    /**
     * @param $value
     * @return string
     */
    public static function valueToNumber($value)
    {
        $brlFormat = preg_match('/,/', $value);
        if($brlFormat)
            $value = self::brlFormatToUSAFormat($value);

        return number_format($value, 2,'','');
    }

    /**
     * @param $value
     * @return mixed
     */
    public static function brlFormatToUSAFormat($value)
    {
        $value = str_replace('.', '', $value);
        $value = str_replace(',', '.', $value);

        return $value;
    }

    /**
     * @param $cnpj
     * @return int
     */
    public static function verifyTipoPessoa($document)
    {
        $document = preg_replace('/[^0-9]/', '', (string) $document);
        if (strlen($document) != 14)
            return TipoInscricao::CPF;

        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
        {
            $soma += $document{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        if ($document{12} != ($resto < 2 ? 0 : 11 - $resto))
            return TipoInscricao::CPF;

        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
        {
            $soma += $document{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;

        if($document{13} == ($resto < 2 ? 0 : 11 - $resto))
            return TipoInscricao::CNPJ;
    }

    /**
     * @param $date
     * @return mixed
     */
    public static function formatDateToRemessa($date)
    {
        $formatoBrasileiro = preg_match('/\d{2}\/\d{2}\/\d{4}/', $date);
        if($formatoBrasileiro)
            return \DateTime::createFromFormat('d/m/Y', $date)->format('dmy');

        $formatoAmericano = preg_match('/\d{4}\-\d{2}\-\d{2}/', $date);
        if($formatoAmericano)
            return \DateTime::createFromFormat('Y-m-d', $date)->format('dmy');
    }
}
