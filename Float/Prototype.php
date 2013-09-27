<?php

/**
 * Float Column
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
abstract class Float_Prototype extends Column_Prototype
{
    /**
     * Number of digits after decimal point
     *
     * Default is 2
     *
     * @var int
     */
    protected $decimals = 2;

    /**
     * Number of digits before decimal point
     *
     * Default is `999 999 999`
     *
     * @var int
     */
    protected $max = 999999999;

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
        $this->decimals = intval(DependencyContainer::get(get_called_class().'.decimals'));
        $this->max     =     abs(DependencyContainer::get(get_called_class().'.max'));
        $this->min     =     abs(DependencyContainer::get(get_called_class().'.min'));
        $this->is_null =!!       DependencyContainer::get(get_called_class().'.is_null');

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
        $size = strlen(ltrim("{$this->min}", '-'));
        $size = strlen("{$this->max}") > $size ? strlen("{$this->max}") : $size;

        return "DECIMAL(".$size.",".$this->decimals.") ". ($this->is_null ? ' NULL' : ' NOT NULL');
    }
}
