<?php
/**
 * @author  Actine <actine@actinarium.com>
 * Date: 13.04.14
 * Time: 21:35
 */

namespace Actinarium\Philtre\Impl;


use Actinarium\Philtre\Core\Filter;
use Actinarium\Philtre\Core\ExecutionManager;
use Actinarium\Philtre\Core\FilterContext;
use Actinarium\Philtre\Core\StreamOperatingFilterContext;
use InvalidArgumentException;

/**
 * A reference execution manager that executes filters sequentially and takes care of sandboxing and stream ID aliasing
 *
 * @package Actinarium\Philtre\Impl
 */
class BundledPipeline implements ExecutionManager
{
    /** @var object */
    private $configuration;
    /** @var StreamedFilterContext */
    private $streamHolder;
    /** @var StreamedFilterContext[] */
    private $namedContextsBag;
    /** @var array */
    private $filterClassMap;

    public function __construct($configuration)
    {
        $this->configuration = $configuration;
        $this->populateFiltersMap();
    }

    /**
     * @throws InvalidArgumentException
     * @return string|string[]|null
     */
    public function process()
    {
        // Init stream holder with empty context; init namedContextsBag with empty array
        $this->flush();

        // Fill context with initial data from configuration, if provided
        $this->fillInitialData();

        // If there's filter chain (well, there might be not...), create filters with contexts one by one and execute
        if (is_array($this->configuration->chain)) {
            foreach ($this->configuration->chain as $filter) {
                $filterContext = $this->getFilterContext($filter);
                $filterObject = $this->createFilter($filter, $filterContext);
                $this->injectRequiredStreams($filter, $filterContext);
                $filterObject->process();
                $this->extractExportedStreams($filter, $filterContext);
            }
        }

        // If the chain is configured to return data, return data from given stream(s) as a string or indexed array
        switch ($this->getReturnType()) {
            case 1:
                return $this->streamHolder->getData($this->configuration->return);
            case 2:
                $dataArray = array();
                foreach ($this->configuration->return as $streamId) {
                    $dataArray[$streamId] = $this->streamHolder->getData($streamId);
                }
                return $dataArray;
            case 3:
                $dataArray = array();
                foreach ($this->configuration->return as $exportId => $streamId) {
                    $dataArray[$exportId] = $this->streamHolder->getData($streamId);
                }
                return $dataArray;
            default:
                return null;
        }
    }

    private function flush()
    {
        $this->streamHolder = new StreamedFilterContext();
        $this->namedContextsBag = array();
    }

    private function requireNamedContext(&$id)
    {
        if (!array_key_exists($id, $this->namedContextsBag)) {
            $this->namedContextsBag[$id] = new StreamedFilterContext();
        }
        return $this->namedContextsBag[$id];
    }

    private function fillInitialData()
    {
        if (self::isIterable($this->configuration->initStreams)) {
            foreach ($this->configuration->initStreams as $streamId => $data) {
                $this->streamHolder->setData($streamId, $data);
            }
        }
    }

    private function populateFiltersMap()
    {
        if (self::isIterable($this->configuration->filters)) {
            $this->filterClassMap = (array)$this->configuration->filters;
        } else {
            $this->filterClassMap = array();
        }
    }

    private function getFilterContext(&$filter)
    {
        // If named context is given, use it (allows sharing), otherwise create anonymous context
        if (is_string($filter->context)) {
            return $this->requireNamedContext($filter->context);
        } else {
            return new StreamedFilterContext();
        }
    }

    /**
     * @param               $filter
     * @param FilterContext $filterContext
     *
     * @return Filter
     * @throws \InvalidArgumentException
     */
    private function createFilter($filter, $filterContext)
    {
        // Resolve classname from provided parameter and get object instance
        if (isset($filter->filter) && is_string($filter->filter)) {
            if (array_key_exists($filter->filter, $this->filterClassMap)) {
                $filterClassName = $this->filterClassMap[$filter->filter];
            } else {
                $filterClassName = $filter->filter;
            }
            return new $filterClassName($filterContext, $filter->parameters);
        } else {
            throw new InvalidArgumentException("One of filters doesn't have 'filter' field set properly");
        }
    }

    private function injectRequiredStreams($filter, StreamOperatingFilterContext $filterContext)
    {
        if (self::isIterable($filter->requires)) {
            foreach ($filter->requires as $innerId => $outerId) {
                $filterContext->setStream($innerId, $this->streamHolder->getStream($outerId));
            }
        }
    }

    private function extractExportedStreams($filter, StreamOperatingFilterContext $filterContext)
    {
        if (self::isIterable($filter->exports)) {
            foreach ($filter->exports as $innerId => $outerId) {
                $this->streamHolder->setStream($outerId, $filterContext->getStream($innerId));
            }
        }
    }

    private function getReturnType()
    {
        if (is_string($this->configuration->return)) {
            return 1;
        } elseif (self::isIterable($this->configuration->return)) {
            return 3;
        } elseif (is_array($this->configuration->return)) {
            return 2;
        } else {
            return 0;
        }
    }

    private static function isIterable(&$var)
    {
        return isset($var) && (is_object($var) || (self::isAssoc($var)));
    }

    private static function isAssoc(&$var)
    {
        return is_array($var) && count(array_filter(array_keys($var))) === count($var);
    }
}
