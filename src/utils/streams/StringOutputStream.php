<?php
/**
 * @author Grinfeld Mikhail
 * @since 8/26/2015.
 */

namespace grinfeld\phpjsonable\utils\streams;


class StringOutputStream implements OutputStream {
    protected $str;

    /**
     * StringOutputStream constructor.
     * @param $str
     */
    public function __construct() { $this->str = ""; }

    /**
     * @return string
     */
    public function toString() { return $this->str; }

    public function write($str) {
        $this->str = $this->str . $str;
    }
}