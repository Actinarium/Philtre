<?php
/**
 * @author  Actine <actine@actinarium.com>
 * Date: 17.04.14
 * Time: 22:17
 */

namespace Actinarium\Philtre\Impl\Filters;

use Actinarium\Philtre\Core\IO\DeclaringIO;
use Actinarium\Philtre\Core\IO\Metadata\IODescriptorBuilder;
use Actinarium\Philtre\Core\AbstractSimpleFilter;

class StringConcatFilter extends AbstractSimpleFilter implements DeclaringIO
{

    private static $ioDescriptor;

    public function getIODescriptor()
    {
        if (self::$ioDescriptor == null) {
            $builder = new IODescriptorBuilder();
            self::$ioDescriptor = $builder
                ->requires()->streamId('in1')->type('string')->description('First input string')
                ->requires()->streamId('in2')->type('string')->description('Second input string')
                ->exports()->streamId('out')->type('string')->description('Output string')
                ->get();
        }
        return self::$ioDescriptor;
    }

    public function process()
    {
        $string = $this->getFilterContext()->getData('in1') . $this->getFilterContext()->getData('in2');
        $this->getFilterContext()->setData('in1', $string);
        $this->getFilterContext()->setData('out', $string);
    }
}
