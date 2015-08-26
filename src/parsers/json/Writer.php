<?php
/**
 * @author Grinfeld Mikhail
 * @since 8/26/2015.
 */

namespace grinfeld\phpjsonable\parsers\json;

use grinfeld\phpjsonable\utils\Configuration;

class Writer {
    /**
     * @var Configuration
     */
    protected $conf;

    /**
     * Writer constructor.
     * @param Configuration $conf
     */
    public function __construct(Configuration $conf) { $this->conf = $conf; }


}