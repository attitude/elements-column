<?php

/**
 * Integer Column
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
abstract class Integer_Prototype extends Column_Prototype
{
    const SIZE_OF_TINYINT   =                  255;// 2 ^  8 - 1
    const SIZE_OF_SMALLINT  =                65535;// 2 ^ 16 - 1
    const SIZE_OF_MEDIUMINT =             16777215;// 2 ^ 24 - 1
    const SIZE_OF_INT       =           4294967295;// 2 ^ 32 - 1
    const SIZE_OF_BIGINT    = 18446744073709551615;// 2 ^ 64 - 1

    const BYTES_OF_TINYINT   = 1;
    const BYTES_OF_SMALLINT  = 2;
    const BYTES_OF_MEDIUMINT = 3;
    const BYTES_OF_INT       = 4;
    const BYTES_OF_BIGINT    = 8;

    /**
     * Mininum value being indexed
     *
     * Default is 0 as for `UNSIGNED INT`
     *
     * @var int
     */
    protected $min = 0;

    /**
     * Maximum value being indexed
     *
     * Default is 65535 as for `UNSIGNED INT`
     *
     * @var int
     */
    protected $max = 65535;

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
        $this->min     = DependencyContainer::get(get_called_class().'.min');
        if (!is_int($this->min)) {
            trigger_error('Minimum has to be an integer.');
        }

        $this->max     = DependencyContainer::get(get_called_class().'.max');

        if (!is_int($this->max)) {
            trigger_error('Maximum has to be an integer.');
        }

        if ($this->min > $this->max) {
            trigger_error("A minimum must be less than maximum", E_USER_ERROR);
        }

        if ($this->max > self::SIZE_OF_BIGINT) {
            trigger_error("A maximum must be less than ".self::SIZE_OF_BIGINT.".", E_USER_ERROR);
        }

        if (
            $this->previousPowExponent($this->max, 256)===$this->nextPowExponent($this->max, 256)
            ||
            ($this->max===abs($this->min) && $this->previousPowExponent($this->max*2, 256)===$this->nextPowExponent($this->max*2, 256))
        ) {
            trigger_error("Passing {$this->max} is memory inefficient as next byte needs to be allocated. Consider passing ".($this->max-1)." or a number higher than {$this->max}.", E_USER_ERROR);
        }

        $this->is_null =!!        DependencyContainer::get(get_called_class().'.is_null');

        return parent::__construct();
    }

    /**
     * Returns exponent of `$base` for the next pow
     *
     * @param   int $n Number
     * @returns int
     *
     */
    protected function nextPowExponent($n, $base=2)
    {
        return (int) ceil(log($n)/log($base));
    }

    /**
     * Returns exponent of `$base` for the previous pow
     *
     * @param   int $n Number
     * @returns int
     *
     */
    protected function previousPowExponent($n, $base=2)
    {
        return (int) floor(log($n)/log($base));
    }

    /**
     * Returns next pow of `$base`
     *
     * @param   int $n Number
     * @returns int
     *
     */
    protected function nextPow($n, $base=2)
    {
        return (int) pow(2, $this->nextPowExponent($n, $base));
    }

    /**
     * Returns bytes needed for next pow of `$base`
     *
     * @param   int $n Number
     * @returns int
     *
     */
    protected function nextBytesPow($n, $base=2)
    {
        return (int) ceil($this->nextPowExponent($n, $base) / 8);
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
        // Asume minimum first, caches the min() results
        $size = abs($this->min);
        // Positives are -1 (-128 to 127)
        $size = $size < ($this->max + 1) ? ($this->max + 1) : $size;
        if ($this->min < 0) {
            $size = $size * 2;
        }

        $size = $this->nextBytesPow($size);

        if ($size >= self::BYTES_OF_BIGINT) {
            $size = 'BIGINT('.$size.')';
        } elseif ($size >= self::BYTES_OF_INT) {
            $size = 'INT('.$size.')';
        } elseif ($size >= self::BYTES_OF_MEDIUMINT) {
            $size = 'MEDIUMINT('.$size.')';
        } elseif ($size >= self::BYTES_OF_SMALLINT) {
            $size = 'SMALLINT('.$size.')';
        } else {
            $size = 'TINYINT('.$size.')';
        }

        return $size. ($this->min >= 0 ? ' UNSIGNED' : '') . ($this->is_null ? ' NULL' : ' NOT NULL');
    }
}
