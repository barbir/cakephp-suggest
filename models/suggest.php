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
		$simplifiedResults = Set::extract(sprintf('/%s/%s', $model, $field), $results);

		return $simplifiedResults;
	}
}
?>