<?php
/**
 * @author  Actine <actine@actinarium.com>
 * Date: 13.04.14
 * Time: 2:58
 *
 * @version GIT: $Id$
 */

namespace Actinarium\Philtre\Core\Simple;


use Actinarium\Philtre\Core\Exceptions\InvalidIdentifierException;
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
    private $parameters;

    /**
     * @inheritdoc
     */
    public function __construct(FilterContext $filterContext, $parameters = null)
    {
        $this->filterContext = $filterContext;
        $this->parameters = $parameters;
    }

    protected function getFilterContext()
    {
        return $this->filterContext;
    }

    protected function getParameters()
    {
        return $this->parameters;
    }

    protected function getParameter($name)
    {
        if (!is_string($name)) {
            throw new InvalidIdentifierException("Non-string was provided as parameter name");
        }

        if (is_array($this->parameters) && array_key_exists($name, $this->parameters)) {
            return $this->parameters[$name];
        } elseif (is_object($this->parameters) && isset($this->parameters->$name)) {
            return $this->parameters->$name;
        } else {
            return null;
        }
    }
}
