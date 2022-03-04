<?php
class Pensiun_model extends CI_Model
{
    protected $table = 'pensiun';
    private $select_column = [
        'pers.nrp as nrp',
        'pers.nama as nama',
        'pers.jabatan as jabatan',
        'pang.nama as pangkat',
        'kes.nama as kesatuan',
        'pen.gaji_pensiun as gaji',
        'pen.tglpensiun as tglpensiun'
    ];
    // kolom yg diijinkan untuk dijadikan ORDER BY 
    private $order_column = [null, 'nrp', 'nama', 'jabatan', 'pangkat', 'kesatuan', 'gaji', 'tglpensiun'];

    // menyiapkan data yang akan ditampilkan di tabel pensuiunan dan di cetak di excel
    private function _prepare_datatable()
    {
        $data = $this->input->post('mydata');

        $this->db
            ->select($this->select_column)
            ->from($this->table . ' pen')
            ->join('mspersonil pers', 'pers.id = pen.personilid', 'left')
            ->join('mspangkat as pang', 'pers.pangkatid = pang.id', 'left')
            ->join('mskesatuan as kes', 'pers.kesatuanid = kes.id', 'left');

        if ($this->input->post('search')) {

            $keyword = $this->input->post('search')["value"];

            $this->db
                ->group_start()
                ->like('nrp', $keyword)
                ->or_like('pers.nama', $keyword)
                ->or_like('kes.nama', $keyword)
                ->or_like('pang.nama', $keyword)
                ->or_like('pers.jabatan', $keyword)
                ->or_like('pen.gaji_pensiun', $keyword)
                ->group_end();
        }

        if (isset($data['tglpensiun'])) {

            $start = $data['tglpensiun']['start'];
            $end = $data['tglpensiun']['end'];

            $this->db
                ->group_start()
                ->where('pen.tglpensiun > ', $start)
                ->where('pen.tglpensiun < ', $end)
                ->group_end();
        }

        if (isset($data['kesatuan'])) {

            $kesatuan = $data['kesatuan'];

            $this->db->group_start()
                ->like('kes.nama', $kesatuan)
                ->group_end();
        }

        // Jika ada data order , urutkan data dari kolom yg tersedia
        if ($this->input->post('order')) {
            $order = $this->input->post('order')[0];
            $this->db->order_by($this->order_column[$order['column']], $order['dir']);
        } else {
            $this->db->order_by('pen.id', 'DESC');
        }
    }

    public function get_datatable()
    {
        $this->_prepare_datatable();

        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }

        return $this->db->get()->result();
    }

    public function get_filtered_data()
    {
        $this->_prepare_datatable();
        return $this->db->get()->num_rows();
    }

    public function get_all_data()
    {
        return $this->db->select('*')
            ->from($this->table)
            ->count_all_results();
    }

    public function get_data()
    {
        $this->_prepare_datatable();
        return $this->db->get()->result_array();
    }
}
