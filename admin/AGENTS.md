# Admin Panel — PHP 8.3+ Custom MVC

## Tech Stack

- **Language:** PHP 8.3+
- **Architecture:** Custom MVC (no Laravel/Symfony/WordPress)
- **Database:** MySQL with PDO
- **Authentication:** Session-based with bcrypt password hashing
- **Templating:** Plain PHP views (no Twig/Blade)
- **Styling:** Custom CSS (Apple-inspired design)
- **Image Processing:** GD library for thumbnails

## Project Structure

```
admin/
├── config.php                    # App configuration (DB, paths, autoloader)
├── install.php                   # First-run setup wizard
├── migrations/
│   └── 001_initial_schema.sql    # Database schema + seed data
├── public/
│   ├── .htaccess                 # URL rewriting to index.php
│   ├── index.php                 # Front controller (all routes defined here)
│   └── assets/
│       └── css/
│           └── admin.css         # Admin panel styles
├── src/
│   ├── Config/
│   │   └── Database.php          # PDO singleton
│   ├── Core/
│   │   ├── Router.php            # Method-based routing with URL params
│   │   ├── Controller.php        # Base controller (redirect, render, json, CSRF)
│   │   ├── Model.php             # Base model (find, all, create, update, delete, paginate)
│   │   ├── View.php              # Simple PHP view renderer
│   │   ├── Request.php           # Request data accessor
│   │   ├── Session.php           # Session management
│   │   └── Auth.php              # Authentication helper
│   ├── Controllers/
│   │   ├── AuthController.php    # Login/logout
│   │   ├── DashboardController.php
│   │   ├── ProjectController.php # CRUD + screenshot management
│   │   ├── TechnologyController.php
│   │   ├── ExperienceController.php
│   │   ├── ProfileController.php
│   │   ├── ScreenshotController.php
│   │   └── ExportController.php  # JSON export to frontend/src/lib/data/
│   ├── Models/
│   │   ├── AdminUser.php
│   │   ├── Project.php
│   │   ├── Technology.php
│   │   ├── Experience.php
│   │   └── Profile.php
│   ├── Helpers/
│   │   ├── ImageHelper.php       # GD thumbnail generation
│   │   └── ValidationHelper.php  # Input validation
│   └── Views/
│       ├── layouts/
│       │   ├── default.php       # Main layout (sidebar + content)
│       │   └── login.php         # Login layout (centered form)
│       ├── auth/
│       │   └── login.php
│       ├── dashboard/
│       │   └── index.php
│       ├── projects/
│       │   ├── index.php         # Project list
│       │   ├── create.php        # Create form
│       │   ├── edit.php          # Edit form
│       │   └── screenshots.php   # Screenshot management
│       ├── technologies/
│       │   ├── index.php
│       │   ├── create.php
│       │   └── edit.php
│       ├── experience/
│       │   ├── index.php
│       │   ├── create.php
│       │   └── edit.php
│       ├── profile/
│       │   └── edit.php
│       └── export/
│           └── index.php
```

## Route Definitions

All routes are defined in `admin/public/index.php`:

```php
$router->get('/login', 'AuthController@loginForm');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');
$router->get('/dashboard', 'DashboardController@index');
$router->get('/projects', 'ProjectController@index');
$router->get('/projects/create', 'ProjectController@create');
$router->post('/projects/create', 'ProjectController@store');
$router->get('/projects/edit/{id}', 'ProjectController@edit');
$router->post('/projects/edit/{id}', 'ProjectController@update');
$router->post('/projects/delete/{id}', 'ProjectController@delete');
$router->get('/projects/{id}/screenshots', 'ScreenshotController@index');
$router->post('/projects/{id}/screenshots', 'ScreenshotController@upload');
$router->post('/screenshots/delete/{id}', 'ScreenshotController@delete');
$router->get('/technologies', 'TechnologyController@index');
$router->get('/technologies/create', 'TechnologyController@create');
$router->post('/technologies/create', 'TechnologyController@store');
$router->get('/technologies/edit/{id}', 'TechnologyController@edit');
$router->post('/technologies/edit/{id}', 'TechnologyController@update');
$router->post('/technologies/delete/{id}', 'TechnologyController@delete');
$router->get('/experience', 'ExperienceController@index');
$router->get('/experience/create', 'ExperienceController@create');
$router->post('/experience/create', 'ExperienceController@store');
$router->get('/experience/edit/{id}', 'ExperienceController@edit');
$router->post('/experience/edit/{id}', 'ExperienceController@update');
$router->post('/experience/delete/{id}', 'ExperienceController@delete');
$router->get('/profile', 'ProfileController@edit');
$router->post('/profile', 'ProfileController@update');
$router->get('/export', 'ExportController@index');
$router->post('/export', 'ExportController@export');
```

## Key Patterns

### Controller
```php
class ProjectController extends Controller {
    public function index() {
        $this->auth()->requireLogin();
        $projects = Project::all('ORDER BY sort_order');
        $this->render('projects/index', ['projects' => $projects]);
    }
}
```

### Model
```php
$project = Project::find($id);
$project = Project::create($data);
$project = Project::update($id, $data);
Project::delete($id);
$projects = Project::all('ORDER BY sort_order');
$count = Project::count();
$paginated = Project::paginate(1, 10);
```

### View
```php
// In controller:
$this->render('projects/index', ['projects' => $projects]);

// In view:
<?php foreach ($projects as $project): ?>
  <tr>
    <td><?= htmlspecialchars($project['title_ru']) ?></td>
  </tr>
<?php endforeach; ?>
```

### CSRF Protection
```php
// In form:
<input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">

// In controller:
$this->validateCsrf();
```

### JSON Export
```php
$this->json(['success' => true, 'message' => 'Export completed']);
```

## Database Schema (7 tables)

1. **profile** — Single row with personal info (bilingual fields)
2. **technologies** — Technology stack items with category
3. **experience** — Work experience entries with dates
4. **projects** — Project entries with bilingual fields, status, external_url
5. **project_technologies** — Many-to-many: project_id ↔ technology_id
6. **project_screenshots** — Screenshot files with thumbnails
7. **admin_users** — Admin credentials (bcrypt passwords)

## Important Rules

1. **Always use PDO prepared statements** — never concatenate SQL
2. **Always use `htmlspecialchars()`** — escape output in views
3. **Always validate CSRF token** — on all POST requests
4. **Always require authentication** — on all routes except login
5. **Always validate file uploads** — check extension, size, MIME type
6. **Never expose config.php** — it contains database credentials
7. **Never commit config.php** — it's server-specific
8. **Always use `App\` namespace** — PSR-4 autoloading maps to `admin/src/`

## Quick Commands

```bash
# Point web server to admin/public/
# Run install.php for first-time setup
# Access at: https://erreality.ru/admin/

# JSON Export (CLI)
php scripts/export-json.php