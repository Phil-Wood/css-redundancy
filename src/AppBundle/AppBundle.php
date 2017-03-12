<?php

namespace AppBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Sabberworm\CSS\Parser;

class AppBundle extends Bundle
{
	function removeDirectory($path) {
		if(is_dir($path)){
			$files = glob($path . '/*');
			foreach ($files as $file) {
				is_dir($file) ? removeDirectory($file) : unlink($file);
			}
			rmdir($path);
		}
	 	return;
	}

	function crunchCss($path){
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
}
