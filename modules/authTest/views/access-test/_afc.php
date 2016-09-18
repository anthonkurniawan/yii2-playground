 <?php
use yii\helpers\Html;
?>

Access Control Filter (ACF) is a simple authorization method that is best used by applications that only need some simple access control. As its name indicates,<br>
ACF is an action filter that can be attached to a controller or a module as a behavior. ACF will check a set of access rules to make sure the current user can access the requested action.

<div class="myBox" style="display: table; width:100%">
    <b class="pull-left">Access : </b> 
    <div class="btn-group btn-group-sm pull-left" style="margin-left:5px">
    <?php 
    echo Html::a('Guest User', ['access-test/guest-user'], ['class'=>'action_ajax btn btn-sm btn-primary']); 		
    echo Html::a('Authenticated User', ['access-test/authenticated-user'], ['class'=>'action_ajax btn btn-sm btn-primary']); 		
    echo Html::a('IPs User', ['access-test/ip-user'], ['class'=>'action_ajax btn btn-sm btn-primary']); 		
    ?>	
    </div>
</div>