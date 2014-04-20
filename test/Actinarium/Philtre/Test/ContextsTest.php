<?php
/**
 * @author Actine <actine@actinarium.com>
 * Date: 20.04.14
 * Time: 20:59
 */

namespace Actinarium\Philtre\Test;

define("TEST", "TEST");

use Actinarium\Philtre\Core\IO\Streams\MutableStream;
use Actinarium\Philtre\Impl\SimpleFilterContext;
use Actinarium\Philtre\Impl\StreamedFilterContext;
use Actinarium\Philtre\Impl\WiringFilterContext;

class ContextsTest extends AbstractPhiltreTest
{
    /**
     * @dataProvider dataProvider
     */
    public function testSimpleContext($data)
    {
        $context = new SimpleFilterContext();
        $this->assertFalse($context->isRegistered(TEST));

        $context->setData(TEST, $data);
        $this->assertTrue($context->isRegistered(TEST));

        $storedData = $context->getData(TEST);
        $this->assertSame($data, $storedData);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testStreamedFilterContext($data)
    {
        $context = new StreamedFilterContext();
        $this->assertFalse($context->isRegistered(TEST));

        $context->setData(TEST, $data);
        $this->assertTrue($context->isRegistered(TEST));

        $storedData = $context->getData(TEST);
        $this->assertSame($data, $storedData);

        $stream = $context->getStream(TEST);
        $this->assertSame($data, $stream->getData());

        // new stream must be created when setting data at the same ID
        $context->setData(TEST, $data);
        $this->assertNotSame($stream, $context->getStream(TEST));

        $context->setStream(TEST, $stream);
        $this->assertSame($stream, $context->getStream(TEST));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testWiringFilterContext($data)
    {
        $context = new WiringFilterContext();
        $this->assertFalse($context->isRegistered(TEST));

        $context->setData(TEST, $data);
        $this->assertTrue($context->isRegistered(TEST));

        $storedData = $context->getData(TEST);
        $this->assertSame($data, $storedData);

        $stream = $context->getStream(TEST);
        $this->assertSame($data, $stream->getData());

        // Data should be written to the same stream
        $context->setData(TEST, $data);
        $this->assertSame($stream, $context->getStream(TEST));

        $stream2 = new MutableStream($data);
        $context->setStream(TEST, $stream2);
        $this->assertSame($stream2, $context->getStream(TEST));
        $this->assertNotSame($stream, $context->getStream(TEST));
    }

    /**
     * @expectedException \Actinarium\Philtre\Core\Exceptions\UnregisteredStreamException
     */
    public function testSimpleContextFailsWhenUnregisteredStreamRequested()
    {
        $context = new SimpleFilterContext();
        $context->getData(TEST);
    }

    /**
     * @expectedException \Actinarium\Philtre\Core\Exceptions\UnregisteredStreamException
     */
    public function testStreamedContextFailsWhenUnregisteredStreamRequested()
    {
        $context = new StreamedFilterContext();
        $context->getData(TEST);
    }

    /**
     * @expectedException \Actinarium\Philtre\Core\Exceptions\UnregisteredStreamException
     */
    public function testStreamedContextFailsWhenUnregisteredStreamRequestedAsStream()
    {
        $context = new StreamedFilterContext();
        $context->getStream(TEST);
    }

    public function testWiringContextReturnsEmptyStreamWhenUnregistered()
    {
        $context = new WiringFilterContext();
        $this->assertNull($context->getData(TEST));
        $this->assertNotNull($context->getStream("TEST2"));
        $this->assertNull($context->getStream("TEST3")->getData());
    }
}
