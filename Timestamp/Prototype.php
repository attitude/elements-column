<?php

/**
 * Timestamp Column
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
abstract class Timestamp_Prototype extends Column_Prototype
{
    const MAX_TIME = 34359738367;

    /**
     * Mininum value
     *
     * Default is 0 as for `UNSIGNED INT`
     *
     * @var int
     */
    protected $min = 0;

    /**
     * Maximum value
     *
     * Default is 65535 as for `UNSIGNED INT`
     *
     * @var int
     */
    protected $max = 34359738367; // Year: 36812-02-20

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
        $this->min     =   DependencyContainer::get(get_called_class().'.seconds_since');
        if (!is_int($this->min)) {
            trigger_error('Time since has to be an integer.');
        }

        $this->max     =   DependencyContainer::get(get_called_class().'.seconds_until');
        if (!is_int($this->max)) {
            trigger_error('Time until has to be an integer.');
        }

        if ($this->min > $this->max) {
            trigger_error("A minimum must be less than maximum", E_USER_ERROR);
        }

        if ($this->max > self::SIZE_OF_BIGINT) {
            trigger_error("A maximum must be less than ".self::SIZE_OF_BIGINT.".", E_USER_ERROR);
        }

        $this->is_null =!! DependencyContainer::get(get_called_class().'.is_null');

        return \attitude\Elements\Storage\Column::__construct();
    }
}
