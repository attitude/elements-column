<?php

/**
 * Storage Column Interface
 */

namespace attitude\Elements;

/**
 * Storage Column Interface
 *
 * @author Martin Adamko <@martin_adamko>
 * @version v0.1.0
 * @licence MIT
 *
 */
interface Column_Interface
{
    /**
     * Describes column
     *
     * In SQL terms it returns definition of column like `INT(4) UNSIGNED NOT NULL`
     *
     * @param   void
     * @returns string
     *
     */
    public function describe();

    /**
     * Returns column name
     *
     * @param   void
     * @returns string
     *
     */
    public function name();
}
