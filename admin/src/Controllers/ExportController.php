<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Config\Database;

class ExportController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        Auth::requireAuth();
    }

    /**
     * Show export page.
     */
    public function index(): void
    {
        $this->display('export/index', [
            'csrf_token' => $this->csrfToken(),
        ]);
    }

    /**
     * Export all data to JSON files for the frontend.
     */
    public function export(): void
    {
        if (!$this->validateCsrf()) {
            $this->redirect('/admin/export');
        }

        $db = Database::getInstance();
        $dataPath = FRONTEND_DATA_PATH;

        if (!is_dir($dataPath)) {
            mkdir($dataPath, 0755, true);
        }

        try {
            // Export profile
            $profile = $db->fetchOne('SELECT * FROM profile WHERE id = 1');
            if ($profile) {
                unset($profile['id'], $profile['created_at'], $profile['updated_at']);
                file_put_contents(
                    $dataPath . '/profile.json',
                    json_encode($profile, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
                );
            }

            // Export technologies
            $technologies = $db->fetchAll('SELECT * FROM technologies ORDER BY sort_order');
            foreach ($technologies as &$tech) {
                unset($tech['created_at'], $tech['updated_at']);
            }
            file_put_contents(
                $dataPath . '/technologies.json',
                json_encode($technologies, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
            );

            // Export experience
            $experience = $db->fetchAll('SELECT * FROM experience ORDER BY sort_order');
            foreach ($experience as &$exp) {
                unset($exp['created_at'], $exp['updated_at']);
            }
            file_put_contents(
                $dataPath . '/experience.json',
                json_encode($experience, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
            );

            // Export projects with technologies and screenshots
            $projects = $db->fetchAll('SELECT * FROM projects ORDER BY sort_order');
            foreach ($projects as &$project) {
                unset($project['created_at'], $project['updated_at']);

                // Get technology IDs
                $techRows = $db->fetchAll(
                    'SELECT technology_id FROM project_technologies WHERE project_id = ? ORDER BY technology_id',
                    [$project['id']]
                );
                $project['technologies'] = array_column($techRows, 'technology_id');

                // Get screenshots
                $screenshots = $db->fetchAll(
                    'SELECT * FROM project_screenshots WHERE project_id = ? ORDER BY sort_order',
                    [$project['id']]
                );
                foreach ($screenshots as &$screenshot) {
                    unset($screenshot['id'], $screenshot['project_id'], $screenshot['created_at']);
                }
                $project['screenshots'] = $screenshots;

                unset($project['id']);
            }
            file_put_contents(
                $dataPath . '/projects.json',
                json_encode($projects, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
            );

            Session::setFlash('success', 'Данные успешно экспортированы в JSON. Запустите сборку Next.js для применения изменений.');
        } catch (\Throwable $e) {
            Session::setFlash('error', 'Ошибка экспорта: ' . $e->getMessage());
        }

        $this->redirect('/admin/export');
    }
}