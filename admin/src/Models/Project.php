<?php
/**
 * Project model.
 */

namespace App\Models;

use App\Core\Model;

class Project extends Model
{
    protected string $table = 'projects';

    /**
     * Get all projects with their technologies.
     */
    public function allWithTechnologies(string $orderBy = 'sort_order', string $direction = 'ASC'): array
    {
        $projects = $this->all($orderBy, $direction);

        foreach ($projects as &$project) {
            $project['technologies'] = $this->getTechnologies($project['id']);
            $project['screenshots'] = $this->getScreenshots($project['id']);
        }

        return $projects;
    }

    /**
     * Find a project with its relations.
     */
    public function findWithRelations(int $id): ?array
    {
        $project = $this->find($id);
        if (!$project) {
            return null;
        }

        $project['technologies'] = $this->getTechnologies($id);
        $project['screenshots'] = $this->getScreenshots($id);

        return $project;
    }

    /**
     * Get technologies for a project.
     */
    public function getTechnologies(int $projectId): array
    {
        return $this->db->fetchAll(
            "SELECT t.* FROM technologies t
             JOIN project_technologies pt ON t.id = pt.technology_id
             WHERE pt.project_id = ?
             ORDER BY t.sort_order",
            [$projectId]
        );
    }

    /**
     * Get screenshots for a project.
     */
    public function getScreenshots(int $projectId): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM project_screenshots
             WHERE project_id = ?
             ORDER BY sort_order",
            [$projectId]
        );
    }

    /**
     * Sync technologies for a project (replace all).
     */
    public function syncTechnologies(int $projectId, array $technologyIds): void
    {
        $this->db->execute(
            'DELETE FROM project_technologies WHERE project_id = ?',
            [$projectId]
        );

        foreach ($technologyIds as $techId) {
            $this->db->insert(
                'INSERT INTO project_technologies (project_id, technology_id) VALUES (?, ?)',
                [$projectId, (int) $techId]
            );
        }
    }

    /**
     * Delete a project with its relations.
     */
    public function delete(int $id): int
    {
        // Delete screenshots files
        $screenshots = $this->getScreenshots($id);
        foreach ($screenshots as $screenshot) {
            $filePath = SCREENSHOTS_PATH . '/' . $screenshot['filename'];
            $thumbPath = THUMBNAILS_PATH . '/' . $screenshot['thumbnail'];
            if (file_exists($filePath)) unlink($filePath);
            if (file_exists($thumbPath)) unlink($thumbPath);
        }

        return parent::delete($id);
    }
}