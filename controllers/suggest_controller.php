<?php
/*
 * This file is part of CakePHP Suggest Plugin.
 *
 * CakePHP Suggest Plugin
 * Copyright (c) 2010, Miljenko Barbir (http://miljenkobarbir.com)
 * 
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
*/

class SuggestController extends AppController
{
	var $name = 'Suggest';

	function index()
	{
		//if ($this->RequestHandler->isAjax())
		{
			$data = $this->Suggest->getSuggestions($this->params['url']['model'], $this->params['url']['field'], $this->params['url']['param']);

			// set layout type to ajax
			$this->layout = 'ajax';
			$this->set('data', $data);
			$this->set('control', $this->params['url']['control']);
		}
	}

}
?>