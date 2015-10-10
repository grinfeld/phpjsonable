<?php

use \grinfeld\phpjsonable\utils\streams\StreamInputStream;

/**
 * @author Grinfeld Mikhail
 * @since 8/24/2015.
 */
class StreamInputStreamTest extends PHPUnit_Framework_TestCase {

    public function testIsReady() {
        $str = "1";
        $fp = fopen("php://memory", 'r+');
        fputs($fp, $str);
        rewind($fp);
        $in = new StreamInputStream($fp);
        $this->assertEquals(true, $in->isReady(), "Just initialized. Should return isReady");
        $c = $in->nextChar();
        // in windows the last char is eof, but in linux is always end of line before eof
        if ($c == '\r\n' || $c == '\n')
            $c = $in->nextChar();
        $this->assertEquals(false, $in->isReady(), "End of string. Should return NOT isReady: " . chr($c));
    }

    public function testNextChar() {
        $str = "123";
        $fp = fopen("php://memory", 'r+');
        fputs($fp, $str);
        rewind($fp);
        $in = new StreamInputStream($fp);
        $c = $in->nextChar();
        $this->assertEquals(1, $c, "Should be the first char: 1, but it's $c");
        $c = $in->nextChar();
        $this->assertEquals(2, $c, "Should be the second char: 2, but it's $c");
        $c = $in->nextChar();
        $this->assertEquals(3, $c, "Should be the third char: 3, but it's $c");
        $c = $in->nextChar();
        $this->assertEquals(false, $c, "End of stream. Should return false");
    }

    public function testRewind() {
        $str = "12";
        $fp = fopen("php://memory", 'r+');
        fputs($fp, $str);
        rewind($fp);
        $in = new StreamInputStream($fp);
        $in->nextChar(); // now 1
        $in->nextChar(); // now 2
        $in->nextChar(); // now false
        $in->rewind();
        $c = $in->nextChar();
        $this->assertEquals(1, $c, "Should be first char again");
    }
}