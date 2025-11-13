<?php
/**
 * Resolve Package Configuration
 * 
 * @package Resolve
 */

namespace Resolve;

use Gateway\Package\Package as GatewayPackage;

class Package extends GatewayPackage
{
    protected $key = 'resolve';
    protected $label = 'Resolve';
    protected $description = 'Ticket system and help desk';
    protected $icon = 'dashicons-sos';
    protected $position = 32; // Below Compass and Showcase
    protected $capability = 'edit_posts';
    protected $parent = null;
}