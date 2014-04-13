<?php
/**
 * @author pdanyliuk
 * Date: 06.03.14
 * Time: 0:56
 */

namespace Actinarium\Philtre\Core;

use Actinarium\Philtre\Core\Exceptions\StoringDataException;

interface FilterContext
{
    /**
     * Any FilterContext must implement this method so that Filters and PipelineManagers can use it to check whether
     * stream with given ID exists. Should return true or false.
     *
     * @param string $streamId
     *
     * @return boolean
     */
    public function hasData($streamId);

    /**
     * Any FilterContext must implement this method so that Filters and PipelineManagers can use it to put data into
     * context. Implementation is frivolous. Method should not return anything, but rather throw
     * {@link StoringDataException} in case of failure.
     *
     * @param string $streamId
     * @param mixed  $data
     *
     * @return void
     * @throws StoringDataException
     */
    public function putData($streamId, $data);

    /**
     * Any FilterContext must implement this method so that Filters and PipelineManagers can use it to get data from context.
     * context. Implementation is frivolous.
     *
     * @param string $streamId
     *
     * @return mixed
     */
    public function getData($streamId);
}
