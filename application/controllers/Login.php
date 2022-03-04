<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }

    public function index()
    {
        if (logged_in('user') || logged_in('personil')) {
            redirect(base_url() . "Home");
        }

        $data = [
            "title" => "Login"
        ];
        $this->load->view("login_templates/header", $data);
        $this->load->view('login/index');
        $this->load->view("login_templates/footer");
    }

    public function personil()
    {
        $data = [
            "title" => "Login Personil"
        ];
        $this->load->view("login_templates/header", $data);
        $this->load->view('login/login_personil');
        $this->load->view("login_templates/footer");
    }

    public function user()
    {
        $data = [
            "title" => "Login user"
        ];
        $this->load->view("login_templates/header", $data);
        $this->load->view('login/login_user');
        $this->load->view("login_templates/footer");
    }

    public function ceklogin_user()
    {
        $this->form_validation->set_rules([
            [
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'required|trim',
                'errors' => [
                    'required' => '%s harus diisi'
                ]
            ],
            [
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim|min_length[6]|max_length[6]|callback_password_check|required',
                'errors' => [
                    'required' => '%s harus diisi',
                    'min_length' => '%s harus terdiri dari 6 karakter',
                    'max_length' => '%s harus terdiri dari 6 karakter'
                ]
            ]
        ]);
        if ($this->form_validation->run()) {
            $this->_ceklogin_user();
        } else {
            $this->user();
        }
    }

    public function ceklogin_personil()
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
            ],
            [
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim|min_length[6]|max_length[6]|callback_password_check|required',
                'errors' => [
                    'required' => '%s harus diisi',
                    'min_length' => '%s harus terdiri dari 6 karakter',
                    'max_length' => '%s harus terdiri dari 6 karakter'
                ]
            ]
        ]);
        if ($this->form_validation->run()) {
            $this->_ceklogin_personil();
        } else {
            $this->personil();
        }
    }

    public function logout()
    {
        if (logged_in('personil')) {
            $this->session->unset_userdata('personil');
        } else {
            $this->session->unset_userdata('user');
        }
        return redirect(base_url('/login'));
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

    private function _ceklogin_personil()
    {
        $nrp = $this->input->post('nrp', true);
        $password = $this->input->post('password', true);

        $user_datas = $this->db->select(['id', 'nrp', 'nama', 'password'])->where('nrp', $nrp)->get('mspersonil')->result_array();

        if ($user_datas) {
            foreach ($user_datas as $data) {

                if ($data['password'] == md5($password)) {
                    $userdata = [
                        'id' => $data['id'],
                        'nama' => $data['nama']
                    ];
                    $this->session->set_userdata('personil', $userdata);
                    // REDIRECT JIKA BERHASIL LOGIN
                    return redirect(base_url());
                }
            }
            $this->session->set_flashdata('personil_error_message', 'password yang anda masukkan salah');
            $this->personil();
        } else {
            $this->session->set_flashdata('personil_error_message', 'NRP tidak terdaftar');
            $this->personil();
        }
    }

    private function _ceklogin_user()
    {
        $username = $this->input->post('username', true);
        $password = $this->input->post('password', true);

        $user_datas = $this->db->select(['id', 'username', 'nama', 'password'])->where('username', $username)->get('msuser')->result_array();

        if ($user_datas) {
            foreach ($user_datas as $data) {
                if ($data['password'] == md5($password)) {
                    $userdata = [
                        'id' => $data['id'],
                        'nama' => $data['nama']
                    ];
                    $this->session->set_userdata('user', $userdata);
                    // REDIRECT JIKA BERHASIL LOGINs
                    return redirect(base_url());
                }
            }
            $this->session->set_flashdata('user_error_message', 'password yang anda masukkan salah');
            $this->user();
        } else {
            $this->session->set_flashdata('user_error_message', 'username tidak terdaftar');
            $this->user();
        }
    }
}
