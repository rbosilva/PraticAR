<?php

/**
 * Description of Turma_Model
 *
 * @author rodrigobarbosa
 */
class Turma_Model extends MY_Model {
    
    public function get_where($where = null, $order_by = null, $limit = null, $offset = null, $return_as_object = false) {
        $this->db->select("t.id, t.descricao, case when t.ativa then 'Sim' else 'Não' end as ativa, u.nome as usuario");
        $this->db->from($this->get_table() . ' t');
        $this->db->join('usuario_turma ut', 'ut.id_turma = t.id');
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
    
    public function get_first_by_aluno($where = null, $order_by = null, $return_as_object = false) {
        $return = $this->get_where($where, $order_by, null, null, $return_as_object);
        if (is_array($return)) {
            return reset($return);
        }
        return false;
    }
    
    public function count($where) {
        $this->db->from($this->get_table() . ' t');
        $this->db->join('usuario_turma ut', 'ut.id_turma = t.id');
        $this->db->join('usuarios u', 'u.id = ut.id_usuario and u.tipo = 1');
        if (!empty($where)) {
            $this->db->where($where);
        }
        return $this->db->count_all_results();
    }
    
}
