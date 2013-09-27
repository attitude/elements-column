<?php

/**
 * Storage Column
 */

namespace attitude\Elements;

/**
 * Storage Column Class
 *
 * @author Martin Adamko <@martin_adamko>
 * @version v0.1.0
 * @licence MIT
 *
 */
abstract class Column_Prototype implements Column_Interface
{
    /**
     * Column name
     *
     * @var string
     */
    protected $name = null;

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
        $this->setName(DependencyContainer::get(get_called_class().'.name'));

        return $this;
    }

    /**
     * Set column name dependancy
     *
     * @param   string  $name
     * @returns object          Returns `$this`
     *
     */
    public function setName($name)
    {
        if (!is_string($name)) {
            trigger_error('Column name must be a string', E_USER_ERROR);
        }

        if (strlen($name)===0) {
            trigger_error('Column name must be a non-empty string');
        }

        $this->name = $name;

        return $this;
    }

    /**
     * Returns column name
     *
     * @param   void
     * @returns string
     *
     */
    public function name()
    {
        return $this->name;
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
    abstract public function describe();
}
