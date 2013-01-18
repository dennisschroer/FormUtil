<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

$config['default_template'] = array(
	// Default template for one item (input, dropdown etc)
	"item" => 	'<div class="control-group {validation_state_class}">
					{label_item}
					<div class="controls">
						{item}
						<span class="help-inline">{error_message}</span>
					</div>
				</div>',
	
	// This template is used for an item of type "submit"
	// The template "item" can be overwritten for every item. Just add a key "item-<itemname>" with the value the new template
	"item-submit" => '<div class="form-actions">{item}</div>',
	
	// Only print the hidden item, no markup needed
	"item-hidden" => '{item}',
	
	// Template for one option in a radio group
	// All options parsed in this template will go into {item} in the normal template,
	// or in the "item-radiogroup" template if this exists.
	"radiogroup-item" => '<label class="radio">{item}{label}</label>',
	
	// Same as above, template for one option in a checkboxgroup
	"checkboxgroup-item" => '<label class="checkbox">{item}{label}</label>',
	
	// The template used when there are controls (submit etc) added to the top of the form
	"controls-top" => '<div class="form-actions" style="border-bottom: 1px solid #e5e5e5; border-top: 0px;">{items}</div>',
	
	// Template used for one item (ie submit button) in the controls section on the top of the form
	"controls-top-wrapper" => '{item}',
	
	// The template used when there are controls (submit etc) added to the bottom of the form
	"controls-bottom" => '<div class="form-actions">{items}</div>',
	
	// Template used for one item (ie submit button) in the controls section on the bottom of the form
	"controls-bottom-wrapper" => '{item}',
	
	// Attributes used for the <label> item.
	"label-attributes" => array('class' => 'control-label')
);

/* End of file formutil.php */
/* Location: ./application/config/formutil.php */
