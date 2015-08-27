<?php
use grinfeld\phpjsonable\utils\streams\StreamOutputStream;
/**
 * @author Grinfeld Mikhail
 * @since 8/27/2015.
 */
class StreamOutputStreamTest extends PHPUnit_Framework_TestCase {
    public function testWrite() {
        $str = "str";
        $fp = fopen("php://memory", 'r+');
        $output = new StreamOutputStream($fp);
        $output->write($str);
        $res = stream_get_contents($output->get());
        $this->assertEquals($str, $res, "Should $str == " . $res);
    }
}