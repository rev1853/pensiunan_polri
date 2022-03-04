<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as writexlsx;
use \PhpOffice\PhpSpreadsheet\Reader\Xlsx as readxlsx;

class Data_pensiun extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Pensiun_model', 'pensiun');
        $this->load->model('Kesatuan_model', 'kesatuan');
        $this->load->model('Pangkat_model', 'pangkat');
        $this->load->model('Excel_model', 'excel');
        if (!logged_in('personil') && !logged_in('user')) {
            redirect(base_url() . "login/");
        }
    }

    public function index()
    {
        $data = [
            "title" => "Data Pensiun"
        ];
        $this->load->view('templates/header', $data);
        $this->load->view('data_pensiun/index');
        $this->load->view('templates/footer');
    }

    public function data()
    {
        $all_data = $this->pensiun->get_datatable();
        $data = [];
        $i = $_POST['start'];
        foreach ($all_data as $datas) {
            $i++;
            $row = [];
            $row[] = $i;
            $row[] = $datas->nrp;
            $row[] = $datas->nama;
            $row[] = $datas->jabatan;
            $row[] = $datas->pangkat;
            $row[] = $datas->kesatuan;
            $row[] = number_format($datas->gaji, 0, ',', '.');
            $row[] = $datas->tglpensiun;
            $data[] = $row;
        }

        $output = [
            "draw" => intval($_POST['draw']),
            "recordsTotal" => $this->pensiun->get_all_data(),
            "recordsFiltered" => $this->pensiun->get_filtered_data(),
            "data" => $data
        ];

        echo json_encode($output);
    }

    public function getkesatuan()
    {
        $datas = $this->kesatuan->get_all_data();
        $count = $this->kesatuan->get_data_count();
        $data = [];
        foreach ($datas as $d) {
            $data[] = [
                'id' => $d->id,
                'text' => $d->nama
            ];
        }

        $output = [
            'results' => $data,
            'count' => $count
        ];
        echo json_encode($output);
    }

    public function getpangkat()
    {
        $datas = $this->pangkat->get_all_data();
        $count = $this->pangkat->get_data_count();
        $data = [];
        foreach ($datas as $d) {
            $data[] = [
                'id' => $d->id,
                'text' => $d->nama
            ];
        }

        $output = [
            'results' => $data,
            'count' => $count
        ];
        echo json_encode($output);
    }

    public function get_excel()
    {
        $header = [
            'nrp',
            'nama',
            'jabatan',
            'pangkat',
            'kesatuan',
            'gaji',
            'tanggal pensiun'
        ];
        $option = [
            "columns" => [
                "nrp" => [
                    "numberFormat" => [
                        "formatCode" => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER
                    ],
                ], "gaji" => [
                    "numberFormat" => [
                        "formatCode" => "Rp #,##0_-",
                    ]
                ], "tanggal pensiun" => [
                    "numberFormat" => [
                        "formatCode" => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD
                    ],
                ]
            ]
        ];
        $data_pensiun = $this->pensiun->get_data();

        $this->excel
            ->set_title("data pensiunan")
            ->set_data($data_pensiun, $header, $option)
            ->set_filename("data-pensiun-" . date("y-m-d H:i:s"))
            ->download();
    }

    public function email()
    {
        $header = [
            'nrp',
            'nama',
            'jabatan',
            'pangkat',
            'kesatuan',
            'gaji',
            'tanggal pensiun'
        ];
        $option = [
            "columns" => [
                "nrp" => [
                    "numberFormat" => [
                        "formatCode" => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER
                    ],
                ], "gaji" => [
                    "numberFormat" => [
                        "formatCode" => "Rp #,##0_-",
                    ]
                ], "tanggal pensiun" => [
                    "numberFormat" => [
                        "formatCode" => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDD
                    ],
                ]
            ]
        ];
        $data_pensiun = $this->pensiun->get_data();
        $email = [
            'from' => 'ferim5840@gmail.com',
            'as' => 'Aplikasi Pensiunan',
            'to' => 'dhevanbocah@gmail.com',
            'subject' => 'Data Pensiunan',
            'message' => 'Ini update data terbaru dari kami'
        ];

        $config = [
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.gmail.com',
            'smtp_user' => 'ferim5840@gmail.com',
            'smtp_pass' => 'revanersa1987',
            'smtp_port' => 465,
            'smtp_crypto' => 'ssl',
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'newline' => "\r\n",
        ];

        $result = $this->excel
            ->set_title("data pensiunan")
            ->set_data($data_pensiun, $header, $option)
            ->set_filename("data-pensiun" . random_int(0, 9))
            ->prepare_email($email)
            ->send_by_email($config);

        if ($result) {
            redirect($_SERVER['HTTP_REFERER']);
        } else {
            echo $this->excel->get_email_debugger();
        }
    }
}
