<?php

/**
 * Description of Usuario_Turma_Model
 *
 * @author rodrigobarbosa
 */
class Usuario_Turma_Model extends MY_Model {
    
    protected $system = true;
    
    public function __construct() {
        $this->set_table('usuario_turma');
        parent::__construct();
    }
    
    public function get_where($where = null, $order_by = null, $limit = null, $offset = null, $return_as_object = false) {
        $this->db->select('ut.id_turma as turma, u.id as usuario, u.nome, u.tipo');
        $this->db->from($this->get_table() . ' ut');
        $this->db->join('usuarios u', 'u.id = ut.id_usuario');
        if (!empty($where)) {
            $this->db->where($where);
        }
        if (!empty($order_by)) {
            $this->db->order_by($order_by);
        }
        if (!empty($limit)) {
            if (!empty($offset)) {
                $this->db->limit($limit, $offset);
            } else {
                $this->db->limit($limit);
            }
        }
        $query = $this->db->get();
        if ($query) {
            if ($return_as_object) {
                return $query->result();
            } else {
                return $query->result_array();
            }
        }
        return false;
    }
    
}
