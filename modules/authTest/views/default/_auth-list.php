<?php 
use yii\widgets\Pjax;
?>

<?php //print_r($model);
$permissions = $auth->permissions; 
$roles = $this->context->getAllRoles(); //$auth->roles;
$rules = $auth->rules; 
$user = Yii::$app->user->getIdentity();
?>

<div class="btn-group btn-group-sm pull-right">
    <span class="btn btn-primary" onclick="authCreate('Permission');">Create Permission</span>
    <span class="btn btn-primary" onclick="authCreate('Role');">Create Role</span>
    <span class="btn btn-primary" onclick="authCreate('Rule');">Create Rule</span>
</div>

<div class="clearfix"></div>

<img class="loading" src="<?=Yii::$app->homeUrl?>/images/loader/box_loading.gif" style="display:none"/>

<?php Pjax::begin([
    'id'=>'pjax-auth-list', 
    'linkSelector'=>'#pjax-auth-list a',
    'formSelector'=>'.gridview-filter-form, #user-search, #form-tools', 
    'timeout'=>false, 
    'enablePushState' => false,
    'clientOptions'=>['container'=>"#auth-list"],
]); ?>

<div id="bol">
<div  class="view" style="padding-top:0px">
	<span class="label label-success">Permissions</span><br>
    <table class="simpleTbl" cellpadding="3">
        <tr><th>Name</th> <th>Type</th> <th>Description</th> <th>Rule Name</th> <th>Data</th> <th>CreatedAt</th> <th>UpdatedAt</th> <th>Children</th><th>Permissions</th><th>Actions</th></tr>
        <?php foreach( $permissions as $permit ): ?>
        <tr>
            <td><?= $permit->name; ?></td><td><?= $permit->type; ?></td><td><?= $permit->description; ?></td><td><?= $permit->ruleName; ?></td><td><?= $permit->data; ?></td>
            <td><?= date("Y-m-d H:i:s",$permit->createdAt); ?></td><td><?= date("Y-m-d H:i:s",$permit->updatedAt); ?></td> <td><?=$this->context->getChildren( $permit->name);?></td>
            <td align="center"><?php echo (Yii::$app->user->can($permit->name)) ?  "<span class='glyphicon glyphicon-check'></span>" : "<span class='glyphicon glyphicon-remove'></span>"; ?> </td>
            <td> 
                <button class="glyphicon glyphicon-search" onclick="authView('<?=$permit->name?>', '<?=$permit->type?>');"/> 
                <button class="glyphicon glyphicon-edit" onclick="authUpdate('<?=$permit->name?>', '<?=$permit->type?>');"/>  
                <button class="glyphicon glyphicon-trash" onclick="authDelete('<?=$permit->name?>', '<?=$permit->type?>');"/> 
            </td>
		</tr>
		<?php endforeach; ?>
	</table>
</div>

	<span class="label label-success">Roles</span><br>
    <b> Default Roles : </b> <?= $this->context->getDefaultRole(true); ?>
    <table class="simpleTbl" cellpadding="3">
        <tr><th>Name</th> <th>Type</th> <th>Description</th> <th>Rule Name</th> <th>Data</th> <th>CreatedAt</th> <th>UpdatedAt</th> <th>Children</th><th>Permissions</th><th>Actions</th></tr>
        <?php foreach( $roles as $role ): ?>
        <tr>
            <td><?= $role->name; ?></td><td><?= $role->type; ?></td><td><?= $role->description; ?></td><td><?= $role->ruleName; ?></td><td><?= $role->data; ?></td><td><?= date("Y-m-d H:i:s", $role->createdAt); ?></td>
            <td><?= date("Y-m-d H:i:s", $role->updatedAt); ?></td> <td><?=$this->context->getChildren( $role->name);?></td>  <td><?=$this->context->getPermitRole( $role->name);?></td>
            <td> 
                <button class="glyphicon glyphicon-search" onclick="authView('<?=$role->name?>', '<?=$role->type?>');"/> 
                <button class="glyphicon glyphicon-edit" onclick="authUpdate('<?=$role->name?>', '<?=$role->type?>');"/>  
                <button class="glyphicon glyphicon-trash" onclick="authDelete('<?=$role->name?>', '<?=$role->type?>');"/> 
            </td>
		</tr>
		<?php endforeach; ?>
	</table>
    
    <span class="label label-success">Rules</span><br>
    <table class="simpleTbl" cellpadding="3">
        <tr><th>Name</th> <th>CreatedAt</th> <th>UpdatedAt</th> <th>ClassName</th><th>Action</th></tr>
        <?php foreach( $rules as $key=>$rule ): ?>   
        <tr>
            <td><?= $rule->name; ?></td><td><?= ($rule->createdAt) ? date("Y-m-d H:i:s",$rule->createdAt) : null; ?></td><td><?= ($rule->updatedAt) ? date("Y-m-d H:i:s",$rule->updatedAt) : null; ?></td> <td><?=$rules[$key]->className();?></td>
            <td> 
                    <button class="glyphicon glyphicon-search" onclick="authView('<?=$rule->name?>');"/> 
                    <button class="glyphicon glyphicon-edit" onclick="authUpdate('<?=$rule->name?>');"/>  
                    <button class="glyphicon glyphicon-trash" onclick="authDelete('<?=$rule->name?>', 'Rule');"/> 
                </td>
        </tr>
		<?php endforeach; ?>
	</table>
    </div>
<?php Pjax::end(); ?>

