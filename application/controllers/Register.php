<?php

class Register extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index($data = ['title' => 'Register', 'nrp_exist' => false, 'action_url' => 'cek-nrp'])
    {
        $this->load->view('login_templates/header', $data);
        $this->load->view('register/index');
        $this->load->view('login_templates/footer');
    }

    public function cek_nrp()
    {

        $this->form_validation->set_rules([
            [
                'field' => 'nrp',
                'label' => 'NRP',
                'rules' => 'required|numeric|trim',
                'errors' => [
                    'required' => '%s harus diisi',
                    'numeric' => '%s harus berupa angka'
                ]
            ]
        ]);

        // CEK NRP DARI DATABASE
        if ($this->form_validation->run()) {

            $nrp = $this->input->post('nrp', true);
            $personil = $this->db->select(['id', 'nama', 'password'])->where('nrp', $nrp)->get('mspersonil')->result_array()[0];
            $is_regist = $this->db->select("*")->where('personilid', $personil['id'])->get('calon_pensiun')->result_array();
            if ($is_regist) {
                if (empty($personil['password'])) {

                    $data = [
                        'title' => 'Register',
                        'nrp_exist' => true,
                        'action_url' => 'proses',
                        'nama' => $personil['nama']
                    ];
                    $this->load->view('login_templates/header', $data);
                    $this->load->view('register/index');
                    $this->load->view('login_templates/footer');
                } else {
                    $this->session->set_flashdata('register_error_message', 'Anda telah terdaftar');

                    $data = [
                        'title' => 'Register',
                        'nrp_exist' => false,
                        'action_url' => 'cek-nrp'
                    ];
                    $this->index($data);
                }
            } else {
                $this->session->set_flashdata('register_error_message', 'nrp yang anda masukkan tidak terdaftar');
                $this->index();
            }
        } else {
            $this->index();
        }
    }


    public function proses()
    {
        $this->form_validation->set_rules([
            [
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim|min_length[6]|max_length[6]|callback_password_check|required',
                'errors' => [
                    'required' => '%s harus diisi',
                    'min_length' => '%s harus terdiri dari 6 karakter',
                    'max_length' => '%s harus terdiri dari 6 karakter'
                ],
            ],
            [
                'field' => 'confirm_password',
                'label' => 'Konfirmasi Password',
                'rules' => 'trim|min_length[6]|max_length[6]|callback_password_check|required',
                'errors' => [
                    'required' => '%s harus diisi',
                    'min_length' => '%s harus terdiri dari 6 karakter',
                    'max_length' => '%s harus terdiri dari 6 karakter',
                ]
            ]
        ]);

        if ($this->form_validation->run()) {
            $nrp = $this->input->post('nrp', true);
            $password = $this->input->post('password', true);
            $confirm_password = $this->input->post('confirm_password');

            $personil = ($this->db->select(['id', 'nama', 'password'])->where('nrp', $nrp)->get('mspersonil')->result_array())[0];
            if ($password == $confirm_password) {
                $result = $this->db
                    ->set(['password' => md5($password)])
                    ->where('nrp', $nrp)
                    ->update('mspersonil');

                if ($result) {
                    // REDIRECT KE HALAMAN LOGIN JIKA BERHASIL REGISTER KE PERSONIL
                    $this->session->set_flashdata('register_success_message', 'Anda berhasil terdaftar');

                    $data = [
                        'title' => 'Register',
                        'nrp_exist' => false,
                        'action_url' => 'cek-nrp'
                    ];
                    $this->index($data);
                } else {

                    $this->session->set_flashdata('register_error_message', 'permintaan gagal dimuat, mohon coba beberapa saat lagi');
                    $data = [
                        'title' => 'Register',
                        'nrp_exist' => true,
                        'action_url' => 'proses'
                    ];
                    $this->index($data);
                }
            } else {

                $this->session->set_flashdata('register_error_message', 'Konfirmasi password salah');

                $data = [
                    'title' => 'Register',
                    'nrp_exist' => true,
                    'action_url' => 'proses'
                ];
                $this->index($data);
            }
        }
    }

    public function password_check($str)
    {
        if (preg_match('#[0-9]#', $str) && preg_match('#[a-zA-Z]#', $str)) {
            return true;
        } else {
            $this->form_validation->set_message('password_check', '{field} harus kombinasi huruf dan angka');
            return false;
        }
    }
}
