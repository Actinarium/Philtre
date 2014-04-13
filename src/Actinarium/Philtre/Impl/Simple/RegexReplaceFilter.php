<?php
/**
 * @author Actine <actine@actinarium.com>
 * Date: 13.04.14
 * Time: 17:58
 *
 * @version GIT: $Id$
 */

namespace Actinarium\Philtre\Impl\Simple;

use Actinarium\Philtre\Core\IO\DeclaringIO;
use Actinarium\Philtre\Core\IO\Metadata\IODescriptor;
use Actinarium\Philtre\Core\IO\Metadata\StreamDescriptor;
use Actinarium\Philtre\Core\Simple\AbstractSimpleFilter;

class RegexReplaceFilter extends AbstractSimpleFilter implements DeclaringIO {

    private $ioDescriptor;

    public function getIODescriptor()
    {
        if ($this->ioDescriptor == null) {
            $this->ioDescriptor = new IODescriptor(
                array(
                    new StreamDescriptor("in", "string", "Input as one string")
                ),
                array(
                    new StreamDescriptor("out", "string", "Output as one string")
                )
            );
        }
        return $this->ioDescriptor;
    }

    public function process()
    {
        $input = $this->getFilterContext()->getData("in");
        $output = preg_replace(
            $this->getConfiguration()->regex,
            $this->getConfiguration()->replacement,
            $input
        );
        $this->getFilterContext()->setData("out", $output);
    }
}
