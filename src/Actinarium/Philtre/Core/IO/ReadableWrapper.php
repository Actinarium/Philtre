<?php
/**
 * @author  Actine <actine@actinarium.com>
 * Date: 20.04.14
 * Time: 1:30
 */
namespace Actinarium\Philtre\Core\IO;


/**
 * Interface that secures presence of getData() method in all data wrappers
 *
 * @package Actinarium\Philtre\Core\IO
 */
interface ReadableWrapper
{
    /**
     * @return null|mixed
     */
    public function getData();
}
