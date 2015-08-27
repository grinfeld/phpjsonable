<?php
/**
 * @author Grinfeld Mikhail
 * @since 8/26/2015.
 */

namespace grinfeld\phpjsonable\utils\streams;


class StreamOutputStream implements OutputStream {

    protected $sr;

    /**
     * StreamOutputStream constructor.
     * @param resource $sr
     */
    public function __construct($sr) { $this->sr = $sr; }

    public function write($str) {
        fputs($this->sr, $str);
    }

    /**
     * returns rewind resource
     * @return resource
     */
    public function get() {
        rewind($this->sr);
        return $this->sr;
    }
}