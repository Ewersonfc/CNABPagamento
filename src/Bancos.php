<?php
/**
 * Created by PhpStorm.
 * User: ewerson
 * Date: 27/07/18
 * Time: 14:51
 */
namespace Ewersonfc\CNABPagamento;

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
     * Bancos constructor.
     * @param int $banco
     * @throws CNABPagamentoException
     */
    function __construct(int $banco)
    {
        switch ($banco) {
            case self::SAFRA:
                return [
                    'codigo_banco' => '422',
                    'nome_banco' => 'Safra'
                ];
            default:
                throw new CNABPagamentoException("Banco não encontrado.");
                break;
        }
    }
}