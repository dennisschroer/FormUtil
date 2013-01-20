<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Example extends CI_Controller {

	public function index()
	{
		// Load the library
		$this->load->library('formutil');
		
		// Initialize the form and call it 'testform'
		$this->formutil->init_form(array(
			'name'=>'testform',
			// Extra attributes which will be added to the <form> tag
			'attributes'=>array('class'=>'form-horizontal')
			));	
		// Add an text input item (<input type="text">)	
		$this->formutil->add_input(array(
			'name'=>'inputtext',
			'label'=>'Type text', 
			// The validation rule as used by the form validator
			// As you can see, your own callback functions can be added too.
			'validate'=>'required|min_length[10]|callback__my_check', 
			// The default text in this field
			'value'=>'default text'
			));
		$this->formutil->add_password(array(
			'name'=>'inputpassword', 
			'label'=>'Type password', 
			'validate'=>'required|min_length[10]'
			));
		$this->formutil->add_hidden(array(
			'name'=>'inputhidden', 
			'value'=>'value of this hidden field'
			));
		$this->formutil->add_textarea(array(
			'name'=>'inputtextarea',
			'label'=>'Type textarea',  
			'value'=>'Default text in this textarea'
			));
		// Add an wysiwyg (what you see is what you get) editor. 
		// To use this, you have to include the CKEditor javascript file.
		$this->formutil->add_wysiwyg(array(
			'name'=>'inputwysiwyg',
			'label'=>'Type wysiwyg',  
			'value'=>'Default text in this wysiwyg editor',
			// Config as passed to the CKEditor initializer
			'config'=>array('toolbar'=>'Basic', 'uiColor'=>'#006DCC', 'width'=>'218')
			));
		// Add default html code in between
		$this->formutil->add_html('<hr>');
		$this->formutil->add_dropdown(array(
			'name'=>'inputdropdown',
			'label'=>'Type dropdown', 
			'options'=>array(
				'1'=>'one',
				'2'=>'two',
				'3'=>'three'
			),
			'value'=>'2'
			));
		$this->formutil->add_multiselect(array(
			'name'=>'inputmultiselect[]',
			'label'=>'Type multiselect', 
			'options'=>array(
				'1'=>'one',
				'2'=>'two',
				'3'=>'three'
			),
			'value'=>array('2', '3')
			));
		$this->formutil->add_radio(array(
			'name'=>'inputradio',
			'label'=>'Type radio',
			'value'=>'1',
			'selected'=>TRUE
			));
		// Add a group of radio buttons with the same name
		$this->formutil->add_radiogroup(array(
			'name'=>'inputradiogroup',
			'label'=>'Type radiogroup',
			// Default selected options
			'value'=>'2',
			// The options in this group, specified as value=>label
			'options'=>array(
				'1'=>'one',
				'2'=>'two',
				'3'=>'three'
			)));
		$this->formutil->add_checkbox(array(
			'name'=>'inputcheckbox',
			'label'=>'Type checkbox',
			'value'=>'1',
			'selected'=>TRUE
			));
		$this->formutil->add_checkboxgroup(array(
			'name'=>'inputcheckboxgroup[]',
			'label'=>'Type checkboxgroup',
			'value'=>'2',
			'options'=>array(
				'1'=>'one',
				'2'=>'two',
				'3'=>'three'
			)));
		// Button added (by default) to the bottom of the form
		$this->formutil->add_submit(array(
			'name'=>'submit',
			'attributes'=>array('class'=>'btn btn-primary'
			)));
		// Button added to the top of the form by specifying the second argument
		$this->formutil->add_submit(array(
			'name'=>'submit',
			'attributes'=>array('class'=>'btn btn-inverse'
			)), 'top');
			
			
		if($this->formutil->validate('testform')){
			// Validation succesfull
			print_r($this->formutil->get_input('testform'));
		}else{
			// Load the view, in which the form will be generated and repopulated
			$this->load->view('formutil/example', $aData);
		}
		
		
	}

	// Custom callback validation function, used in the input type field
	public function _my_check($str){
		if (count($str)==0 || $str[0] != 'a'){
			$this->form_validation->set_message('_my_check', 'The %s field has to start with an "a"');
			return FALSE;
		}else{
			return TRUE;
		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */