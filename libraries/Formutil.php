<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter library for automatic initialization, generation and validation of forms.
 * @author Dennis Schroer
 * @version 1.0.0 
 */
class MY_Formutil {
	
	private $aForms = array();
	/** The form with the focus, on which inputs will be added */
	private $sFocus = '';
	/** The CodeIgniter object */
	private $CI;
	
	/**
	 * Construct this class and load the needed helpers and libraries
	 */
	function __construct(){
		$this->CI =& get_instance();
		$this->CI->load->helper('form');
		$this->CI->load->library('parser');
		$this->CI->config->load('formutil');
	}
	
	/**
	 * Initialize a form and set the focus to it.
	 * @param array aFormInfo An array containing information about the form
	 */
	public function init_form($aFormInfo){
		$aDefaultForm = array(
			"name"=>"form",
			"action"=>'',
			"template"=>"default_template",
			"attributes"=>array()
		);
		
		if(isset($aFormInfo['template'])){
			if(substr($aFormInfo['template'], -9)!="_template") $aFormInfo['template'] .= '_template';
		} 
		
		$aForm = array_merge($aDefaultForm, $aFormInfo);
			
		$this->aForms[$aForm['name']] =& $aForm;
		
		$this->set_focus($aForm['name']);
	}
	
	/**
	 * Set the focus to the form with this name. Any items added after this call will be added to this form.
	 * @param string sName The name of the form you want to set the focus on.
	 */
	public function set_focus($sName){
		if(!isset($this->aForms[$sName])) die('FormUtil: You can only set the focus on an existing form!');	
		$this->sFocus = $sName;
	}
	 
	private function _add_item($aItemInfo){
		if($this->sFocus=='') die('FormUtil: You can\'t add an item if you haven\'t selected a form!');
			
		$aDefaultItem = array(
			"type"=>"unknown",
			"name"=>"unknown",
			"label"=>NULL,
			"validate"=>NULL,
			"value"=>"",
			//"default_value"=>"",
			"options"=>NULL,
			"error_message"=>NULL,
			"attributes"=>array()
		);
		
		$aItem = array_merge($aDefaultItem, $aItemInfo);
			
		$this->aForms[$this->sFocus]['items'][] =& $aItem;
	}
	
	/**
	 * Checks get and post data if it contains a value for the given name.
	 * If this is set, it returns this value, otherwise the defaultValue is returned
	 * @param $sName string The key you want to check
	 * @param $defaultValue mixed|optional The value to return if there is no input found
	 */
	private function _check_input($sName, $defaultValue=''){
		$value = $this->CI->input->get_post($sName);
		return $value===FALSE ? $defaultValue : $value;
	}
	
	private function _fetch_variable_name($sName){
		return substr($sName, -2)=='[]' ? substr($sName, 0, -2) : $sName;
	}
	
	public function add_input($aItemInfo){
		// Checks	
		if(!isset($aItemInfo['name'])) die('FormUtil: Specify a name of this item.');
		if(!isset($aItemInfo['label'])) $aItemInfo['label'] = $aItemInfo['name'];
		// Typeset
		$aItemInfo['type'] = 'input';
		// Input check
		$aItemInfo['value'] = $this->_check_input($aItemInfo['name'], $aItemInfo['value']);
		// Add item to the form
		$this->_add_item($aItemInfo);
	}
	
	public function add_password($aItemInfo, $bRepopulate = FALSE){
		// Checks
		if(!isset($aItemInfo['name'])) die('FormUtil: Specify a name of this item.');
		if(!isset($aItemInfo['label'])) $aItemInfo['label'] = $aItemInfo['name'];
		// Typeset
		$aItemInfo['type'] = 'password';
		// Input check
		if($bRepopulate){
			$aItemInfo['value'] = $this->_check_input($aItemInfo['name'], $aItemInfo['value']);
		}
		// Add item to the form
		$this->_add_item($aItemInfo);
	}
	
	public function add_hidden($aItemInfo){
		// Checks		
		if(!isset($aItemInfo['name'])) die('FormUtil: Specify a name of this item.');
		if(!isset($aItemInfo['value'])) $aItemInfo['value'] = 'hidden_value';
		// Typeset
		$aItemInfo['type'] = 'hidden';
		// Add item to the form
		$this->_add_item($aItemInfo);
	}
	
	public function add_textarea($aItemInfo){
		// Checks		
		if(!isset($aItemInfo['name'])) die('FormUtil: Specify a name of this item.');
		if(!isset($aItemInfo['label'])) $aItemInfo['label'] = $aItemInfo['name'];
		// Typeset
		$aItemInfo['type'] = 'textarea';
		// Input check
		$aItemInfo['value'] = $this->_check_input($aItemInfo['name'], $aItemInfo['value']);
		// Add item to the form
		$this->_add_item($aItemInfo);
	}
	
	public function add_dropdown($aItemInfo){
		// Checks	
		if(!isset($aItemInfo['name'])) die('FormUtil: Specify a name of this item.');
		if(!isset($aItemInfo['options'])||!is_array($aItemInfo['options'])) die('FormUtil: A dropdown needs an options array');
		if(!isset($aItemInfo['label'])) $aItemInfo['label'] = $aItemInfo['name'];
		// Typeset
		$aItemInfo['type'] = 'dropdown';
		// Input check
		$aItemInfo['value'] = $this->_check_input($aItemInfo['name'], $aItemInfo['value']);
		// Add item to the form
		$this->_add_item($aItemInfo);
	}

	public function add_multiselect($aItemInfo){
		// Checks	
		if(!isset($aItemInfo['name'])) die('FormUtil: Specify a name of this item.');
		$aItemInfo['name'] = $this->_fetch_variable_name($aItemInfo['name']) . '[]';
		if(!isset($aItemInfo['options'])||!is_array($aItemInfo['options'])) die('FormUtil: A multiselect needs an options array');
		if(!isset($aItemInfo['label'])) $aItemInfo['label'] = $aItemInfo['name'];
		// Typeset
		$aItemInfo['type'] = 'multiselect';
		// Input check
		$aItemInfo['value'] = $this->_check_input(substr($aItemInfo['name'],0,-2), $aItemInfo['value']);
		// Add item to the form
		$this->_add_item($aItemInfo);
	}
	
	public function add_radio($aItemInfo){
		// Checks	
		if(!isset($aItemInfo['name'])) die('FormUtil: Specify a name of this item.');
		if(!isset($aItemInfo['label'])) $aItemInfo['label'] = $aItemInfo['name'];
		if(!isset($aItemInfo['selected'])) $aItemInfo['selected'] = FALSE;
		// Typeset
		$aItemInfo['type'] = 'radio';
		// Input check
		if($this->_check_input($aItemInfo['name'])===$aItemInfo['value']){
			$aItemInfo['selected']=TRUE;
		}
		// Add item to the form
		$this->_add_item($aItemInfo);
	}

	public function add_radiogroup($aItemInfo){
		// Checks	
		if(!isset($aItemInfo['name'])) die('FormUtil: Specify a name of this item.');
		if(!isset($aItemInfo['label'])) $aItemInfo['label'] = $aItemInfo['name'];
		if(!isset($aItemInfo['options'])||!is_array($aItemInfo['options'])) die('FormUtil: A radiogroup needs an options array');
		// Typeset
		$aItemInfo['type'] = 'radiogroup';
		// Input check
		$aItemInfo['value'] = $this->_check_input($aItemInfo['name'], $aItemInfo['value']);
		// Add item to the form
		$this->_add_item($aItemInfo);
	}
	
	public function add_checkbox($aItemInfo){
		// Checks	
		if(!isset($aItemInfo['name'])) die('FormUtil: Specify a name of this item.');
		if(!isset($aItemInfo['label'])) $aItemInfo['label'] = $aItemInfo['name'];
		if(!isset($aItemInfo['selected'])) $aItemInfo['selected'] = FALSE;
		// Typeset
		$aItemInfo['type'] = 'checkbox';
		// Input check
		$value = $this->CI->input->get_post($aItemInfo['name']);
		if($value===FALSE){
			if(count($_POST) !== 0) $aItemInfo['selected'] = FALSE;
		}else{
			$aItemInfo['selected'] = TRUE;
		}
		
		if($this->_check_input($aItemInfo['name'])===$aItemInfo['value']){
			$aItemInfo['selected']=TRUE;
		}
		// Add item to the form
		$this->_add_item($aItemInfo);
	}

	public function add_checkboxgroup($aItemInfo){
		// Checks	
		if(!isset($aItemInfo['name'])) die('FormUtil: Specify a name of this item.');
		$aItemInfo['name'] = $this->_fetch_variable_name($aItemInfo['name']) . '[]';
		if(!isset($aItemInfo['label'])) $aItemInfo['label'] = $aItemInfo['name'];
		if(!isset($aItemInfo['value'])) $aItemInfo['value'] = array();
		if(!is_array($aItemInfo['value'])) $aItemInfo['value'] = array($aItemInfo['value']);
		if(!isset($aItemInfo['options'])||!is_array($aItemInfo['options'])) die('FormUtil: A checkboxgroup needs an options array');
		// Typeset
		$aItemInfo['type'] = 'checkboxgroup';
		// Input check
		$value = $this->CI->input->get_post(substr($aItemInfo['name'],0,-2));
		if($value===FALSE){
			if(count($_POST) !== 0) $aItemInfo['value'] = array();
		}else{
			$aItemInfo['value'] =& $value;
		}
		// Add item to the form
		$this->_add_item($aItemInfo);
	}
	
	public function add_submit($aItemInfo){
		// Checks	
		if(!isset($aItemInfo['name'])) die('FormUtil: Specify a name of this item.');
		if(!isset($aItemInfo['value'])) $aItemInfo['value'] = 'Submit';
		// Typeset
		$aItemInfo['type'] = 'submit';		
		// Add item to the form
		$this->_add_item($aItemInfo);
	}
	
	public function add_html($sHtml){
		$aItemInfo['type'] = 'html';
		$aItemInfo['value'] = $sHtml;
		$this->_add_item($aItemInfo);
	}
	
	/**
	 * Generates the form with the given name and prints it to the standard output
	 * @param $sFormName: The name of the form you want to generate
	 * @
	 */
	public function generate_form($sFormName){
		if(!isset($this->aForms[$sFormName])) die('FormUtil: form "' . $sFormName . '" is not set.');
		
		$form =& $this->aForms[$sFormName];
		
		echo "\n\n<!-- FORM GENERATED WITH FORMUTIL -->\n\n";
		echo form_open($form['action'], $form['attributes']);
		// Load the template configured for this form
		$template = $this->CI->config->item($form['template']);	
		foreach($form['items'] as $item){
			if($item['type']=='html'){
				echo $item['value'];
				continue;
			}	
				
			// Set the data needed for the template
			$aData = array();
			$aData['error_message'] =& $item['error_message'];
			$aData['validation_state_class'] = $item['error_message']==NULL ? '' : 'error';
			$aData['label'] =& $item['label']==NULL ? '' : $item['label'];
			$aData['label_item'] = $item['label']==NULL ? '' : form_label($item['label'], $item['name'], $template['label-attributes']);
			$aData['value'] =& $item['value'];
			
			// Set item itself
			switch($item['type']){
				case "input":
					$aData['item'] = form_input(array_merge(array('name'=>$item['name'], 'value'=>$item['value']), $item['attributes']));
					break;
				case "password":
					$aData['item'] = form_password(array_merge(array('name'=>$item['name'], 'value'=>$item['value']), $item['attributes']));
					break;
				case "hidden":
					$aData['item'] = form_hidden(array($item['name']=>$item['value']));
					break;
				case "textarea":
					$aData['item'] = form_textarea(array_merge(array('name'=>$item['name'], 'value'=>$item['value']), $item['attributes']));
					break;
				case "dropdown":
					$aData['item'] = form_dropdown($item['name'], $item['options'], $item['value']);
					break;
				case "multiselect":
					$aData['item'] = form_multiselect($item['name'], $item['options'], $item['value']);
					break;
				case "radio":
					$aData['item'] = form_radio(array_merge(array('name'=>$item['name'], 'value'=>$item['value'], 'checked'=>$item['selected']), $item['attributes']));
					break;
				case "radiogroup":
					$aData['item'] = '';
					foreach($item['options'] as $value=>$label){
						$aItemData['item'] = form_radio(array_merge(array('name'=>$item['name'], 'value'=>$value, 'checked'=>$item['value']===$value), $item['attributes']));
						$aItemData['label'] = $label;
						$aData['item'] .= $this->CI->parser->parse_string($template['radiogroup-item'], $aItemData, TRUE);
					}
					break;
				case "checkbox":
					$aData['item'] = form_checkbox(array_merge(array('name'=>$item['name'], 'value'=>$item['value'], 'checked'=>$item['selected']), $item['attributes']));
					break;
				case "checkboxgroup":
					$aData['item'] = '';
					foreach($item['options'] as $value=>$label){
						$aItemData['item'] = form_checkbox(array_merge(array('name'=>$item['name'], 'value'=>$value, 'checked'=>in_array($value, $item['value'])), $item['attributes']));
						$aItemData['label'] = $label;
						$aData['item'] .= $this->CI->parser->parse_string($template['checkboxgroup-item'], $aItemData, TRUE);
					}
					break;
				case "submit":
					$aData['item'] = form_submit(array_merge(array('name'=>$item['name'], 'value'=>$item['value']), $item['attributes']));
					break;
				default:
					$aData['item'] = 'Type "' . $item['type'] . '" not recognized';
					break;
			}
			
			// Get item specific chunck, if it exists	
			if(isset($template['item-' . $item['type']])){
				echo $this->CI->parser->parse_string($template['item-' . $item['type']], $aData, TRUE);
			// Item specific chunck doesn't exist: use standard one
			}else{
				echo $this->CI->parser->parse_string($template['item'], $aData, TRUE);
			}
		}
		echo form_close();
		echo "\n\n<!-- END OF FORM -->\n\n";	
	}

	/**
	 * Validate the form with the given name
	 * @param $sName: The name of the form to validate
	 * @return TRUE on succesfull validation, FALSE otherwise
	 */
	public function validate($sName){
		$this->CI->load->library('form_validation');
		$form =& $this->aForms[$sName];
		// Set rules
		foreach($form['items'] as $item){
			if($item['validate']!=NULL && $item['validate']!=''){
				$this->CI->form_validation->set_rules($item['name'], $item['label'], $item['validate']);
			}
		}
		// Validate
		if($this->CI->form_validation->run()==FALSE){
			// Set error messages	
			foreach($form['items'] as &$item){
				$item['error_message'] = form_error($item['name']);
			}
			return FALSE;
		}else{
			return TRUE;
		}
	}
	
	public function get_input($sName){
		if(!isset($this->aForms[$sName])) die('FormUtil: You can only get data from an existing form!');
		$aInput = array();
		foreach($this->aForms[$sName]['items'] as &$item){
			$sName = $this->_fetch_variable_name($item['name']);
			$value = $this->CI->input->get_post($sName);
			if($value!==FALSE) $aInput[$sName] = $value;
		}
		return $aInput;
	}
	
	public function get_item_input($sItemName){
		$sItemName = $this->_fetch_variable_name($sItemName);
		return $this->CI->input->get_post($sItemName);;
	}
	
	/**
	 * DEBUG FUNCTION
	 */
	public function get_forms(){
		return $this->aForms;
	}
}

/* End of file Formutil.php */