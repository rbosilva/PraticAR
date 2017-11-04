<?php

/**
 * Description of Banco_Model
 *
 * @author rodrigobarbosa
 */
class Banco_Model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
        $this->db = $this->load->database('exercicios', true);
    }
    
    public function query($sql = null) {
        if (!empty($sql)) {
            $query = $this->db->query($sql);
            if (is_bool($query)) {
                if ($query) {
                    return $query;
                }
                $error = $this->db->error();
                $message = str_replace("Table 'aluno_praticar_exercicios.", "Table '", $error['message']);
                return $message;
            } else {
                return $query->result_array();
            }
        }
        return false;
    }
    
    public function get_tables() {
        $columns = array();
        $tables = $this->db->list_tables();
        foreach ($tables as $table) {
            $columns[$table] = $this->db->list_fields($table);
        }
        return $columns;
    }
    
    public function table_data($table = null) {
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
        $this->load->dbforge($this->db);
        return $this->dbforge->drop_table($table);
    }
    
}
