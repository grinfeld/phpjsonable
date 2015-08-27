<?php
use \grinfeld\phpjsonable\utils\streams\StringOutputStream;
/**
 * @author Grinfeld Mikhail
 * @since 8/27/2015.
 */
class StringOutputStreamTest extends PHPUnit_Framework_TestCase {
    public function testWrite() {
        $str = "hello";
        $output = new StringOutputStream();
        $output->write($str);
        $this->assertEquals($str, $output->toString(), "Should $str == " . $output->toString());
    }
}