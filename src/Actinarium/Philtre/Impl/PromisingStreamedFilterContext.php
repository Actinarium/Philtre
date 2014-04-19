<?php
/**
 * @author  Actine <actine@actinarium.com>
 * Date: 13.04.14
 * Time: 3:03
 */

namespace Actinarium\Philtre\Impl;


use Actinarium\Philtre\Core\FilterContext;
use Actinarium\Philtre\Core\IO\Streams\MutableStream;
use InvalidArgumentException;

class PromisingStreamedFilterContext implements FilterContext
{
    /** @var \Actinarium\Philtre\Core\IO\Streams\MutableStream[] */
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
     * @param               $streamId
     * @param MutableStream $stream
     *
     * @throws \InvalidArgumentException
     */
    public function setStream($streamId, MutableStream $stream)
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
