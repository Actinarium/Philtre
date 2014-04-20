<?php
/**
 * @author  Actine <actine@actinarium.com>
 * Date: 13.04.14
 * Time: 3:03
 */

namespace Actinarium\Philtre\Impl;


use Actinarium\Philtre\Core\FilterContext;
use Actinarium\Philtre\Core\IO\Streams\MutableStream;
use Actinarium\Philtre\Core\StreamOperatingFilterContext;
use InvalidArgumentException;

/**
 * Similar to {@link StreamedFilterContext} but based on mutable streams. Also, unlike {@link StreamedFilterContext}, it
 * implicitly creates empty mutable streams when inexistent stream is requested.
 * <p>
 * The fact that the streams are reused on data change makes this type of context more efficient than
 * {@link StreamedFilterContext}, however it should be used with caution when the streams are shared across contexts.
 *
 * @package Actinarium\Philtre\Impl
 */
class WiringFilterContext implements FilterContext, StreamOperatingFilterContext
{
    /** @var MutableStream[] */
    protected $streamsBag = array();

    /**
     * @inheritdoc
     */
    public function isRegistered($streamId)
    {
        if (!is_string($streamId)) {
            throw new InvalidArgumentException("Non-string entity was provided as stream ID");
        }
        return array_key_exists($streamId, $this->streamsBag);
    }

    /**
     * @inheritdoc
     */
    public function setData($streamId, $data)
    {
        if (!is_string($streamId)) {
            throw new InvalidArgumentException("Non-string entity was provided as stream ID");
        }
        $this->getStream($streamId)->setData($data);
    }

    /**
     * @inheritdoc
     */
    public function getData($streamId)
    {
        if (!is_string($streamId)) {
            throw new InvalidArgumentException("Non-string entity was provided as stream ID");
        }
        return $this->getStream($streamId)->getData();
    }

    /**
     * Put a stream without unwrapping data
     *
     * @param string        $streamId
     * @param MutableStream $stream
     *
     * @throws \InvalidArgumentException
     */
    public function setStream($streamId, $stream)
    {
        if (!is_string($streamId)) {
            throw new InvalidArgumentException("Non-string entity was provided as stream ID");
        }
        if (!$stream instanceof MutableStream) {
            throw new InvalidArgumentException("Stream must be an instance of MutableStream for this type of context");
        }
        $this->streamsBag[$streamId] = $stream;
    }

    /**
     * Get a stream without unwrapping data
     *
     * @param string $streamId
     *
     * @return MutableStream
     * @throws \InvalidArgumentException
     */
    public function getStream($streamId)
    {
        if (!is_string($streamId)) {
            throw new InvalidArgumentException("Non-string entity was provided as stream ID");
        }
        if (!$this->isRegistered($streamId)) {
            $this->streamsBag[$streamId] = new MutableStream(null);
        }
        return $this->streamsBag[$streamId];
    }
}
