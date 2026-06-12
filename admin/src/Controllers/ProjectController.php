<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Models\Project;
use App\Models\Technology;

class ProjectController extends Controller
{
    private Project $projectModel;
    private Technology $techModel;

    public function __construct()
    {
        parent::__construct();
        Auth::requireAuth();
        $this->projectModel = new Project();
        $this->techModel = new Technology();
    }

    /**
     * List all projects.
     */
    public function index(): void
    {
        $projects = $this->projectModel->allWithTechnologies();

        $this->display('projects/index', [
            'csrf_token' => $this->csrfToken(),
            'projects'   => $projects,
        ]);
    }

    /**
     * Show create form.
     */
    public function create(): void
    {
        $technologies = $this->techModel->all();

        $this->display('projects/create', [
            'csrf_token'   => $this->csrfToken(),
            'technologies' => $technologies,
        ]);
    }

    /**
     * Store a new project.
     */
    public function store(): void
    {
        if (!$this->validateCsrf()) {
            $this->redirect('/admin/projects/create');
        }

        $data = [
            'slug_ru'              => $this->request->post('slug_ru', ''),
            'slug_en'              => $this->request->post('slug_en', ''),
            'title_ru'             => $this->request->post('title_ru', ''),
            'title_en'             => $this->request->post('title_en', ''),
            'short_description_ru' => $this->request->post('short_description_ru'),
            'short_description_en' => $this->request->post('short_description_en'),
            'full_description_ru'  => $this->request->post('full_description_ru'),
            'full_description_en'  => $this->request->post('full_description_en'),
            'role_ru'              => $this->request->post('role_ru'),
            'role_en'              => $this->request->post('role_en'),
            'responsibilities_ru'  => $this->request->post('responsibilities_ru'),
            'responsibilities_en'  => $this->request->post('responsibilities_en'),
            'external_url'         => $this->request->post('external_url'),
            'featured'             => $this->request->post('featured') ? 1 : 0,
            'status'               => $this->request->post('status', 'completed'),
            'sort_order'           => (int) $this->request->post('sort_order', 0),
        ];

        $projectId = $this->projectModel->create($data);

        // Sync technologies
        $techIds = $this->request->post('technologies', []);
        if (is_array($techIds)) {
            $this->projectModel->syncTechnologies($projectId, $techIds);
        }

        Session::setFlash('success', 'Проект успешно создан.');
        $this->redirect('/admin/projects');
    }

    /**
     * Show edit form.
     */
    public function edit(array $params): void
    {
        $id = (int) ($params['id'] ?? 0);
        $project = $this->projectModel->findWithRelations($id);

        if (!$project) {
            Session::setFlash('error', 'Проект не найден.');
            $this->redirect('/admin/projects');
        }

        $technologies = $this->techModel->all();
        $projectTechIds = array_column($project['technologies'], 'id');

        $this->display('projects/edit', [
            'csrf_token'       => $this->csrfToken(),
            'project'          => $project,
            'technologies'     => $technologies,
            'projectTechIds'   => $projectTechIds,
        ]);
    }

    /**
     * Update a project.
     */
    public function update(array $params): void
    {
        if (!$this->validateCsrf()) {
            $this->redirect('/admin/projects');
        }

        $id = (int) ($params['id'] ?? 0);

        $data = [
            'slug_ru'              => $this->request->post('slug_ru', ''),
            'slug_en'              => $this->request->post('slug_en', ''),
            'title_ru'             => $this->request->post('title_ru', ''),
            'title_en'             => $this->request->post('title_en', ''),
            'short_description_ru' => $this->request->post('short_description_ru'),
            'short_description_en' => $this->request->post('short_description_en'),
            'full_description_ru'  => $this->request->post('full_description_ru'),
            'full_description_en'  => $this->request->post('full_description_en'),
            'role_ru'              => $this->request->post('role_ru'),
            'role_en'              => $this->request->post('role_en'),
            'responsibilities_ru'  => $this->request->post('responsibilities_ru'),
            'responsibilities_en'  => $this->request->post('responsibilities_en'),
            'external_url'         => $this->request->post('external_url'),
            'featured'             => $this->request->post('featured') ? 1 : 0,
            'status'               => $this->request->post('status', 'completed'),
            'sort_order'           => (int) $this->request->post('sort_order', 0),
        ];

        $this->projectModel->update($id, $data);

        // Sync technologies
        $techIds = $this->request->post('technologies', []);
        if (is_array($techIds)) {
            $this->projectModel->syncTechnologies($id, $techIds);
        }

        Session::setFlash('success', 'Проект успешно обновлён.');
        $this->redirect('/admin/projects');
    }

    /**
     * Delete a project.
     */
    public function destroy(array $params): void
    {
        if (!$this->validateCsrf()) {
            $this->redirect('/admin/projects');
        }

        $id = (int) ($params['id'] ?? 0);
        $this->projectModel->delete($id);

        Session::setFlash('success', 'Проект удалён.');
        $this->redirect('/admin/projects');
    }
}