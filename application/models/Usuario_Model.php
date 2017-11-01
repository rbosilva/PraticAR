<?php

/**
 * Description of Usuario_Model
 *
 * @author rodrigobarbosa
 */
class Usuario_Model extends MY_Model {
    
    protected $system = true;
    
    public function __construct() {
        parent::__construct();
        $this->set_rules(array(
            array(
                'field' => 'id',
                'label' => 'ID',
                'rules' => 'integer|max_length[11]'
            ),
            array(
                'field' => 'nome',
                'label' => 'Nome',
                'rules' => 'required'
            ),
            array(
                'field' => 'login',
                'label' => 'Login',
                'rules' => 'required|max_length[20]'
            ),
            array(
                'field' => 'senha',
                'label' => 'Senha',
                'rules' => array(
                    array(
                        'check_password',
                        function ($senha) {
                            if (!is_numeric($this->input->post('id', true))) {
                                $this->form_validation->set_message('check_password', 'O campo Senha é obrigatório.');
                                return $this->form_validation->required($senha);
                            }
                        }
                    )
                )
            )
        ));
    }
    
    /**
     * Retorna informações de um usuário selecionado pelo seu login
     * @param string $login
     * @return array
     */
    public function get_user($login) {
        $this->db->where('login', $login);
        return $this->db->get($this->get_table())->row_array();
    }
    
    /**
     * Seta o número de tentativas de login
     * @param int $user_id O id do usuário
     * @param int $attempts O número de tentativas
     * @param float $time_locked O tempo de bloqueio restante (em segundos)
     */
    public function set_attempts($user_id, $attempts, $time_locked) {
        $this->db->set('tentativas', $attempts);
        $this->db->set('bloqueado', $time_locked);
        $this->db->where('id', $user_id);
        $this->db->update($this->get_table());
    }
    
    public function get_users_with_lists($where = null, $order_by = null, $limit = null, $offset = null) {
        $this->db->select(array(
            'u.id',
            'u.nome',
            '(select data
              from respostas
              where exercicio in (select id
                                  from exercicios
                                  where lista = l.id)
                    and aluno = u.id
              limit 1) as data',
            '(select hora
              from respostas
              where exercicio in (select id
                                  from exercicios
                                  where lista = l.id)
                    and aluno = u.id
              limit 1) as hora'
        ));
        $this->db->from('usuarios u');
        $this->db->join('usuario_turma ut', 'ut.id_usuario = u.id', 'left');
        $this->db->join('listas l', 'l.turma = ut.id_turma', 'left');
        if (!empty($where)) {
            $this->db->where($where);
        }
        if (!empty($order_by)) {
            $this->db->order_by($order_by);
        }
        $this->db->group_by('u.id');
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
    
    public function count_users_with_lists($where = null) {
        $this->db->from('usuarios u');
        $this->db->join('usuario_turma ut', 'ut.id_usuario = u.id');
        $this->db->join('listas l', 'l.turma = ut.id_turma');
        if (!empty($where)) {
            $this->db->where($where);
        }
        return $this->db->count_all_results();
    }
    
}
