<form class="form-horizontal">
    <div id="informacoes-lista">
        <nav class="breadcrumb">
            <a href="<?php echo base_url(); ?>" title="Ir para o Início"><i class="fa fa-home"></i> Início</a>
            <a href="" title="Ir para Minhas turmas" data-object="cadastro_exercicios"> Minhas turmas</a>
            <a href="" title="Ir para Consulta de questionários" data-object="cadastro_exercicios/list_lists/<?php echo $turma['id']; ?>">Visualizar questionários</a>
            <span class="active"><?php echo !isset($lista['id']) ? 'Novo' : 'Editar'; ?></span>
        </nav>
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?php
                        echo !isset($lista['id']) ?
                            '<i class="fa fa-plus"></i> Novo questionário para turma "' . evaluate($turma['descricao']) . '"' :
                            '<i class="fa fa-pencil"></i> Editando questionário da turma "' . evaluate($turma['descricao']) . '"'; ?>
                    </div>
                    <div class="panel-body">
                        <input type="hidden" id="id-lista" name="lista[id]" value="<?php echo evaluate($lista['id']); ?>">
                        <input type="hidden" name="lista[turma]" value="<?php echo $turma['id']; ?>">
                        <div class="form-group">
                            <label for="titulo" class="control-label col-lg-2">Título</label>
                            <div class="col-lg-10">
                                <input type="text" id="titulo" name="lista[titulo]" class="form-control"
                                    placeholder="Digite aqui um título para esta lista de exercícios" value="<?php echo evaluate($lista['titulo']); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="data_prazo" class="control-label col-lg-2">Data de Entrega</label>
                            <div class="col-lg-10">
                                <input type="text" id="data_prazo" name="lista[data_prazo]" class="form-control"
                                    data-mask="00/00/0000" data-date-format="dd/mm/yyyy"
                                    value="<?php echo evaluate($lista['data_prazo'], date('d/m/Y', strtotime(date('Y-m-d') . ' +7 days'))); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="hora_prazo" class="control-label col-lg-2">Hora de Entrega</label>
                            <div class="col-lg-10">
                                <input type="text" id="hora_prazo" name="lista[hora_prazo]" class="form-control clockpicker"
                                    data-mask="00:00" value="<?php echo evaluate($lista['hora_prazo'], '22:15'); ?>">
                            </div>
                        </div>
                        <div class="col-lg-offset-2 col-lg-10 form-buttons">
                            <button type="submit" class="btn btn-outline btn-primary"><i class="fa fa-save"></i> <span>Salvar</span></button>
                            <button type="button" class="btn btn-outline btn-danger cancelar"><i class="fa fa-ban"></i> <span>Cancelar</span></button>
                            <button type="button" class="btn btn-outline btn-primary adicionar-exercicio pull-right"><i class="fa fa-plus"></i> <span>Novo Exercício</span></button>
                        </div>
                        <div style="width: 100%; height: 350px; overflow-y: auto; float: left; margin-top: 10px;">
                            <table class="table table-striped table-bordered table-hover no-datatables">
                                <thead>
                                    <tr>
                                        <th width="80">Exercício</th>
                                        <th>Descrição</th>
                                        <th>Resposta</th>
                                        <th colspan="2" class="no-sort no-print text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sequencia = 1;
                                    foreach ($exercicios as $exercicio) {
                                        $exercicio['sequencia'] = $sequencia++;
                                        $this->load->view('cadastro_exercicios/row_exercises', array('exercicio' => $exercicio));
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="informacoes-exercicio" style="display: none;"></div>
</form>
