<nav class="breadcrumb">
    <a href="<?php echo base_url(); ?>" title="Ir para o Início"><i class="fa fa-home"></i> Início</a>
    <a href="" title="Ir para Turmas" data-object="turma"> Turmas</a>
    <span class="active"><?php echo !isset($id) ? 'Novo' : 'Editar'; ?></span>
</nav>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?php echo !isset($id) ? '<i class="fa fa-plus"></i> Nova Turma' : '<i class="fa fa-pencil"></i> Editando Turma'; ?>
            </div>
            <div class="panel-body">
                <form class="form-horizontal">
                    <input type="hidden" id="id" name="turma[id]" value="<?php echo evaluate($id); ?>">
                    <div class="form-group">
                        <label for="descricao" class="control-label col-lg-2">Descrição</label>
                        <div class="col-lg-10">
                            <input type="text" id="descricao" name="turma[descricao]" class="form-control" autocomplete="off"
                                value="<?php echo evaluate($descricao); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="professor" class="control-label col-lg-2">Professor</label>
                        <div class="col-lg-10">
                            <select id="professor" name="usuarios[professor]" class="no-select2" style="width: 100%;">
                            <?php
                            if (!empty($id_professor)) {
                            ?>
                                <option value="<?php echo $id_professor; ?>" selected><?php echo $professor; ?></option>
                            <?php
                            }
                            ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="alunos" class="control-label col-lg-2">Alunos</label>
                        <div class="col-lg-10">
                            <select id="alunos" name="usuarios[alunos]" class="no-select2" style="width: 100%;" multiple>
                                <?php
                                foreach ($alunos as $aluno) {
                                ?>
                                    <option value="<?php echo $aluno['id']; ?>" selected><?php echo $aluno['nome']; ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ativa" class="control-label col-lg-2">Ativa</label>
                        <div class="col-lg-10">
                            <input type="checkbox" id="ativa" name="turma[ativa]" class="form-control"
                                <?php echo evaluate($ativa, 1) == 1 ? 'checked' : ''; ?>>
                        </div>
                    </div>
                    <div class="col-lg-offset-2 col-lg-10 form-buttons">
                        <button type="submit" class="btn btn-outline btn-primary"><i class="fa fa-save"></i> <span>Salvar</span></button>
                        <button type="button" class="btn btn-outline btn-danger cancelar"><i class="fa fa-ban"></i> <span>Cancelar</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
