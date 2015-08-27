<?php
/**
 * @author Grinfeld Mikhail
 * @since 8/23/2015.
 */

namespace grinfeld\phpjsonable\parsers\json;

use grinfeld\phpjsonable\utils\Configuration;
use grinfeld\phpjsonable\utils\streams\InputStream;
use grinfeld\phpjsonable\utils\Pair;

class Reader {
    const START_MAP = '{';
    const END_MAP = '}';
    const START_ARRAY = '[';
    const END_ARRAY = ']';
    const ESCAPE_CHAR = '\\';
    const STRING_CHAR = '"';
    const CHAR_CHAR = '\'';
    const ELEM_DELIM = ',';
    const VALUE_DELIM = ':';
    const SPACE_CHAR = ' ';
    const TAB_CHAR = '\t';
    const EMPTY_CHAR = '\0';

    private static $convert = array(
        "\\\\" => self::ESCAPE_CHAR,
        "\\\"" => self::STRING_CHAR,
        "\\t" => self::TAB_CHAR,
        "\\'" => self::CHAR_CHAR,
        "\\n" => "\n",
        "\\r" => "\r",
        "\\/" => "/"
    );


    /**
     * @var /SplQueue
     */
    protected $queue;
    /**
     * @var resource - stream resource
     */
    protected $in;

    /**
     * @var Configuration
     */
    protected $conf;

    /**
     * Reader constructor.
     * @param InputStream $in
     * @param Configuration $conf
     */
    public function __construct(InputStream $in, Configuration $conf = null) {
        $this->queue = new \SplQueue();
        $this->in = $in;
        if ($conf == null)
            $this->conf = new Configuration();
        else
            $this->conf = $conf;
    }


    /**
     * @return mixed
     */
    public function parse() {
        if (!$this->in->isReady())
            return null;
        $resp = $this->parseRecursive();
        if (isset($resp) && $resp != null && $resp->getRight() != null) {
            return $resp->getRight();
        }
        return null;
    }

    /**
     * @return Pair return Pair where in left/value1 stored last read character and in right created object
     */
    private function parseRecursive() {
        $sb = "";
        while (false !== ($c = $this->in->nextChar())) {
            if ($c != self::SPACE_CHAR && $c != self::TAB_CHAR) {
                $p = $this->parseStructure($c);
                $o = $p->getRight();
                $c = $p->getLeft();
                if ($o != null) {
                    return new Pair($c, $o);
                } else if ($c != self::ELEM_DELIM) {
                    $sb = $sb . $c;
                }
            }
        }
        return new Pair($c, null);
    }

    private function createClass($m) {
        $cl = $this->conf->getString(Configuration::CLASS_PROPERTY, Configuration::DEFAULT_CLASS_PROPERTY_VALUE);
        $className = str_replace(".", "\\", $m[$cl]);
        $refClass = new \ReflectionClass($className);
        // TODO: add try catch
        $obj = $refClass->newInstanceArgs(array());
        $props = $refClass->getProperties();
        foreach ($props as $prop) {
            if ($prop->getName() != $cl && isset($m[$prop->getName()])) {
                $prop->setAccessible(true);
                $prop->setValue($obj, $m[$prop->getName()]);
            }
        }
        return $obj;
    }

    /**
     * @param $c
     * @return Pair
     */
    private function parseStructure($c) {
        $m = null;
        $l = null;
        $sb = "";
        $pn = null;
        switch($c) {
            case self::START_MAP:
                $m = array();
                $this->queue->enqueue($m);
                $c = $this->parseMap($m);
                if ($c == self::END_MAP) {
                    $this->queue->dequeue();
                    $cl = $this->conf->getString(Configuration::CLASS_PROPERTY, Configuration::DEFAULT_CLASS_PROPERTY_VALUE);
                    if (isset($m[$cl])) {
                        $o = $this->createClass($m);
                        $c = $this->in->nextChar();
                        return new Pair($c, $o);
                    }
                    return new Pair($c, $m);
                }
                break;
            case self::START_ARRAY:
                $l = array();
                $this->queue->enqueue($l);
                $c = $this->parseList($l);
                if ($c == self::END_ARRAY) {
                    $this->queue->dequeue();
                    $c = $this->in->nextChar();
                    return new Pair($c, $l);
                }
                break;
            case self::STRING_CHAR:
            case self::CHAR_CHAR:
                $sb = "";
                $this->queue->enqueue($sb);
                $c = $this->parseString($c, $sb);
                $pn = trim($sb);
                $this->queue->dequeue();
                if (strtolower($pn) == "null")
                    return new Pair($c, "");
                return new Pair($c, $pn);
            default:
                $sb = $c;
                $this->queue->enqueue($sb);
                $c = $this->parseNumber($sb);
                $pn = trim($sb);
                $this->queue->dequeue();
                if (strtolower($pn) == "null")
                    return new Pair($c, "");
                if (preg_replace("/[0-9]/", "", $pn) == "") {
                    $o = intval($pn);
                } else if (preg_replace("/[0-9\.]/", "", $pn) == "") {
                    $o = floatval($pn);
                } else {
                    $o = $pn;
                }
                return new Pair($c, $o);
        }

        return new Pair($c, null);
    }

    private function startsWith($str, $char, $case = false) {
        if (strlen($char) > 1)
            throw new \Exception("Invalid Argument: should be 1 char");
        if (strlen($str) <= 0)
            throw new \Exception("Invalid Argument: empty array");
        return $case ? strtolower($str[0]) == strtolower($char) : $str[0] == $char;
    }


    private function endsWith($str, $char, $case = false) {
        if (strlen($char) > 1)
            throw new \Exception("Invalid Argument: should be 1 char");
        $len = strlen($str);
        if ($len <= 0)
            throw new \Exception("Invalid Argument: empty array");
        $ind = $len - 1;
        return $case ? strtolower($str[$ind]) == strtolower($char) : $str[$ind] == $char;
    }

    /**
     * @param $m
     * @return string
     * @throws \Exception for error
     */
    private function parseMap(&$m) {
        $c = self::SPACE_CHAR;
        while (false !== ($c = $this->in->nextChar())) {
            if ($c == self::END_MAP) {
                return $c;
            } else {
                $sb = "";
                // searching key
                do {
                    if ($c === false)
                        throw new \Exception("Reached end of stream - un-parsed data");
                    if ($c != self::ELEM_DELIM)
                        $sb = $sb . $c;
                } while (false !== ($c = $this->in->nextChar()) && $c != self::VALUE_DELIM);
                $key = trim($sb);
                if (self::startsWith($key, self::CHAR_CHAR) || self::startsWith($key, self::STRING_CHAR))
                    $key = substr($key, 1);
                if (self::endsWith($key, self::CHAR_CHAR) || self::endsWith($key, self::STRING_CHAR))
                    $key = substr($key, 0, strlen($key) - 1);

                $p = $this->parseRecursive();
                $o = $p->getRight();
                $c = $p->getLeft();
                $m[$key] = $o;

                if ($c == self::END_MAP) {
                    return $c;
                }
            }
        }

        return $c;
    }

    /**
     * @param $l
     * @return string
     */
    private function parseList(&$l) {
        $c = self::SPACE_CHAR;
        while (false !== ($c = $this->in->nextChar())) {
            if ($c == self::END_ARRAY) {
                return $c;
            } else if ($c == self::ELEM_DELIM || $c == self::VALUE_DELIM) {
                // do nothing
            } else {
                $p = $this->parseListInnerElement($c);
                $o = $p->getRight();
                $c = $p->getLeft();
                array_push($l, $o);
                if ($c == self::END_ARRAY) {
                    return $c;
                }
            }
        }


        return $c;
    }

    /**
     * @param $sb
     * @return string
     */
    private function parseNumber(&$sb) {
        $c = self::SPACE_CHAR;
        while (false !== ($c = $this->in->nextChar())) {
            switch ($c) {
                case self::END_ARRAY:
                case self::END_MAP:
                case self::ELEM_DELIM:
                    return $c;
            }
            $sb = $sb . $c;
        }
        return $c;
    }

    /**
     * @param $sb
     * @return string
     */
    private function parseString ($prevC, &$sb) {
        $c = self::SPACE_CHAR;
        while (false !== ($c = $this->in->nextChar()) && !($prevC != self::ESCAPE_CHAR && ($c == self::CHAR_CHAR || $c == self::STRING_CHAR))) {
            if ($c == self::ESCAPE_CHAR) {
                //$sb = $sb . $c;
                //$prevC = $c;
            } else if ($prevC == self::ESCAPE_CHAR && isset(self::$convert[$prevC . $c])) {
                $sb = $sb . self::$convert[$prevC . $c];
            } else if ($prevC == self::ESCAPE_CHAR) {
                $sb = $sb . $prevC . $c;
            } else {
                $sb = $sb . $c;
            }
            $prevC = $c;
        }
        return $c;
    }

    /**
     * @param $c
     * @return Pair
     */
    private function parseListInnerElement($c) {
        $sb = "";
        do {
            if ($c != self::SPACE_CHAR) {
                $p = $this->parseStructure($c);
                $o = $p->getRight();
                $c = $p->getLeft();
                if ($o != null) {
                    return $p;
                } else if ($c != self::ELEM_DELIM) {
                    if (!(strlen($sb) == 0 && $c == self::EMPTY_CHAR)) // avoid empty string at the beginning
                        $sb = $sb . $c;
                }
            }
        } while (false !== ($c = $this->in->nextChar()));
        return new Pair($c, null);
    }
}