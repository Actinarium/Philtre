<?php
/**
 * @author Actine <actine@actinarium.com>
 * Date: 20.04.14
 * Time: 14:42
 */

namespace Actinarium\Philtre\Test;


abstract class AbstractPhiltreTest extends \PHPUnit_Framework_TestCase
{
    public function dataProvider()
    {
        $object1 = new \stdClass();
        $object1->value1 = 123;
        $object2 = new \stdClass();
        $object2->value2 = "456";
        return array(
            array(1337),
            array(15.59),
            array("Hello World!"),
            array(false),
            array(true),
            array(null),
            array(array(1, "2", array(3, "4", $object1), $object2)),
            array($object1)
        );
    }
}
