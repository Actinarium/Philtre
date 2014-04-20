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
    /** @var array|string|null */
    private $result;

    public function __construct($configuration)
    {
        $this->configuration = $configuration;
        $this->canonizeConfig();
        $this->populateFiltersMap();
    }

    /**
     * Perform processing and return result according to configuration.
     * Result can also be obtained using {@link #getResult} method afterwards.
     *
     * @throws \InvalidArgumentException
     * @return array|string|null Result - the data to be returned according to configuration
     */
    public function process()
    {
        // Init stream holder with empty context; init namedContextsBag with empty array
        $this->flush();

        // Fill context with initial data from configuration, if provided
        $this->fillInitialData();

        // If there's filter chain (well, there might be not...), create filters with contexts one by one and execute
        if (property_exists($this->configuration, 'chain') && is_array($this->configuration->chain)) {
            foreach ($this->configuration->chain as $filter) {
                $filterContext = $this->getFilterContext($filter);
                $this->injectRequiredStreams($filter, $filterContext);
                $filterObject = $this->createFilter($filter, $filterContext);
                $filterObject->process();
                $this->extractExportedStreams($filter, $filterContext);
            }
        }

        // Create return data (might be null, a string or an assoc array) and return it
        $this->writeResult();
        return $this->result;
    }

    /**
     * Get processing result
     *
     * @return array|string|null
     */
    public function getResult()
    {
        return $this->result;
    }

    private function flush()
    {
        $this->streamHolder = new StreamedFilterContext();
        $this->namedContextsBag = array();
        $this->result = null;
    }

    /**
     * @param string $id
     */
    private function requireNamedContext(&$id)
    {
        if (!array_key_exists($id, $this->namedContextsBag)) {
            $this->namedContextsBag[$id] = new StreamedFilterContext();
        }
        return $this->namedContextsBag[$id];
    }

    private function fillInitialData()
    {
        if (property_exists($this->configuration, 'initStreams') && is_object($this->configuration->initStreams)) {
            foreach ($this->configuration->initStreams as $streamId => $data) {
                $this->streamHolder->setData($streamId, $data);
            }
        }
    }

    private function populateFiltersMap()
    {
        if (property_exists($this->configuration, 'filters') && is_object($this->configuration->filters)) {
            $this->filterClassMap = (array)$this->configuration->filters;
        } else {
            $this->filterClassMap = array();
        }
    }

    private function getFilterContext($filter)
    {
        // If named context is given, use it (allows sharing), otherwise create anonymous context
        if (property_exists($filter, 'context') && is_string($filter->context)) {
            return $this->requireNamedContext($filter->context);
        } else {
            return new StreamedFilterContext();
        }
    }

    /**
     * @param object        $filter
     * @param FilterContext $filterContext
     *
     * @return Filter
     * @throws \InvalidArgumentException
     */
    private function createFilter($filter, $filterContext)
    {
        // Resolve classname from provided parameter and get object instance
        if (property_exists($filter, 'filter') && is_string($filter->filter)) {
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
        if (property_exists($filter, 'inject') && is_object($filter->inject)) {
            foreach ($filter->inject as $innerId => $outerId) {
                $filterContext->setStream($innerId, $this->streamHolder->getStream($outerId));
            }
        }
    }

    private function extractExportedStreams($filter, StreamOperatingFilterContext $filterContext)
    {
        if (property_exists($filter, 'extract') && is_object($filter->extract)) {
            foreach ($filter->extract as $innerId => $outerId) {
                $this->streamHolder->setStream($outerId, $filterContext->getStream($innerId));
            }
        }
    }

    private function writeResult()
    {
        // If the chain is configured to return data, return data from given stream(s) as a string or indexed array
        if (!property_exists($this->configuration, 'return')) {
            return null;
        }

        if (is_string($this->configuration->return)) {
            $this->result = $this->streamHolder->getData($this->configuration->return);
        } elseif (is_array($this->configuration->return)) {
            $dataArray = array();
            foreach ($this->configuration->return as $streamId) {
                $dataArray[$streamId] = $this->streamHolder->getData($streamId);
            }
            $this->result = $dataArray;
        } elseif (is_object($this->configuration->return)) {
            $dataArray = array();
            foreach ($this->configuration->return as $exportId => $streamId) {
                $dataArray[$exportId] = $this->streamHolder->getData($streamId);
            }
            $this->result = $dataArray;
        } else {
            $this->result = null;
        }
    }

    /**
     * A dirty method to convert provided configuration into expected format
     */
    private function canonizeConfig()
    {
        if (is_array($this->configuration)) {
            $this->configuration = json_decode(json_encode($this->configuration));
        }
    }
}
