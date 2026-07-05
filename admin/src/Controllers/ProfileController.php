<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\Session;
use App\Models\Profile;

class ProfileController extends Controller
{
    private Profile $model;

    public function __construct()
    {
        parent::__construct();
        Auth::requireAuth();
        $this->model = new Profile();
    }

    /**
     * Show profile edit form.
     */
    public function edit(): void
    {
        $profile = $this->model->get();

        $this->display('profile/edit', [
            'csrf_token' => $this->csrfToken(),
            'profile'    => $profile,
        ]);
    }

    /**
     * Update profile.
     */
    public function update(): void
    {
        if (!$this->validateCsrf()) {
            $this->redirect('/admin/profile');
        }

        $data = [
            'name_ru'       => $this->request->post('name_ru', ''),
            'name_en'       => $this->request->post('name_en', ''),
            'position_ru'   => $this->request->post('position_ru', ''),
            'position_en'   => $this->request->post('position_en', ''),
            'summary_ru'    => $this->request->post('summary_ru', ''),
            'summary_en'    => $this->request->post('summary_en', ''),
            'email'         => $this->request->post('email', ''),
            'github_url'    => $this->request->post('github_url'),
            'linkedin_url'  => $this->request->post('linkedin_url'),
            'telegram_url'  => $this->request->post('telegram_url'),
        ];

        // Handle photo upload
        $photo = $this->request->file('photo');
        if ($photo) {
            $uploadResult = $this->handleFileUpload($photo, 'photo');
            if ($uploadResult) {
                $data['photo'] = $uploadResult;
            }
        }

        // Handle resume upload
        $resume = $this->request->file('resume_file');
        if ($resume) {
            $ext = pathinfo($resume['name'], PATHINFO_EXTENSION);
            if (strtolower($ext) === 'pdf') {
                $filename = 'resume.' . $ext;
                $destPath = RESUME_PATH . '/' . $filename;
                move_uploaded_file($resume['tmp_name'], $destPath);
                $data['resume_file'] = '/admin/uploads/resume/' . $filename;
            }
        }

        $this->model->updateProfile($data);

        Session::setFlash('success', 'Профиль обновлён.');
        $this->redirect('/admin/profile');
    }

    /**
     * Handle file upload.
     */
    private function handleFileUpload(array $file, string $prefix): ?string
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            Session::setFlash('error', 'Недопустимый тип файла.');
            return null;
        }

        if ($file['size'] > MAX_FILE_SIZE) {
            Session::setFlash('error', 'Файл слишком большой.');
            return null;
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = $prefix . '_' . time() . '.' . $ext;
        $destPath = UPLOADS_PATH . '/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $destPath)) {
            return '/admin/uploads/' . $filename;
        }

        return null;
    }
}