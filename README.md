# Laravel Deployer

A modern server management and deployment platform built with Laravel and React. Similar to Laravel Forge, this application provides an intuitive interface for managing servers, deploying applications, and automating your development workflow.

## Features

- **Server Management**: Provision and manage your servers
- **Application Deployment**: Deploy Laravel and other applications with ease
- **Database Management**: Create and manage databases
- **SSL Certificates**: Automatic SSL certificate provisioning
- **Site Management**: Manage multiple sites per server
- **Queue Management**: Monitor and manage Laravel queues
- **Cron Job Scheduling**: Schedule and manage cron jobs
- **User Management**: Multi-user support with role-based access

## 🗺️ Development Roadmap

For a detailed breakdown of the MVP development plan, features, and timeline, see our [**ROADMAP.md**](ROADMAP.md).

The roadmap outlines 10 phases over 20 weeks to complete the MVP, covering everything from authentication and server management to deployment automation and monitoring.

## Tech Stack

- **Backend**: Laravel 12.x with PHP 8.2+
- **Frontend**: React 19 with TypeScript
- **UI Framework**: Inertia.js for SPA-like experience
- **Styling**: Tailwind CSS 4.0
- **Components**: Radix UI primitives
- **Icons**: Lucide React
- **Database**: SQLite (default), PostgreSQL, MySQL supported
- **Build Tools**: Vite, ESLint, Prettier

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- Database (SQLite, MySQL, or PostgreSQL)

## Installation

### 1. Clone the Repository

```bash
git clone <repository-url> laravel-deployer
cd laravel-deployer
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node.js Dependencies

```bash
npm install
```

### 4. Environment Configuration

```bash
# Copy the environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 5. Database Setup

The application uses SQLite by default. For production, consider using PostgreSQL or MySQL.

#### SQLite (Default)
```bash
# Create the database file (if it doesn't exist)
touch database/database.sqlite

# Run migrations
php artisan migrate
```

#### PostgreSQL/MySQL
Update your `.env` file with your database credentials:

```env
DB_CONNECTION=pgsql  # or mysql
DB_HOST=127.0.0.1
DB_PORT=5432        # or 3306 for MySQL
DB_DATABASE=laravel_deployer
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

Then run migrations:
```bash
php artisan migrate
```

### 6. Build Frontend Assets

```bash
# For development
npm run dev

# For production
npm run build
```

## Development

### Quick Start

The easiest way to start development is using the built-in dev script that runs all services concurrently:

```bash
composer run dev
```

This command starts:
- Laravel development server (port 8000)
- Queue worker
- Log monitoring (Laravel Pail)
- Vite development server for hot reloading

### Individual Commands

If you prefer to run services separately:

```bash
# Start Laravel server
php artisan serve

# Start Vite dev server (in another terminal)
npm run dev

# Run queue worker (in another terminal)
php artisan queue:listen

# Monitor logs (in another terminal)
php artisan pail
```

### Server-Side Rendering (SSR)

For production-ready SSR:

```bash
composer run dev:ssr
```

### Code Quality

```bash
# Format code
npm run format

# Check formatting
npm run format:check

# Lint JavaScript/TypeScript
npm run lint

# Fix PHP code style
./vendor/bin/pint

# Type checking
npm run types
```

### Testing

```bash
# Run all tests
composer run test

# Or directly with pest
php artisan test
```

## Configuration

### Server Configuration

Update the following environment variables for server management:

```env
# Server provider API keys
DIGITAL_OCEAN_TOKEN=your_digital_ocean_token
AWS_ACCESS_KEY_ID=your_aws_key
AWS_SECRET_ACCESS_KEY=your_aws_secret
LINODE_TOKEN=your_linode_token

# Notification services
SLACK_WEBHOOK_URL=your_slack_webhook
TELEGRAM_BOT_TOKEN=your_telegram_token
TELEGRAM_CHAT_ID=your_chat_id
```

### Mail Configuration

For notifications and user management:

```env
MAIL_MAILER=smtp
MAIL_HOST=your_mail_host
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="Laravel Deployer"
```

### Queue Configuration

For background job processing:

```env
QUEUE_CONNECTION=database  # or redis, sqs
```

## Deployment

### Production Build

```bash
# Install dependencies
composer install --optimize-autoloader --no-dev
npm ci

# Build assets
npm run build

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force
```

### Environment Setup

Ensure these are set in production:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Use a production database
DB_CONNECTION=pgsql

# Use Redis for caching and sessions
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

### Web Server Configuration

#### Nginx Example

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com;
    root /var/www/laravel-deployer/public;
 
    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
 
    index index.php;
 
    charset utf-8;
 
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
 
    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }
 
    error_page 404 /index.php;
 
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
 
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## API Documentation

The application provides a REST API for programmatic access. API documentation is available at `/docs` when the application is running.

### Authentication

API requests require authentication via Laravel Sanctum tokens:

```bash
# Get API token (after login)
POST /api/auth/tokens

# Use token in requests
Authorization: Bearer your-api-token
```

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes
4. Run tests and ensure code quality
5. Commit your changes (`git commit -m 'Add amazing feature'`)
6. Push to the branch (`git push origin feature/amazing-feature`)
7. Open a Pull Request

### Development Guidelines

- Follow PSR-12 coding standards for PHP
- Use TypeScript for all frontend code
- Write tests for new features
- Update documentation as needed
- Run `composer run test` before submitting

## Security

If you discover a security vulnerability, please send an e-mail to [security@yourdomain.com](mailto:security@yourdomain.com). All security vulnerabilities will be promptly addressed.

## License

This project is licensed under the [MIT License](https://opensource.org/licenses/MIT).

## Support

- **Documentation**: [Link to docs]
- **Issues**: [GitHub Issues](repository-url/issues)
- **Discussions**: [GitHub Discussions](repository-url/discussions)
- **Discord**: [Community Discord](discord-invite-link)

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for details on releases and updates.

---

**Laravel Deployer** - Simplifying server management and deployment workflows.
