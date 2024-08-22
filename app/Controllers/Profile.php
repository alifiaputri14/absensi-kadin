<?php

namespace App\Controllers;

use App\Models\UserModel;

class Profile extends BaseController
{
    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $userId = session()->get('user_id');

        // Ambil data user dari userModel
        $userModel = new UserModel();
        $userData = $userModel->find($userId);

        // Data array
        $data = [
            'nama' => $userData['Nama'],
            'institusi' => $userData['asal_institusi'],
            'jenis_kelamin' => $userData['Jenis_kelamin'],
            'telepon' => $userData['Nomor_telepon'],
            'email' => $userData['email'],
            'alamat' => $userData['alamat']
        ];
        $data['title'] = 'Profil';
        return view('profileview', $data);
    }

    public function edit(): string
    {
        $userId = session()->get('user_id');

        // Ambil data user dari userModel
        $userModel = new UserModel();
        $userData = $userModel->find($userId);

        // Data array
        $data = [
            'nama' => $userData['Nama'],
            'institusi' => $userData['asal_institusi'],
            'jenis_kelamin' => $userData['Jenis_kelamin'],
            'telepon' => $userData['Nomor_telepon'],
            'email' => $userData['email'],
            'alamat' => $userData['alamat'],
            'password' => '',
        ];
        $data['title'] = 'Edit Profil';
        return view('profileedit', $data);
    }

    public function update()
    {
        $userId = session()->get('user_id');
        $userModel = new UserModel();
    
        $rules = [
            'nama' => [
                'label' => 'Nama lengkap',
                'rules' => 'permit_empty|min_length[3]|max_length[255]',
                'errors' => [
                    'min_length' => '{field} minimal 3 karakter.',
                    'max_length' => '{field} maksimal 255 karakter'
                ]
            ],
            'telepon' => [
                'label' => 'Nomor telepon',
                'rules' => 'permit_empty|min_length[10]|max_length[15]',
                'errors' => [
                    'min_length' => '{field} minimal 10 karakter.',
                    'max_length' => '{field} maksimal 15 karakter'
                ]
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'permit_empty|min_length[8]|regex_match[/(?=.*[0-9])(?=.*[\W])/]',
                'errors' => [
                    'min_length' => '{field} minimal 8 karakter.',
                    'regex_match' => '{field} harus terdiri dari huruf, angka, dan karakter spesial (@,#,!,?, dll).'
                ]
            ],
        ];
    
        if ($this->validate($rules)) {
            $dataUser = [
                'Nama' => $this->request->getPost('nama'),
                'Nomor_telepon' => $this->request->getPost('telepon'),
                'alamat' => $this->request->getPost('alamat'),
            ];
    
            // password
            if ($this->request->getPost('password')) {
                $dataUser['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
            }
    
            $userModel->update($userId, $dataUser);
    
            return redirect()->to('/profile');
        } else {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
    }
    
}
