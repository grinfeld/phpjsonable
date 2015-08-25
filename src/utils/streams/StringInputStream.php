<?php
/**
 * @author Grinfeld Mikhail
 * @since 8/24/2015.
 */

namespace grinfeld\phpjsonable\utils\streams;

class StringInputStream implements InputStream {

    protected $str;
    protected $current;
    /**
     * StringInputStream constructor.
     * @param $str
     */
    public function __construct($str) {
        $this->str = $str;
        $this->current = -1;
    }


    public function isReady() {
        return $this->current + 1 < strlen($this->str) ? true : false;
    }

    public function nextChar() {
        if ($this->isReady()) {
            $this->current++;
            return $this->str[$this->current];
        }
        return false;
    }

    public function rewind() {
        $this->current = -1;
    }
}