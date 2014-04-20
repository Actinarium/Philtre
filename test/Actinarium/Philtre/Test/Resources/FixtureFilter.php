<?php
/**
 * @author Actine <actine@actinarium.com>
 * Date: 20.04.14
 * Time: 20:51
 */

namespace Actinarium\Philtre\Test\Resources;

use Actinarium\Philtre\Core\AbstractSimpleFilter;
use Actinarium\Philtre\Core\Exceptions\FilterProcessingException;
use Actinarium\Philtre\Core\IO\Metadata\IODescriptorBuilder;

/**
 * Filter for testing. Takes IN1 and IN2 as inputs, produces concatenation IN2 = IN2_IN1, OUT = IN1_IN2_suffix
 *
 * @package Actinarium\Philtre\Test\Resources
 */
class FixtureFilter extends AbstractSimpleFilter
{
    private static $ioDescriptor;

    public function getIODescriptor()
    {
        if (self::$ioDescriptor == null) {
            $builder = new IODescriptorBuilder();
            self::$ioDescriptor = $builder
                ->requires()->streamId('IN1')->type('string')->description('First string')
                ->uses()->streamId('IN2')->type('string')->description('Second string that gets changed to IN1_IN2')
                ->exports()->streamId('OUT')->type('string')->description('Output string: IN2_IN1_suffix')
                ->get();
        }
        return self::$ioDescriptor;
    }

    /**
     * @return void|mixed
     * @throws FilterProcessingException
     */
    public function process()
    {
        $in1 = $this->getFilterContext()->getData("IN1");
        $in2 = $this->getFilterContext()->getData("IN2");
        $this->getFilterContext()->setData("IN2", $in2 . '_' . $in1);
        $this->getFilterContext()->setData("OUT", $in1 . '_' . $in2 . '_' . $this->getParameter('suffix'));
    }
}
