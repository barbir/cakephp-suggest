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

class SuggestHelper extends AppHelper
{
	/*
	 * Returns a script which contains the suggest logic, and attaches the triggering to the proper control.
	 */
	function element($fieldName, $settings = array())
	{
		// get the calling view object
		$view =& ClassRegistry::getObject('view'); 

		// get the id of the control on which the logic will be attached
		$controlId = $this->__extractSetting($settings, 'controlId', '');
		if($controlId == '')
		{
			$controlOptions = $view->Form->_initInputField($fieldName);
			$controlId = $controlOptions['id'];
		}

		// get the model from the calling view
		$modelId = $this->__extractSetting($settings, 'modelId', '');
		if($modelId == '')
		{
			$modelId = $view->model;
		}

		// get the current controller name
		$controllerName = $view->params['controller'];
		
		// generate the suggest url for the ajax request
		$suggestUrl = $view->Html->url(array('controller' => 'suggest', 'action' => 'index'));

		$script = "
			<script type=\"text/javascript\">

				var suggestKeyboard = function (control, suggestList, pressed)
				{
					var suggestListItems = suggestList + ' li';
					var suggestListSelectedItem = suggestListItems + '.selected';

					var position = -1;
					var len = $(suggestListItems).length;
					if($(suggestListSelectedItem).length == 1)
					{
						$.each
						(
							$(suggestListItems),
							function(index, value)
							{ 
								if($(value).text() == $(suggestListSelectedItem).text())
								{
									position = index; 
								}
							}
						);
					}

					switch(pressed)
					{
						// up
						case 38:
							position--
							if(position < 0)
								position = len - 1;
							$(suggestListSelectedItem).removeClass('selected');
							$(suggestListItems).eq(position).addClass('selected');
							$(suggestListItems + ' a').eq(position).focus();
							return true;

						// down
						case 40:
							position++
							if(position >= len)
								position = 0;
							$(suggestListSelectedItem).removeClass('selected');
							$(suggestListItems).eq(position).addClass('selected');
							$(suggestListItems + ' a').eq(position).focus();
							return true;

						// enter
						case 13:
							if(position > -1 && position < len)
							{
								$(control).val($(suggestListSelectedItem).text());
								$(suggestList).addClass('hidden');
								$(suggestList).html('');
								$(control).focus().select();
							}
							return true;

						// escape
						case 27:
							$(suggestList).removeClass('hidden').addClass('hidden');
							$(suggestList).html('');
							$(control).focus();
							return true;
					}

					return false;
				};

				$('#$controlId').bind
				(
					'keyup',
					function(e)
					{
						// get controls and collections names, to simplify the js
						var control = '#" . $controlId . "';
						var suggestList = '#" . $controlId . "_Suggest';
						var suggestListItems = '#" . $controlId . "_Suggest li';
						var suggestListItemLinks = '#" . $controlId . "_Suggest li a';
						var suggestListSelectedItem = '#" . $controlId . "_Suggest li.selected';

						pressed = e.charCode || e.keyCode || -1;

						if(!suggestKeyboard(control, suggestList, pressed))
						{
							$.get
							(
								'$suggestUrl',
								{
									field: '$fieldName',
									param: $('#$controlId').val(),
									model: '$modelId',
									control: '$controlId'
								},
								function(data)
								{
									// fill the list
									$(suggestList).html(data);

									// display or hide the list depending on the results
									if($(suggestListItems).length > 0)
									{
										$(suggestList).removeClass('hidden');

										$(suggestListItemLinks).keypress
										(
											function(e)
											{
												pressed = e.charCode || e.keyCode || -1;
												suggestKeyboard(control, suggestList, pressed);
												return false;
											}
										);
									}
									else
									{
										$(suggestList).removeClass('hidden').addClass('hidden');
									}

									// bind the click on list item handler
									$(suggestListItems).bind
									(
										'click',
										function()
										{
											$(control).val($(this).text());
											$(suggestList).addClass('hidden');
											$(suggestList).html('');
											$(control).focus().select();
										}
									);
								}
							);
						}
					}
				);
				$('#$controlId').after('<ul id=\"$controlId" . "_Suggest\" class=\"suggest hidden\"></ul>');
			</script>
		";

		return $script;
	}

	/*
	 * Extracts a setting under the provided key if possible, otherwise, returns a provided default value.
	 */
	function __extractSetting($settings, $key, $defaultValue = '')
	{
		if(!$settings && empty($settings))
			return $defaultValue;

		if(isset($settings[$key]))
			return $settings[$key];
		else
			return $defaultValue;
	}
}

?>