<?php
App::uses('FormHelper', 'View/Helper');
/**
 * Twitter Bootstrap Form Helper
 */
class BootstrapFormHelper extends FormHelper {

	/**
	* Build custom input field for Twitter Bootstrap support
	*
	* @param string $fieldName
	* @param array $options
	*
	* @return string
	*/
	public function input($fieldName, $options = array()) {
		$defaults = array(
			'before'	=> '',
			'between'	=> '<div class="controls">',
			'after'		=> '</div>',
			'format'	=> array('before', 'label', 'between', 'input', 'error', 'after'),
			'class'		=> 'span9', // @todo make this dynamic
			'div'		=> array(
				'class' => 'control-group'
			),
			'error'		=> array(
				'attributes' => array(
					'class' => 'help-block error',
					'wrap'	=> 'span'
				)
			),
			'help'		=> '',
			'required'	=> false,
			'append' => array(),
			'prepend' => array(),
		);

		$options = array_merge($defaults, $this->_inputDefaults, $options);

		if(!empty($options['type']) && $options['type'] == 'radio') {
			$options['before'] = $options['between'];
			$options['between'] = '';
		}

		if (!empty($options['help'])) {
			$options['after'] =  '<p class="help-block">' . $options['help'] . '</p>' . $options['after'];
			unset($options['help']);
		}

		if (!empty($options['actions'])) {
			$options['after'] .= '<div class="actions">' . join("\n", $options['actions']) . '</div>';
			unset($options['actions']);
		}

		if(!empty($options['prepend']) && empty($options['append'])) {
			$prepend = is_array($options['prepend']) ? join("\n", $options['prepend']) : '<span class="add-on">' . $options['prepend'] . '</span>';
			$options['between'] .= '<div class="input-prepend">' . $prepend;
			$options['after'] = '</div>' . $options['after'];
		}
		elseif(!empty($options['append']) && empty($options['prepend'])) {
			$append = is_array($options['append']) ? join("\n", $options['append']) : '<span class="add-on">' . $options['append'] . '</span>';
			$options['between'] .= '<div class="input-append">';
			$options['after'] = $append . '</div>' . $options['after'];
		}
		elseif(!empty($options['prepend']) && !empty($options['append'])) {
			$prepend = is_array($options['prepend']) ? join("\n", $options['prepend']) : '<span class="add-on">' . $options['prepend'] . '</span>';
			$append = is_array($options['append']) ? join("\n", $options['append']) : '<span class="add-on">' . $options['append'] . '</span>';

			$options['between'] .= '<div class="input-append input-prepend">' . $prepend;
			$options['after'] = $append . '</div>' . $options['after'];
		}
		unset($options['prepend']);
		unset($options['append']);
		unset($options['help']);

		return parent::input($fieldName, $options);
	}

	public function label($fieldName = null, $text = null, $options = array()) {
		$options = $this->addClass($options, 'control-label');

		return parent::label($fieldName, $text, $options);
	}


	protected function _inputLabel($fieldName, $label, $options) {
		if (isset($options['help'])) {
			$this->_labelHelpText = $options['help'];
		}

		$label = parent::_inputLabel($fieldName, $label, $options);
		$this->_labelHelpText = '';
		return $label;
	}


	/**
	 * Creates an HTML link, but access the url using method DELETE.
	 * Requires javascript to be enabled in browser.
	 *
	 * This method creates a `<form>` element. So do not use this method inside an existing form.
	 * Instead you should add a submit button using FormHelper::submit()
	 *
	 * ### Options:
	 *
	 * - `data` - Array with key/value to pass in input hidden
	 * - `confirm` - Can be used instead of $confirmMessage.
	 * - Other options is the same of HtmlHelper::link() method.
	 * - The option `onclick` will be replaced.
	 *
	 * @param string $title The content to be wrapped by <a> tags.
	 * @param mixed $url Cake-relative URL or array of URL parameters, or external URL (starts with http://)
	 * @param array $options Array of HTML attributes.
	 * @param string $confirmMessage JavaScript confirmation message.
	 * @return string An `<a />` element.
	 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/form.html#FormHelper::postLink
	 */
	public function deleteLink($title, $url = null, $options = array(), $confirmMessage = false) {
		if (!empty($options['confirm'])) {
			$confirmMessage = $options['confirm'];
			unset($options['confirm']);
		}

		$formName = uniqid('post_');
		$formUrl = $this->url($url);
		$out = $this->Html->useTag('form', $formUrl, array('name' => $formName, 'id' => $formName, 'style' => 'display:none;', 'method' => 'post'));
		$out .= $this->Html->useTag('hidden', '_method', ' value="DELETE"');
		$out .= $this->_csrfField();

		$fields = array();
		if (isset($options['data']) && is_array($options['data'])) {
			foreach ($options['data'] as $key => $value) {
				$fields[$key] = $value;
				$out .= $this->hidden($key, array('value' => $value, 'id' => false));
			}
			unset($options['data']);
		}
		$out .= $this->secure($fields);
		$out .= $this->Html->useTag('formend');

		$url = '#';
		$onClick = 'document.' . $formName . '.submit();';
		if ($confirmMessage) {
			$confirmMessage = str_replace(array("'", '"'), array("\'", '\"'), $confirmMessage);
			$options['onclick'] = "if (confirm('{$confirmMessage}')) { {$onClick} }";
		} else {
			$options['onclick'] = $onClick;
		}
		$options['onclick'] .= ' event.returnValue = false; return false;';

		$out .= $this->Html->link($title, $url, $options);
		return $out;
	}

	/**
	 * Submit button
	 *
	 * @param string $label
	 *
	 * @return string
	 */
	public function submit($label = null, $options = array()) {
		$defaults = array(
			'div'	=> 'actions',
			'class' => 'btn primary'
		);
		$options = array_merge($defaults, $options);

		return parent::submit($label, $options);
	}

/**
 * Creates a set of radio widgets. Will create a legend and fieldset
 * by default.  Use $options to control this
 *
 * ### Attributes:
 *
 * - `separator` - define the string in between the radio buttons
 * - `between` - the string between legend and input set
 * - `legend` - control whether or not the widget set has a fieldset & legend
 * - `value` - indicate a value that is should be checked
 * - `label` - boolean to indicate whether or not labels for widgets show be displayed
 * - `hiddenField` - boolean to indicate if you want the results of radio() to include
 *    a hidden input with a value of ''. This is useful for creating radio sets that non-continuous
 * - `disabled` - Set to `true` or `disabled` to disable all the radio buttons.
 * - `empty` - Set to `true` to create a input with the value '' as the first option.  When `true`
 *   the radio label will be 'empty'.  Set this option to a string to control the label value.
 *
 * @param string $fieldName Name of a field, like this "Modelname.fieldname"
 * @param array $options Radio button options array.
 * @param array $attributes Array of HTML attributes, and special attributes above.
 * @return string Completed radio widget set.
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/form.html#options-for-select-checkbox-and-radio-inputs
 */
	public function radio($fieldName, $options = array(), $attributes = array()) {
		$attributes = $this->_initInputField($fieldName, $attributes);

		$showEmpty = $this->_extractOption('empty', $attributes);
		if ($showEmpty) {
			$showEmpty = ($showEmpty === true) ? __('empty') : $showEmpty;
			$options = array('' => $showEmpty) + $options;
		}
		unset($attributes['empty']);

		$legend = false;
		if (isset($attributes['legend'])) {
			$legend = $attributes['legend'];
			unset($attributes['legend']);
		} elseif (count($options) > 1) {
			$legend = __(Inflector::humanize($this->field()));
		}

		$label = true;
		if (isset($attributes['label'])) {
			$label = $attributes['label'];
			unset($attributes['label']);
		}

		$separator = null;
		if (isset($attributes['separator'])) {
			$separator = $attributes['separator'];
			unset($attributes['separator']);
		}

		$between = null;
		if (isset($attributes['between'])) {
			$between = $attributes['between'];
			unset($attributes['between']);
		}

		$value = null;
		if (isset($attributes['value'])) {
			$value = $attributes['value'];
		} else {
			$value = $this->value($fieldName);
		}

		$disabled = array();
		if (isset($attributes['disabled'])) {
			$disabled = $attributes['disabled'];
		}

		$out = array();

		$hiddenField = isset($attributes['hiddenField']) ? $attributes['hiddenField'] : true;
		unset($attributes['hiddenField']);

		foreach ($options as $optValue => $optTitle) {
			$optionsHere = array('value' => $optValue);

			if (isset($value) && $optValue == $value) {
				$optionsHere['checked'] = 'checked';
			}
			if ($disabled && (!is_array($disabled) || in_array($optValue, $disabled))) {
				$optionsHere['disabled'] = true;
			}
			$tagName = Inflector::camelize(
				$attributes['id'] . '_' . Inflector::slug($optValue)
			);

			$allOptions = array_merge($attributes, $optionsHere);
			$radio = $this->Html->useTag('radio', $attributes['name'], $tagName,
				array_diff_key($allOptions, array('name' => '', 'type' => '', 'id' => '')), ''
			);
			if ($label) {
				$radio = $this->Html->useTag('label', $tagName, ' class="radio"', $radio . $optTitle);
			}			
			$out[] = $radio;
		}
		$hidden = null;

		if ($hiddenField) {
			if (!isset($value) || $value === '') {
				$hidden = $this->hidden($fieldName, array(
					'id' => $attributes['id'] . '_', 'value' => '', 'name' => $attributes['name']
				));
			}
		}
		$out = $hidden . implode($separator, $out);

		if ($legend) {
			$out = $this->Html->useTag('fieldset', '', $this->Html->useTag('legend', $legend) . $between . $out);
		}
		return $out;
	}
}