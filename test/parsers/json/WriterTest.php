<?php

use \grinfeld\phpjsonable\utils\streams\StringOutputStream;
use \grinfeld\phpjsonable\utils\streams\StringInputStream;
use \grinfeld\phpjsonable\parsers\json\Writer;
use \grinfeld\phpjsonable\parsers\json\Json;
use \grinfeld\phpjsonable\utils\Configuration;

/**
 * @author Grinfeld Mikhail
 * @since 8/26/2015.
 */
class WriterTest extends PHPUnit_Framework_TestCase {
    public function testParse() {
        $str = new StringOutputStream();
        Json::encode("string", $str);
        $this->assertEquals("\"string\"", $str->toString(), "Should be \"string\"");

        $str = new StringOutputStream();
        (new Writer($str))->parse("string");
        $this->assertEquals("\"string\"", $str->toString(), "Should be \"string\"");

        $str = "111";
        $fp = new StringInputStream($str);
        $res = Json::decode($fp);
        $output = new StringOutputStream();
        Json::encode($res, $output);
        $this->assertEquals($str, $output->toString(), "Should be \"111\"");

        $str = "{\"left\":100,\"right\":\"Test\",\"class\":\"grinfeld\\phpjsonable\\utils\\Pair\"}";
        $fp = new StringInputStream($str);
        $result = Json::decode($fp);
        $output = new StringOutputStream();
        Json::encode($result, $output, (new Configuration())->push(Configuration::INCLUDE_CLASS_NAME_PROPERTY, "true"));
        $this->assertEquals($str, $output->toString(), "Should be same");

    }
}