<?php
/**
 * @author  Actine <actine@actinarium.com>
 * Date: 13.04.14
 * Time: 3:03
 *
 * @version GIT: $Id$
 */

namespace Actinarium\Philtre\Core\Simple;


use Actinarium\Philtre\Core\Exceptions\InvalidIdentifierException;
use Actinarium\Philtre\Core\Exceptions\UnregisteredStreamException;
use Actinarium\Philtre\Core\FilterContext;
use Actinarium\Philtre\Core\IO\Stream;

class StreamedFilterContext implements FilterContext
{
    /** @var Stream[] */
    protected $streamsBag;

    /**
     * @inheritdoc
     */
    public function hasData($streamId)
    {
        if (!is_string($streamId)) {
            throw new InvalidIdentifierException("Non-string entity was provided as stream ID");
        }
        return array_key_exists($streamId, $this->streamsBag);
    }

    /**
     * @inheritdoc
     */
    public function setData($streamId, $data)
    {
        if (!is_string($streamId)) {
            throw new InvalidIdentifierException("Non-string entity was provided as stream ID");
        }
        $this->streamsBag[$streamId] = new Stream($data);
    }

    /**
     * @inheritdoc
     */
    public function getData($streamId)
    {
        if (!is_string($streamId)) {
            throw new InvalidIdentifierException("Non-string entity was provided as stream ID");
        }
        if ($this->hasData($streamId)) {
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
     * @throws \Actinarium\Philtre\Core\Exceptions\InvalidIdentifierException
     */
    public function setStream($streamId, Stream $stream) {
        if (!is_string($streamId)) {
            throw new InvalidIdentifierException("Non-string entity was provided as stream ID");
        }
        $this->streamsBag[$streamId] = $stream;
    }

    /**
     * Get a stream without unwrapping data
     *
     * @param $streamId
     *
     * @return Stream
     * @throws \Actinarium\Philtre\Core\Exceptions\InvalidIdentifierException
     * @throws \Actinarium\Philtre\Core\Exceptions\UnregisteredStreamException
     */
    public function getStream($streamId) {
        if (!is_string($streamId)) {
            throw new InvalidIdentifierException("Non-string entity was provided as stream ID");
        }
        if ($this->hasData($streamId)) {
            return $this->streamsBag[$streamId];
        } else {
            throw new UnregisteredStreamException("Requested unregistered stream");
        }
    }
}
