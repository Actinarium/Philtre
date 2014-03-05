<?php
/**
 * @author pdanyliuk
 * Date: 06.03.14
 * Time: 1:08
 */

namespace Actinarium\Philtre\Core\IO\Metadata;


class FilterIODescriptor
{
    /** @var StreamDescriptor[] */
    protected $inputStreams;
    /** @var StreamDescriptor[] */
    protected $outputStreams;

    function __construct(array $inputStreams, array $outputStreams)
    {
        $this->inputStreams = $inputStreams;
        $this->outputStreams = $outputStreams;
    }

    /**
     * @return StreamDescriptor[]
     */
    public function getInputStreams()
    {
        return $this->inputStreams;
    }

    /**
     * @return StreamDescriptor[]
     */
    public function getOutputStreams()
    {
        return $this->outputStreams;
    }
} 
