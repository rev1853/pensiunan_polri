<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use \PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as writexlsx;
use \PhpOffice\PhpSpreadsheet\Reader\Xlsx as readxlsx;


class Excel_model extends CI_Model
{

    /**
     *  contoh penggunaan
     * $this->load->model('Excel_model', 'excel');
     * $this->excel
     *     ->set_title("data pensiunan")
     *     ->set_data($data, $header:opsional, $option:opsional)
     *     ->set_filename("nama-file")
     *     ->download();
     */

    private $alphabet;
    private $spreadsheet;
    private $writer;

    //export attribute
    private $filename;
    private $title;
    private $header;
    private $data;

    // import attribute
    private $data_result;

    // STYLE
    private $table_header_style = [
        "font" => [
            "bold" => true
        ],
        "alignment" => [
            "horizontal" => Alignment::HORIZONTAL_CENTER,
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_MEDIUM
            ],
        ],
    ];

    private $table_body_style = [
        "alignment" => [
            "horizontal" => Alignment::HORIZONTAL_LEFT,
        ],
        'borders' => [
            'allBorders' => [
                'borderStyle' => Border::BORDER_THIN
            ],
        ],
    ];

    private $title_style = [
        "alignment" => [
            "horizontal" => Alignment::HORIZONTAL_CENTER,
        ], "font" => [
            "bold" => true,
            "size" => 16
        ]
    ];

    // EMAIL ATTRIBuTE
    private $from = 'example1@email.com';
    private $to = 'example2@email.com';
    private $as = 'Friends';
    private $subject = 'testing';
    private $message = 'hello world';

    public function __construct()
    {
        $this->spreadsheet = new Spreadsheet();
        $this->alphabet = array_merge(range('A', 'Z'));
        $this->writer = new writexlsx($this->spreadsheet);
        $this->load->library('email');
    }

    public function set_filename($name)
    {
        $this->filename = $name;
        return $this;
    }

    /**
     * * @param array $style mengubah style default table header
     */
    public function set_header_style($style)
    {
        $this->table_header_style = $style;
    }

    public function set_body_style($style)
    {
        $this->table_body_style = $style;
    }

    public function set_title_style($style)
    {
        $this->title_style = $style;
    }

    /** @param String $title judul tabel
     * title akan diletakkan di atas table 
     * ex $title = JUDUL TABLE;
     * hasil :
     * |             JUDUL TABLE            |
     * -------------------------------------
     * |  header1 |  heaeder2  |  header3  |
     * ------------------------------------- 
     */
    public function set_title($title)
    {
        $this->title = $title;
        return $this;
    }

    /** parameter => $data,$header:opsional,$option:opsional

     * @param array $data data yang akan dicetak ke excel
     * ex: [
     *     [dataA1,dataB1],
     *     [dataA2,datB2],
     *     [dst]
     * ]

     * hasil :
     * | dataA1 | dataB1 |
     * -------------------
     * | dataA2 | dataB2 |

     * @param array $header header tabel
     * tipe array
     * ex: [nama,nrp,alamat]

     * hasil :
     * |  nama  |  nrp  |  alamat  |
     * -----------------------------
     * |  data  |  data |   data   |
     * 
     * @param array $option opsi custom style
     * ex: 
     * $option = [
     *     "columns" => [
     *         "A" => [
     *             "numberFormat" => \PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_PERCENTAGE,
     *             "font" => [
     *                 "bold" => true,
     *                 "size" => 24
     *             ]
     *         ]
     *     ],
     *     "rows" => [
     *         "4" => [
     *             "alignment" => [
     *                 "horizontal" => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
     *             ],
     *             "borders" => [
     *                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
     *             ]
     *         ]
     *     ],
     *     "cells" => [
     *         "J6" => [
     *             "font" => [
     *                 "bold" => true
     *             ], "borders" => [
     *                 'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK
     *             ]
     *         ]
     *     ]
     * ];
     * 
     * $option 
     * key yang diijinkan : columns,rows,cells
     * 
     * 1.columns
     * key yang dijinkan : nama kolom seperti A,B.C.dst / dapat menggunakan nama header : nama,NRP
     * 2.rows 
     * key yang diijinkan : nama rows seperti 1,2,3,4,dst
     * 3.cells
     * key yang diijinkan : nama koordinat seprti A1,B8,C9,dst atau range koordinat : A1:B9,C8:E10,dst
     * 
     * setelah key A,1,A5,nama dll
     * diikuti style yang akan diterapkan contoh
     * font" => [
     *    "bold" => true
     * ], "borders" => [
     *    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK
     * ]
     * 
     * lebih lengkap tentang style lihat di :
     * https://phpspreadsheet.readthedocs.io/en/latest/topics/recipes/#valid-array-keys-for-style-applyfromarray
     * lebih lengkap tentang numberformat lihat di :
     * https://scrutinizer-ci.com/g/PHPOffice/PhpSpreadsheet/code-structure/master/class/PhpOffice%5CPhpSpreadsheet%5CStyle%5CNumberFormat
     */
    public function set_data($data, $header = [], $option = [])
    {
        $this->header = $header;
        $this->data = $data;
        $this->option = $option;
        return $this;
    }

    private function make_table()
    {
        //cetak file ke excel
        $sheet = $this->spreadsheet->getActiveSheet();
        $column_count = count($this->data[0]);
        $row_count = count($this->data);

        $this->apply_style();

        $i = 1;

        if ($this->title) {
            // set title
            $start = "A1";
            $end = $this->alphabet[$column_count - 1] . "$i";
            $sheet->setCellValue("A$i", strtoupper($this->title));
            $sheet->mergeCells("$start:$end");
            $sheet->getStyle($start)->applyFromArray($this->title_style);

            $i++;
        }

        if ($this->header) {
            foreach ($this->header as $key => $value) {
                $alpha = $this->alphabet[$key];
                $sheet->setCellValue("$alpha$i", strtoupper($value));
            }
            // set header style
            $alpha = $this->alphabet[count($this->header) - 1] . "$i";
            $sheet->getStyle("A$i:$alpha")
                ->applyFromArray($this->table_header_style);

            $i++;
        }

        foreach ($this->data as $row) { //baca data sebagai baris

            $j = 0;
            foreach ($row as $cell) { //baca data sebagai sel
                $alpha = $this->alphabet[$j++];
                $sheet->getColumnDimension($alpha)->setAutoSize(true);
                $sheet->setCellValue("$alpha$i", $cell);
            }
            $i++;
        }

        // set body sytle
        $start = 'A' . ($i - $row_count);
        $end = $this->alphabet[$column_count - 1] . ($i - 1);

        $sheet->getStyle("$start:$end")
            ->applyFromArray($this->table_body_style);
    }

    public function download()
    {
        $this->make_table();
        // download file
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $this->filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $this->writer->save('php://output');
    }

    public function send_by_email($config = [])
    {
        $this->make_table();
        $this->writer->save($this->filename . '.xlsx');

        $this->email->initialize($config);

        $this->email->from($this->from, $this->as);
        $this->email->to($this->to);
        $this->email->subject($this->subject);
        $this->email->attach($this->filename . '.xlsx');
        $this->email->message($this->message);

        $result = $this->email->send();
        unlink($this->filename . '.xlsx');

        return $result;
    }

    public function get_email_debugger()
    {
        return $this->email->print_debugger();
    }

    /** 
     * allowed keys
     * 1.from (email asal/pengirim)
     * 2.to (email tujuan)
     * 3.as (alias)
     * 4.subject (subjek pengirim)
     * 5.message (pesan)
     */
    public function prepare_email($data = [])
    {
        $this->from = $data['from'];
        $this->to = $data['to'];
        $this->as = $data['as'];
        $this->subject = $data['subject'];
        $this->message = $data['message'];

        return $this;
    }


    private function apply_style()
    {
        $sheet = $this->spreadsheet->getActiveSheet();

        foreach ($this->option as $opt_key => $opt_v) {

            foreach ($opt_v as $key => $style) {

                if ($opt_key == "columns") {

                    if (in_array($key, $this->alphabet)) {
                        $sheet->getStyle("$key:$key")->applyFromArray($style);
                    } else {
                        $ind = array_search($key, $this->header);
                        $alpha = $this->alphabet[$ind];
                        $sheet->getStyle("$alpha:$alpha")->applyFromArray($style);
                    }
                } else if ($opt_key == "rows") {
                    $sheet->getStyle("$key:$key")->applyFromArray($style);
                } else if ($opt_key == "cells") {
                    $sheet->getStyle("$key")->applyFromArray($style);
                }
            }
        }
    }

    public function read($file)
    {
        $reader = new readxlsx();
        $reader->setReadDataOnly(true);

        $this->spreadsheet = $reader->load($file);
        $sheet = $this->spreadsheet->getActiveSheet();

        $this->data_result = $sheet->toArray();

        return $this->data_result;
    }
}
