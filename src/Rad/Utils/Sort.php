<?php

/*
 * The MIT License
 *
 * Copyright 2017 Guillaume Monet.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Rad\Utils;

/**
 * Description of Sort
 *
 * @author Guillaume Monet
 */
class Sort {

    private $order;
    private $order_by;

    const DESC = -1;
    const ASC = 1;

    public function __construct($order_by, $order = Sort::ASC) {
        $this->order = $order;
        $this->order_by = $order_by;
    }

    /**
     * 
     * @param type $a
     * @param type $b
     * @return int
     */
    public function sort($a, $b) {
        $field = $this->order_by;
        if ($a->{$field} == $b->{$field}) {
            return 0;
        }
        return $this->order * (($a->{$field} < $b->{$field}) ? -1 : 1);
    }

    /**
     * 
     * @param array $array
     * @param type $order_by
     * @param type $order
     * @return bool
     */
    public static function sortBy(array &$array, $order_by, $order = Sort::ASC): bool {
        $sort = new Sort($order_by, $order);
        return uasort($array, array($sort, "sort"));
    }

}
