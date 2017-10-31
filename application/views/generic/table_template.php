<table class="table table-striped table-bordered table-hover no-datatables" style="width: 100%">
    <thead>
        <tr>
            <?php
            echo '<th>' . implode('</th><th>', $columns) . '</th>';
            ?>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach (evaluate($data, array()) as $index => $value) {
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
