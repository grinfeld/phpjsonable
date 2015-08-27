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
    protected $out;

    /**
     * Writer constructor.
     * @param Configuration $conf
     */
    public function __construct(OutputStream $output, Configuration $conf = null) {
        if ($conf == null)
            $this->conf = new Configuration();
        else
            $this->conf = $conf;
        $this->out = $output;
    }

    /**
     * @param $obj
     */
    public function parse($obj) {
        TransformerFactory::get($obj)->transform($obj, $this->out, $this->conf);
    }
}