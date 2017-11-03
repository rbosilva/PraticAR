<?php

/**
 * Description of Resposta_Model
 *
 * @author rodrigobarbosa
 */
class Resposta_Model extends MY_Model {
    
    protected $system = true;
    
    public function get_answers_by_aluno($lista, $aluno) {
        $this->db->select(array(
            '(@row_number := @row_number + 1) as sequencia',
            'e.descricao',
            'e.resposta as resposta_professor',
            'r.resposta',
            'r.tentativas'
        ));
        $this->db->from('exercicios e');
        $this->db->join('respostas r', "e.id = r.exercicio and r.aluno = $aluno", 'left');
        $this->db->join('(select @row_number := 0) as gambi', '1 = 1');
        $this->db->where("e.lista = $lista");
        $this->db->order_by('e.id');
        $query = $this->db->get();
        if ($query) {
            return $query->result_array();
        }
        return false;
    }
    
    public function count_answers_by_aluno($aluno, $where = null) {
        $this->db->from('exercicios e');
        $this->db->join('respostas r', "e.id = r.exercicio and r.aluno = $aluno", 'left');
        if (!empty($where)) {
            $this->db->where($where);
        }
        return $this->db->count_all_results();
    }
    
}
