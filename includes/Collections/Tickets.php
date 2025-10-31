<?php

namespace Resolve\Collections;

use Gateway\Collection;

/**
 * Tickets Collection
 *
 * Stores support tickets for tracking issues and requests
 */
class Tickets extends Collection
{
    /**
     * Collection key
     *
     * @var string
     */
    protected $key = 'tickets';

    /**
     * The table associated with the collection
     *
     * @var string
     */
    protected $table = 'tickets';

    /**
     * Collection title (singular)
     *
     * @var string
     */
    protected $title = 'Ticket';

    /**
     * Collection title (plural)
     *
     * @var string
     */
    protected $titlePlural = 'Tickets';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = ['title', 'description', 'status', 'priority', 'assigned_to', 'created_by'];

    /**
     * Apply defaults before validation
     *
     * @param array $data
     * @return array
     */
    public function beforeValidate($data)
    {
        // Apply default status if not provided
        if (!isset($data['status']) || $data['status'] === '') {
            $data['status'] = 'open';
        }

        // Apply default priority if not provided
        if (!isset($data['priority']) || $data['priority'] === '') {
            $data['priority'] = 'medium';
        }

        return $data;
    }

    /**
     * Route configuration
     *
     * @var array
     */
    protected $routes = [
        'enabled' => true,
        'namespace' => 'resolve',
        'version' => 'v1',
        'route' => 'tickets',
        'allow_basic_auth' => true,
        'methods' => [
            'get_many' => true,
            'get_one' => true,
            'create' => true,
            'update' => true,
            'delete' => true,
        ],
        'permissions' => [
            'get_many' => [
                'type' => 'nonce_only'
            ],
            'get_one' => [
                'type' => 'nonce_only'
            ],
        ]
    ];

    /**
     * Filter definitions
     *
     * @var array
     */
    protected $filters = [
        [
            'type' => 'text',
            'field' => 'search',
            'label' => 'Search',
            'placeholder' => 'Search tickets...',
        ],
        [
            'type' => 'select',
            'field' => 'status',
            'label' => 'Status',
            'placeholder' => 'All Statuses',
        ],
        [
            'type' => 'select',
            'field' => 'priority',
            'label' => 'Priority',
            'placeholder' => 'All Priorities',
        ],
    ];

    /**
     * Default values for new tickets
     *
     * @var array
     */
    protected $defaults = [
        'status' => 'open',
        'priority' => 'medium',
    ];

    /**
     * Field definitions
     *
     * @var array
     */
    protected $fields = [
        'title' => [
            'type' => 'text',
            'label' => 'Title',
            'required' => true,
            'placeholder' => 'Enter ticket title',
            'helpText' => 'Brief summary of the issue or request',
        ],
        'description' => [
            'type' => 'textarea',
            'label' => 'Description',
            'required' => false,
            'placeholder' => 'Detailed description of the ticket',
            'helpText' => 'Full description of the issue, request, or task',
        ],
        'status' => [
            'type' => 'select',
            'label' => 'Status',
            'required' => false,
            'default' => 'open',
            'options' => [
                ['value' => 'open', 'label' => 'Open'],
                ['value' => 'in-progress', 'label' => 'In Progress'],
                ['value' => 'closed', 'label' => 'Closed'],
            ],
            'helpText' => 'Current status of the ticket (defaults to Open)',
        ],
        'priority' => [
            'type' => 'select',
            'label' => 'Priority',
            'required' => false,
            'default' => 'medium',
            'options' => [
                ['value' => 'low', 'label' => 'Low'],
                ['value' => 'medium', 'label' => 'Medium'],
                ['value' => 'high', 'label' => 'High'],
            ],
            'helpText' => 'Priority level of this ticket (defaults to Medium)',
        ],
        'assigned_to' => [
            'type' => 'text',
            'label' => 'Assigned To',
            'required' => false,
            'placeholder' => 'User ID or name',
            'helpText' => 'Person assigned to handle this ticket',
        ],
        'created_by' => [
            'type' => 'text',
            'label' => 'Created By',
            'required' => false,
            'placeholder' => 'User ID or name',
            'helpText' => 'Person who created this ticket',
        ],
    ];
}
