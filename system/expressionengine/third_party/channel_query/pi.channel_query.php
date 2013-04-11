<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$plugin_info = array(
    'pi_name'           => 'Channel Query',
    'pi_version'        => Channel_query::VERSION,
    'pi_author'         => 'Adrian Macneil',
    'pi_author_url'     => 'http://adrianmacneil.com/',
    'pi_description'    => 'Display channel entries using a custom SQL query.',
    'pi_usage'          => Channel_query::usage(),
);

class Channel_query
{
    const VERSION = '1.0';

    public $return_data;

    public function __construct()
    {
        $this->EE = get_instance();
        $this->return_data = $this->entries();
    }

    public function entries()
    {
        // only allow SELECT statements
        $sql = $this->EE->TMPL->fetch_param('sql');
        if (stripos($sql, 'select') !== 0) {
            return false;
        }

        // run the SQL query
        $result = $this->EE->db->query($sql)->result_array();

        // collate the results
        $entry_ids = array();
        foreach ($result as $row) {
            // check for an entry_id column in each row
            // if that doesn't exist, assume first column is the entry ID
            if (isset($row['entry_id'])) {
                $entry_ids[] = (int) $row['entry_id'];
            } else {
                $entry_ids[] = (int) reset($row);
            }
        }

        // add pipe-separated list of entry IDs to tag parameters
        $entry_ids = '0|'.implode('|', $entry_ids);
        if (isset($this->EE->TMPL->tagparams['orderby'])) {
            $this->EE->TMPL->tagparams['entry_id'] = $entry_ids;
        } else {
            $this->EE->TMPL->tagparams['fixed_order'] = $entry_ids;
        }

        // load Channel class
        if (!class_exists('Channel')) {
            require_once(APPPATH.'modules/channel/mod.channel.php');
        }

        // our work here is done
        $channel = new Channel();

        return $channel->entries();
    }

    public static function usage()
    {
        // for performance only load README if inside control panel
        return REQ === 'CP' ? file_get_contents(dirname(__FILE__).'/README.md') : null;
    }
}
