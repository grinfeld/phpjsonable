<?php
/**
 * @author Grinfeld Mikhail
 * @since 8/26/2015.
 */

namespace grinfeld\phpjsonable\parsers\json;

use grinfeld\phpjsonable\transformers\TransformerFactory;
use grinfeld\phpjsonable\utils\Configuration;
use grinfeld\phpjsonable\utils\streams\OutputStream;

class Writer {
    /**
     * @var Configuration
     */
    protected $conf;

    /**
     * Writer constructor.
     * @param Configuration $conf
     */
    public function __construct(Configuration $conf = null) {
        if ($conf == null)
            $this->conf = new Configuration();
        else
            $this->conf = $conf;
    }

    /**
     * @param $obj
     * @param OutputStream $output
     */
    public function parse($obj, OutputStream $output) {
        return TransformerFactory::get($obj)->transform($obj, $output, $this->conf);
    }
}