<?php
/**
 * @author  Actine <actine@actinarium.com>
 * Date: 19.04.14
 * Time: 16:24
 *
 * @version GIT: $Id$
 */

namespace Actinarium\Philtre\Core;


use Actinarium\Philtre\Core\IO\Stream;

interface StreamOperatingFilterContext
{
    public function setStream($streamId, Stream $stream);

    public function getStream($streamId);
}
