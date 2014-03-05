<?php
/**
 * @author pdanyliuk
 * Date: 06.03.14
 * Time: 0:53
 */

namespace Actinarium\Philtre\Core;


use Actinarium\Philtre\Core\IO\Metadata\FilterIODescriptor;

interface Filter
{

    /**
     * @param array|object $configuration Configuration passed to filter in any expected form
     *
     * @return mixed
     */
    public function setConfiguration($configuration);

    /**
     * This method should be implemented so that FilterContext can set a reference to itself to every filter registered
     * within it.
     *
     * @param FilterContext $filterContext
     *
     * @return mixed
     */
    public function setFilterContext(FilterContext &$filterContext);

    /**
     * Each filter must implement this method in a valid fashion so that filter context can be aware of used streams.
     *
     * @return FilterIODescriptor valid descriptor with streams metadata
     */
    public function getFilterIODescriptor();

    /**
     * This method should contain the logic that reads data from context, processes, and puts data back to context.
     * It should not accept or return anything.
     *
     * @return void
     */
    public function process();

} 
