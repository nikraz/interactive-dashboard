# Interactive Dashboard Project

This project is structured as a multi-container Docker application. It includes a Laravel PHP application, a MySQL database, a MailHog server for handling emails during development, a Vue.js frontend, and a WebSocket server.

## Prerequisites

Before you begin, ensure you have the following installed on your system:
- Docker
- Docker Compose

## Getting Started

Follow these steps to set up the project:

1. **Clone the Repository**

   If you haven't already, clone the project repository to your local machine.

   ```bash
    git clone https://github.com/nikraz/interactive-dashboard.git
    cd interactive-dashboard
    cp .env.example .env
    cp frontend/.env.example frontend/.env
    docker-compose up
    docker ps -a | grep "interactive-dashboard_app"
    docker exec -it <container id>  bash
    composer install 
    chown -R www-data:www-data /var/www/html \
    && chmod -R 777 /var/www/html/storage \
    && chmod -R 777 /var/www/html/bootstrap/cache
    php artisan key:generate
    php artisan migrate
    php artisan db:seed
   ```

## Running tests
   ```bash
    docker ps -a | grep "interactive-dashboard_app"
    docker exec -it <container id>  bash
    php artisan test
    exit
    docker ps -a | grep "interactive-dashboard_vueapp"
    docker exec -it <container id>  bash
    npm run test:unit
    exit
   ```
Note that tests are also accessible via postman collections inside `./tests/postman`

## Endpoints and testing
Mailhog: http://localhost:8025/
LaravelApi: http://localhost:8000/api
VueApp: http://localhost:8080/login

Credentials: test@example.com/password

In order to fully test the email verify and password change postman collection must be used.
After fetching email use token that is generated and add to request body in the collection. 
Same process for password change.
Emails can be send via the vueApp UI.

# Polling approach 
A data polling approach can be found in: 
`git chececkout feature/with-data-polling`
After it all containers must be re-build.

## [Plan for proceeding further](DOCUMENTATION.md)
