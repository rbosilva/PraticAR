<nav class="breadcrumb">
    <a href="<?php echo base_url(); ?>" title="Ir para o Início"><i class="fa fa-home"></i> Início</a>
    <a href="" title="Ir para Minhas turmas" data-object="cadastro_exercicios"> Minhas turmas</a>
    <a href="" title="Ir para Consulta de questionários" data-object="cadastro_exercicios/list_lists/<?php echo $turma['id']; ?>">Visualizar questionários</a>
    <a href="" title="Ir para Cadastro de questionário"><?php echo empty($lista['id']) ? 'Novo' : 'Editar'; ?></a>
    <span class="active"><?php echo empty($exercicio['id']) ? 'Novo Exercício' : 'Editar Exercício'; ?></span>
</nav>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?php
                echo empty($exercicio['id']) ?
                    '<i class="fa fa-plus"></i> Novo exercício para o questionário "' . evaluate($lista['titulo']) . '" da turma "' . evaluate($turma['descricao']) . '"' :
                    '<i class="fa fa-pencil"></i> Editando exercício do questionário "' . evaluate($lista['titulo']) . '" da turma "' . evaluate($turma['descricao']) . '"';
                ?>
            </div>
            <div class="panel-body">
                <input type="hidden" id="id-exercicio" name="exercicio[id]" value="<?php echo evaluate($exercicio['id']); ?>">
                <input type="hidden" id="sequencia" value="<?php echo evaluate($exercicio['sequencia']); ?>">
                <input type="hidden" name="exercicio[lista]" value="<?php echo evaluate($lista['id']); ?>">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#tab-exercicio" data-toggle="tab">Informações do Exercício</a></li>
                    <li role="presentation"><a href="#tab-tabelas" data-toggle="tab">Tabelas</a></li>
                    <li role="presentation"><a href="#tab-resultados" data-toggle="tab">Resultados</a></li>
                </ul>
                <div class="tab-content" style="margin-top: 15px;">
                    <div role="tabpanel" class="tab-pane active" id="tab-exercicio">
                        <div class="form-group">
                            <label for="descricao" class="control-label col-lg-2">Descrição</label>
                            <div class="col-lg-10">
                                <textarea type="text" id="descricao" name="exercicio[descricao]" class="form-control" rows="4"
                                    placeholder="Digite aqui o que o aluno deve fazer neste exercício"><?php echo evaluate($exercicio['descricao']); ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="resposta" class="control-label col-lg-2">Resposta</label>
                            <div class="col-lg-10 operacoes">
                                <?php
                                $this->load->view('generic/ra/operations');
                                ?>
                                <br>
                                <?php
                                $this->load->view('generic/ra/operators');
                                ?>
                                <br>
                                <textarea id="resposta" name="exercicio[resposta]" class="form-control" rows="4"
                                    style="resize: none; margin-top: 4px;" spellcheck="false"
                                    placeholder="Digite aqui a expressão correspondente a resposta deste exercício"><?php echo evaluate($exercicio['resposta']); ?></textarea>
                                <label for="sql" class="control-label">Expressão em SQL</label>
                                <textarea tabindex="-1" id="sql" class="form-control" rows="4" style="resize: none; margin-top: 4px;"
                                    spellcheck="false" placeholder='Digite uma consulta em "Resposta"' readonly><?php echo evaluate($exercicio['resposta_sql']); ?></textarea>
                            </div>
                        </div>
                        <div class="col-lg-offset-2 col-lg-10 form-buttons">
                            <button type="submit" class="btn btn-outline btn-primary salvar-exercicio"><i class="fa fa-save"></i> <span>Salvar</span></button>
                            <button type="button" class="btn btn-outline btn-danger cancelar"><i class="fa fa-ban"></i> <span>Cancelar</span></button>
                            <button type="button" class="btn btn-outline btn-default pull-right visualizar-consulta"><i class="fa fa-play"></i> <span>Visualizar consulta</span></button>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tab-tabelas" style="height: 450px; overflow: auto;">
                        <?php
                        $this->load->view('generic/tables', array('tables' => $tables));
                        ?>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tab-resultados" style="height: 450px; overflow: auto;">
                        Execute uma consulta para checar os resultados!
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
