<?php
/**
 * @author Grinfeld Mikhail
 * @since 8/26/2015.
 */

namespace grinfeld\phpjsonable\transformers;

use grinfeld\phpjsonable\utils\Configuration;
use grinfeld\phpjsonable\utils\streams\OutputStream;

class NullTransformer implements Transformer {

    /**
     * checks if specific object matches current Transformer
     * @param $obj object to test
     * @return bool
     */
    public function match($obj) {
        return $obj == null;
    }

    /**
     * @param $obj object to transform
     * @param OutputStream $output
     * @param Configuration $conf
     */
    public function transform($obj, OutputStream $output, Configuration $conf) {
        $output->write("null");
    }
}