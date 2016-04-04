<?php
use Cake\Utility\Inflector;
?>

<?php if (!empty($entities)) : ?>
<div class="row">
    <div class="col-xs-12">
        <h3><strong><?= Inflector::humanize($this->request->params['pass'][0]); ?></strong> <?= __('search results'); ?>:</h3>
        <table class="table table-hover">
            <thead>
                <tr>
                <?php foreach ($fields as $field) : ?>
                    <th><?= $this->Paginator->sort($field); ?></th>
                <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($entities as $entity) : ?>
                <tr>
                    <?php foreach ($fields as $field) : ?>
                        <td><?= $entity->{$field} ?></td>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>