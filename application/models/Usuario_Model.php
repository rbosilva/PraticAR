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
    
}
