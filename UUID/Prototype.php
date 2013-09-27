<?php

/**
 * Universarily Unique Identifier Column
 */

namespace attitude\Elements\Column;

use \attitude\Elements\Column_Prototype;
use \attitude\Elements\DependencyContainer;

/**
 * Universarily Unique Identifier Column Class
 *
 * @author Martin Adamko <@martin_adamko>
 * @version v0.1.0
 * @licence MIT
 *
 */
abstract class UUID_Prototype extends Column_Prototype
{
    /**
     * Class constructor
     *
     * Protected visibility allows building singleton class.
     *
     * @param   void
     * @returns object  Returns `$this`
     *
     */
    protected function __construct()
    {
        return parent::__construct();
    }

    /**
     * Returns Universally Unique IDentifier
     *
     * See https://gist.github.com/dahnielson/508447
     *
     * @param   void
     * @returns string  32 bit hexadecimal hash
     *
     */
    public function nextPrimaryKey()
    {
        return sprintf('%04x%04x%04x%04x%04x%04x%04x%04x',

        // 32 bits for "time_low"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),

        // 16 bits for "time_mid"
        mt_rand(0, 0xffff),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand(0, 0x0fff) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand(0, 0x3fff) | 0x8000,

        // 48 bits for "node"
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    /**
     * Describes column
     *
     * In SQL terms it returns definition of column like `INT(4) UNSIGNED NOT NULL`
     *
     * @param   void
     * @returns string
     *
     */
    public function describe()
    {
        return "BINARY(32) NOT NULL";
    }
}
