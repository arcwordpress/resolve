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
    protected $fillable = ['title', 'description', 'status_id', 'priority', 'assigned_to', 'created_by'];

    /**
     * Eager load relationships
     *
     * @var array
     */
    protected $with = ['status'];

    /**
     * Append computed attributes to array/JSON output
     *
     * @var array
     */
    protected $appends = ['status_name'];

    /**
     * Apply defaults before validation
     *
     * @param array $data
     * @return array
     */
    public function beforeValidate($data)
    {
        // Apply default status_id if not provided (get the "open" status ID)
        if (!isset($data['status_id']) || $data['status_id'] === '') {
            $openStatus = TicketStatuses::where('slug', 'open')->first();
            if ($openStatus) {
                $data['status_id'] = $openStatus->id;
            }
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
        'priority' => 'medium',
    ];

    /**
     * Grid configuration
     *
     * @var array
     */
    protected $grid = [
        'columns' => [
            [
                'field' => 'title',
                'label' => 'Title',
                'sortable' => true,
            ],
            [
                'field' => 'status',
                'label' => 'Status',
                'sortable' => true,
            ],
            [
                'field' => 'priority',
                'label' => 'Priority',
                'sortable' => true,
            ],
        ],
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
        'status_id' => [
            'type' => 'relation',
            'label' => 'Status',
            'required' => false,
            'relation' => [
                'endpoint' => '/wp-json/resolve/v1/ticket-statuses',
                'labelField' => 'name',
                'valueField' => 'id',
                'placeholder' => 'Select a status...',
            ],
            'relationshipType' => 'belongsTo',
            'collection' => 'ticket_statuses',
            'displayField' => 'name',
            'helpText' => 'Current status of the ticket',
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

    /**
     * Relationship: Status this ticket belongs to
     */
    public function status()
    {
        return $this->belongsTo(TicketStatuses::class, 'status_id');
    }

    /**
     * Accessor: Get the status name for display
     *
     * @return string|null
     */
    public function getStatusNameAttribute()
    {
        if (is_object($this->status) && isset($this->status->name)) {
            return $this->status->name;
        }
        return null;
    }
}
