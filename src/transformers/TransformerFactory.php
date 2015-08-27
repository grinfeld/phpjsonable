<?php
/**
 * @author Grinfeld Mikhail
 * @since 8/26/2015.
 */

namespace grinfeld\phpjsonable\transformers;

class TransformerFactory {

    const SEM_KEY = "grinfeld\\phpjsonable\transformers\\TransformerFactory";
    private static $transformers = null;

    /**
     * @param $obj
     * @return Transformer
     * @throws \Exception if not Transformer found for $obj
     */
    public static function get($obj) {
        if (self::$transformers == null) {
                self::init();
        }
        foreach(self::$transformers as $transformer) {
            if ($transformer->match($obj))
                return $transformer;
        }

        throw new \Exception("Appropriate transformer not found for " . $obj);
    }



    /**
     * TransformerFactory constructor.
     */
    public static function init() {
        // order is important !!!
        self::$transformers = array(
            new NullTransformer(),
            new IntTransformer(),
            new FloatTransformer(),
            new StringTransformer(),
            new ArrayTransformer(),
            new MapTransformer(),
            new BeanTransformer()
        );
    }
}