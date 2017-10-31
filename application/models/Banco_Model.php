<?php

/**
 * Description of Banco_Model
 *
 * @author rodrigobarbosa
 */
class Banco_Model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
//        $this->db->db_select('aluno_praticar_exercicios');
    }
    
    public function query($sql = null) {
        $this->db->db_select('aluno_praticar_exercicios');
        if (!empty($sql)) {
            $query = $this->db->query($sql);
            if (is_bool($query)) {
                return $query;
            } else {
                return $query->result_array();
            }
        }
        return false;
    }
    
    public function get_tables() {
        $this->db->db_select('aluno_praticar_exercicios');
        $columns = array();
        $tables = $this->db->list_tables();
        foreach ($tables as $table) {
            $columns[$table] = $this->db->list_fields($table);
        }
        return $columns;
    }
    
    public function table_data($table = null) {
        $this->db->db_select('aluno_praticar_exercicios');
        if (!empty($table)) {
            $columns = $this->db->list_fields($table);
            $data = $this->db->from($table)->get()->result_array();
            return array(
                'columns' => $columns,
                'data' => $data
            );
        }
        return false;
    }
    
    public function drop_table($table = null) {
        $this->db->db_select('aluno_praticar_exercicios');
        $this->load->dbforge();
        return $this->dbforge->drop_table($table);
    }
    
}
