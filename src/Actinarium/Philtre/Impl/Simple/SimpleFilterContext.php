<?php
/**
 * @author  Actine <actine@actinarium.com>
 * Date: 13.04.14
 * Time: 3:03
 *
 * @version GIT: $Id$
 */

namespace Actinarium\Philtre\Impl\Simple;


use Actinarium\Philtre\Core\Exceptions\InvalidIdentifierException;
use Actinarium\Philtre\Core\Exceptions\StoringDataException;
use Actinarium\Philtre\Core\Exceptions\UndeclaredStreamException;
use Actinarium\Philtre\Core\Exceptions\UnregisteredStreamException;
use Actinarium\Philtre\Core\FilterContext;
use Actinarium\Philtre\Core\IO\Stream;

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
    public function putData($streamId, $data)
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
