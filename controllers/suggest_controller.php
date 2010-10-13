<?php
/*
This file is part of CakePHP Suggest Plugin.
 
CakePHP Suggest Plugin is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.
 
CakePHP Suggest Plugin is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with CakePHP Suggest Plugin. If not, see <http://www.gnu.org/licenses/>.
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