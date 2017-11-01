<?php

/**
 * Description of Lista_Model
 *
 * @author rodrigobarbosa
 */
class Lista_Model extends MY_Model {
    
    protected $system = true;
    
    public function get_listas_by_aluno($where = null, $order_by = null, $limit = null, $offset = null) {
        $this->db->select(array(
            'l.*',
            '(select concat(date_format(data, \'%d/%m/%Y\'), \' \', hora)
              from respostas
              where exercicio = (select id
                                 from exercicios
                                 where lista = l.id
                                 limit 1)
                    and aluno = ut.id_usuario
              limit 1) as respondido_em'
        ));
        $this->db->from('listas l');
        $this->db->join('turmas t', 't.id = l.turma');
        $this->db->join('usuario_turma ut', 'ut.id_turma = t.id');
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
            return $query->result_array();
        }
        return false;
    }
    
    public function count_by_aluno($where) {
        $this->db->from('listas l');
        $this->db->join('turmas t', 't.id = l.turma');
        $this->db->join('usuario_turma ut', 'ut.id_turma = t.id');
        if (!empty($where)) {
            $this->db->where($where);
        }
        return $this->db->count_all_results();
    }
    
}
