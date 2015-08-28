<?php
/**
 * @author Grinfeld Mikhail
 * @since 8/26/2015.
 */

namespace grinfeld\phpjsonable\transformers;

use grinfeld\phpjsonable\utils\JsonEscapeUtils;
use grinfeld\phpjsonable\utils\strategy\LanguageStrategyFactory;
use grinfeld\phpjsonable\utils\streams\OutputStream;
use grinfeld\phpjsonable\utils\Configuration;

class BeanTransformer implements Transformer {

    /**
     * checks if specific object matches current Transformer
     * @param $obj object to test
     * @return bool
     */
    public function match($obj) {
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
        $refClass = new \ReflectionClass($obj);
        $props = $refClass->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE);
        if (count($props) > 0) {
            $output->write("{");
            $clazz = get_class($obj);
            $excludeNull = $conf->getBoolean(Configuration::EXCLUDE_NULL_PROPERTY, false);
            $includeClass = $conf->getBoolean(Configuration::INCLUDE_CLASS_NAME_PROPERTY, false);
            $clazzName = $conf->getString(Configuration::CLASS_PROPERTY, Configuration::DEFAULT_CLASS_PROPERTY_VALUE);
            $clazzStrategy = $conf->getString(Configuration::CLASS_TYPE_PROPERTY, LanguageStrategyFactory::LANG_PHP);
            foreach($props as $prop) {
                $prop->setAccessible(true);
                $val = $prop->getValue($obj);
                if ($excludeNull === false || ($val !== null || $val !== "")) {
                    if ($prop->getName() != $clazzName) {
                        if ($i != 0)
                            $output->write(",");
                        $output->write("\"" . JsonEscapeUtils::escapeJson($prop->getName()) . "\":");
                        TransformerFactory::get($val)->transform($val, $output, $conf);
                        $i++;
                    } else {
                        $clazz = $val;
                    }
                }
            }
            if ($includeClass) {
                $clazz = LanguageStrategyFactory::getClassStrategy($clazzStrategy)->className($clazz);
                if ($i != 0)
                    $output->write(",");
                $output->write("\"$clazzName\":\"$clazz\"");
            }
            $output->write("}");
        } else {
            $output->write("null");
        }
    }
}