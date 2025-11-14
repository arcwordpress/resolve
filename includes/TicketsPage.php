<?php

namespace Resolve;

class TicketsPage
{
    public function __construct()
    {
        add_filter('template_include', [$this, 'interceptTicketsPage']);
        add_action('wp_enqueue_scripts', [$this, 'enqueueApp'], 20);
    }

    /**
     * Intercept the "tickets" page and use our custom template.
     */
    public function interceptTicketsPage($template)
    {
        if (is_page('tickets')) {
            $custom = RESOLVE_PATH . 'templates/tickets-app-page.php';
            if (file_exists($custom)) {
                return $custom;
            }
        }
        return $template;
    }

    /**
     * Enqueue the built React app on the "tickets" page.
     */
    public function enqueueApp()
    {
        if (is_page('tickets')) {
            $asset_file = RESOLVE_PATH . 'react/apps/tickets/build/index.asset.php';
            if (!file_exists($asset_file)) {
                // Optionally: do not enqueue if asset file is missing
                return;
            }
            $asset = include $asset_file;

            wp_enqueue_script(
                'resolve-tickets-app',
                RESOLVE_URL . 'react/apps/tickets/build/index.js',
                $asset['dependencies'],
                $asset['version'],
                true
            );
            wp_enqueue_style(
                'resolve-tickets-app',
                RESOLVE_URL . 'react/apps/tickets/build/index.css',
                [],
                $asset['version']
            );
        }
    }
}