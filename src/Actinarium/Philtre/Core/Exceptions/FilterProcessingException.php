<?php
/**
 * @author  Actine <actine@actinarium.com>
 * Date: 13.04.14
 * Time: 2:36
 */

namespace Actinarium\Philtre\Core\Exceptions;

/**
 * An exception that should be thrown by a filter in case there was an error during processing. Filters should throw
 * this exception or any of its sub-classes.
 *
 * @package Actinarium\Philtre\Core\Exceptions
 */
class FilterProcessingException extends \RuntimeException
{

}
