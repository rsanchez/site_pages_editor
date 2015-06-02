<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Site Pages Editor Module Install/Update File
 *
 * @package    ExpressionEngine
 * @subpackage Addons
 * @category   Module
 * @author     Rob Sanchez
 * @link       https://github.com/rsanchez
 */
class Site_pages_editor_upd
{
    public $version = '1.0.1';

    /**
     * Installation Method
     *
     * @return  boolean
     */
    public function install()
    {
        ee()->db->insert('modules', array(
            'module_name'        => 'Site_pages_editor',
            'module_version'     => $this->version,
            'has_cp_backend'     => 'y',
            'has_publish_fields' => 'n',
        ));

        return TRUE;
    }

    /**
     * Uninstall
     *
     * @return  boolean
     */
    public function uninstall()
    {
        ee()->db->where('class', 'Site_pages_editor')->delete('actions');

        $mod_id = ee()->db->select('module_id')->get_where('modules', array('module_name' => 'Site_pages_editor'))->row('module_id');
        ee()->db->where('module_id', $mod_id)->delete('module_member_groups');
        ee()->db->where('module_name', 'Site_pages_editor')->delete('modules');

        return TRUE;
    }

    /**
     * Module Updater
     *
     * @return  boolean
     */
    public function update($current = '')
    {
        ee()->db->update('modules', array('module_version' => $this->version), array('module_name', 'Site_pages_editor'));

        return TRUE;
    }
}

/* End of file upd.site_pages_editor.php */
/* Location: /system/expressionengine/third_party/site_pages_editor/upd.site_pages_editor.php */