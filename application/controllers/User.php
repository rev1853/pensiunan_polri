<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (logged_in("user")) {
            redirect(base_url() . "login/user");
        }
        $this->load->model("User_model", "usermodel");
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data = [
            "title" => "Data User"
        ];
        $this->load->view('templates/header', $data);
        $this->load->view('data_user/index');
        $this->load->view('templates/footer');
    }

    public function data()
    {
        $all_data = $this->usermodel->get_datatable();
        $data = [];
        $i = $_POST['start'];
        $url = base_url();
        foreach ($all_data as $datas) {
            $i++;
            $row = [];
            $row[] = $i;
            $row[] = $datas->nama;
            $row[] = $datas->email;
            $row[] = $datas->username;
            $row[] = $datas->telepon;
            $row[] = $datas->role;
            $row[] = $datas->kesatuan;
            $row[] = $datas->pangkat;
            $row[] = "
            <a href='" . $url . "user/edit/$datas->id' class='btn btn-success'><i class='fas fa-edit'></i></a>
            <a href='" . $url . "user/hapus/$datas->id' class='btn btn-danger'><i class='fas fa-trash-alt'></i></a>
            ";
            $data[] = $row;
        }

        $output = [
            "draw" => intval($_POST['draw']),
            "recordsTotal" => $this->usermodel->get_all_data(),
            "recordsFiltered" => $this->usermodel->get_filtered_data(),
            "data" => $data
        ];

        echo json_encode($output);
    }

    public function tambah()
    {
        $data = [
            "title" => "Tambah User"
        ];
        $this->load->view('templates/header', $data);
        $this->load->view('data_user/tambah');
        $this->load->view('templates/footer');
    }

    public function proses_tambah()
    {
        $this->form_validation->set_rules([
            [
                'field' => 'nama',
                'label' => 'Nama',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => '%s harus diisi',
                    'alpha_numeric_spaces' => '%s harus terdiri dari huruf dan spasi'
                ],
            ],
            [
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => '%s harus diisi',
                    'alpha' => '%s harus terdiri dari huruf saja'
                ],
            ],
            [
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required|valid_email',
                'errors' => [
                    'required' => '%s harus diisi',
                    'valid_email' => '%s bukan format email yang valid'
                ]
            ],
            [
                'field' => 'telepon',
                'label' => 'Telepon',
                'rules' => 'trim|required|numeric',
                'errors' => [
                    'required' => '%s harus diisi',
                    'numeric' => '%s harus terdiri dari angka saja'
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
                ],
            ],
            [
                'field' => 'confirmpassword',
                'label' => 'Konfirmasi Password',
                'rules' => 'trim|min_length[6]|max_length[6]|callback_password_check|required',
                'errors' => [
                    'required' => '%s harus diisi',
                    'min_length' => '%s harus terdiri dari 6 karakter',
                    'max_length' => '%s harus terdiri dari 6 karakter',
                ]
            ],
            [
                'field' => 'role',
                'label' => 'Role',
                'rules' => 'trim|alpha|required',
                'errors' => [
                    'required' => '%s harus diisi',
                    'alpha' => '%s harus terdiri dari huruf'
                ],
            ],
            [
                'field' => 'kesatuan',
                'label' => 'Kesatuan',
                'rules' => 'trim|required|numeric',
                'errors' => [
                    'required' => '%s harus diisi',
                    'numeric' => '%s harus terdiri dari angka saja'
                ]
            ],
        ]);

        if ($this->form_validation->run()) {

            $password = $this->input->post('password');
            $confirmpassword = $this->input->post('confirmpassword');

            if ($password == $confirmpassword) {
                $password = md5($password);
                $data = [
                    'nama' => $this->input->post('nama'),
                    'username' => $this->input->post('username'),
                    'no_telp' => $this->input->post('telepon'),
                    'email' => $this->input->post('email'),
                    'role' => $this->input->post('role'),
                    'password' => $password,
                    'kesatuanid' => $this->input->post('kesatuan'),
                ];
                $this->db->insert('msuser', $data);

                if ($this->db->affected_rows() > 0) {
                    $output = [
                        "success" =>  true
                    ];
                    echo json_encode($output);
                } else {
                    $output = [
                        "success" => false
                    ];
                    echo json_encode($output);
                }
            } else {
                $output = [
                    "error" => [
                        'confirmpassword' => 'Konfirmasi password gagal'
                    ]
                ];
                echo json_encode($output);
            }
        } else {
            $output = [
                "error" => $this->form_validation->error_array()
            ];
            echo json_encode($output);
        }
    }

    public function proses_edit()
    {
        $this->form_validation->set_rules([
            [
                'field' => 'nama',
                'label' => 'Nama',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => '%s harus diisi',
                    'alpha_numeric_spaces' => '%s harus terdiri dari huruf dan spasi'
                ],
            ],
            [
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'trim|required',
                'errors' => [
                    'required' => '%s harus diisi',
                    'alpha' => '%s harus terdiri dari huruf saja'
                ],
            ],
            [
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required|valid_email',
                'errors' => [
                    'required' => '%s harus diisi',
                    'valid_email' => '%s bukan format email yang valid'
                ]
            ],
            [
                'field' => 'telepon',
                'label' => 'Telepon',
                'rules' => 'trim|required|numeric',
                'errors' => [
                    'required' => '%s harus diisi',
                    'numeric' => '%s harus terdiri dari angka saja'
                ]
            ],
            [
                'field' => 'role',
                'label' => 'Role',
                'rules' => 'trim|alpha|required',
                'errors' => [
                    'required' => '%s harus diisi',
                    'alpha' => '%s harus terdiri dari huruf'
                ],
            ],
            [
                'field' => 'kesatuan',
                'label' => 'Kesatuan',
                'rules' => 'trim|required|numeric',
                'errors' => [
                    'required' => '%s harus diisi',
                    'numeric' => '%s harus terdiri dari angka saja'
                ]
            ],
        ]);

        if ($this->form_validation->run()) {

            $password = $this->input->post('password');
            $confirmpassword = $this->input->post('confirmpassword');


            $password = md5($password);
            $data = [
                'nama' => $this->input->post('nama'),
                'username' => $this->input->post('username'),
                'no_telp' => $this->input->post('telepon'),
                'email' => $this->input->post('email'),
                'role' => $this->input->post('role'),
                'kesatuanid' => $this->input->post('kesatuan'),
            ];
            $this->db->set($data)
                ->where('id', $this->input->post('id'))
                ->update('msuser');

            if ($this->db->affected_rows() > 0) {
                $output = [
                    "success" =>  true
                ];
                echo json_encode($output);
            } else {
                $output = [
                    "success" => false
                ];
                echo json_encode($output);
            }
        } else {
            $output = [
                "error" => $this->form_validation->error_array()
            ];
            echo json_encode($output);
        }
    }

    public function hapus($id)
    {
        $result = $this->db->where('id', $id)
            ->delete('msuser');
        if ($result) {
            $this->session->set_flashdata('delete_success_message', 'Data berhasil dihapus');
        } else {
            $this->session->set_flashdata('delete_error_message', 'Data gagal dihapus');
        }

        redirect(base_url("/user"));
    }

    public function edit($id)
    {
        $data = $this->db
            ->select(['id', 'nama', 'username', 'email', 'no_telp', 'role', 'kesatuanid'])
            ->where('id', $id)
            ->get('msuser')->result_array();
        $data = $data[0];
        $data['title'] = 'Ubah data user';

        $kesatuan = $this->db->where('id', $data['kesatuanid'])->get('mskesatuan')->result_array()[0]['nama'];
        $data['kesatuan'] = $kesatuan;

        $this->load->view('templates/header', $data);
        $this->load->view('data_user/edit', $data);
        $this->load->view('templates/footer');
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
