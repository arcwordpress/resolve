# AI Instructions for Resolve Plugin Development

This document contains important guidelines and lessons learned during the development of the Resolve plugin, a Gateway extension.

## Overview

Resolve is a ticket system/help desk plugin that **extends** the Gateway plugin. It is NOT a standalone plugin - it depends on Gateway for core functionality like Collections, routing, forms, and grids.

## Critical Guidelines

### 1. Plugin Extensions Should Not Duplicate Gateway Functionality

**Problem:** When initially creating the plugin main file using Gateway's `Plugin.php` as a template, there was a tendency to copy Gateway's internal functions like collection registry management, route initialization, admin pages, etc.

**Solution:** Gateway extensions should be minimal and only contain:
- Plugin header and constants
- Namespace and autoloader setup
- Basic init hooks
- Dependency checks
- Collection instantiation/registration

**Example of WRONG approach:**
```php
class Plugin {
    private $collectionRegistry;
    private $standardRoutes;
    private $collectionRoutes;

    public function onGatewayLoaded() {
        $this->collectionRegistry = \Gateway\Plugin::getInstance()->getRegistry();
        $this->registerCollections();
    }

    private function registerCollections() {
        // Trying to manage registration ourselves
    }
}
```

**Example of CORRECT approach:**
```php
class Plugin {
    public function onInit() {
        // Just register our collections - Gateway handles the rest
        Collections\Tickets::register();

        do_action('resolve_loaded');
    }
}
```

**Key Principle:** Extensions **create** collections and call their `register()` method. Gateway's base `Collection` class handles all the registration with Gateway's registry automatically.

### 2. How to Properly Register Collections

Collections in Gateway extensions must be registered using the static `register()` method inherited from `Gateway\Collection`.

**WRONG:**
```php
// Don't instantiate directly
new Collections\Tickets();

// Don't try to manually register with Gateway's registry
$registry = \Gateway\Plugin::getInstance()->getRegistry();
$registry->register(new Collections\Tickets());
```

**CORRECT:**
```php
// Use the static register() method
Collections\Tickets::register();
```

**How it works:**
1. The `register()` method is defined in `Gateway\Collection` base class
2. It creates an instance of your collection: `$instance = new static()`
3. It automatically registers with Gateway's CollectionRegistry: `Plugin::getInstance()->getRegistry()->register($instance)`
4. Gateway then handles all routing, API endpoints, admin menus, etc.

**Reference:** See `/gateway/includes/Collection.php:98-102` for the `register()` implementation.

### 3. Plugin Structure for Gateway Extensions

```
resolve/
├── Plugin.php              # Main plugin file (minimal setup only)
├── docs/
│   └── AI-INSTRUCTIONS.md  # This file
└── includes/
    ├── Collections/        # Your collections that extend Gateway\Collection
    │   └── Tickets.php
    └── Database/           # Database table creation helpers
        └── TicketsDatabase.php
```

### 4. What Plugin.php Should Contain

**Essential only:**
- Plugin header with metadata
- Define constants (VERSION, PATH, URL, FILE)
- SPL autoloader for your namespace
- Singleton pattern (optional but recommended)
- `init` hook that calls `YourCollection::register()`
- `activate()` hook that:
  - Checks if Gateway is active (dependency check)
  - Runs database migrations
  - Flushes rewrite rules
- `deactivate()` hook that flushes rewrite rules

**What NOT to include:**
- Collection registry management
- Route management
- Admin page initialization
- Form/Grid rendering setup
- Any Gateway core functionality

### 5. Migrating Collections from Theme to Plugin

When moving collections from a theme to a plugin:

1. **Move the collection files:**
   - Move `YourCollection.php` from theme `includes/` to plugin `includes/Collections/`
   - Move `YourCollectionDatabase.php` from theme `includes/` to plugin `includes/Database/`

2. **Update namespace:**
   - Change from `namespace ARCWP;` to `namespace Resolve\Collections;` (or your plugin namespace)

3. **Update theme's functions.php:**
   - Remove `require_once` statements for the moved files
   - Remove the `init` hook that registered the collection
   - Remove database installation calls from `after_switch_theme` hook

4. **Add to plugin:**
   - Call `YourCollection::register()` in plugin's `onInit()` method
   - Call `YourCollectionDatabase::install()` in plugin's `activate()` method

## Common Pitfalls

1. **Don't duplicate Gateway's registry** - Extensions don't need their own collection registry
2. **Don't manually manage routes** - Gateway handles this when you register a collection
3. **Don't forget the static register() call** - Just instantiating won't register it with Gateway
4. **Don't skip dependency checks** - Always verify Gateway is active in your activate() hook

## Testing Your Extension

After creating your extension:

1. Activate Gateway plugin first
2. Activate your extension plugin
3. Check that collections appear in Gateway's admin menus
4. Verify API endpoints are available at `/wp-json/{namespace}/{version}/{route}`
5. Test that forms and grids render properly

## Additional Resources

- Gateway Collection base class: `/gateway/includes/Collection.php`
- Gateway Plugin main file: `/gateway/Plugin.php`
- Example theme collection: `/themes/arcwp-theme/includes/Package.php`
