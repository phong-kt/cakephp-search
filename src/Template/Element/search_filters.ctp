<?php if (!empty($searchFields) && !empty($searchOperators)) : ?>

    <?= $this->Html->css('Search.search', ['block' => true]); ?>
    <?= $this->Html->script('Search.search', ['block' => 'scriptBottom']); ?>

    <?= $this->Html->scriptBlock(
        'search.setFieldTypeOperators(' . json_encode($searchOperators) . ');',
        ['block' => 'scriptBottom']
    ); ?>

    <?= $this->Html->scriptBlock(
        'search.setFieldProperties(' . json_encode($searchFields) . ');',
        ['block' => 'scriptBottom']
    ); ?>
    <?php
    if (isset($this->request->data['criteria'])) {
        echo $this->Html->scriptBlock(
            'search.generateCriteriaFields(' . json_encode($this->request->data['criteria']) . ');',
            ['block' => 'scriptBottom']
        );
    }
    ?>
<div class="well">
    <h4><?= __('Search Filters') ?></h4>
    <hr />
    <div class="row">
        <div class="col-md-4 col-md-push-8 col-lg-3 col-lg-push-9">
            <?= $this->Form->label(__('Add Filter')) ?>
            <?php
            $selectOptions = array_combine(
                array_keys($searchFields),
                array_map(function ($v) { return $v['label']; }, $searchFields)
            );
            echo $this->Form->select(
                'fields',
                 $selectOptions, [
                    'class' => 'form-control input-sm',
                    'id' => 'addFilter',
                    'empty' => true
                ]
            ) ?>
        </div>
        <?= $this->Form->create(null, [
            'id' => 'SearchFilterForm',
            'url' => [
                'plugin' => $this->request->plugin,
                'controller' => $this->request->controller,
                'action' => 'search'
            ]
        ]) ?>
        <hr class="visible-xs visible-sm" />
        <div class="col-md-8 col-md-pull-4 col-lg-9 col-lg-pull-3">
            <fieldset></fieldset>
        </div>
    </div>
    <h4><?= __('Options') ?></h4>
    <hr />
    <div class="row">
        <div class="col-md-8 col-lg-9">
            <?= $this->element('Search.search_options'); ?>
            <?= $this->Form->button(__('Search'), ['class' => 'btn btn-primary']) ?>
            <?= $this->Form->end() ?>
        </div>
        <div class="col-md-4 col-lg-3">
            <div class="row">
                <div class="col-sm-6 col-md-12">
                    <?= $this->Form->label(__('Save search')) ?>
                </div>
                <div class="col-sm-6 col-md-12">
                    <?= $this->element('Search.save_search_criterias'); ?>
                    <?= $this->element('Search.save_search_results'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
