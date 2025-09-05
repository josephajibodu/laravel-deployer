# Laravel Teams Package Creation Guide

This guide will help you extract the teams feature from this project into a reusable Laravel package.

## 📦 Package Overview

**Package Name:** `cremir/laravel-teams`  
**Description:** A comprehensive teams feature package for Laravel applications  
**Location:** `/Users/cremirdevio/software-projects/CREMIR.CO/laravel-teams-package/`

## 🚀 Step 1: Create Package Directory Structure

```bash
cd /Users/cremirdevio/software-projects/CREMIR.CO
mkdir laravel-teams-package
cd laravel-teams-package
```

Create the following directory structure:

```
laravel-teams-package/
├── src/
│   ├── Commands/
│   │   └── InstallTeamsCommand.php
│   ├── Models/
│   │   ├── Team.php
│   │   ├── TeamUser.php
│   │   ├── TeamInvitation.php
│   │   └── Membership.php
│   ├── Traits/
│   │   ├── HasTeams.php
│   │   └── BelongsToCurrentTeam.php
│   ├── Actions/
│   │   └── Teams/
│   │       ├── CreateTeam.php
│   │       ├── AddTeamMember.php
│   │       ├── InviteTeamMember.php
│   │       ├── RemoveTeamMember.php
│   │       ├── DeleteTeam.php
│   │       ├── DeleteUserWithTeams.php
│   │       └── UpdateTeamName.php
│   ├── Events/
│   │   ├── AddingTeam.php
│   │   ├── AddingTeamMember.php
│   │   ├── InvitingTeamMember.php
│   │   ├── RemovingTeamMember.php
│   │   ├── TeamCreated.php
│   │   ├── TeamDeleted.php
│   │   ├── TeamMemberAdded.php
│   │   ├── TeamMemberRemoved.php
│   │   ├── TeamMemberUpdated.php
│   │   └── TeamUpdated.php
│   ├── Mail/
│   │   └── TeamInvitation.php
│   ├── Http/
│   │   └── Middleware/
│   │       └── EnsureValidTeamContext.php
│   ├── Helpers/
│   │   └── TeamContext.php
│   ├── Models/
│   │   └── Scopes/
│   │       └── CurrentTeamScope.php
│   ├── Policies/
│   │   └── TeamPolicy.php
│   ├── Providers/
│   │   └── TeamsServiceProvider.php
│   └── Teams.php
├── database/
│   └── migrations/
│       ├── create_teams_table.php
│       ├── create_team_users_table.php
│       ├── create_team_invitations_table.php
│       └── add_current_team_id_to_users_table.php
├── resources/
│   └── views/
│       └── mail/
│           └── team-invitation.blade.php
├── tests/
│   └── Feature/
│       └── TeamTest.php
├── composer.json
├── README.md
└── teams.php
```

## 📋 Step 2: Copy Files from Current Project

Run these commands to copy all the teams-related files:

```bash
# From laravel-deployer directory, copy files to the package

# Models
cp app/Models/Team.php ../laravel-teams-package/src/Models/
cp app/Models/TeamUser.php ../laravel-teams-package/src/Models/
cp app/Models/TeamInvitation.php ../laravel-teams-package/src/Models/
cp app/Models/Membership.php ../laravel-teams-package/src/Models/

# Traits
cp app/Traits/HasTeams.php ../laravel-teams-package/src/Traits/
cp app/Traits/BelongsToCurrentTeam.php ../laravel-teams-package/src/Traits/

# Actions
cp -r app/Actions/Teams ../laravel-teams-package/src/Actions/

# Events
cp app/Events/AddingTeam.php ../laravel-teams-package/src/Events/
cp app/Events/AddingTeamMember.php ../laravel-teams-package/src/Events/
cp app/Events/InvitingTeamMember.php ../laravel-teams-package/src/Events/
cp app/Events/RemovingTeamMember.php ../laravel-teams-package/src/Events/
cp app/Events/TeamCreated.php ../laravel-teams-package/src/Events/
cp app/Events/TeamDeleted.php ../laravel-teams-package/src/Events/
cp app/Events/TeamMemberAdded.php ../laravel-teams-package/src/Events/
cp app/Events/TeamMemberRemoved.php ../laravel-teams-package/src/Events/
cp app/Events/TeamMemberUpdated.php ../laravel-teams-package/src/Events/
cp app/Events/TeamUpdated.php ../laravel-teams-package/src/Events/

# Mail
cp app/Mail/TeamInvitation.php ../laravel-teams-package/src/Mail/

# Middleware
cp app/Http/Middleware/EnsureValidTeamContext.php ../laravel-teams-package/src/Http/Middleware/

# Helpers
cp app/Helpers/TeamContext.php ../laravel-teams-package/src/Helpers/

# Scopes
cp app/Models/Scopes/CurrentTeamScope.php ../laravel-teams-package/src/Models/Scopes/

# Policies
cp app/Policies/TeamPolicy.php ../laravel-teams-package/src/Policies/

# Migrations
cp database/migrations/2025_01_15_000001_create_teams_table.php ../laravel-teams-package/database/migrations/create_teams_table.php
cp database/migrations/2025_01_15_000002_create_team_users_table.php ../laravel-teams-package/database/migrations/create_team_users_table.php
cp database/migrations/2025_01_15_000003_create_team_invitations_table.php ../laravel-teams-package/database/migrations/create_team_invitations_table.php
cp database/migrations/2025_01_15_000004_add_current_team_id_to_users_table.php ../laravel-teams-package/database/migrations/add_current_team_id_to_users_table.php

# Views
cp resources/views/mail/team-invitation.blade.php ../laravel-teams-package/resources/views/mail/

# Tests
cp tests/Feature/TeamTest.php ../laravel-teams-package/tests/Feature/
```

## 🔧 Step 3: Update Namespaces

After copying, update all namespaces from `App\` to `Cremir\LaravelTeams\` in all copied files.

### Files to Update:
- All files in `src/Models/`
- All files in `src/Traits/`
- All files in `src/Actions/Teams/`
- All files in `src/Events/`
- All files in `src/Mail/`
- All files in `src/Http/Middleware/`
- All files in `src/Helpers/`
- All files in `src/Models/Scopes/`
- All files in `src/Policies/`

### Example namespace change:
```php
// Change from:
namespace App\Models;

// To:
namespace Cremir\LaravelTeams\Models;
```

## 📄 Step 4: Create Package-Specific Files

### 4.1 Create `composer.json`

```json
{
    "name": "cremir/laravel-teams",
    "description": "A comprehensive teams feature package for Laravel applications",
    "type": "library",
    "license": "MIT",
    "authors": [
        {
            "name": "Cremir",
            "email": "cremir@example.com"
        }
    ],
    "require": {
        "php": "^8.1",
        "illuminate/support": "^10.0|^11.0|^12.0",
        "illuminate/database": "^10.0|^11.0|^12.0",
        "illuminate/mail": "^10.0|^11.0|^12.0",
        "illuminate/console": "^10.0|^11.0|^12.0",
        "illuminate/filesystem": "^10.0|^11.0|^12.0"
    },
    "autoload": {
        "psr-4": {
            "Cremir\\LaravelTeams\\": "src/"
        }
    },
    "autoload-dev": {
        "psr-4": {
            "Cremir\\LaravelTeams\\Tests\\": "tests/"
        }
    },
    "extra": {
        "laravel": {
            "providers": [
                "Cremir\\LaravelTeams\\Providers\\TeamsServiceProvider"
            ]
        }
    },
    "minimum-stability": "dev",
    "prefer-stable": true
}
```

### 4.2 Create `src/Teams.php`

```php
<?php

namespace Cremir\LaravelTeams;

class Teams
{
    public static function install(): void
    {
        // This will be called by the install command
        app(Commands\InstallTeamsCommand::class)->handle();
    }
}
```

### 4.3 Create `src/Commands/InstallTeamsCommand.php`

```php
<?php

namespace Cremir\LaravelTeams\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallTeamsCommand extends Command
{
    protected $signature = 'teams:install {--force : Overwrite existing files}';
    protected $description = 'Install the Laravel Teams package';

    public function handle(): int
    {
        $this->info('Installing Laravel Teams...');

        // Publish migrations
        $this->call('vendor:publish', [
            '--tag' => 'teams-migrations',
            '--force' => $this->option('force')
        ]);

        // Publish config
        $this->call('vendor:publish', [
            '--tag' => 'teams-config',
            '--force' => $this->option('force')
        ]);

        // Publish views
        $this->call('vendor:publish', [
            '--tag' => 'teams-views',
            '--force' => $this->option('force')
        ]);

        // Run migrations
        if ($this->confirm('Run migrations now?')) {
            $this->call('migrate');
        }

        // Add trait to User model
        $this->addTraitToUserModel();

        $this->info('Laravel Teams installed successfully!');
        $this->line('Next steps:');
        $this->line('1. Add the HasTeams trait to your User model');
        $this->line('2. Add current_team_id to your User model fillable array');
        $this->line('3. Publish and customize the configuration file');

        return 0;
    }

    protected function addTraitToUserModel(): void
    {
        $userModelPath = app_path('Models/User.php');
        
        if (!File::exists($userModelPath)) {
            $this->warn('User model not found. Please add the HasTeams trait manually.');
            return;
        }

        $content = File::get($userModelPath);
        
        // Check if trait is already added
        if (str_contains($content, 'HasTeams')) {
            $this->info('HasTeams trait already added to User model.');
            return;
        }

        // Add the trait
        $content = str_replace(
            'use Illuminate\Foundation\Auth\User as Authenticatable;',
            "use Illuminate\Foundation\Auth\User as Authenticatable;\nuse Cremir\\LaravelTeams\\Traits\\HasTeams;",
            $content
        );

        $content = str_replace(
            'use HasFactory, Notifiable;',
            'use HasFactory, Notifiable, HasTeams;',
            $content
        );

        // Add current_team_id to fillable
        if (!str_contains($content, 'current_team_id')) {
            $content = str_replace(
                "'password',",
                "'password',\n        'current_team_id',",
                $content
            );
        }

        File::put($userModelPath, $content);
        $this->info('Added HasTeams trait to User model.');
    }
}
```

### 4.4 Create `src/Providers/TeamsServiceProvider.php`

```php
<?php

namespace Cremir\LaravelTeams\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class TeamsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Publish migrations
        $this->publishes([
            __DIR__.'/../../database/migrations' => database_path('migrations'),
        ], 'teams-migrations');

        // Publish config
        $this->publishes([
            __DIR__.'/../../teams.php' => config_path('teams.php'),
        ], 'teams-config');

        // Publish views
        $this->publishes([
            __DIR__.'/../../resources/views' => resource_path('views/vendor/teams'),
        ], 'teams-views');

        // Load migrations
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Cremir\LaravelTeams\Commands\InstallTeamsCommand::class,
            ]);
        }
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../../teams.php', 'teams');
    }
}
```

### 4.5 Create `teams.php` (Config file)

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Team Model
    |--------------------------------------------------------------------------
    |
    | This is the model that will be used to represent teams in your application.
    |
    */
    'team_model' => \Cremir\LaravelTeams\Models\Team::class,

    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    |
    | This is the model that will be used to represent users in your application.
    | This should be your main User model.
    |
    */
    'user_model' => \App\Models\User::class,

    /*
    |--------------------------------------------------------------------------
    | Team Invitation Model
    |--------------------------------------------------------------------------
    |
    | This is the model that will be used to represent team invitations.
    |
    */
    'team_invitation_model' => \Cremir\LaravelTeams\Models\TeamInvitation::class,

    /*
    |--------------------------------------------------------------------------
    | Team Context Cache TTL
    |--------------------------------------------------------------------------
    |
    | This is the time-to-live for team context caching in seconds.
    | Default is 5 minutes (300 seconds).
    |
    */
    'team_context_cache_ttl' => 300,

    /*
    |--------------------------------------------------------------------------
    | Default Team Roles
    |--------------------------------------------------------------------------
    |
    | These are the default roles that can be assigned to team members.
    |
    */
    'roles' => [
        'owner' => 'Owner',
        'member' => 'Member',
    ],

    /*
    |--------------------------------------------------------------------------
    | Team Permissions
    |--------------------------------------------------------------------------
    |
    | These are the permissions that can be assigned to team roles.
    |
    */
    'permissions' => [
        'owner' => ['*'],
        'member' => [],
    ],
];
```

### 4.6 Create `README.md`

```markdown
# Laravel Teams Package

A comprehensive teams feature package for Laravel applications.

## Installation

### Method 1: Composer (Recommended)
```bash
composer require cremir/laravel-teams
php artisan teams:install
```

### Method 2: Local Development
```bash
# Add to composer.json
"repositories": [
    {
        "type": "path",
        "url": "./laravel-teams-package"
    }
]

# Install
composer require cremir/laravel-teams:dev-main
php artisan teams:install
```

## Features

- ✅ Team CRUD operations
- ✅ User management with roles
- ✅ Team invitations via email
- ✅ Team context and switching
- ✅ Authorization policies
- ✅ Event system
- ✅ Mail notifications
- ✅ Global scoping
- ✅ Comprehensive tests

## Quick Start

After installation, your User model will automatically have team functionality:

```php
// Create a team
$team = $user->ownedTeams()->create([
    'name' => 'My Team',
    'personal_team' => false,
]);

// Add a member
$team->users()->attach($member, ['role' => 'member']);

// Check team membership
$user->belongsToTeam($team); // true/false
$user->teamRole($team); // 'owner' or 'member'
```

## Configuration

Publish the config file to customize:
```bash
php artisan vendor:publish --tag=teams-config
```

## Testing

```bash
php artisan test
```

## License

MIT
```

## 🧪 Step 5: Test the Package

### 5.1 Add Package to Current Project

In `laravel-deployer/composer.json`, add:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "../laravel-teams-package"
        }
    ],
    "require": {
        "cremir/laravel-teams": "dev-main"
    }
}
```

### 5.2 Install and Test

```bash
cd laravel-deployer
composer update
php artisan teams:install
```

## 📦 Step 6: Package Distribution Options

### Option A: GitHub + Composer
1. Push to GitHub
2. Use `composer require cremir/laravel-teams`

### Option B: Local Path (For Development)
1. Keep in your projects directory
2. Use local path in composer.json

### Option C: Packagist (Public Package)
1. Submit to Packagist
2. Available globally via Composer

## ✅ Verification Checklist

- [ ] All files copied successfully
- [ ] All namespaces updated
- [ ] Package files created
- [ ] Composer.json configured
- [ ] Service provider created
- [ ] Install command working
- [ ] Tests passing
- [ ] Documentation complete

## 🎯 Benefits

1. **Reusable** - Install in any Laravel project
2. **Maintainable** - Update once, use everywhere
3. **Configurable** - Customize for each project
4. **Tested** - Comprehensive test suite included
5. **Documented** - Clear installation and usage instructions

---

**Note:** This guide assumes you're working from the `/Users/cremirdevio/software-projects/CREMIR.CO/laravel-deployer/` directory. Adjust paths as needed for your setup.
