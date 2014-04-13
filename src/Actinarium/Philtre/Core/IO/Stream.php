<?php
/**
 * @author  Actine <actine@actinarium.com>
 * Date: 13.04.14
 * Time: 3:06
 *
 * @version GIT: $Id$
 */

namespace Actinarium\Philtre\Core\IO;

/**
 * Stream is just an immutable plain object wrapping some data within.
 *
 * @package Actinarium\Philtre\Core\IO
 */
class Stream
{
    /** @var mixed */
    protected $data;

    public function __construct(&$data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->getData();
    }
} 
