# Resolve Plugin 

Resolve is a WordPress plugin and a Gateway Extension. 

## Front End Tickets 

Resolve will create a front-end display for tickets if you provide a WordPress page with the slug "tickets". It does this by intercepting the page and applying the /templates/ticket-app-page.php which loads the Ticket Display app from /react/apps/tickets.

## App Authentication 

The app uses Basic Authentication and expects a .env file to be located at the app root in order to do process.env.WP_GATEWAY_API_USERNAME and process.env.WP_GATEWAY_API_PASSWORD.