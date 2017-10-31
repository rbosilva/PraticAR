<?php
if (empty($results)) {
    echo 'A consulta não retornou resultados.';
} else {
?>
<table class="table table-striped table-bordered table-hover no-datatables" style="width: 100%">
    <thead>
        <tr>
            <?php
            echo '<th>' . implode('</th><th>', array_keys($results[0])) . '</th>';
            ?>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($results as $index => $value) {
        ?>
            <tr>
            <?php
            foreach ($value as $value) {
            ?>
                <td><?php echo $value; ?></td>
            <?php
            }
            ?>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>
<?php
}
