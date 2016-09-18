<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$view = ($type !=='Rule') ?
    # VIEW ITEMS (ROLE, PERMIISSION)
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'type',
            'description:ntext',
            'ruleName',
            'data:ntext',
            'createdAt:datetime',
            'updatedAt:datetime',
            [                      // the owner name of the model
                'label' => 'Children',
                'value' => $this->context->getChildren($model->name),
            ],
        ],
    ])
    : 
    # VIEW RULE
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'createdAt:datetime',
            'updatedAt:datetime',
        ],
    ]);
    
 echo $view;
?>