<?php

namespace srag\DIC\Reporting\Database;

use ilDBStatement;
use ilPDOStatement;
use PDOStatement;
use srag\DIC\Reporting\Exception\DICException;

/**
 * Class PdoStatementContextHelper
 *
 * @package srag\DIC\Reporting\Database
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @internal
 */
final class PdoStatementContextHelper extends ilPDOStatement
{

    /**
     * PdoStatementContextHelper constructor
     *
     * @throws DICException
     */
    public function __construct()
    {
        throw new DICException("");

        parent::__construct(self::getPdoStatement($this));
    }


    /**
     * @param ilDBStatement $stm
     *
     * @return PDOStatement
     *
     * @throws DICException PdoStatementContextHelper only supports ilPDOStatement!
     *
     * @internal
     */
    public static function getPdoStatement(ilDBStatement $stm) : PDOStatement
    {
        if (!($stm instanceof ilPDOStatement)) {
            throw new DICException("PdoStatementContextHelper only supports ilPDOStatement!");
        }

        return $stm->pdo_statement;
    }
}
