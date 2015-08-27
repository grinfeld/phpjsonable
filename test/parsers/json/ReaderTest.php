<?php

use grinfeld\phpjsonable\utils\streams\StringInputStream;
use grinfeld\phpjsonable\parsers\json\Reader;

/**
 * @author Grinfeld Mikhail
 * @since 8/24/2015.
 */
class ReaderTest extends PHPUnit_Framework_TestCase {

    public function testParse() {
        $str = "111";
        $fp = new StringInputStream($str);
        $res = (new Reader($fp))->parse();
        $this->assertEquals(111, $res, "should $res == 111");

        $str = "111.111";
        $fp = new StringInputStream($str);
        $res = (new Reader($fp))->parse();
        $this->assertEquals(111.111, $res, "should $res == 111.111");

        $str = "\"hello\"";
        $fp = new StringInputStream($str);
        $res = (new Reader($fp))->parse();
        $this->assertEquals("hello", $res, "should $res == hello");

        $str = "\"he\\\"llo\"";
        $fp = new StringInputStream($str);
        $res = (new Reader($fp))->parse();
        $this->assertEquals("he\"llo", $res, "should $res == he\"llo");

        $str = "\"he\\'llo\"";
        $fp = new StringInputStream($str);
        $res = (new Reader($fp))->parse();
        $this->assertEquals("he'llo", $res, "should $res == he'llo");

        $str = "\"he\\nllo\"";
        $fp = new StringInputStream($str);
        $res = (new Reader($fp))->parse();
        $this->assertEquals("he\nllo", $res, "should $res == he\nllo");

        $str = "{\"key1\":\"hello\"}";
        $fp = new StringInputStream($str);
        $res = (new Reader($fp))->parse();
        $this->assertEquals("hello", $res["key1"], "should " . $res["key1"] . " == hello");

        $str = "{\"key1\":\"hello\", \"key2\":111}";
        $fp = new StringInputStream($str);
        $res = (new Reader($fp))->parse();
        $this->assertEquals("hello", $res["key1"], "should " . $res["key1"] . " == hello");
        $this->assertEquals(111, $res["key2"], "should " . $res["key2"] . " == 111");

        $str = "[\"hello\"]";
        $fp = new StringInputStream($str);
        $res = (new Reader($fp))->parse();
        $this->assertEquals("hello", $res[0], "should " . $res[0] . " == hello");

        $str = "[\"hello\", 111]";
        $fp = new StringInputStream($str);
        $res = (new Reader($fp))->parse();
        $this->assertEquals("hello", $res[0], "should " . $res[0] . " == hello");
        $this->assertEquals(111, $res[1], "should " . $res[1] . " == 111");

        $str = "[{\"key1\":\"hello\", \"key2\": 222},111, \"bye\"]";
        $fp = new StringInputStream($str);
        $res = (new Reader($fp))->parse();
        $this->assertEquals(111, $res[1], "should " . $res[1] . " == 111");
        $this->assertEquals("bye", $res[2], "should " . $res[2] . " == bye");
        $m = $res[0];
        $this->assertEquals("hello", $m["key1"], "should " . $m["key1"] . " == hello");
        $this->assertEquals(222, $m["key2"], "should " . $m["key2"] . " == 222");


        $str = "[{\"key1\":\"hello\", \"key2\": 222},111, \"bye\", [1, 2,3 ]]";
        $fp = new StringInputStream($str);
        $res = (new Reader($fp))->parse();
        $this->assertEquals(111, $res[1], "should " . $res[1] . " == 111");
        $this->assertEquals("bye", $res[2], "should " . $res[2] . " == bye");
        $m = $res[0];
        $this->assertEquals("hello", $m["key1"], "should " . $m["key1"] . " == hello");
        $this->assertEquals(222, $m["key2"], "should " . $m["key2"] . " == 222");
        $l = $res[3];
        $this->assertEquals(1, $l[0], "should " . $l[0] . " == 1");
        $this->assertEquals(2, $l[1], "should " . $l[1] . " == 2");
        $this->assertEquals(3, $l[2], "should " . $l[2] . " == 3");

        $str = "{\"class\":\"grinfeld.phpjsonable.utils.Pair\",\"left\":100,\"right\":\"Test\"}";
        $fp = new StringInputStream($str);
        $result = (new Reader($fp))->parse();
        $this->assertEquals("grinfeld\\phpjsonable\\utils\\Pair", get_class($result), "should " . get_class($result) . " == grinfeld\\phpjsonable\\utils\\Pair");
        $this->assertEquals(100, $result->getLeft(), "should " . $result->getLeft() . " == 100");
        $this->assertEquals("Test", $result->getRight(), "should " . $result->getRight() . " == Test");
    }
}