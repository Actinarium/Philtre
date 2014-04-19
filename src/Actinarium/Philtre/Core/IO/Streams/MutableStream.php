<?php
/**
 * @author  Actine <actine@actinarium.com>
 * Date: 13.04.14
 * Time: 3:06
 */

namespace Actinarium\Philtre\Core\IO\Streams;

use Actinarium\Philtre\Core\IO\ReadableWrapper;
use Actinarium\Philtre\Core\IO\WritableWrapper;

/**
 * MutableStream is a kind of {@link Stream} that allows changing wrapped data.
 *
 * @package Actinarium\Philtre\Core\IO
 */
class MutableStream implements ReadableWrapper, WritableWrapper
{
    /** @var null|mixed */
    protected $data;

    /**
     * @param mixed|null $data Data to wrap
     */
    public function __construct($data = null)
    {
        $this->data = $data;
    }

    /**
     * @param mixed|null $data Data to wrap in current stream
     *
     * @return void
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return null|mixed Wrapped data
     */
    public function getData()
    {
        return $this->data;
    }
}
