<?php

namespace AppBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Sabberworm\CSS\Parser;
use Masterminds\HTML5;
use Symfony\Component\DomCrawler\Crawler;
use IvoPetkov\HTML5DOMDocument;
use GuzzleHttp\Client;

class AppBundle extends Bundle
{
	function removeDirectory($path)
	{
		if(is_dir($path)){
			$files = glob($path . '/*');
			foreach ($files as $file) {
				is_dir($file) ? removeDirectory($file) : unlink($file);
			}
			rmdir($path);
		}
	 	return;
	}

	function splitCss($path)
	{
		$cssArray = array();
		$oCssParser = new Parser(file_get_contents($path));
		$oCss = $oCssParser->parse();
		foreach($oCss->getAllDeclarationBlocks() as $oBlock) {
			foreach($oBlock->getSelectors() as $oSelector) {
		        //Loop over all selector parts (the comma-separated strings in a selector) and prepend the id
		        $cssArray[] = $oSelector->getSelector();
		    }
		}
		return $cssArray;
	}

	function runTheNumbers($selectorArray, $path)
	{
		/*
		 * Allowed
		 *
		 * :enabled, :disabled, :checked, :unchecked
		 * 
		 */
		
		$notAllowed = array(':link',
		 					':visited',
		 					':target',
		 					':hover',
		 					':focus',
		 					':active',
		 					':invalid',
		 					':indeterminate',
		 					':before',
		 					':after',
		 					':first-line',
		 					':first-letter'
		 					);

		# Http Client
		$client = new Client();
		$response = $client->request('GET', $path);
		$result = $response->getBody();
	
		# HTML5 Parser
		$dom = new HTML5DOMDocument();
		$dom->loadHTML($result);

		# DOM Crawler
		$crawler = new Crawler($dom);

		


		echo "<pre>";

		# Loop through selectors
		foreach ($selectorArray as $selector){

			$noPseudoSelector = $selector->getName();
			if(strpos($noPseudoSelector, ":") !== false) {
				foreach($notAllowed as $case) {
					if(strpos($noPseudoSelector, $case) !== false) {
						$noPseudoSelector = str_replace($case, "", $noPseudoSelector);
					}
				}
			}
			$link = $crawler->filter($noPseudoSelector);
			echo count($link) . ' ' . $selector->getName() . "\n";
		}

		echo "</pre>";

		// echo var_dump(count($link));
		die;
	}
}
