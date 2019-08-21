<?php
class Posts_model extends CI_Model {

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
        $this->title    = $_POST['title']; // please read the below note
        $this->content  = $_POST['content'];
        $this->date     = time();

        $this->db->insert('entries', $this);
    }

    public function update_entry()
    {
        $this->title    = $_POST['title'];
        $this->content  = $_POST['content'];
        $this->date     = time();

        $this->db->update('entries', $this, array('id' => $_POST['id']));
    }

    /**
     * Get all purchase info about each product registered on the system
     * such as Buyer, purchase datetime and etc.
     */
    public function getDatatablesList($limit = null, $offset = 0)
    {
        $orderable = [
            'id' => 'id',
            'title' => 'title',
            'treated_datetime' => 'P.created_at'
        ];

        // Ajustei a query para retornar o total de posts (500). Removi os Joins que estavam trazendo mais informacoes das outras tabelas, 
        // mas acho que não era isso o esperado... Não entendi muito bem qual deveria ser o requisito aqui...
        $query = $this->db
            ->select('SQL_CALC_FOUND_ROWS P.id, 
                        P.id, 
                        title, 
                        DATE_FORMAT(P.created_at, \'%d/%m/%Y %H:%i\') as treated_datetime,'
                , false)
            ->join('users AS U', 'P.user_id = U.id', 'inner')
            ->from('posts AS P');

        //Ao filtrar por "todos" no datatables, ele envia -1
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