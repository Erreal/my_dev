<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Project;
use App\Models\Technology;
use App\Models\Experience;

class DashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        Auth::requireAuth();
    }

    /**
     * Show dashboard.
     */
    public function index(): void
    {
        $projectModel = new Project();
        $techModel = new Technology();
        $expModel = new Experience();

        $this->display('dashboard/index', [
            'csrf_token' => $this->csrfToken(),
            'stats' => [
                'projects'     => $projectModel->count(),
                'technologies' => $techModel->count(),
                'experience'   => $expModel->count(),
                'featured'     => $this->getFeaturedCount(),
            ],
        ]);
    }

    private function getFeaturedCount(): int
    {
        $db = \App\Config\Database::getInstance();
        $result = $db->fetchOne(
            'SELECT COUNT(*) as count FROM projects WHERE featured = TRUE'
        );
        return (int) ($result['count'] ?? 0);
    }
}