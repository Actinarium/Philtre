<?php
/**
 * @author  Actine <actine@actinarium.com>
 * Date: 06.03.14
 * Time: 0:58
 */

namespace Actinarium\Philtre\Core\IO\Metadata;

use InvalidArgumentException;

/**
 * Immutable object that describes one stream used by a filter.
 *
 * @package Actinarium\Philtre\Core\IO\Metadata
 */
class StreamDescriptor
{
    /** @var string */
    protected $streamId;
    /** @var null|string */
    protected $description;
    /** @var string[] */
    protected $types;

    /**
     * @param string          $streamId    Stream identifier (any conventional string)
     * @param string|string[] $types       A type or array of types. As long as the type is conventional it can be used:
     *                                     validator should only check that the input of filter B can handle any type
     *                                     that can be produced by output of filter A
     * @param string|null     $description Optional description of the stream, to be used in chain designers etc.
     *
     * @throws \InvalidArgumentException if streamId or types is missing or invalid, or description is not string.
     */
    public function __construct($streamId, $types, $description = null)
    {
        if (empty($streamId) || empty($types) || !is_string($streamId) || !(is_array($types) || is_string($types))) {
            throw new InvalidArgumentException('streamId and types are invalid or empty');
        }
        if (!empty($description) && !is_string($description)) {
            throw new InvalidArgumentException('illegal type provided for description');
        }

        $this->streamId = $streamId;
        if (is_array($types)) {
            $this->types = $types;
        } elseif (is_string($types)) {
            $this->types = array($types);
        }
        $this->description = $description;
    }

    /**
     * @return string|null
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getStreamId()
    {
        return $this->streamId;
    }

    /**
     * @return string[]
     */
    public function getTypes()
    {
        return $this->types;
    }
}
