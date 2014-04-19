<?php
/**
 * @author  Actine <actine@actinarium.com>
 * Date: 13.04.14
 * Time: 3:06
 */

namespace Actinarium\Philtre\Core\IO\Streams;

use Actinarium\Philtre\Core\IO\ReadableWrapper;

/**
 * Stream is just an immutable plain object wrapping some data within.
 *
 * @package Actinarium\Philtre\Core\IO
 */
class Stream implements ReadableWrapper
{
    /** @var null|mixed */
    protected $data;

    /**
     * @param mixed|null $data Data to wrap in current stream
     */
    public function __construct($data = null)
    {
        $this->data = $data;
    }

    /**
     * @return mixed|null Wrapped data
     */
    public function getData()
    {
        return $this->data;
    }
}
