# Channel Query plugin for ExpressionEngine 2.x

This plugin lets you display channel entries using a custom SQL query. It brings you the
benefits of combining the `{exp:query}` tag with the `{exp:channel:entries}` tag, without
having to worry about `GROUP_CONCAT` or parse order.

## Usage

    {exp:channel_query sql="SELECT entry_id FROM exp_channel_titles"}
        <!-- all channel entries variables can be used here -->
    {/exp:channel_query}

The `sql` parameter specifies the custom SQL query. Only SELECT statements can be used,
INSERT, UPDATE and DELETE are not supported for security reasons.

All other parameters and variables are exactly the same as the standard EE
[Channel Entries Tag](http://ellislab.com/expressionengine/user-guide/modules/channel/channel_entries.html).

If an `orderby` parameter is specified, that will take precendence. Otherwise, entries will be
displayed in the order returned by your SQL query.

## Changelog

**1.0** *(2013-04-11)*

* Initial release
