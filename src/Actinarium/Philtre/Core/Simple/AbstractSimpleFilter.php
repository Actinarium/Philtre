<?php
/**
 * @author  Actine <actine@actinarium.com>
 * Date: 13.04.14
 * Time: 2:58
 *
 * @version GIT: $Id$
 */

namespace Actinarium\Philtre\Core\Simple;


use Actinarium\Philtre\Core\Filter;
use Actinarium\Philtre\Core\FilterContext;

/**
 * Extend this class to build simple filters
 *
 * @package Actinarium\Philtre\Impl\Simple
 */
abstract class AbstractSimpleFilter implements Filter
{

    /** @var FilterContext */
    private $filterContext;
    /** @var array|object|null */
    private $configuration;

    /**
     * @inheritdoc
     */
    public function __construct(FilterContext $filterContext, $configuration = null)
    {
        $this->filterContext = $filterContext;
        $this->configuration = $configuration;
    }

    protected function getFilterContext()
    {
        return $this->filterContext;
    }

    protected function getConfiguration()
    {
        return $this->configuration;
    }
}
