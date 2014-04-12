<?php
/**
 * @author pdanyliuk
 * Date: 06.03.14
 * Time: 1:08
 */

namespace Actinarium\Philtre\Core\IO\Metadata;


class IODescriptor
{
    /** @var StreamDescriptor[] */
    protected $requiredStreams;
    /** @var StreamDescriptor[] */
    protected $exportedStreams;

    function __construct(array $requires, array $exports)
    {
        $this->requiredStreams = $requires;
        $this->exportedStreams = $exports;
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
