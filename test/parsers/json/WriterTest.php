<?php

use \grinfeld\phpjsonable\utils\streams\StringOutputStream;
use \grinfeld\phpjsonable\utils\streams\StringInputStream;
use \grinfeld\phpjsonable\parsers\json\Writer;
use \grinfeld\phpjsonable\parsers\json\Json;
use \grinfeld\phpjsonable\utils\Configuration;
use \grinfeld\phpjsonable\utils\strategy\LanguageStrategyFactory;
use \grinfeld\phpjsonable\utils\Pair;
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

        $str = "111.111";
        $fp = new StringInputStream($str);
        $res = Json::decode($fp);
        $output = new StringOutputStream();
        Json::encode($res, $output);
        $this->assertEquals($str, $output->toString(), "Should be \"111.111\"");

        $str = "\"he\\\"llo\"";
        $fp = new StringInputStream($str);
        $res = Json::decode($fp);
        $output = new StringOutputStream();
        Json::encode($res, $output);
        $this->assertEquals($str, $output->toString(), "Should be \"$str\"");

        $str = "\"he'llo\"";
        $fp = new StringInputStream($str);
        $res = Json::decode($fp);
        $output = new StringOutputStream();
        Json::encode($res, $output);
        $this->assertEquals($str, $output->toString(), "Should be \"$str\"");

        $str = "\"he\\nllo\"";
        $fp = new StringInputStream($str);
        $res = Json::decode($fp);
        $output = new StringOutputStream();
        Json::encode($res, $output);
        $this->assertEquals($str, $output->toString(), "Should be \"$str\"");

        $str = "{\"key1\":\"hello\"}";
        $fp = new StringInputStream($str);
        $res = Json::decode($fp);
        $output = new StringOutputStream();
        Json::encode($res, $output);
        $this->assertEquals($str, $output->toString(), "Should be \"$str\"");

        $str = "{\"key1\":\"hello\",\"key2\":111,\"key3\":true}";
        $fp = new StringInputStream($str);
        $res = Json::decode($fp);
        $output = new StringOutputStream();
        Json::encode($res, $output);
        $this->assertEquals($str, $output->toString(), "Should be \"$str\"");

        $str = "[\"hello\"]";
        $fp = new StringInputStream($str);
        $res = Json::decode($fp);
        $output = new StringOutputStream();
        Json::encode($res, $output);
        $this->assertEquals($str, $output->toString(), "Should be \"$str\"");

        $str = "[\"hello\",111]";
        $fp = new StringInputStream($str);
        $res = Json::decode($fp);
        $output = new StringOutputStream();
        Json::encode($res, $output);
        $this->assertEquals($str, $output->toString(), "Should be \"$str\"");

        $str = "[{\"key1\":\"hello\",\"key2\":222},111,\"bye\",false]";
        $fp = new StringInputStream($str);
        $res = Json::decode($fp);
        $output = new StringOutputStream();
        Json::encode($res, $output);
        $this->assertEquals($str, $output->toString(), "Should be \"$str\"");

        $str = "[{\"key1\" : \"hello\",\"key2\": 222}, 111, \"bye\",[1, 2, 3]]";
        $fp = new StringInputStream($str);
        $res = Json::decode($fp);
        $output = new StringOutputStream();
        Json::encode($res, $output);
        $this->assertEquals(str_replace(" ", "", $str), $output->toString(), "Should be \"$str\"");

        $str = "{\"left\":100,\"right\":\"Test\",\"class\":\"grinfeld\\phpjsonable\\utils\\Pair\"}";
        $fp = new StringInputStream($str);
        $result = Json::decode($fp);
        $output = new StringOutputStream();
        Json::encode($result, $output, (new Configuration())->push(Configuration::INCLUDE_CLASS_NAME_PROPERTY, "true"));
        $this->assertEquals($str, $output->toString(), "Should be same");

        $str = "{\"left\":100,\"right\":\"Test\",\"class\":\"grinfeld.phpjsonable.utils.Pair\"}";
        $expected = "{\"left\":100,\"right\":\"Test\",\"class\":\"grinfeld.phpjsonable.utils.Pair\"}";
        $fp = new StringInputStream($str);
        $result = Json::decode($fp);
        $output = new StringOutputStream();
        Json::encode($result, $output, (new Configuration())->push(Configuration::CLASS_TYPE_PROPERTY, LanguageStrategyFactory::LANG_JAVA)->push(Configuration::INCLUDE_CLASS_NAME_PROPERTY, "true"));
        $this->assertEquals($expected, $output->toString(), "Should be $expected");

        $str = "{\"left\":100,\"class\":\"grinfeld\\phpjsonable\\utils\\Pair\"}";
        $expected = "{\"left\":100,\"right\":null,\"class\":\"grinfeld\\phpjsonable\\utils\\Pair\"}";
        $fp = new StringInputStream($str);
        $result = Json::decode($fp);
        $output = new StringOutputStream();
        Json::encode($result, $output, (new Configuration())->push(Configuration::INCLUDE_CLASS_NAME_PROPERTY, "true")->push(Configuration::EXCLUDE_NULL_PROPERTY, "false"));
        $this->assertEquals($expected, $output->toString(), "Should be $expected");

        $expected = "{\"left\":100,\"right\":\"Test\",\"class\":\"grinfeld\\phpjsonable\\utils\\Pair\"}";
        $output = new StringOutputStream();
        $pair = new Pair(100, "Test");
        Json::encode($pair, $output, (new Configuration())->push(Configuration::INCLUDE_CLASS_NAME_PROPERTY, "true")->push(Configuration::EXCLUDE_NULL_PROPERTY, "false"));
        $this->assertEquals($expected, $output->toString(), "Should be $expected");

        $output = new StringOutputStream();
        Json::encode(new SimpleBean(), $output, (new Configuration())->push(Configuration::INCLUDE_CLASS_NAME_PROPERTY, "false")->push(Configuration::EXCLUDE_NULL_PROPERTY, "false"));
        $this->assertEquals("{\"val\":\"123\"}", $output->toString(), "Should be 123");

        $date = new DateTime('now');
        $timestamp = $date->getTimestamp();
        $output = new StringOutputStream();
        Json::encode(array("date" => $date), $output);
        $this->assertEquals("{\"date\":$timestamp}", $output->toString(), "Should be $timestamp");
    }
}

    class SimpleBean {
        protected $val = "1";

        /**
         * @return mixed
         */
        public function getVal() { return "123"; }
    }