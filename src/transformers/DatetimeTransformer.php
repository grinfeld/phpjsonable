<?php
/**
 * Created by IntelliJ IDEA.
 * User: Grinfeld Mikhail
 * Date: 10/26/2016
 * Time: 11:42 PM
 */

namespace grinfeld\phpjsonable\transformers;


use grinfeld\phpjsonable\utils\Configuration;
use grinfeld\phpjsonable\utils\streams\OutputStream;

class DatetimeTransformer implements Transformer {

    /**
     * checks if specific object matches current Transformer, i.e. it's instance of DateTime
     * @param $obj object to test
     * @return bool true if if specific object matches current Transformer, i.e. it's instance of DateTime, else false
     */
    public function match($obj) {
        return $obj instanceof \DateTime;
    }

    /**
     * @param $obj object to transform
     * @param OutputStream $output
     * @param Configuration $conf
     * @return void
     */
    public function transform($obj, OutputStream $output, Configuration $conf) {
        if ($conf == null)
            $conf = new Configuration();

        $strategy = $conf->getString(Configuration::DATE_STRATEGY_PROPERTY, Configuration::DATE_STRATEGY_TIMESTAMP);

        if ($strategy == Configuration::DATE_STRATEGY_TIMESTAMP) {
            $res = $obj->getTimestamp();
            $output->write("" . $res);
        } else {
            $format = $conf->getString(Configuration::DATE_FORMAT_PROPERTY, Configuration::DEFAULT_DATE_FORMAT);
            $res = $obj->format($format);
            $output->write("\"" . $res . "\"");
        }
    }
}