<?php
/**
 * @author Grinfeld Mikhail
 * @since 8/28/2015.
 */

namespace grinfeld\phpjsonable\transformers;


use grinfeld\phpjsonable\utils\Configuration;
use grinfeld\phpjsonable\utils\streams\OutputStream;

class BooleanTransformer implements Transformer {

    /**
     * checks if specific object matches current Transformer
     * @param $obj object to test
     * @return bool
     */
    public function match($obj) {
        return is_bool($obj);
    }

    /**
     * @param $obj object to transform
     * @param OutputStream $output
     * @param Configuration $conf
     */
    public function transform($obj, OutputStream $output, Configuration $conf) {
        $output->write($obj === true ? "true" : "false");
    }
}