<div role="tabpanel" class="tab-pane <?php echo $sequencia == 1 ? 'active' : ''; ?>" id="tab-exercicio<?php echo $sequencia; ?>">
    <input type="hidden" class="tentativas" name="resposta[<?php echo $id; ?>][tentativas]"
        value="<?php echo evaluate($tentativas, 1); ?>">
    <nav class="breadcrumb" style="margin-top: -25px;">
        <span class="active">Exercício <?php echo $sequencia; ?></span>
    </nav>
    <div class="form-group">
        <label for="descricao<?php echo $id; ?>" class="control-label col-lg-2">Descrição do exercício</label>
        <div class="col-lg-10">
            <textarea id="descricao<?php echo $id; ?>" class="form-control" rows="2"
                style="resize: none; margin-top: 4px;" readonly><?php echo $descricao; ?></textarea>
        </div>
    </div>
    <div class="form-group">
        <label for="resposta<?php echo $id; ?>" class="control-label col-lg-2">Resposta</label>
        <div class="col-lg-10 operacoes">
            <?php
            $this->load->view('generic/ra/operations');
            ?>
            <br>
            <?php
            $this->load->view('generic/ra/operators');
            ?>
            <br>
            <textarea id="resposta<?php echo $id; ?>" name="resposta[<?php echo $id; ?>][ra]" class="form-control resposta" rows="2"
                style="resize: none; margin-top: 4px;" spellcheck="false" data-exercicio="<?php echo $id; ?>"
                placeholder="Digite aqui a expressão correspondente a sua resposta"><?php echo $resposta; ?></textarea>
            <label for="sql<?php echo $id; ?>" class="control-label">Expressão em SQL</label>
            <textarea id="sql<?php echo $id; ?>" name="resposta[<?php echo $id; ?>][sql]" tabindex="-1"
                class="form-control sql" rows="2" style="resize: none; margin-top: 4px;" spellcheck="false"
                placeholder='Digite uma consulta em "Resposta"' readonly><?php echo $resposta_sql; ?></textarea>
            <br>
            <button type="button" class="btn btn-outline btn-default visualizar-consulta"><i class="fa fa-play"></i> <span>Visualizar consulta</span></button>
            <button type="button" class="btn btn-outline btn-default visualizar-tabelas"><i class="fa fa-table"></i> <span>Visualizar tabelas</span></button>
        </div>
    </div>
    <div class="col-lg-offset-2 col-lg-10 form-buttons">
        <?php
        if ($sequencia > 1) {
        ?>
            <button type="button" class="btn btn-outline btn-primary exercicio-anterior"><i class="fa fa-arrow-left"></i> <span>Voltar</span></button>
        <?php
        }
        ?>
        <button type="button" class="btn btn-outline btn-primary proximo-exercicio"><i class="fa fa-arrow-right"></i> <span>Avançar</span></button>
        <button type="button" class="btn btn-outline btn-danger cancelar"><i class="fa fa-ban"></i> <span>Cancelar</span></button>
    </div>
</div>
