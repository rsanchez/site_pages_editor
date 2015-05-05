<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Site Pages Editor Module Control Panel File
 *
 * @package    ExpressionEngine
 * @subpackage Addons
 * @category   Module
 * @author     Rob Sanchez
 * @link       https://github.com/rsanchez
 */
class Site_pages_editor_mcp
{
	public function index()
	{
        ee()->view->cp_page_title = lang('site_pages_editor_module_name');

        ee()->load->helper(array('form', 'html'));

        ee()->load->library('table');

        // disallow editing
        $readonly = ee()->config->item('site_pages_editor_readonly');

        $site_id = ee()->config->item('site_id');

        $site_pages = ee()->config->item('site_pages');

        if (empty($site_pages[$site_id]))
        {
            return '<p>No site pages detected on this site.</p>';
        }

        $templates = $this->get_templates($site_id);

        $titles = $this->get_titles(array_keys($site_pages[$site_id]['uris']));

        $rows = array();

        foreach ($titles as $row)
        {
            $row['uri'] = $site_pages[$site_id]['uris'][$row['entry_id']];
            $row['template'] = $site_pages[$site_id]['templates'][$row['entry_id']];

            $rows[] = $row;
        }

        // sort by pages uri
        usort($rows, function($a, $b) {
            return strcmp($a['uri'], $b['uri']);
        });

        ee()->table->set_heading(lang('entry_id'), lang('title'), lang('pages_uri'), lang('template'));

        foreach ($rows as $row)
        {
            $title = str_repeat('&middot;&nbsp;', substr_count($row['uri'], '/', 2) - 1).$row['title'];

            $title_link = anchor(sprintf('%s&C=content_publish&M=entry_form&channel_id=%s&entry_id=%s', BASE, $row['channel_id'], $row['entry_id']), $title);

            if ($readonly)
            {
                ee()->table->add_row(
                    array('width' => '1%', 'data' => $row['entry_id']),
                    $title_link,
                    '<pre>'.$row['uri'].'</pre>',
                    $templates[$row['template']]
                );
            }
            else
            {
                ee()->table->add_row(
                    array('width' => '1%', 'data' => $row['entry_id']),
                    $title_link,
                    form_input(sprintf('uris[%s]', $row['entry_id']), $row['uri']),
                    array('width' => '1%', 'data' => form_dropdown(sprintf('templates[%s]', $row['entry_id']), $templates, $row['template']))
                );
            }
        }

        $table = ee()->table->generate();

        if ($readonly)
        {
            return $table;
        }

        return form_open('C=addons_modules&M=show_module_cp&module=site_pages_editor&method=save').
            $table.
            form_submit('', 'Save', 'class="submit"').
            form_close();
    }

    public function save()
    {
        $site_id = ee()->config->item('site_id');

        $site_pages = ee()->config->item('site_pages');

        $site_pages[$site_id]['uris'] = ee()->input->post('uris');

        $site_pages[$site_id]['templates'] = ee()->input->post('templates');

        ee()->db->update(
            'sites',
            array('site_pages' => base64_encode(serialize($site_pages))),
            array('site_id' => $site_id)
        );

        ee()->session->set_flashdata('message_success', lang('saved_site_pages'));

        ee()->functions->redirect(BASE.'&C=addons_modules&M=show_module_cp&module=site_pages_editor');
    }

    protected function get_templates($site_id)
    {
        $query = ee()->db->join('template_groups', 'template_groups.group_id = templates.group_id')
            ->order_by("template_groups.is_site_default = 'y' DESC, template_groups.group_name ASC, templates.template_name = 'index' DESC, templates.template_name ASC")
            ->where('template_groups.site_id', $site_id)
            ->get('templates');

        $templates = array();

        foreach ($query->result_array() as $row)
        {
            $templates[$row['template_id']] = $row['group_name'].'/'.$row['template_name'];
        }

        $query->free_result();

        return $templates;
    }

    protected function get_titles(array $entry_ids)
    {
        $query = ee()->db->select('entry_id, channel_id, title')
            ->where_in('entry_id', $entry_ids)
            ->get('channel_titles');

        $titles = $query->result_array();

        $query->free_result();

        return $titles;
    }
}
/* End of file mcp.site_pages_editor.php */
/* Location: /system/expressionengine/third_party/site_pages_editor/mcp.site_pages_editor.php */