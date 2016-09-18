<?php 
use yii\helpers\Html; 
use yii\bootstrap\Tabs;

$this->title = Yii::t('app', $this->context->module->id);
?>

<h2 class="muted"><?= $this->context->module->id ?></h2>

<!----- FORM SAVE CODE --->  
<?= $this->render('modal');?>

<?php
echo Tabs::widget([
    'id'=>'tab-auth',
    'items' => [
        [
            'label' => 'User Assignment',
            'content' => 'Loading..',
            'active' => false,
            //'headerOptions' => [...],
            'options' => ['id' => 'user-list', 'style'=>'padding-top:10px'],
        ],
        [
            'label' => 'Auth Items',
            'content' => 'Loading..',
            //'active' => true,
            'options' => ['id' =>'auth-list', 'style'=>'padding-top:10px'],
            //'linkOptions'=>['onclick'=>'loadTab();'],
        ],
        
    ],

    'clientEvents'=>[
        'show.bs.tab' => 'function (e) { console.log("tab on show : ", e); 
            loadTab(e.target.hash);
        }',
        'shown.bs.tab' => 'function (e) { console.log("tab on shown : "); }',
        'hide.bs.tab' => 'function (e) { console.log("tab on hide : "); }',
        'hidden.bs.tab'=> 'function (e) { console.log("tab on hidden : "); 

        }',
    ],
]);
 ?>

<pre class="alert-danger" style="margin-top:5px">
    <strong>NOTE : </strong>
    When remove rule item auth file in PhpManager not save old atrribute "ruleName"
    solution :
    add "$this->saveItems();" in line 553
    When remove role, assignment on user not save
    add -> "$this->saveAssignments();" on line 330
</pre>

<div class="auth-default-index">
    <h2><?= $this->context->action->uniqueId ?></h2>
    <p>
        This is the view content for action "<?= $this->context->action->id ?>".
        The action belongs to the controller "<?= get_class($this->context) ?>"
        in the "<?= $this->context->module->id ?>" module.
    </p>
    <p>
        You may customize this page by editing the following file: <code><?= __FILE__ ?></code>
    </p>
</div>

<?php
$this->registerJs("
    $('document').ready(function(){ 
        var target = $('#tab-auth li.active a').attr('href');  console.log(target);
        loadTab(target);
    });    
   
   function loadTab(target){    
        var url = (target=='#auth-list') ? './authTest/load-auth' : './authTest/load-user';
        $(target).load(url);
    };
   ",  \yii\web\View::POS_END);
?>
