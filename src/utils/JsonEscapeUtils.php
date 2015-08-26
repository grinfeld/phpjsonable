<?php
/**
 * @author Grinfeld Mikhail
 * @since 8/26/2015.
 */

namespace grinfeld\phpjsonable\utils;


class JsonEscapeUtils {
    /**
     * escapes JSON string
     * @param $value
     * @return string
     */
    static function escapeJson($value) {
        $escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c");
        $replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b");
        $result = str_replace($escapers, $replacements, $value);
        return $result;
    }
}