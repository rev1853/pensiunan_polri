<?php
class User_model extends CI_Model
{
    protected $table = 'msuser';
    private $select_column = [
        'user.id as id',
        'user.nama as nama',
        'user.email as email',
        'user.username as username',
        'user.role as role',
        'user.no_telp as telepon',
        'kes.nama as kesatuan',
        'pang.nama as pangkat'
    ];
    // kolom yg diijinkan untuk dijadikan ORDER BY 
    private $order_column = [null, 'nama', 'email', 'username', 'role', 'telepon', 'kesatuan', 'pangkat'];

    // menyiapkan data yang akan ditampilkan di tabel pensuiunan dan di cetak di excel
    private function _prepare_datatable()
    {
        $data = $this->input->post('mydata');

        $this->db
            ->select($this->select_column)
            ->from($this->table . ' user')
            ->join('mspangkat as pang', 'user.pangkatid = pang.id', 'left')
            ->join('mskesatuan as kes', 'user.kesatuanid = kes.id', 'left');

        if ($this->input->post('search')) {

            $keyword = $this->input->post('search')["value"];

            $this->db
                ->group_start()
                ->like('user.nama', $keyword)
                ->or_like('user.email', $keyword)
                ->or_like('user.username', $keyword)
                ->or_like('user.role', $keyword)
                ->or_like('user.no_telp', $keyword)
                ->or_like('kes.nama', $keyword)
                ->or_like('pang.nama', $keyword)
                ->group_end();
        }

        if (isset($data['kesatuan'])) {

            $kesatuan = $data['kesatuan'];

            $this->db->group_start()
                ->like('kes.nama', $kesatuan)
                ->group_end();
        }

        if (isset($data['pangkat'])) {

            $pangkat = $data['pangkat'];

            $this->db->group_start()
                ->like('pang.nama', $pangkat)
                ->group_end();
        }

        // Jika ada data order , urutkan data dari kolom yg tersedia
        if ($this->input->post('order')) {
            $order = $this->input->post('order')[0];
            $this->db->order_by($this->order_column[$order['column']], $order['dir']);
        } else {
            $this->db->order_by('user.id', 'DESC');
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
