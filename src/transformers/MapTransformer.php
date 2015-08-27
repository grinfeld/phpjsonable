<?php
/**
 * @author Grinfeld Mikhail
 * @since 8/26/2015.
 */

namespace grinfeld\phpjsonable\transformers;

use grinfeld\phpjsonable\utils\Configuration;
use grinfeld\phpjsonable\utils\JsonEscapeUtils;
use grinfeld\phpjsonable\utils\streams\OutputStream;

class MapTransformer implements Transformer {

    /**
     * checks if specific object matches current Transformer
     * @param $obj object to test
     * @return bool
     */
    public function match($obj) {
        if (!is_array($obj))
            return false;
        return true;
    }

    /**
     * @param $obj object to transform
     * @param OutputStream $output
     * @param Configuration $conf
     * @throws \Exception
     */
    public function transform($obj, OutputStream $output, Configuration $conf) {
        $i = 0;
        $output->write("{");
        while(list($key, $value) = each($obj)) {
            if ($i != 0)
                $output->write(",");
            $output->write("\"" . JsonEscapeUtils::escapeJson($key) . "\":");
            TransformerFactory::get($value)->transform($value, $output, $conf);
            $i++;
        }
        $output->write("}");
    }
}