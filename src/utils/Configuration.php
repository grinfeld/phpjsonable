<?php

namespace grinfeld\phpjsonable\utils;

/**
 * @author Grinfeld Mikhail
 * @since 8/23/2015.
 */
class Configuration {

    const CLASS_PROPERTY = "class_property";
    const DEFAULT_CLASS_PROPERTY_VALUE = "class";

    const EXCLUDE_NULL_PROPERTY = "exclude_null";

    protected $properties = array();

    /**
     * Configuration constructor.
     * @param array $properties
     */
    public function __construct(array $properties = array()) { $this->properties = $properties; }

    /**
     * @param $name
     * @return mixed
     */
    public function get($name) {
        if (isset($this->properties[$name]))
            return $this->properties[$name];
        return null;
    }

    public function getString($name, $def = "") {
        $val = $this->get($name);
        return $val !== null ? $val : $def;
    }

    public function getBoolean($name, $def = null) {
        $val = $this->get($name);
        if ($val != null) {
            return $val !== false && $val !== "false" && $val !== 0 && $val !== "0" ? true : false;
        }
        return $def;
    }
}