<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Config\Database;
use App\Helpers\ImageHelper;

class ScreenshotController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        Auth::requireAuth();
    }

    /**
     * Show screenshots for a project.
     */
    public function index(array $params): void
    {
        $projectId = (int) ($params['id'] ?? 0);
        $db = Database::getInstance();

        $project = $db->fetchOne('SELECT id, title_ru, title_en FROM projects WHERE id = ?', [$projectId]);
        if (!$project) {
            Session::setFlash('error', 'Проект не найден.');
            $this->redirect('/admin/projects');
        }

        $screenshots = $db->fetchAll(
            'SELECT * FROM project_screenshots WHERE project_id = ? ORDER BY sort_order',
            [$projectId]
        );

        $this->display('projects/screenshots', [
            'csrf_token'  => $this->csrfToken(),
            'project'     => $project,
            'screenshots' => $screenshots,
        ]);
    }

    /**
     * Upload screenshots for a project.
     */
    public function upload(array $params): void
    {
        if (!$this->validateCsrf()) {
            $this->redirect('/admin/projects');
        }

        $projectId = (int) ($params['id'] ?? 0);
        $files = $this->request->file('screenshots');

        if (!$files) {
            Session::setFlash('error', 'Файлы не выбраны.');
            $this->redirect("/admin/projects/{$projectId}/screenshots");
        }

        // Normalize single file to array
        if (!is_array($files['name'])) {
            $files = [
                'name'     => [$files['name']],
                'tmp_name' => [$files['tmp_name']],
                'error'    => [$files['error']],
                'size'     => [$files['size']],
                'type'     => [$files['type']],
            ];
        }

        $db = Database::getInstance();
        $uploaded = 0;

        foreach ($files['name'] as $i => $name) {
            if ($files['error'][$i] !== UPLOAD_ERR_OK) {
                continue;
            }

            $tmpPath = $files['tmp_name'][$i];
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

            if (!in_array($ext, ALLOWED_EXTENSIONS)) {
                continue;
            }

            if ($files['size'][$i] > MAX_FILE_SIZE) {
                continue;
            }

            // Generate unique filename
            $filename = $projectId . '_' . time() . '_' . $i . '.' . $ext;
            $destPath = SCREENSHOTS_PATH . '/' . $filename;

            if (!move_uploaded_file($tmpPath, $destPath)) {
                continue;
            }

            // Generate thumbnail
            $thumbnail = 'thumb_' . $filename;
            $thumbPath = THUMBNAILS_PATH . '/' . $thumbnail;
            ImageHelper::createThumbnail($destPath, $thumbPath, THUMBNAIL_WIDTH, THUMBNAIL_HEIGHT);

            // Get max sort_order
            $maxOrder = $db->fetchOne(
                'SELECT COALESCE(MAX(sort_order), -1) + 1 as next FROM project_screenshots WHERE project_id = ?',
                [$projectId]
            );

            $db->insert(
                'INSERT INTO project_screenshots (project_id, filename, thumbnail, sort_order) VALUES (?, ?, ?, ?)',
                [$projectId, $filename, $thumbnail, $maxOrder['next'] ?? 0]
            );

            $uploaded++;
        }

        Session::setFlash('success', "Загружено {$uploaded} скриншотов.");
        $this->redirect("/admin/projects/{$projectId}/screenshots");
    }

    /**
     * Delete a screenshot.
     */
    public function destroy(array $params): void
    {
        if (!$this->validateCsrf()) {
            $this->redirect('/admin/projects');
        }

        $id = (int) ($params['id'] ?? 0);
        $db = Database::getInstance();

        $screenshot = $db->fetchOne('SELECT * FROM project_screenshots WHERE id = ?', [$id]);
        if ($screenshot) {
            // Delete files
            $filePath = SCREENSHOTS_PATH . '/' . $screenshot['filename'];
            $thumbPath = THUMBNAILS_PATH . '/' . $screenshot['thumbnail'];
            if (file_exists($filePath)) unlink($filePath);
            if (file_exists($thumbPath)) unlink($thumbPath);

            $db->execute('DELETE FROM project_screenshots WHERE id = ?', [$id]);
        }

        Session::setFlash('success', 'Скриншот удалён.');
        $this->redirectBack();
    }
}