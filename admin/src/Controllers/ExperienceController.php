<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Models\Experience;

class ExperienceController extends Controller
{
    private Experience $model;

    public function __construct()
    {
        parent::__construct();
        Auth::requireAuth();
        $this->model = new Experience();
    }

    public function index(): void
    {
        $this->display('experience/index', [
            'csrf_token' => $this->csrfToken(),
            'items'      => $this->model->all('start_date', 'DESC'),
        ]);
    }

    public function create(): void
    {
        $this->display('experience/create', [
            'csrf_token' => $this->csrfToken(),
        ]);
    }

    public function store(): void
    {
        if (!$this->validateCsrf()) {
            $this->redirect('/admin/experience/create');
        }

        $endDate = $this->request->post('end_date');
        $this->model->create([
            'company_ru'     => $this->request->post('company_ru', ''),
            'company_en'     => $this->request->post('company_en', ''),
            'position_ru'    => $this->request->post('position_ru', ''),
            'position_en'    => $this->request->post('position_en', ''),
            'description_ru' => $this->request->post('description_ru'),
            'description_en' => $this->request->post('description_en'),
            'start_date'     => $this->request->post('start_date', ''),
            'end_date'       => $endDate ?: null,
            'sort_order'     => (int) $this->request->post('sort_order', 0),
        ]);

        Session::setFlash('success', 'Запись добавлена.');
        $this->redirect('/admin/experience');
    }

    public function edit(array $params): void
    {
        $id = (int) ($params['id'] ?? 0);
        $item = $this->model->find($id);

        if (!$item) {
            Session::setFlash('error', 'Запись не найдена.');
            $this->redirect('/admin/experience');
        }

        $this->display('experience/edit', [
            'csrf_token' => $this->csrfToken(),
            'item'       => $item,
        ]);
    }

    public function update(array $params): void
    {
        if (!$this->validateCsrf()) {
            $this->redirect('/admin/experience');
        }

        $id = (int) ($params['id'] ?? 0);
        $endDate = $this->request->post('end_date');

        $this->model->update($id, [
            'company_ru'     => $this->request->post('company_ru', ''),
            'company_en'     => $this->request->post('company_en', ''),
            'position_ru'    => $this->request->post('position_ru', ''),
            'position_en'    => $this->request->post('position_en', ''),
            'description_ru' => $this->request->post('description_ru'),
            'description_en' => $this->request->post('description_en'),
            'start_date'     => $this->request->post('start_date', ''),
            'end_date'       => $endDate ?: null,
            'sort_order'     => (int) $this->request->post('sort_order', 0),
        ]);

        Session::setFlash('success', 'Запись обновлена.');
        $this->redirect('/admin/experience');
    }

    public function destroy(array $params): void
    {
        if (!$this->validateCsrf()) {
            $this->redirect('/admin/experience');
        }

        $id = (int) ($params['id'] ?? 0);
        $this->model->delete($id);

        Session::setFlash('success', 'Запись удалена.');
        $this->redirect('/admin/experience');
    }
}