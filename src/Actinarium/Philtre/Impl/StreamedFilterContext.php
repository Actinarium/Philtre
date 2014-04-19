<?php
/**
 * @author  Actine <actine@actinarium.com>
 * Date: 13.04.14
 * Time: 3:03
 */

namespace Actinarium\Philtre\Impl;


use Actinarium\Philtre\Core\Exceptions\UnregisteredStreamException;
use Actinarium\Philtre\Core\FilterContext;
use Actinarium\Philtre\Core\IO\Streams\Stream;
use Actinarium\Philtre\Core\StreamOperatingFilterContext;
use InvalidArgumentException;

class StreamedFilterContext implements FilterContext, StreamOperatingFilterContext
{
    /** @var Stream[] */
    protected $streamsBag;

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
        $this->streamsBag[$streamId] = new Stream($data);
    }

    /**
     * @inheritdoc
     */
    public function getData($streamId)
    {
        if (!is_string($streamId)) {
            throw new InvalidArgumentException("Non-string entity was provided as stream ID");
        }
        if ($this->isRegistered($streamId)) {
            return $this->streamsBag[$streamId]->getData();
        } else {
            throw new UnregisteredStreamException("Requested unregistered stream");
        }
    }

    /**
     * Put a stream without unwrapping data
     *
     * @param        $streamId
     * @param Stream $stream
     *
     * @return void
     * @throws \InvalidArgumentException
     */
    public function setStream($streamId, Stream $stream)
    {
        if (!is_string($streamId)) {
            throw new InvalidArgumentException("Non-string entity was provided as stream ID");
        }
        $this->streamsBag[$streamId] = $stream;
    }

    /**
     * Get a stream without unwrapping data
     *
     * @param $streamId
     *
     * @throws \InvalidArgumentException
     * @throws \Actinarium\Philtre\Core\Exceptions\UnregisteredStreamException
     * @return Stream
     */
    public function getStream($streamId)
    {
        if (!is_string($streamId)) {
            throw new InvalidArgumentException("Non-string entity was provided as stream ID");
        }
        if ($this->isRegistered($streamId)) {
            return $this->streamsBag[$streamId];
        } else {
            throw new UnregisteredStreamException("Requested unregistered stream");
        }
    }
}
