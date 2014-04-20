<?php
/**
 * @author  Actine <actine@actinarium.com>
 * Date: 19.04.14
 * Time: 16:24
 */

namespace Actinarium\Philtre\Core;


use Actinarium\Philtre\Core\IO\ReadableWrapper;
use Actinarium\Philtre\Core\IO\Streams\Stream;

/**
 * An interface that adds possibility to get/set data wrappers
 *
 * @package Actinarium\Philtre\Core
 */
interface StreamOperatingFilterContext
{
    /**
     * Any FilterContext must implement this method so that Filters and PipelineManagers can use it to check whether the
     * stream with given ID exists. Should return true or false.
     *
     * @param string $streamId
     *
     * @return boolean
     */
    public function isRegistered($streamId);

    /**
     * Put a stream with wrapped data
     *
     * @param string $streamId
     * @param ReadableWrapper $stream
     *
     * @return void
     */
    public function setStream($streamId, $stream);

    /**
     * Get stream with data
     *
     * @param string $streamId
     *
     * @return Stream
     */
    public function getStream($streamId);
}
