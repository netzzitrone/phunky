<?php
/**
 * Beispiele für phunky
 *
 * @author netzzitrone
 *
 */
 
function dummy ($v1, $v2)
{
	return $v1+$v2;
}

function sub ($v1, $v2, $abs)
{
	if ($abs === true)
	{
		return abs($v1-$v2);
	}
	else 
	{
		return $v1-$v2;
	}
	
}

function dummy2 ($val)
{
	return $val;	
}

require_once ("phunky.php");

$test = new phunky();

$test->addTestCase('dummy2', true, true);
$test->addTestCase('sub', 2, 2, 4, true);
$test->addTestCase('sub', 2, 2, 4, true);
$test->addTestCase('dummy', 32, 1, 2);
$test->addTestCase('dummy', 2, 1, 2);
$test->addTestCase('dummy', 3, 1, 2);

$test->showTestResults();
