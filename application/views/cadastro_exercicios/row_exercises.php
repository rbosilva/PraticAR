<tr data-id="<?php echo $exercicio['id']; ?>"
    data-sequencia="<?php echo $exercicio['sequencia']; ?>"
    data-descricao="<?php echo $exercicio['descricao']; ?>"
    data-resposta="<?php echo $exercicio['resposta']; ?>"
    data-resposta_sql="<?php echo $exercicio['resposta_sql']; ?>"
    data-excluido="false">
    <td class="text-right" width="80"><?php echo $exercicio['sequencia']; ?></td>
    <td title="<?php echo $exercicio['descricao']; ?>" style="max-width: 350px;"><?php echo $exercicio['descricao']; ?></td>
    <td title="<?php echo $exercicio['resposta']; ?>" style="max-width: 350px;"><?php echo $exercicio['resposta']; ?></td>
    <td class="text-center" width="40">
    <?php
    if ($exercicio['id'] != 0) {
    ?>
        <a href="#" title="Editar Exercício" class="editar"><i class="fa fa-pencil"></i></a>
    <?php
    }
    ?>
    </td>
    <td class="text-center" width="40"><a href="#" title="Excluir Exercício" class="excluir"><i class="fa fa-remove"></i></a></td>
</tr>
