<?php
/**
 * @author Grinfeld Mikhail
 * @since 8/24/2015.
 */

namespace grinfeld\phpjsonable\utils\streams;


interface InputStream {
    public function isReady();
    public function nextChar();
    public function rewind();
}