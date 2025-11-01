<?php

namespace Resolve\Database;

/**
 * Ticket Statuses Database Manager
 *
 * Handles database table creation for ticket statuses collection
 */
class TicketStatusesDatabase
{
    /**
     * Install the ticket_statuses table
     *
     * @return void
     */
    public static function install()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'ticket_statuses';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            slug varchar(255) NOT NULL,
            color varchar(50) NULL,
            `order` int(11) NULL DEFAULT 0,
            created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY  (id),
            UNIQUE KEY slug_unique (slug),
            KEY order_index (`order`)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        // Insert default statuses
        self::insertDefaultStatuses();
    }

    /**
     * Insert default ticket statuses
     *
     * @return void
     */
    private static function insertDefaultStatuses()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'ticket_statuses';

        // Check if statuses already exist
        $count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");

        if ($count > 0) {
            return; // Statuses already exist
        }

        // Insert default statuses
        $default_statuses = [
            ['name' => 'Open', 'slug' => 'open', 'color' => '#3b82f6', 'order' => 1],
            ['name' => 'In Progress', 'slug' => 'in-progress', 'color' => '#f59e0b', 'order' => 2],
            ['name' => 'Closed', 'slug' => 'closed', 'color' => '#10b981', 'order' => 3],
        ];

        foreach ($default_statuses as $status) {
            $wpdb->insert(
                $table_name,
                $status,
                ['%s', '%s', '%s', '%d']
            );
        }
    }
}
