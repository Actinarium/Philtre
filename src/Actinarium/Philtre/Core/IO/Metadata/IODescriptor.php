<?php
/**
 * @author pdanyliuk
 * Date: 06.03.14
 * Time: 1:08
 */

namespace Actinarium\Philtre\Core\IO\Metadata;

/**
 * Immutable object that describes which streams are used by a filter. A filter is obliged to provide a truthful
 * descriptor to whatever requests it. Pre-processing validation is based primarily on the descriptor.
 *
 * @package Actinarium\Philtre\Core\IO\Metadata
 */
class IODescriptor
{
    /** @var StreamDescriptor[] */
    protected $requiredStreams;
    /** @var StreamDescriptor[] */
    protected $exportedStreams;
    /** @var StreamDescriptor[] */
    protected $usedStreams;

    /**
     * @param StreamDescriptor[] $requires Streams that the filter reads data from
     * @param StreamDescriptor[] $exports  New streams that the filter produces
     * @param StreamDescriptor[] $uses     Streams that the filter may use for both input and output, i.e. alter data
     */
    function __construct(array $requires, array $exports, array $uses)
    {
        $this->requiredStreams = $requires;
        $this->exportedStreams = $exports;
        $this->usedStreams = $uses;
    }

    /**
     * @return StreamDescriptor[]
     */
    public function requires()
    {
        return $this->requiredStreams;
    }

    /**
     * @return StreamDescriptor[]
     */
    public function exports()
    {
        return $this->exportedStreams;
    }
} 
