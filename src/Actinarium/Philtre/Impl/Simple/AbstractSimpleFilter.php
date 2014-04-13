<?php
/**
 * @author  Actine <actine@actinarium.com>
 * Date: 13.04.14
 * Time: 2:58
 *
 * @version GIT: $Id$
 */

namespace Actinarium\Philtre\Impl\Simple;


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
    protected $filterContext;
    /** @var array|object|null */
    protected $configuration;

    /**
     * @inheritdoc
     */
    public function __construct(FilterContext $filterContext, $configuration = null)
    {
        $this->filterContext = $filterContext;
        $this->configuration = $configuration;
    }
}
