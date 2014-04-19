<?php
/**
 * @author  Actine <actine@actinarium.com>
 * Date: 13.04.14
 * Time: 17:58
 *
 * @version GIT: $Id$
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
                ->requires()->id('in')->type('string')->description('Input string')
                ->exports()->id('out')->type('string')->description('Output string')
                ->get();
        }
        return self::$ioDescriptor;
    }

    public function process()
    {
        $input = $this->getFilterContext()->getData("in");
        $output = preg_replace(
            $this->getParameters()->regex,
            $this->getParameters()->replacement,
            $input
        );
        $this->getFilterContext()->setData("out", $output);
    }
}
