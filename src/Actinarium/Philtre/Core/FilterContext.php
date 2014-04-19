<?php
/**
 * @author  Actine <actine@actinarium.com>
 * Date: 06.03.14
 * Time: 0:56
 */

namespace Actinarium\Philtre\Core;

use Actinarium\Philtre\Core\Exceptions\StoringDataException;
use Actinarium\Philtre\Core\Exceptions\AcquiringDataException;

/**
 * The interface that declares core functionality of a context - basically a shareable storage for data for a filter.
 *
 * @package Actinarium\Philtre\Core
 */
interface FilterContext
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
     * Any FilterContext must implement this method so that Filters and PipelineManagers can use it to put data into the
     * context. Implementation is frivolous. Method should not return anything, but rather throw
     * {@link StoringDataException} or one of its sub-classes in case of failure.
     *
     * @param string $streamId
     * @param mixed  $data
     *
     * @return void
     * @throws StoringDataException
     */
    public function setData($streamId, $data);

    /**
     * Any FilterContext must implement this method so that Filters and PipelineManagers can use it to get data from the
     * context. Implementation is frivolous. In case data cannot be retrieved, the method should throw
     * {@link AcquiringDataException} or one of its sub-classes.
     *
     * @param string $streamId
     *
     * @return mixed
     * @throws AcquiringDataException
     */
    public function getData($streamId);
}
