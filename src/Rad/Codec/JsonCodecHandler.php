<?php

/*
 * The MIT License
 *
 * Copyright 2017 guillaume.
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

namespace Rad\Codec;

use Rad\Error\CodecException;

/**
 * Description of JsonCodec
 *
 * @author guillaume
 */
class JsonCodecHandler implements CodecInterface {

    public function __toString() {
        return "Json encode/decode";
    }

    public function deserialize(string $string) {
        $ret = json_decode($string);
        if (json_last_error() > 0) {
            throw new CodecException("Error during json_decode");
        }
        return $ret;
    }

    public function serialize($object): string {
        $ret = json_encode((array) $object);
        if (json_last_error() > 0) {
            throw new CodecException("Error during json_encode");
        }
        return $ret;
    }

    public function getMimeTypes(): array {
        return ['json'];
    }

    public function sign($datas, $secret) {
        
    }

}
