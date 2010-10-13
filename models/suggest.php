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

class Suggest extends AppModel
{
	var $name = 'Suggest';
	var $useTable = false;

	function getSuggestions($model, $field, $param)
	{
		// get the model object
		$Model = ClassRegistry::init($model);

		// do the query
		$results = $Model->find
		(
			'all',
			array
			(
				'conditions' => array
				(
					$field . ' like' => '%' . $param . '%'
				),
				'fields' => array
				(
					'DISTINCT ' . $field
				),
				'limit' => 10
			)
		);

		// simplify the results
		$simplifiedResults = array();
		foreach($results as $result)
			$simplifiedResults[count($simplifiedResults)] = array('value' => $result[$model][$field]);

		return $simplifiedResults;
	}
}
?>