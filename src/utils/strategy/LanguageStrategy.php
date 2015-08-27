<?php
/**
 * @author Grinfeld Mikhail
 * @since 8/27/2015.
 */

namespace grinfeld\phpjsonable\utils\strategy;


interface LanguageStrategy {
    /**
     * normalize class name according to specific language strategy
     * @param string $clazzName class name to normalize
     * @return string
     */
    public function className($clazzName);
}