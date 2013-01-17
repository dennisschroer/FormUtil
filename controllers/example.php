<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Formutil extends CI_Controller {

	public function index()
	{
		$this->load->library('formutil');
		
		$this->formutil->init_form(array(
			'name'=>'testform',
			'attributes'=>array('class'=>'form-horizontal')
			));		
		$this->formutil->add_input(array(
			'name'=>'inputtext', 
			'label'=>'Type text', 
			'validate'=>'required|min_length[10]', 
			'value'=>'default text'
			));
		$this->formutil->add_password(array(
			'name'=>'inputpassword', 
			'label'=>'Type password', 
			'validate'=>'required|min_length[10]'
			));
		$this->formutil->add_password(array(
			'name'=>'inputpassword2', 
			'label'=>'Type password (again)', 
			'validate'=>'required|min_length[10]|matches[inputpassword]'
			));
		$this->formutil->add_hidden(array(
			'name'=>'inputhidden', 
			'value'=>'value_of_this_hidden_field'
			));
		$this->formutil->add_textarea(array(
			'name'=>'inputtextarea',
			'label'=>'Type textarea',  
			'value'=>'Default text in this textarea'
			));
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
		$this->formutil->add_radiogroup(array(
			'name'=>'inputradiogroup',
			'label'=>'Type radiogroup',
			'value'=>'2',
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
		$this->formutil->add_submit(array(
			'name'=>'submit',
			'attributes'=>array('class'=>'btn btn-primary'
			)));
			
			
		if($this->formutil->validate('testform')){
			print_r($this->formutil->get_input('testform'));
		}else{
			$this->load->view('example', $aData);
		}
		
		
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */