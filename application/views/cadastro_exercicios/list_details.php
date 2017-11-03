<nav class="breadcrumb">
    <a href="<?php echo base_url(); ?>" title="Ir para o Início"><i class="fa fa-home"></i> Início</a>
    <a href="" title="Ir para Minhas turmas" data-object="cadastro_exercicios"> Minhas turmas</a>
    <a href="" title="Ir para Consulta de questionários" data-object="cadastro_exercicios/list_lists/<?php echo $turma; ?>">Visualizar questionários</a>
    <a href="" title="Ir para Visualização de entregas" data-object="cadastro_exercicios/list_deliveries/<?php echo $turma; ?>/<?php echo $lista['id']; ?>">Visualizar entregas</a>
    <span class="active">Visualizar detalhes</span>
</nav>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="ellipsis">
                    <i class="fa fa-th"></i> Visualizando respostas do aluno "<?php echo $aluno['nome']; ?>" para o questionário "<?php echo $lista['titulo']; ?>"
                </div>
                <button class="btn btn-outline btn-primary voltar"><i class="fa fa-arrow-left"></i> <span>Voltar</span></button>
            </div>
            <div class="panel-body">
                <div  style="height: 450px; width: 100%; overflow-y: auto;">
                    <table class="table table-striped table-bordered table-hover no-datatables">
                        <thead>
                            <tr>
                                <th width="50">Sequência</th>
                                <th>Descrição</th>
                                <th width="250">Resposta do Professor</th>
                                <th width="250">Resposta do Aluno</th>
                                <th width="50">Tentativas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($data as $row) {
                                $this->load->view('cadastro_exercicios/row_details', $row);
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
