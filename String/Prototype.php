<?php

/**
 * String Column
 */

namespace attitude\Elements\Column;

use \attitude\Elements\Column_Prototype;
use \attitude\Elements\DependencyContainer;

/**
 * Integer Column Class
 *
 * @author Martin Adamko <@martin_adamko>
 * @version v0.1.0
 * @licence MIT
 *
 */
abstract class String_Prototype extends Column_Prototype
{
    const   LENGTH_OF_CHAR       =        255;// 2 ^ 8  - 1
    const   LENGTH_OF_TINYTEXT   =        255;// 2 ^ 8  - 1
    const   LENGTH_OF_TEXT       =      65535;// 2 ^ 16 - 1
    const   LENGTH_OF_MEDIUMTEXT =   16777215;// 2 ^ 24 - 1
    const   LENGTH_OF_LONGTEXT   = 4294967296;// 2 ^ 32 - 1

    /**
     * Length of string
     *
     * Default is 255 as for `CHAR`
     *
     * @var int
     */
    protected $length = 255;

    /**
     * Defines whether string is of variable length
     *
     * Default is `TRUE`.
     *
     * @var int
     */
    protected $is_varchar = true;

    /**
     * Can be `NULL`
     *
     * @var bool
     */
    protected $is_null = false;

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
        $this->length     = abs(DependencyContainer::get(get_called_class().'.length'));

        // TEXTs are variable in lenght only max is important
        if ($this->length <=self::LENGTH_OF_CHAR) {
            $this->is_varchar =!!   DependencyContainer::get(get_called_class().'.is_varchar');
        }
        $this->is_null    =!!        DependencyContainer::get(get_called_class().'.is_null');

        return parent::__construct();
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
        // http://stackoverflow.com/questions/466204/rounding-off-to-nearest-power-of-2
        $size = pow(2, ceil(log($this->length)/log(2)));

        $definition = '';

        if ($this->length > self::LENGTH_OF_LONGTEXT) {
            trigger_error('Column is probably too long', E_USER_ERROR);
        } elseif ($this->length > self::LENGTH_OF_MEDIUMTEXT) {
            $definition = "LONGTEXT";
        } elseif ($this->length > self::LENGTH_OF_TEXT) {
            $definition = "MEDIUMTEXT";
        } elseif ($this->length > self::LENGTH_OF_TINYTEXT) {
            $definition = "TEXT";
        } else {
            $definition = $this->is_variable ? "VARCHAR({$size})" : "CHAR({$size})";
        }

        return $definition . ($this->is_null ? ' NULL' : ' NOT NULL');
    }
}
