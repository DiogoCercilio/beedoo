<?php
class Group_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get_last_ten_entries()
    {
        $query = $this->db->get('entries', 10);
        return $query->result();
    }

    public function insert_entry()
    {
        $this->title    = $_POST['title'];
        $this->content  = $_POST['content'];
        $this->date     = time();

        $this->db->insert('entries', $this);
    }

    /**
     * Get all purchase info about each product registered on the system
     * such as Buyer, purchase datetime and etc.
     */
    public function getDatatablesList($limit = null, $offset = 0)
    {
        $orderable = [
            'id' => 'id',
            'name' => 'name',
            'treated_datetime' => 'created_at'
        ];

        $query = $this->db
            ->select('SQL_CALC_FOUND_ROWS G.id, 
                        G.id, 
                        name, 
                        DATE_FORMAT(G.created_at, \'%d/%m/%Y %H:%i\') as treated_datetime,'
                , false)
            ->from('groups AS G');

        if ( $limit > 0 ) {
            $query
                ->limit($limit)
                ->offset($offset);
        }

        $this->datatablesQuery($query, [], $orderable);
        $result = $query->get()->result();
        $foundRows = $this->db->select('FOUND_ROWS() as found_rows')->get()->result_array()[0]['found_rows'];

        return ['foundRows' => $foundRows, 'data' => $result];
    }
}