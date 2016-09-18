 <?php
use yii\helpers\Html;
?>

<h2>Role based access control (RBAC)</h2>
Role-Based Access Control (RBAC) provides a simple yet powerful centralized access control.


<div class="myBox" style="display: table; width:100%">
    <b class="pull-left">Permission Access : </b> 
    <div class="btn-group btn-group-sm pull-left" style="margin-left:5px">
    <?php 
    foreach(array_keys(Yii::$app->authManager->permissions) as $p){
        if($p=='updateOwnPost') 
            echo Html::a($p, ['access-test/rbac-test?actionID='.$p.'&postID=1'], ['class'=>'action_ajax btn btn-sm btn-primary']); 		
        else
            echo Html::a($p, ['access-test/rbac-test?actionID='.$p], ['class'=>'action_ajax btn btn-sm btn-primary']); 
    }
    ?>	
    </div>
</div>