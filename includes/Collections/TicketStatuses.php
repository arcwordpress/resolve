<?php

namespace Resolve\Collections;

use Gateway\Collection;

/**
 * Ticket Statuses Collection
 *
 * Stores available status options for tickets
 */
class TicketStatuses extends Collection
{
    /**
     * Collection key
     *
     * @var string
     */
    protected $key     = 'ticket_statuses';
    protected $package = 'resolve';

    /**
     * The table associated with the collection
     *
     * @var string
     */
    protected $table = 'ticket_statuses';

    /**
     * Collection title (singular)
     *
     * @var string
     */
    protected $title = 'Ticket Status';

    /**
     * Collection title (plural)
     *
     * @var string
     */
    protected $titlePlural = 'Ticket Statuses';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'color', 'order'];

    /**
     * Route configuration
     *
     * @var array
     */
    protected $routes = [
        'enabled' => true,
        'namespace' => 'resolve',
        'version' => 'v1',
        'route' => 'ticket-statuses',
        'methods' => [
            'get_many' => true,
            'get_one' => true,
            'create' => true,
            'update' => true,
            'delete' => true,
        ],
        'permissions' => [
            'get_many' => [
                'type' => 'protected'
            ],
            'get_one' => [
                'type' => 'protected'
            ],
        ]
    ];

    /**
     * Field definitions
     *
     * @var array
     */
    protected $fields = [
        'name' => [
            'type' => 'text',
            'label' => 'Name',
            'required' => true,
            'placeholder' => 'e.g., Open, In Progress, Closed',
            'helpText' => 'Display name for this status',
        ],
        'slug' => [
            'type' => 'text',
            'label' => 'Slug',
            'required' => true,
            'placeholder' => 'e.g., open, in-progress, closed',
            'helpText' => 'URL-friendly identifier',
        ],
        'color' => [
            'type' => 'text',
            'label' => 'Color',
            'required' => false,
            'placeholder' => '#3b82f6',
            'helpText' => 'Hex color code for UI display',
        ],
        'order' => [
            'type' => 'number',
            'label' => 'Order',
            'required' => false,
            'default' => 0,
            'helpText' => 'Sort order (lower numbers appear first)',
        ],
    ];

    /**
     * Relationship: Tickets that have this status
     */
    public function tickets()
    {
        return $this->hasMany(Tickets::class, 'status_id');
    }
}
