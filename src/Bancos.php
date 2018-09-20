<?php
/**
 * Created by PhpStorm.
 * User: ewerson
 * Date: 27/07/18
 * Time: 14:51
 */
namespace Ewersonfc\CNABPagamento;

use Ewersonfc\CNABPagamento\Constants\TipoRetorno;
use Ewersonfc\CNABPagamento\Exceptions\CNABPagamentoException;
/**
 * Class Bancos
 */
class Bancos
{
    /**
     * @var integer|422
     */
    const SAFRA = 422;

    /**
     * @param int $banco
     * @return array
     * @throws CNABPagamentoException
     */
    public static function getBankData(int $banco)
    {
        switch ($banco) {
            case self::SAFRA:
                return [
                    'codigo_banco' => '422',
                    'nome_banco' => 'Safra',
                    'path_remessa' => realpath(dirname(__FILE__)."/../resources/Safra/remessa"),
                    'path_retorno' => realpath(dirname(__FILE__)."/../resources/Safra/retorno")
                ];
            default:
                throw new CNABPagamentoException("Banco não encontrado.");
                break;
        }
    }

    public static function getSafraDetailType($type)
    {
        switch ($type)
        {
            case 'C':
                return TipoRetorno::CONFIRMACAO_REJEICAO;
            case 'L':
                return TipoRetorno::LIQUIDACAO;
            default:
                return TipoRetorno::CONFIRMACAO_REJEICAO;
        }
    }
}