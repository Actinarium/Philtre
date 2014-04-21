<?php
/**
 * @author  Actine <actine@actinarium.com>
 * Date: 13.04.14
 * Time: 17:58
 */

namespace Actinarium\Philtre\Impl\Filters;

use Actinarium\Philtre\Core\IO\DeclaringIO;
use Actinarium\Philtre\Core\IO\Metadata\IODescriptorBuilder;
use Actinarium\Philtre\Core\AbstractSimpleFilter;

class RegexReplaceFilter extends AbstractSimpleFilter implements DeclaringIO
{
    private static $ioDescriptor;

    public function getIODescriptor()
    {
        if (self::$ioDescriptor == null) {
            $builder = new IODescriptorBuilder();
            self::$ioDescriptor = $builder
                ->requires()->streamId('in')->type('string')->description('Input string')
                ->exports()->streamId('out')->type('string')->description('Output string')
                ->get();
        }
        return self::$ioDescriptor;
    }

    public function process()
    {
        $input = $this->filterContext->getData("in");
        $output = preg_replace(
            $this->getParameter('regex'),
            $this->getParameter('replacement'),
            $input
        );
        $this->filterContext->setData("out", $output);
    }
}
