<?php
/**
 * @author Actine <actine@actinarium.com>
 * Date: 17.04.14
 * Time: 22:17
 *
 * @version GIT: $Id$
 */

namespace Actinarium\Philtre\Impl\Simple;


use Actinarium\Philtre\Core\Exceptions\FilterProcessingException;
use Actinarium\Philtre\Core\IO\DeclaringIO;
use Actinarium\Philtre\Core\IO\Metadata\IODescriptor;
use Actinarium\Philtre\Core\IO\Metadata\StreamDescriptor;
use Actinarium\Philtre\Core\Simple\AbstractSimpleFilter;

class StringConcatFilter extends AbstractSimpleFilter implements DeclaringIO {

    private $ioDescriptor;

    /**
     * This method must be implemented in valid fashion so that owning entity can be aware of used streams.
     *
     * @return IODescriptor valid descriptor with streams metadata
     */
    public function getIODescriptor()
    {
        if ($this->ioDescriptor == null) {
            $this->ioDescriptor = new IODescriptor(
                array(
                    new StreamDescriptor("in1", "string", "Input 1 as one string"),
                    new StreamDescriptor("in2", "string", "Input 2 as one string")
                ),
                array(
                    new StreamDescriptor("out", "string", "Output as one string")
                )
            );
        }
        return $this->ioDescriptor;
    }

    /**
     * This method should contain the logic that reads data from context, processes, and puts data back to context.
     * For I/O it should use provided context rather than accept or return anything, however this is not forbidden,
     * especially for custom PipelineManager implementations. In case of failure the method should throw
     * {@link FilterProcessingException}.
     *
     * @return void|mixed
     * @throws FilterProcessingException
     */
    public function process()
    {
        $string = $this->getFilterContext()->getData('in1') . $this->getFilterContext()->getData('in2');
        $this->getFilterContext()->setData('in1', $string);
        $this->getFilterContext()->setData('out', $string);
    }
}
