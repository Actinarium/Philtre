<?php
/**
 * @author  Actine <actine@actinarium.com>
 * Date: 13.04.14
 * Time: 21:35
 *
 * @version GIT: $Id$
 */

namespace Actinarium\Philtre\Core\Simple;


use Actinarium\Philtre\Core\Exceptions\InvalidIdentifierException;
use Actinarium\Philtre\Core\Filter;
use Actinarium\Philtre\Core\PipelineManager;

class SimplePipelineManager implements PipelineManager
{
    /** @var object */
    private $configuration;
    /** @var PromisingStreamedFilterContext */
    private $streamHolder;
    /** @var PromisingStreamedFilterContext[] */
    private $namedContextsBag;

    public function __construct($configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * @return string|string[]|null
     * @throws \Actinarium\Philtre\Core\Exceptions\InvalidIdentifierException
     */
    public function process()
    {
        // Init stream holder with empty context; init namedContextsBag with empty array
        $this->flush();

        // Fill context with initial data from configuration, if provided
        if (isset($this->configuration->initStreams) && is_object($this->configuration->initStreams)) {
            foreach ($this->configuration->initStreams as $streamId => $data) {
                $this->streamHolder->setData($streamId, $data);
            }
        }

        // Extract filters map from configuration
        if (isset($this->configuration->filters) && is_object($this->configuration->filters)) {
            $filterClassMap = (array) $this->configuration->filters;
        } else {
            $filterClassMap = array();
        }

        // If there's filter chain (well, there might be not...), create filters and contexts
        if (isset($this->configuration->chain) && is_array($this->configuration->chain)) {
            $filterList = array();

            foreach ($this->configuration->chain as $filter) {
                // If named context is given, use it (allows sharing), otherwise create anonymous context
                if (isset($filter->context) && is_string($filter->context)) {
                    $filterContext = $this->requireNamedContext($filter->context);
                } else {
                    $filterContext = new PromisingStreamedFilterContext();
                }

                // Perform unchecked wiring of I/O
                if (isset($filter->requires) && is_object($filter->requires)) {
                    foreach ($filter->requires as $innerId => $globalId) {
                        $filterContext->setStream($innerId, $this->streamHolder->getStream($globalId));
                    }
                }
                if (isset($filter->exports) && is_object($filter->exports)) {
                    foreach ($filter->exports as $innerId => $globalId) {
                        $this->streamHolder->setStream($globalId, $filterContext->getStream($innerId));
                    }
                }

                // Classname: look up if it's registered as an ID (alias), otherwise try to use it as class
                if (isset($filter->filter) && is_string($filter->filter)) {
                    if (array_key_exists($filter->filter, $filterClassMap)) {
                        $filterClassName = $filterClassMap[$filter->filter];
                    } else {
                        $filterClassName = $filter->filter;
                    }
                    $filterObject = new $filterClassName($filterContext, $filter->parameters);
                } else {
                    throw new InvalidIdentifierException("One of filters doesn't have 'filter' field set properly");
                }

                // Add the filter to execution queue
                $filterList[] = $filterObject;
            }

            // Finally, if nothing failed previously, execute all filters one by one
            foreach ($filterList as $filter) {
                /** @var $filter Filter */
                $filter->process();
            }
        }

        // If the chain is configured to return data, return data from given stream(s) as a string or indexed array
        if (isset($this->configuration->return)) {
            if (is_string($this->configuration->return)) {
                return $this->streamHolder->getData($this->configuration->return);
            } elseif (is_array($this->configuration->return)) {
                $dataArray = array();
                foreach ($this->configuration->return as $streamId) {
                    $dataArray[$streamId] = $this->streamHolder->getData($streamId);
                }
                return $dataArray;
            } elseif (is_object($this->configuration->return)) {
                $dataArray = array();
                foreach ($this->configuration->return as $exportId => $streamId) {
                    $dataArray[$exportId] = $this->streamHolder->getData($streamId);
                }
                return $dataArray;
            }
        }
        return null;
    }

    private function flush()
    {
        $this->streamHolder = new PromisingStreamedFilterContext();
        $this->namedContextsBag = array();
    }

    private function requireNamedContext(&$id) {
        if (!array_key_exists($id, $this->namedContextsBag)) {
            $this->namedContextsBag[$id] = new PromisingStreamedFilterContext();
        }
        return $this->namedContextsBag[$id];
    }
}
