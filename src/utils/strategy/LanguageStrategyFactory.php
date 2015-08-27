<?php
/**
 * @author Grinfeld Mikhail
 * @since 8/27/2015.
 */

namespace grinfeld\phpjsonable\utils\strategy;


class LanguageStrategyFactory {
    private static $instances = null;

    const LANG_PHP = 0;
    const LANG_JAVA = 1;
    const LANG_DOTNET = 2;

    /**
     * @param int $type
     * @return LanguageStrategy
     */
    public static function getClassStrategy($type = self::LANG_PHP) {
        if (self::$instances == null) {
            self::init();
        }
        if (isset(self::$instances[$type]))
            return self::$instances[$type];
        return self::$instances[self::LANG_PHP];
    }



    private static function init(){
        self::$instances = array(
            self::LANG_PHP => new PHPStrategy(),
            self::LANG_JAVA => new JavaStrategy(),
            self::LANG_DOTNET => new DotNetStrategy()
        );
    }
}