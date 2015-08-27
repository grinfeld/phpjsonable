<?php
/**
 * @author Grinfeld Mikhail
 * @since 8/27/2015.
 */

namespace grinfeld\phpjsonable\parsers\json;


use grinfeld\phpjsonable\utils\Configuration;
use grinfeld\phpjsonable\utils\streams\InputStream;
use grinfeld\phpjsonable\utils\streams\OutputStream;

class Json {
    /**
     * Encode InputStream $input contained JSON into object (array, assoc array, int, string, float or bean)
     * @param InputStream $input data wrapped by InputStream to be decoded
     * @param Configuration|null $conf configuration to be used
     * @return mixed
     */
    public static function decode(InputStream $input, Configuration $conf = null) {
        return (new Reader($input, $conf))->parse();
    }

    /**
     * Encodes object $obj to JSON and writes it into OutputStream $output
     * @param mixed $obj object to be encoded
     * @param OutputStream $output OutputStream to write encoded data in
     * @param Configuration|null $conf configuration to be used
     */
    public static function encode($obj, OutputStream $output, Configuration $conf = null) {
        (new Writer($output, $conf))->parse($obj);
    }
}