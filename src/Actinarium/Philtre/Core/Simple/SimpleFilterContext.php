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
            throw new InvalidIdentifierException("Non-string entity was provided as stream ID");
        }
        return array_key_exists($streamId, $this->dataBag);
    }

    /**
     * @inheritdoc
     */
    public function setData($streamId, $data)
    {
        if (!is_string($streamId)) {
            throw new InvalidIdentifierException("Non-string entity was provided as stream ID");
        }
        $this->dataBag[$streamId] = $data;
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
            return $this->dataBag[$streamId];
        } else {
            throw new UnregisteredStreamException("Requested unregistered stream");
        }
    }
}
