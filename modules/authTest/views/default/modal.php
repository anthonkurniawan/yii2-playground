<?php
use yii\helpers\Html;
use yii\bootstrap\Modal; 
//use yii\bootstrap\ActiveForm;
?>

<?php
Modal::begin([
    'id'=>'modal-auth',
    'header' => '<h4 id="modal-tilte"></h4>',
    'headerOptions'=>null,  // string
    'footer'=>null, //string
    'footerOptions'=>null, //string
    'size'=>null, //string
    'closeButton'=>[],
    'toggleButton' => false, //['label' => 'click me'],
    'options'=>[], // HTML attributes for the widget container tag.
    'clientOptions' => ['show'=>false], // options for the underlying Bootstrap JS plugin.
    'clientEvents' => [
        'show.bs.modal'=>'function (e, d) { console.log("on show : "); 
            $("#auth-item-form").html("<p>Loading..</p>"); 
        }',
        'shown.bs.modal'=>'function (e, d) { console.log("on shown : "); }',
        'hide.bs.modal'=>'function (e, d) { console.log("on hide : "); }',
        'hidden.bs.modal'=>'function (e, d) { console.log("on hidden : "); 
            $("#auth-msg").html("");
            $("#auth-item-form").html("");
        }',
        'loaded.bs.modal'=>'function (e, d) { console.log("on loaded : "); }',
    ], 
    
]);
?>

    <div id="auth-msg"></div>

    <div id="auth-item-form"> <p>Loading..</p> </div>

<?php Modal::end(); ?>

<?php
$this->registerJs("
     $(document).on('pjax:send', function(event, xhr) {   
        $('.loading').show();  //alert('zzzz');  console.log( $('.loading') );
    })
    .on('pjax:complete', function(event, xhr, textStatus) {   //console.log('complete: ', event, xhr, textStatus);
        $('.loading').hide();
    });
    
    function getType(type){
        return (type=='Permission') ? 2 : 1;
    }
    
    function authAssign(id){    event.preventDefault(); console.log(id);
        $('#modal-tilte').text('Assign User '+id);
        $('#auth-item-form').load('./authTest/assign/'+ id);
        $('#modal-auth').modal(true);
    }
    
    function authCreate(type){
        $('#modal-tilte').text('Create new '+type);
        var typename = (type=='Rule') ? type : getType(type);
        $('#auth-item-form').load('./authTest/auth/create/'+ typename);
        $('#modal-auth').modal(true);
    }
    
    function authView(name, type){    console.log(name, type);
        $('#modal-tilte').text('View item '+name);
        var type = (type!=undefined) ? type : 'Rule';
        $('#auth-item-form').load('./authTest/auth/view/'+ encodeURIComponent(name) + '/'+ type, function(data){   //console.log('---->', data);
            $(this).html(data);
        });
        $('#modal-auth').modal({show:true});
    }
    
    function authUpdate(name, type){
        $('#modal-tilte').text('Update item '+name);
        var type = (type!=undefined) ? type : 'Rule';
        $('#auth-item-form').load('./authTest/auth/update/'+ encodeURIComponent(name) + '/'+ type)
        $('#modal-auth').modal(true);
    }
    
    function authDelete(name, type){
        if(confirm('Are you sure you want to delete this item?')){
            $.post('./authTest/auth/delete/'+ encodeURIComponent(name)+'/'+type, function(data){  
                console.log('delete', data.status); 
                if(data.status)  
                    $.pjax.reload({container:'#auth-list', url: './authTest/load-auth', timeout:0, replace:false});
            }, 'json');
        }
    }
    
    function submitAuth(url, container){  
        event.preventDefault();
        var reload_target = (container=='#auth-list') ? './authTest/load-auth' : './authTest/load-user';
        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            data: $('#form-auth').serializeArray(),
            beforeSend: function (jqXHR, settings) {    //console.log(jqXHR, settings);
                //$('form').trigger('ajaxBeforeSend', [jqXHR, settings]);
            },
            success: function(data) {   console.log('DATA : ', typeof data, data);
                if(data.status){
                    $('#modal-auth').modal('toggle');
                    $.pjax.reload({container: container, url: reload_target, timeout:0, replace:false});
                }else
                    $('#auth-msg').html( formatError(data) );
            },
            error: function(xhr, status, errorThrown) {	//console.log('error ', xhr); 
                $('#auth-msg').html( formatError(xhr.responseText) );
            },
        });
        return false;
    }
    
    function formatError(data){      console.log('ERROR : ', typeof data, data);
        var message = (typeof data=='string') ? JSON.parse(data).message : data.content;
        var errors =[];
       
       if(typeof message=='object')
            $.each(message, function(k, i){ errors.push('<li>'+i+'</li>'); });
       else
            errors.push('<li>'+message+'</li>');
        errors = errors.join(\"\"); 

        //add class errors
        $.each($('#form-auth').find('.required :input'), function(i, item){  //console.log(item,  $(this).val(), $(this).parents('.required'));
            if( $(this).val()=='') $(this).parents('.required').addClass('has-error'); 
        });
                    
        return '<div class=\'alert-danger alert fade in\'>'
        +'<button type=\'button\' class=\'close\' data-dismiss=\'alert\'>&times;</button>'
        +'<div style=\'max-height: 70px;overflow:auto\'>'+ errors+'</div></div>';
    }
   ",  \yii\web\View::POS_END);
?>