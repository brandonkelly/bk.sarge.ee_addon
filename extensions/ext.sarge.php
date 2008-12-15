<?php

/**
 * Sarge
 *
 * @author Brandon Kelly
 * @link   http://brandon-kelly.com/apps/sarge/
 */

class Sarge
{
	var $settings		= array();
	
	var $name			= 'Sarge';
	var $class_name     = 'Sarge';
	var $version		= '1.0.0';
	var $description	= 'Drop-down field enhancer';
	var $settings_exist	= 'n';
	var $docs_url		= 'http://brandon-kelly.com/apps/sarge/';
	
    // -------------------------------
	//	Constructor - Extensions use this for settings
	// -------------------------------
	
	function empty_options($settings='')
	{
		$this->settings = $settings;
	}
	
	
	
	
	// -------------------------------
	//	Activate Extension
	// -------------------------------
	
	
	function activate_extension()
	{
		global $DB;
		
		// -- Add edit_option
		$DB->query(
			$DB->insert_string('exp_extensions', 
				array(
					'extension_id' => '',
					'class'        => $this->class_name,
					'method'       => 'edit_option',
					'hook'         => 'publish_form_field_select_option',
					'settings'     => '',
					'priority'     => 10,
					'version'      => $this->version,
					'enabled'      => 'y'
				)
			)
		);
	}
	
	
	
	
	// --------------------------------
	//  Disable Extension
	// --------------------------------
	
	
	function disable_extension()
	{
		global $DB;
		
		$DB->query("DELETE FROM exp_extensions
		            WHERE class = '".$this->class_name."'");
	}
	// END
	
	
	
	
	// -------------------------------
	//	Settings
	// -------------------------------
	
	
	function settings()
	{
		$settings = array();
		
		return $settings;
	}
    
    
    
    
	// -------------------------------
	//	Update Extension
	// -------------------------------
	
	
	function update_extension($current = '')
	{
		global $DB;
		
		if ($current == '' OR $current == $this->version)
		{
			return FALSE;
		}
		
		$DB->query("UPDATE exp_extensions SET version = '".$DB->escape_str($this->version)."' WHERE class = '".get_class($this)."'");
	}
    
    
    
    
	// -------------------------------
	//	Edit Option
	// -------------------------------
	
	
	function edit_option ($v, $v2, $selected, $field_data)
	{
		global $DSP;
		
		$values = preg_split("/\s*=\s*/", $v);
		for ($i=0; $i<count($values); $i++)
		{
			$values[$i] = trim($values[$i]);
		}
		
		
		if (count($values) == 1)
		{
			// -- If empty
			if ( ! count(array_filter($values) ))
			{
				return $DSP->input_select_option('', '', FALSE);
			}
			
			// -- If /optgroup
			if ($values[0] == '[/optgroup]')
			{
				return '</optgroup>';
			}
			
			return $DSP->input_select_option($v, $v, $selected);
		}
		
		if ($values[0] == "[optgroup]")
		{
			return '<optgroup label="'.$values[1].'">';
		}
		
		return $DSP->input_select_option($values[1], $values[0], ($values[1] == $field_data));
	}

}

?>