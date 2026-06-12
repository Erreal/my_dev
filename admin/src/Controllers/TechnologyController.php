<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Models\Technology;

class TechnologyController extends Controller
{
    private Technology $model;

    public function __construct()
    {
        parent::__construct();
        Auth::requireAuth();
        $this->model = new Technology();
    }

    public function index(): void
    {
        $this->display('technologies/index', [
            'csrf_token'   => $this->csrfToken(),
            'technologies' => $this->model->all(),
        ]);
    }

    public function create(): void
    {
        $this->display('technologies/create', [
            'csrf_token' => $this->csrfToken(),
        ]);
    }

    public function store(): void
    {
        if (!$this->validateCsrf()) {
            $this->redirect('/admin/technologies/create');
        }

        $this->model->create([
            'name'       => $this->request->post('name', ''),
            'category'   => $this->request->post('category', 'other'),
            'sort_order' => (int) $this->request->post('sort_order', 0),
        ]);

        Session::setFlash('success', 'Технология добавлена.');
        $this->redirect('/admin/technologies');
    }

    public function edit(array $params): void
    {
        $id = (int) ($params['id'] ?? 0);
        $tech = $this->model->find($id);

        if (!$tech) {
            Session::setFlash('error', 'Технология не найдена.');
            $this->redirect('/admin/technologies');
        }

        $this->display('technologies/edit', [
            'csrf_token' => $this->csrfToken(),
            'tech'       => $tech,
        ]);
    }

    public function update(array $params): void
    {
        if (!$this->validateCsrf()) {
            $this->redirect('/admin/technologies');
        }

        $id = (int) ($params['id'] ?? 0);
        $this->model->update($id, [
            'name'       => $this->request->post('name', ''),
            'category'   => $this->request->post('category', 'other'),
            'sort_order' => (int) $this->request->post('sort_order', 0),
        ]);

        Session::setFlash('success', 'Технология обновлена.');
        $this->redirect('/admin/technologies');
    }

    public function destroy(array $params): void
    {
        if (!$this->validateCsrf()) {
            $this->redirect('/admin/technologies');
        }

        $id = (int) ($params['id'] ?? 0);
        $this->model->delete($id);

        Session::setFlash('success', 'Технология удалена.');
        $this->redirect('/admin/technologies');
    }
}