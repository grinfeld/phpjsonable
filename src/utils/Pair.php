<?php
/**
 * @author Grinfeld Mikhail
 * @since 8/23/2015.
 */

namespace grinfeld\phpjsonable\utils;


class Pair {

    protected $left;
    protected $right;

    /**
     * @param null $left
     * @param null $right
     */
    public function __construct($left = null, $right = null) {
        $this->left = $left;
        $this->right = $right;
    }


    /**
     * @return mixed
     */
    public function getLeft() { return $this->left; }

    /**
     * @param mixed $left
     */
    public function setLeft($left) { $this->left = $left; }

    /**
     * @return mixed
     */
    public function getRight() { return $this->right; }

    /**
     * @param mixed $right
     */
    public function setRight($right) { $this->right = $right; }

    /**
     * @return mixed
     */
    public function getValue1() { return $this->left; }

    /**
     * @param mixed $value1
     */
    public function setValue1($value1) { $this->left = $value1; }

    /**
     * @return mixed
     */
    public function getValue2() { return $this->right; }

    /**
     * @param mixed $value2
     */
    public function setValue2($value2) { $this->right = $value2; }

}