<?php

namespace Resolve\Database;

/**
 * Tickets Database Manager
 *
 * Handles database table creation for tickets collection
 */
class TicketsDatabase
{
    /**
     * Install the tickets table
     *
     * @return void
     */
    public static function install()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'tickets';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            title varchar(255) NOT NULL,
            description text NULL,
            status varchar(50) NULL,
            priority varchar(50) NULL,
            assigned_to varchar(255) NULL,
            created_by varchar(255) NULL,
            created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            KEY title_index (title),
            KEY status_index (status),
            KEY priority_index (priority)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
