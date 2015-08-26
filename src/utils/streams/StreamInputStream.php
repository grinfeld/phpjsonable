<?php
/**
 * @author Grinfeld Mikhail
 * @since 8/24/2015.
 */

namespace grinfeld\phpjsonable\utils\streams;


class StreamInputStream implements InputStream {
    protected $sr;

    /**
     * StreamInputStream constructor.
     * @param resource $sr
     */
    public function __construct($sr) { $this->sr = $sr; }


    public function isReady() {
        return (feof($this->sr)) ? false : true;
    }

    public function nextChar() {
        return fgetc($this->sr);
    }

    public function rewind() {
        rewind($this->sr);
    }
}