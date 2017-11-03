<nav class="breadcrumb">
    <a href="<?php echo base_url(); ?>" title="Ir para o Início"><i class="fa fa-home"></i> Início</a>
    <a href="" title="Ir para Minhas turmas" data-object="cadastro_exercicios"> Minhas turmas</a>
    <a href="" title="Ir para Consulta de questionários" data-object="cadastro_exercicios/list_lists/<?php echo $turma['id']; ?>">Visualizar questionários</a>
    <span class="active">Visualizar entregas</span>
</nav>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="ellipsis">
                    <i class="fa fa-th"></i> Visualizando entregas do questionário "<?php echo $lista['titulo']; ?>" da turma "<?php echo $turma['descricao']; ?>"
                </div>
                <button class="btn btn-outline btn-primary voltar"><i class="fa fa-arrow-left"></i> <span>Voltar</span></button>
            </div>
            <div class="panel-body">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th width="100">Data de Entr.</th>
                            <th width="100">Hora de Entr.</th>
                            <th width="30" class="no-sort no-print">Detalhes</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
