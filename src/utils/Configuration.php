<?php

namespace grinfeld\phpjsonable\utils;

/**
 * @author Grinfeld Mikhail
 * @since 8/23/2015.
 */
class Configuration {

    const CLASS_PROPERTY = "class_property";

    const DEFAULT_CLASS_PROPERTY_VALUE = "class";

    const INCLUDE_CLASS_NAME_PROPERTY = "include_class";

    const EXCLUDE_NULL_PROPERTY = "exclude_null";

    const CLASS_TYPE_PROPERTY = "classname_strategy";

    const DATE_STRATEGY_PROPERTY = "date_strategy";

    const DATE_STRATEGY_TIMESTAMP = "timestamp";

    const DATE_STRATEGY_STRING = "string";

    const DEFAULT_DATE_STRATEGY = self::DATE_STRATEGY_TIMESTAMP;

    const DATE_FORMAT_PROPERTY = "date_format";

    const DEFAULT_DATE_FORMAT = "Y-m-d H:i:s"; // default mysql format

    protected $properties = array();

    /**
     * Configuration constructor.
     * @param array $properties
     */
    public function __construct(array $properties = array()) { $this->properties = $properties; }

    public function push($name, $value) {
        $this->properties[$name] = $value;
        return $this;
    }
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

    public function getInt($name, $def = null) {
        $val = $this->get($name);
        if ($val != null && (is_int($val) || (is_string($val) && preg_replace("/[0-9]/", "", $val) == ""))) {
            return (int)$val;
        }
        return $def;
    }
}