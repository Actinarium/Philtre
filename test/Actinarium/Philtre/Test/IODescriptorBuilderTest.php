<?php
/**
 * @author Actine <actine@actinarium.com>
 * Date: 20.04.14
 * Time: 15:03
 */

namespace Actinarium\Philtre\Test;


use Actinarium\Philtre\Core\IO\Metadata\IODescriptor;
use Actinarium\Philtre\Core\IO\Metadata\IODescriptorBuilder;
use Actinarium\Philtre\Core\IO\Metadata\StreamDescriptor;
use Actinarium\Philtre\Core\IO\Streams\Stream;

class IODescriptorBuilderTest extends \PHPUnit_Framework_TestCase
{
    /** @var StreamDescriptor[] */
    private static $sampleDescriptors;

    public static function setUpBeforeClass()
    {
        $sD = array();
        $sD[] = new StreamDescriptor("ALPHA", "string", "First stream");
        $sD[] = new StreamDescriptor("BETA", "int", "Second stream");
        $sD[] = new StreamDescriptor("GAMMA", array("string", "bool"), "Third stream");
        $sD[] = new StreamDescriptor("DELTA", array("object", "array"), "Fourth stream");
        self::$sampleDescriptors =& $sD;
    }

    public function testBuildDescriptor()
    {
        /** @var StreamDescriptor[] */
        $sD =& self::$sampleDescriptors;

        $builder = new IODescriptorBuilder();
        $descriptor = $builder
            ->requires($sD[0]->getStreamId(), $sD[0]->getTypes(), $sD[0]->getDescription())
            ->requires($sD[1]->getStreamId(), $sD[1]->getTypes(), $sD[1]->getDescription())
            ->exports($sD[2]->getStreamId(), $sD[2]->getTypes(), $sD[2]->getDescription())
            ->uses($sD[3]->getStreamId(), $sD[3]->getTypes(), $sD[3]->getDescription())
            ->get();
        $descriptorExpected = new IODescriptor(
            array($sD[0], $sD[1]),
            array($sD[2]),
            array($sD[3])
        );
        $this->assertEquals($descriptorExpected, $descriptor);
    }

    public function testBuildDescriptorAtomarily()
    {
        /** @var StreamDescriptor[] */
        $sD =& self::$sampleDescriptors;

        $builder = new IODescriptorBuilder();
        $descriptor = $builder
            ->requires()
                ->streamId($sD[0]->getStreamId())->type($sD[0]->getTypes())->description($sD[0]->getDescription())
            ->requires()
                ->streamId($sD[1]->getStreamId())->type($sD[1]->getTypes())->description($sD[1]->getDescription())
            ->exports()
                ->streamId($sD[2]->getStreamId())->type($sD[2]->getTypes())->description($sD[2]->getDescription())
            ->uses()
                ->streamId($sD[3]->getStreamId())->type($sD[3]->getTypes())->description($sD[3]->getDescription())
            ->get();
        $descriptorExpected = new IODescriptor(
            array($sD[0], $sD[1]),
            array($sD[2]),
            array($sD[3])
        );
        $this->assertEquals($descriptorExpected, $descriptor);
    }

    public function testBuildSeveralTypesOneByOne()
    {
        $builder = new IODescriptorBuilder();
        $descriptor = $builder->requires("ID")->type("Type1")->type("Type2")->type("Type3")->get();
        // must do it like this because of php 5.3
        $types = $descriptor->requires();
        $types = $types[0]->getTypes();
        $this->assertEquals(array("Type1", "Type2", "Type3"), $types);
    }

    public function testBuildSeveralTypesWithArrays()
    {
        $builder = new IODescriptorBuilder();
        $descriptor = $builder
            ->requires("ID")->type(array("Type1", "Type2"))->type("Type3")->type(array("Type4", "Type5"))->get();
        // must do it like this because of php 5.3
        $types = $descriptor->requires();
        $types = $types[0]->getTypes();
        $this->assertEquals(array("Type1", "Type2", "Type3", "Type4", "Type5"), $types);
    }

    /**
     * Descriptor must have an ID and at least one type. Description is optional
     *
     * @expectedException \InvalidArgumentException
     */
    public function testFailsWhenIDMissing()
    {
        $builder = new IODescriptorBuilder();
        $builder->requires()->type('type')->get();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFailsWhenTypeMissing()
    {
        $builder = new IODescriptorBuilder();
        $builder->exports()->streamId('ID')->get();
    }
}
