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
 * MutableStream is an object wrapping data that can be changed during life cycle.
 *
 * @package Actinarium\Philtre\Core\IO
 */
class MutableStream extends Stream
{
    public function setData($data)
    {
        return $this->data = $data;
    }
} 
