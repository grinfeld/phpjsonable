<?php
namespace grinfeld\phpjsonable\transformers;
use grinfeld\phpjsonable\utils\streams\OutputStream;
use grinfeld\phpjsonable\utils\Configuration;

/**
 * @author Grinfeld Mikhail
 * @since 8/22/2015.
 */
interface Transformer {

    /**
     * checks if specific object matches current Transformer
     * @param $obj object to test
     * @return bool
     */
    public function match($obj);

    /**
     * @param $obj object to transform
     * @param OutputStream $output
     * @param Configuration $conf
     * @return
     */
    public function transform($obj, OutputStream $output, Configuration $conf);
}