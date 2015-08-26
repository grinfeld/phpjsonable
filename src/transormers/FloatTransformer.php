<?php
/**
 * @author Grinfeld Mikhail
 * @since 8/26/2015.
 */

namespace grinfeld\phpjsonable\transformers;

use grinfeld\phpjsonable\utils\streams\OutputStream;
use grinfeld\phpjsonable\utils\Configuration;

class FloatTransformer implements Transformer {

    public function match($obj) {
        return is_float($obj) || (is_string($obj) && $obj !== "" && preg_replace("/[0-9\.]/", "", $obj) == "");
    }

    public function transform($obj, OutputStream $output, Configuration $conf) {
        $output->write(floatval($obj));
    }
}