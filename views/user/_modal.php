<?php
use yii\helpers\Html;
use yii\bootstrap\Modal; 
//use yii\bootstrap\ActiveForm;
?>

<?php
Modal::begin([
    'id'=>'modal-user',
    'header' => '<h4 id="modal-tilte" class="pull-left"></h4> <img class="loading pull-left" src="'.Yii::$app->homeUrl.'/images/loader/box_loading.gif" style="display:none;margin:10px"/><div class="clearfix"></div>',
    'headerOptions'=>null,  // string
    'footer'=>null, //string
    'footerOptions'=>null, //string
    //'size'=>'modal-lg', //string - defult '', modal-sm, model-lg
    'closeButton'=>[],
    'toggleButton' => false, //['label' => 'click me'],
    'options'=>[], // HTML attributes for the widget container tag.
    'clientOptions' => ['show'=>false], // options for the underlying Bootstrap JS plugin.
    'clientEvents' => [
        'show.bs.modal'=>'function (e, d) { console.log("on show : "); 
            $("#user-form").html("<p>Loading..</p>"); 
        }',
        'shown.bs.modal'=>'function (e, d) { console.log("on shown : "); }',
        'hide.bs.modal'=>'function (e, d) { console.log("on hide : "); }',
        'hidden.bs.modal'=>'function (e, d) { console.log("on hidden : "); 
            $("#user-msg").html("");
            $("#user-form").html("");
        }',
        'loaded.bs.modal'=>'function (e, d) { console.log("on loaded : "); }',
    ], 
    
]);
?>

    <div id="user-msg"></div>  <!-- BELOM DI PAKE -->
    
    <div id="user-form"> <p>Loading..</p> </div>

<?php Modal::end(); ?>

<?php
// $this->registerJs("
    // function getType(type){
        // return (type=='Permission') ? 2 : 1;
    // }
    
    // function userAssign(id){    event.preventDefault(); console.log(id);
        // $('#modal-tilte').text('Assign User '+id);
        // $('#user-form').load('./authTest/assign/'+ id);
        // $('#modal-user').modal(true);
    // }
    
    
    
    // function userView(name, type){    console.log(name, type);
        // $('#modal-tilte').text('View item '+name);
        // var type = (type!=undefined) ? type : 'Rule';
        // $('#user-form').load('./authTest/auth/view/'+ encodeURIComponent(name) + '/'+ type, function(data){   //console.log('---->', data);
            // $(this).html(data);
        // });
        // $('#modal-user').modal({show:true});
    // }
    
    // function userUpdate(name, type){
        // $('#modal-tilte').text('Update item '+name);
        // var type = (type!=undefined) ? type : 'Rule';
        // $('#user-form').load('./authTest/auth/update/'+ encodeURIComponent(name) + '/'+ type)
        // $('#modal-user').modal(true);
    // }
    
    // function userDelete(name, type){
        // if(confirm('Are you sure you want to delete this item?')){
            // $.post('./authTest/auth/delete/'+ encodeURIComponent(name)+'/'+type, function(data){  
                // console.log('delete', data.status); 
                // if(data.status)  
                    // $.pjax.reload({container:'#auth-list', url: './authTest/load-auth', timeout:0, replace:false});
            // }, 'json');
        // }
    // }
    
    // function submitUser(url, container){  
        // event.preventDefault();
        // var reload_target = (container=='#auth-list') ? './authTest/load-auth' : './authTest/load-user';
        // $.ajax({
            // url: url,
            // type: 'POST',
            // dataType: 'json',
            // data: $('#form-auth').serializeArray(),
            // beforeSend: function (jqXHR, settings) {    //console.log(jqXHR, settings);
                // //$('form').trigger('ajaxBeforeSend', [jqXHR, settings]);
            // },
            // success: function(data) {   console.log('DATA : ', typeof data, data);
                // if(data.status){
                    // $('#modal-user').modal('toggle');
                    // $.pjax.reload({container: container, url: reload_target, timeout:0, replace:false});
                // }else
                    // $('#user-msg').html( formatError(data) );
            // },
            // error: function(xhr, status, errorThrown) {	//console.log('error ', xhr); 
                // $('#user-msg').html( formatError(xhr.responseText) );
            // },
        // });
        // return false;
    // }
    
    // function formatError(data){      console.log('ERROR : ', typeof data, data);
        // var message = (typeof data=='string') ? JSON.parse(data).message : data.content;
        // var errors =[];
       
       // if(typeof message=='object')
            // $.each(message, function(k, i){ errors.push('<li>'+i+'</li>'); });
       // else
            // errors.push('<li>'+message+'</li>');
        // errors = errors.join(\"\"); 

        // //add class errors
        // $.each($('#form-auth').find('.required :input'), function(i, item){  //console.log(item,  $(this).val(), $(this).parents('.required'));
            // if( $(this).val()=='') $(this).parents('.required').addClass('has-error'); 
        // });
                    
        // return '<div class=\'alert-danger alert fade in\'>'
        // +'<button type=\'button\' class=\'close\' data-dismiss=\'alert\'>&times;</button>'
        // +'<div style=\'max-height: 70px;overflow:auto\'>'+ errors+'</div></div>';
    // }
   // ",  \yii\web\View::POS_END);
?>