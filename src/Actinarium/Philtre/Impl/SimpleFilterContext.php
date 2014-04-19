<?php
/**
 * @author  Actine <actine@actinarium.com>
 * Date: 13.04.14
 * Time: 3:03
 *
 * @version GIT: $Id$
 */

namespace Actinarium\Philtre\Impl;


use Actinarium\Philtre\Core\Exceptions\UnregisteredStreamException;
use Actinarium\Philtre\Core\FilterContext;
use InvalidArgumentException;

/**
 * This filter context stores data unwrapped in a bag. This means, getting and setting data will follow the same rules
 * as assignment in PHP: scalars will be copied, objects will be passed by reference. This kind of context is fitting
 * for trivial processing chains because it's the simplest one, however you should remember the aforementioned if
 * you plan on using it in your custom pipeline.
 *
 * @package Actinarium\Philtre\Impl
 */
class SimpleFilterContext implements FilterContext
{
    /** @var mixed[] */
    protected $dataBag;

    /**
     * @inheritdoc
     */
    public function hasData($streamId)
    {
        if (!is_string($streamId)) {
            throw new InvalidArgumentException("Non-string entity was provided as stream ID");
        }
        return array_key_exists($streamId, $this->dataBag);
    }

    /**
     * @inheritdoc
     */
    public function setData($streamId, $data)
    {
        if (!is_string($streamId)) {
            throw new InvalidArgumentException("Non-string entity was provided as stream ID");
        }
        $this->dataBag[$streamId] = $data;
    }

    /**
     * @inheritdoc
     */
    public function getData($streamId)
    {
        if (!is_string($streamId)) {
            throw new InvalidArgumentException("Non-string entity was provided as stream ID");
        }
        if ($this->hasData($streamId)) {
            return $this->dataBag[$streamId];
        } else {
            throw new UnregisteredStreamException("Requested unregistered stream");
        }
    }
}
