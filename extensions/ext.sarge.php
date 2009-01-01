<?php

/**
 * Sarge
 *
 * This extension enables you to add optgroups and specify values in your drop-down fields.
 *
 * @package   Sarge
 * @author    Brandon Kelly <me@brandon-kelly.com>
 * @link      http://brandon-kelly.com/apps/sarge
 * @copyright Copyright (c) 2008-2009 Brandon Kelly
 * @license   http://creativecommons.org/licenses/by-sa/3.0/   Attribution-Share Alike 3.0 Unported
 */
class Sarge
{
	/**
	 * Extension Settings
	 *
	 * @var array
	 */
	var $settings		= array();
	
	/**
	 * Extension Name
	 *
	 * @var string
	 */
	var $name			= 'Sarge';
	
	/**
	 * Extension Class Name
	 *
	 * @var string
	 */
	var $class_name     = 'Sarge';
	
	/**
	 * Extension Version
	 *
	 * @var string
	 */
	var $version		= '1.1.0';
	
	/**
	 * Extension Description
	 *
	 * @var string
	 */
	var $description	= 'Add grouping and values to your drop-down fields';
	
	/**
	 * Extension Settings Exist
	 *
	 * If set to 'y', a settings page will be shown in the Extensions Manager
	 *
	 * @var string
	 */
	var $settings_exist	= 'y';
	
	/**
	 * Documentation URL
	 *
	 * @var string
	 */
	var $docs_url		= 'http://brandon-kelly.com/apps/sarge?utm_campaign=sarge_em';
	
	
	
    /**
	 * Extension Constructor
	 *
	 * @param array   $settings
	 * @since version 1.0.0
	 */
	function empty_options($settings=array())
	{
		$this->settings = $this->get_site_settings($settings);
	}
	
	
	
	/**
	 * Get All Settings
	 *
	 * @return array   All extension settings
	 * @since  version 1.1.0
	 */
	function get_all_settings()
	{
		global $DB;

		$query = $DB->query("SELECT settings
		                     FROM exp_extensions
		                     WHERE class = '{$this->class_name}'
		                       AND settings != ''
		                     LIMIT 1");

		return $query->num_rows
			? unserialize($query->row['settings'])
			: array();
	}



	/**
	 * Get Default Settings
	 * 
	 * @return array   Default settings for site
	 * @since 1.1.0
	 */
	function get_default_settings()
	{
		$settings = array(
			'check_for_extension_updates' => 'y'
		);

		return $settings;
	}



	/**
	 * Get Site Settings
	 *
	 * @param  array   $settings   Current extension settings (not site-specific)
	 * @return array               Site-specific extension settings
	 * @since  version 1.0.0
	 */
	function get_site_settings($settings=array())
	{
		global $PREFS;
		
		$site_settings = $this->get_default_settings();
		
		$site_id = $PREFS->ini('site_id');
		if (isset($settings[$site_id]))
		{
			$site_settings = array_merge($site_settings, $settings[$site_id]);
		}

		return $site_settings;
	}
	
	
	
	/**
	 * Settings Form
	 *
	 * Construct the custom settings form.
	 *
	 * @param  array   $current   Current extension settings (not site-specific)
	 * @see    http://expressionengine.com/docs/development/extensions.html#settings
	 * @since  version 1.1.0
	 */
	function settings_form($current)
	{
	    $current = $this->get_site_settings($current);

	    global $DB, $DSP, $LANG, $IN, $PREFS;

		// Breadcrumbs

		$DSP->crumbline = TRUE;

		$DSP->title = $LANG->line('extension_settings');
		$DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'area=utilities', $LANG->line('utilities'))
		            . $DSP->crumb_item($DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=extensions_manager', $LANG->line('extensions_manager')))
		            . $DSP->crumb_item($this->name);

	    $DSP->right_crumb($LANG->line('disable_extension'), BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=toggle_extension_confirm'.AMP.'which=disable'.AMP.'name='.$IN->GBL('name'));

		// Donations button

		$DSP->body = '';
		
		// Donations button
	    $DSP->body .= '<div style="float:right;">'
	                . '<a style="display:block; margin:-2px 10px 0 0; padding:5px 0 5px 70px; width:190px; height:15px; font-size:12px; line-height:15px;'
	                . ' background:url(http://brandon-kelly.com/images/shared/donations.png) no-repeat 0 0; color:#000; font-weight:bold;"'
	                . ' href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=2181794" target="_blank">'
	                . $LANG->line('donate')
	                . '</a>'
	                . '</div>'

		// Form header

		           . "<h1>{$this->name} <small>{$this->version}</small></h1>"

		           . $DSP->form_open(
		                                 array(
		                                     'action' => 'C=admin'.AMP.'M=utilities'.AMP.'P=save_extension_settings',
		                                     'name'   => 'settings_example',
		                                     'id'     => 'settings_example'
		                                 ),
		                                 array(
		                                     'name' => strtolower($this->class_name)
		                                 )
		                             );

		// Updates Setting

		$lgau_query = $DB->query("SELECT class
		                          FROM exp_extensions
		                          WHERE class = 'Lg_addon_updater_ext'
		                            AND enabled = 'y'
		                          LIMIT 1");
		$lgau_enabled = $lgau_query->num_rows ? TRUE : FALSE;
		$check_for_extension_updates = ($lgau_enabled AND $current['check_for_extension_updates'] == 'y') ? TRUE : FALSE;

		$DSP->body .= $DSP->table_open(
		                                   array(
		                                       'class'  => 'tableBorder',
		                                       'border' => '0',
		                                       'style' => 'margin-top:18px; width:100%'
		                                   )
		                               )

		            . $DSP->tr()
		            . $DSP->td('tableHeading', '', '2')
		            . $LANG->line("check_for_extension_updates_title")
		            . $DSP->td_c()
		            . $DSP->tr_c()

		            . $DSP->tr()
		            . $DSP->td('', '', '2')
		            . '<div class="box" style="border-width:0 0 1px 0; margin:0; padding:10px 5px"><p>'.$LANG->line('check_for_extension_updates_info').'</p></div>'
		            . $DSP->td_c()
		            . $DSP->tr_c()

		            . $DSP->tr()
		            . $DSP->td('tableCellOne', '60%')
		            . $DSP->qdiv('defaultBold', $LANG->line("check_for_extension_updates_label"))
		            . $DSP->td_c()

		            . $DSP->td('tableCellOne')
		            . '<select name="check_for_extension_updates"'.($lgau_enabled ? '' : ' disabled="disabled"').'>'
		            . $DSP->input_select_option('y', $LANG->line('yes'), ($current['check_for_extension_updates'] == 'y' ? 'y' : ''))
		            . $DSP->input_select_option('n', $LANG->line('no'),  ($current['check_for_extension_updates'] != 'y' ? 'y' : ''))
		            . $DSP->input_select_footer()
		            . ($lgau_enabled ? '' : NBS.NBS.NBS.$LANG->line('check_for_extension_updates_nolgau'))
		            . $DSP->td_c()
		            . $DSP->tr_c()

		            . $DSP->table_c()

		// Close Form

		            . $DSP->qdiv('itemWrapperTop', $DSP->input_submit())
		            . $DSP->form_c();
	}



	/**
	 * Save Settings
	 *
	 * @since version 1.1.0
	 */
	function save_settings()
	{
		global $DB, $PREFS;

		$settings = $this->get_all_settings();
		$current = $this->get_site_settings($settings);

		// Save new settings
		$settings[$PREFS->ini('site_id')] =
			$this->settings = array(
				'check_for_extension_updates' => $_POST['check_for_extension_updates']
			);

		$DB->query("UPDATE exp_extensions
		            SET settings = '".addslashes(serialize($settings))."'
		            WHERE class = '{$this->class_name}'");
	}
	
	
	
	/**
	 * Activate Extension
	 *
	 * Resets all Sarge exp_extensions rows
	 *
	 * @since version 1.0.0
	 */
	function activate_extension($settings='')
	{
		global $DB;

		// Get settings
		if ( ! (is_array($settings) AND $settings))
		{
			$settings = $this->get_all_settings();
		}

		// Delete old hooks
		$DB->query("DELETE FROM exp_extensions
		            WHERE class = '{$this->class_name}'");

		// Add new extensions
		$ext_template = array(
			'class'    => $this->class_name,
			'settings' => addslashes(serialize($settings)),
			'priority' => 10,
			'version'  => $this->version,
			'enabled'  => 'y'
		);

		$extensions = array(
			// LG Addon Updater
			array('hook'=>'lg_addon_update_register_source',  'method'=>'register_my_addon_source'),
			array('hook'=>'lg_addon_update_register_addon',   'method'=>'register_my_addon_id'),
			
			// Edit Option
			array('hook'=>'publish_form_field_select_option', 'method'=>'edit_option')
		);

		foreach($extensions as $extension)
		{
			$ext = array_merge($ext_template, $extension);
			$DB->query($DB->insert_string('exp_extensions', $ext));
		}
	}
	
	
	
	/**
	 * Update Extension
	 *
	 * @param string   $current   Previous installed version of the extension
	 * @since version 1.0.0
	 */
	function update_extension($current='')
	{
		global $DB;

		if ($current == '' OR $current == $this->version)
		{
			return FALSE;
		}

		if ($current < '1.1.0')
		{
			// add new hooks
			$this->activate_extension();
			return;
		}
		
		// update the version
		$DB->query("UPDATE exp_extensions
		            SET version = '".$DB->escape_str($this->version)."'
		            WHERE class = '{$this->class_name}'");
	}



	/**
	 * Disable Extension
	 *
	 * @since version 1.0.0
	 */
	function disable_extension()
	{
		global $DB;

		$DB->query("UPDATE exp_extensions
		            SET enabled='n'
		            WHERE class='{$this->class_name}'");
	}



	/**
	 * Get Last Call
	 *
	 * @param  mixed   $param   Parameter sent by extension hook
	 * @return mixed            Return value of last extension call if any, or $param
	 * @since  version 1.1.0
	 */
	function get_last_call($param='')
	{
		global $EXT;

		return ($EXT->last_call !== FALSE) ? $EXT->last_call : $param;
	}
	
	
	
	/**
	 * Register a New Addon Source
	 *
	 * @param  array   $sources   The existing sources
	 * @return array              The new source list
	 * @see    http://leevigraham.com/cms-customisation/expressionengine/lg-addon-updater/
	 * @since  version 1.1.0
	 */
	function register_my_addon_source($sources)
	{
		$sources = $this->get_last_call($sources);

		if ($this->settings['check_for_extension_updates'] == 'y')
		{
		    $sources[] = 'http://brandon-kelly.com/apps/versions.xml';
		}
		return $sources;

	}



	/**
	 * Register a New Addon ID
	 *
	 * @param  array   $addons   The existing sources
	 * @return array             The new addon list
	 * @see    http://leevigraham.com/cms-customisation/expressionengine/lg-addon-updater/
	 * @since  version 1.1.0
	 */
	function register_my_addon_id($addons)
	{
		$addons = $this->get_last_call($addons);

	    if ($this->settings['check_for_extension_updates'] == 'y')
	    {
	        $addons[$this->class_name] = $this->version;
	    }
	    return $addons;
	}
    
    
    
    /**
	 * Edit Option
	 *
	 * Modify the <option>
	 *
	 * @param  string   $v          select option value
	 * @param  string   $v2         select option display
	 * @param  int      $selected   item selected? 1 if yes, empty if not
	 * @return string               HTML for <option>
	 * @see    http://expressionengine.com/developers/extension_hooks/publish_form_field_select_option/
	 * @since  version 1.0.0
	 */
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