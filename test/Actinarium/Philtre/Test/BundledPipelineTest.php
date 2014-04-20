<?php
/**
 * @author Actine <actine@actinarium.com>
 * Date: 20.04.14
 * Time: 22:07
 */

namespace Actinarium\Philtre\Test;


use Actinarium\Philtre\Impl\BundledPipeline;

class BundledPipelineTest extends \PHPUnit_Framework_TestCase
{
    /**
     * See documentation for reference config explanation
     */
    public function testProcessPipelineFromReferenceConfig()
    {
        $config = json_decode(file_get_contents(__DIR__ . '/Resources/reference_config.json'));
        $manager = new BundledPipeline($config);
        $result = $manager->process();

        // Two inputs - not touched, even though filters try to edit input 2
        $this->assertEquals('BaseOne', $result['input1']);
        $this->assertEquals('BaseTwo', $result['input2']);
        // Output exported from the fourth filter overwrites output exported from the 1st.
        $this->assertEquals('BaseOne_BaseTwo_Fourth', $result['output']);

        // Since the 1st and the 3rd filters share one context, the 1st puts IN2.IN1 into IN2, and the 3rd appends one
        // more IN1 to the stream, which is OUT of the 1st filter
        $this->assertEquals('BaseTwo_BaseOne', $result['input2-1']);
        $this->assertEquals('BaseTwo_BaseOne_BaseOne_BaseTwo_First', $result['input2-2']);

        // And since IN1 of the 3rd filter is OUT of the 1st, and IN2 is changed by the 1st...
        $this->assertEquals('BaseOne_BaseTwo_First_BaseTwo_BaseOne_Third', $result['output-2']);
    }
}
