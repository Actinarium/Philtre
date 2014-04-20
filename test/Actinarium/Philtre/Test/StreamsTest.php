<?php
/**
 * @author Actine <actine@actinarium.com>
 * Date: 20.04.14
 * Time: 14:36
 */

namespace Actinarium\Philtre\Test;

use Actinarium\Philtre\Core\IO\Streams\MutableStream;
use Actinarium\Philtre\Core\IO\Streams\Stream;

class StreamsTest extends AbstractPhiltreTest
{
    /**
     * @dataProvider dataProvider
     */
    public function testSimpleStream($data)
    {
        $stream = new Stream($data);
        $this->assertSame($data, $stream->getData());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testMutableStream($data)
    {
        $stream = new MutableStream();
        $stream->setData($data);
        $this->assertSame($data, $stream->getData());
    }
}
