<table class="table table-striped table-bordered table-hover no-datatables" style="width: 100%">
    <thead>
        <tr>
            <th>Tabelas</th>
            <th>Colunas</th>
            <?php
            if (!empty($acoes)) {
            ?>
                <th width="50">Ações</th>
            <?php
            }
            ?>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach (evaluate($tables, array()) as $table => $columns) {
        ?>
        <tr data-table="<?php echo $table; ?>">
            <td><a href="#" title="<?php echo evaluate($title, 'Visualizar dados'); ?>" class="visualizar"><?php echo $table; ?></a></td>
            <td><?php echo implode(', ', $columns); ?></td>
            <?php
            if (!empty($acoes)) {
            ?>
                <td style="text-align: center;"><a href="#" title="Excluir Tabela" class="excluir"><i class="fa fa-remove"></i></a></td>
            <?php
            }
            ?>
        </tr>
        <?php
        }
        ?>
    </tbody>
</table>
