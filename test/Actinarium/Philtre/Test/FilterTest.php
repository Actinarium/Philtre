<?php
/**
 * @author Actine <actine@actinarium.com>
 * Date: 20.04.14
 * Time: 21:52
 */

namespace Actinarium\Philtre\Test;


use Actinarium\Philtre\Core\IO\Metadata\IODescriptorBuilder;
use Actinarium\Philtre\Impl\SimpleFilterContext;
use Actinarium\Philtre\Impl\StreamedFilterContext;
use Actinarium\Philtre\Impl\WiringFilterContext;
use Actinarium\Philtre\Test\Resources\FixtureFilter;

class FilterTest extends \PHPUnit_Framework_TestCase
{
    public function testFilterIODescriptor()
    {
        $builder = new IODescriptorBuilder();
        $descriptorExpected = $builder
            ->requires()->streamId('IN1')->type('string')->description('First string')
            ->uses()->streamId('IN2')->type('string')->description('Second string that gets changed to IN1_IN2')
            ->exports()->streamId('OUT')->type('string')->description('Output string: IN2_IN1_suffix')
            ->get();
        $context = new SimpleFilterContext();
        $filter = new FixtureFilter($context, array("suffix" => "value"));
        $descriptorActual = $filter->getIODescriptor();
        $this->assertEquals($descriptorExpected, $descriptorActual);
    }

    public function testFilterOperationInSimpleContext()
    {
        $context = new SimpleFilterContext();
        $context->setData('IN1', 'testing');
        $context->setData('IN2', 'Testing');

        $filter = new FixtureFilter($context, array("suffix" => "123"));
        $filter->process();

        $this->assertEquals('testing', $context->getData('IN1'));
        $this->assertEquals('Testing_testing', $context->getData('IN2'));
        $this->assertEquals('testing_Testing_123', $context->getData('OUT'));
    }

    public function testFilterOperationInStreamedContext()
    {
        $context = new StreamedFilterContext();
        $context->setData('IN1', 'testing');
        $context->setData('IN2', 'Testing');
        $stream1Before = $context->getStream('IN1');
        $stream2Before = $context->getStream('IN2');

        $filter = new FixtureFilter($context, array("suffix" => "123"));
        $filter->process();

        $this->assertEquals('testing', $context->getData('IN1'));
        $this->assertEquals('testing', $context->getStream('IN1')->getData());
        $this->assertEquals('Testing_testing', $context->getData('IN2'));
        $this->assertEquals('Testing_testing', $context->getStream('IN2')->getData());
        $this->assertEquals('testing_Testing_123', $context->getData('OUT'));
        $this->assertEquals('testing_Testing_123', $context->getStream('OUT')->getData());
        $this->assertSame($stream1Before, $context->getStream('IN1'));
        $this->assertNotSame($stream2Before, $context->getStream('IN2'));
    }

    public function testFilterOperationInWiringContext()
    {
        $context = new WiringFilterContext();
        $context->setData('IN1', 'testing');
        $context->setData('IN2', 'Testing');
        $stream1Before = $context->getStream('IN1');
        $stream2Before = $context->getStream('IN2');

        $filter = new FixtureFilter($context, array("suffix" => "123"));
        $filter->process();

        $this->assertEquals('testing', $context->getData('IN1'));
        $this->assertEquals('testing', $context->getStream('IN1')->getData());
        $this->assertEquals('Testing_testing', $context->getData('IN2'));
        $this->assertEquals('Testing_testing', $context->getStream('IN2')->getData());
        $this->assertEquals('testing_Testing_123', $context->getData('OUT'));
        $this->assertEquals('testing_Testing_123', $context->getStream('OUT')->getData());
        $this->assertSame($stream1Before, $context->getStream('IN1'));
        $this->assertSame($stream2Before, $context->getStream('IN2'));  // even though data changed, the stream is same
    }

    /**
     * @expectedException \Actinarium\Philtre\Core\Exceptions\UnregisteredStreamException
     */
    public function testFilterFailsWhenStreamUnregistered()
    {
        $context = new SimpleFilterContext();
        $context->setData('IN1', 'testing');
        // IN2 missing

        $filter = new FixtureFilter($context, array("suffix" => "123"));
        $filter->process();
    }

    public function testFilterWithoutParameter()
    {
        $context = new SimpleFilterContext();
        $context->setData('IN1', 'testing');
        $context->setData('IN2', 'Testing');

        $filter = new FixtureFilter($context);
        $filter->process();

        $this->assertEquals('testing', $context->getData('IN1'));
        $this->assertEquals('Testing_testing', $context->getData('IN2'));
        $this->assertEquals('testing_Testing_', $context->getData('OUT'));    // suffix is null, hence not appended
    }
}
