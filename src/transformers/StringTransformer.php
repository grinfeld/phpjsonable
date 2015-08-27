<?php
/**
 * @author Grinfeld Mikhail
 * @since 8/26/2015.
 */

namespace grinfeld\phpjsonable\transformers;


use grinfeld\phpjsonable\utils\JsonEscapeUtils;
use grinfeld\phpjsonable\utils\Configuration;
use grinfeld\phpjsonable\utils\streams\OutputStream;

class StringTransformer implements Transformer {

    public function match($obj) {
        return (is_string($obj) || preg_replace("/[0-9]/", "", $obj) != "");
    }

    public function transform($obj, OutputStream $output, Configuration $conf) {
        $output->write("\"" . JsonEscapeUtils::escapeJson($obj) . "\"");
    }
}