<?php
/**
 * @author Grinfeld Mikhail
 * @since 8/26/2015.
 */

namespace grinfeld\phpjsonable\transformers;

use grinfeld\phpjsonable\utils\streams\OutputStream;
use grinfeld\phpjsonable\utils\Configuration;

class ArrayTransformer implements Transformer {

    public function match($obj) {
        if (!is_array($obj))
            return false;
        $i = 0;
        while(list($ind, $key) = each($obj)) {
            if (!is_int($ind) || $ind != $i) {
                return false;
            }
            $i++;
        }
        return true;
    }

    public function transform($obj, OutputStream $output, Configuration $conf) {
        $i = 0;
        $output->write("[");
        foreach($obj as $elem) {
            if ($i != 0)
                $output->write(",");
            TransformerFactory::get($elem)->transform($elem, $output, $conf);
            $i++;
        }
        $output->write("]");
    }
}