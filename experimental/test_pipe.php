<?php
/**
 * @author Actine <actine@actinarium.com>
 * Date: 13.04.14
 * Time: 17:51
 *
 * @version GIT: $Id$
 */ 

require "../vendor/autoload.php";

use Actinarium\Philtre\Core\Simple\PromisingStreamedFilterContext;
use Actinarium\Philtre\Core\Simple\SimplePipelineManager;
use Actinarium\Philtre\Impl\Simple\RegexReplaceFilter;

// 1. Process data without manager
$inputString = "Hello World!";
$filterConfig1 = json_decode('{"regex" : "@Wor@", "replacement" : "Phi"}');
$filterConfig2 = json_decode('{"regex" : "@d(?=!)@", "replacement" : "tre"}');

$context1 = new PromisingStreamedFilterContext();
$context2 = new PromisingStreamedFilterContext();
$context1->setData("in", $inputString);
$context2->setStream("in", $context1->getStream("out"));
$filter1 = new RegexReplaceFilter($context1, $filterConfig1);
$filter2 = new RegexReplaceFilter($context2, $filterConfig2);

$filter1->process();
$filter2->process();

echo $context2->getData("out");


// 2. Process data with Pipeline Manager
$config = json_decode(file_get_contents("test_config.json"));
$manager = new SimplePipelineManager($config);
$result = $manager->process();

var_dump($result);
