<?php

/** 
 * phunky ermöglicht das Testen von Funktionen
 * 
 * @author netzzitrone
 * 
 */
class phunky 
{	
	/**
	 * Hier werden die einzelnen Testfälle gespeichert
	 * 
	 * @var array
	 */
	private $_testCases;// = array();
	
	/**
	 * Hier werden die Ergebnisse der Testfälle in Textform gespeichert
	 * 
	 * @var array
	 */
	private $_testResults = array();
	
	/**
	 * Wenn true, wird vor der Ausgabe der Testfälle ein erneurter Aufruf von run() durchgeführt
	 * 
	 * @var bool
	 */
	private $_needRun = true;
	
	/**
	 * Anzahl der fehlgeschlagenen Testfälle
	 * 
	 * @var integer
	 */
	private $_failedTests = 0;
	
	/**
	 * Anzahl der erfolgreichen Testfälle
	 * 
	 * @var integer
	 */
	private $_passedTests = 0;
	
	
	/**
	 * Wandelt bool in string um. true = 'true', false = 'false'
	 * Notwendig, damit bool-Werte bei eval in @executeTestCase erhalten bleiben
	 * 
	 * @param mixed $value
	 * @return mixed
	 */
	private function boolToString ($value)
	{
		if (true === $value)
		{
			return 'true';
		}
		elseif (false === $value)
		{
			return 'false';
		}
		else 
		{
			return $value;
		}
	}
	
	/**
	 * Fügt eine Testfall hinzu
	 * 
	 * @param string $functionName, mixed $assert
	 * @return bool true
	 */
	public function addTestCase($functionName, $assert)
	{
		$testIndex = count($this->getTestCases())+1;
		$this->_testCases[$testIndex]['functionName']  = $functionName;
		$this->_testCases[$testIndex]['assert']  = $assert;
		
		$numerOfArguments = func_num_args();		
		/*
		 * Fügt die Argumente für den Testfall hinzu
		 * Jedes weitere Argument mehr als 2 wird als Parameter an die zu testende Funktion übergeben
		 */
		if ($numerOfArguments > 2)
		{
			$extraArguments = func_get_args();
			unset ($extraArguments[0]);
			unset ($extraArguments[1]);
			$extraArguments = array_merge($extraArguments);
			$this->_testCases[$testIndex]['arguments'] = $extraArguments;			
		}
		else 
		{
			$this->_testCases[$testIndex]['arguments'] = array();
		}
		$this->_needRun = true;
		return true;		
	}
	
	/**
	 * Erzeugt Textausgabe der Ergebnisse der einzelnen Testcases
	 * 
	 * @param none
	 * @return array
	 */
	public function getTestResults()
	{
		/*
		 * Wenn die Tests noch nicht ausgeführt wurden, dies nun tun
		 */
		if ($this->_needRun === true)
		{
			$this->run();
		}
		foreach ($this->_testCases as $key => $testCase)
		{
			/*
			 * Zusicherung nicht erfüllt
			*/
			if ($testCase['assert'] !== $testCase['result'])
			{
				$this->_testResults[$key] = $this->_testCases[$key]['functionName'].": fehlgeschlagen ".
					$this->boolToString($testCase['assert'])." != ".$this->boolToString($testCase['result'])."<br>";
				$this->_failedTests++;
			}
			/*
			 * Zusicherung erfüllt
			*/
			else 
			{
				$this->_testResults[$key] = $this->_testCases[$key]['functionName'].": erfolgreich ".
					$this->boolToString($testCase['assert'])." == ".$this->boolToString($testCase['result'])."<br>";
				$this->_passedTests++;
			}
		} 
		return $this->_testResults;	
	}
	
	/**
	 * Ausgabe der Testergebnisse
	 * 
	 * @param none
	 * @return bool true
	 */
	public function showTestResults()
	{
		$results = $this->getTestResults();
		echo count($this->getTestCases()).' Tests verarbeitet.<br>';
		echo $this->_passedTests.' Tests waren erfolgreich.<br>';
		echo $this->_failedTests.' Tests sind fehlgeschlagen.<br><br>';
		foreach ($results as $result)
		{
			echo $result;
		}
		return true;
	}
	
	/**
	 * Getter für $_testCases
	 * 
	 * @param none
	 * @return array
	 */
	public function getTestCases()
	{
		return $this->_testCases;
	}
	
	/**
	 * Führt einen Testfall aus
	 * 
	 * @param array $testcase
	 * @return mixed
	 */
	private function executeTestCase(array $testcase)
	{
		$returnValue = null;
		$funcName = $testcase['functionName'];
		/*
		 * Umwandlung von bool-Werten in Strings
		*/
		foreach ($testcase['arguments'] as &$x)
		{
			$x = $this->boolToString($x);		
		}
		$arguments = implode(',',$testcase['arguments']);
		$excuteFunction = '$returnValue = '.$funcName.'('.$arguments.');';
		eval($excuteFunction);
		return  $returnValue;
	}
	
	/**
	 * Führt alle Testfälle aus
	 * 
	 * @param none
	 * @return bool true
	 */
	public function run()
	{
		foreach ($this->_testCases as $key => $testCase)
		{
			$this->_testCases[$key]['result'] = $this->executeTestCase($testCase);	
		}	
		$this->_needRun = false;
		return true;
	}	
}
?>