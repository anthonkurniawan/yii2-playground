<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use yii\bootstrap\Collapse;

$this->title = Yii::t('app', 'Authorization-test');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $this->registerCss('action_ajax{ text-decoration: none }'); ?>

<b> Current Active User : </b>
<?php
$user = Yii::$app->user->identity;  //print_r($user); die();
if($user){
    echo DetailView::widget([
            'model' => $user,
            'attributes' => [
                'id',
                'username', 'email', 'groups',
            ],
        ]);
}
else echo "Guest";
?>

<?php
echo Collapse::widget([
    'items' => [
        [
            'label' => 'AFC - Access Control Filter',
            'content' => $this->render('_afc'),
            'options' => [],
            'contentOptions' => ['class' => 'in']
        ],
        // if you want to swap out .panel-body with .list-group, you may use the following
        [
            'label' => 'RBAC - Role based access control',
            'content' => $this->render('_rbac'),
            'contentOptions' => [],
            'options' => [],
            'footer' => 'Footer' // the footer label in list-group
        ],
    ]
]);
 ?>
 
<div id="loading" class="loading box-load pull-left" style="margin:5px;width:15px; display:none">&nbsp;</div>
<b class="pull-left"> Result : </b>
<div id="result" class="cmd_line pull-right" style="width:90%; min-height:35px;"></div>

<?php
$this->registerJs("
	$('body').on('click', '.action_ajax', function(){	
		$.ajax({
			type: 'GET',
			url: $(this).attr('href'),
			cache: false,
			beforesend: function(xhr){  
				$('#result').html('');
				$('#loading').show(); 
			},
			success: function (data, status, xhr) { 	// alert(data);  
				$('#result').html( data );
			},
			error: function (xhr, status, errorThrown) {	//alert(xhr);
				$('#result').html( xhr.responseText +' ( '+ status +' '+xhr.status+' - '+ errorThrown+')' );
			},
			complete: function(){ $('.loading').hide() }
		}); 
		return false;
	});"
    ,$this::POS_END   //the position of the JavaScript code (  POST_END "click_function dan "own  define function" ok )
);
?>