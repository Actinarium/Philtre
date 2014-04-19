<?php
/**
 * @author  Actine <actine@actinarium.com>
 * Date: 20.04.14
 * Time: 1:36
 */

namespace Actinarium\Philtre\Core\IO;

interface WritableWrapper
{
    /**
     * @param mixed|null $data
     *
     * @return void
     */
    public function setData($data);
}
