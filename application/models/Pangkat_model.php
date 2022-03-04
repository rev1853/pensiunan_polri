<?php
class Pangkat_model extends CI_Model
{
    private $table = "mspangkat";

    private function prepare()
    {
        $param = isset($_POST['search']) ? $_POST['search'] : '';
        $length = 5;
        $page = $_POST['page'] * $length;
        $this->db->select(['id', 'nama'])
            ->from($this->table)
            ->like('nama', $param)
            ->limit($length, $page);
    }

    public function get_all_data()
    {
        $this->prepare();
        return $this->db->get()->result();
    }

    public function get_data_count()
    {
        $this->prepare();
        return $this->db->count_all_results();
    }
}
