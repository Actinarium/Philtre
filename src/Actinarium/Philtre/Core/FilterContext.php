<?php
/**
 * @author pdanyliuk
 * Date: 06.03.14
 * Time: 0:56
 */

namespace Actinarium\Philtre\Core;


interface FilterContext
{
    /**
     * Use this method to add filters to Filter Context.
     *
     * Implementation of this method should set back-reference of itself to the filter and add it to the tree,
     * collection or pipeline (depending on implementation)
     *
     * @param Filter $filter
     *
     * @return mixed
     */
    public function registerFilter(Filter &$filter);

    /**
     * Filters should use this method to put data into context. Implementation is frivolous.
     *
     * @param string $streamId
     * @param mixed $data
     *
     * @return mixed
     */
    public function putData($streamId, &$data);

    /**
     * Filters should use this method to get data from context. Implementation is frivolous.
     *
     * @param string $streamId
     *
     * @return mixed
     */
    public function getData($streamId);
}
