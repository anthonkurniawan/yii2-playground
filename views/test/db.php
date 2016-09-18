<?php
use yii\helpers\Html;
use yii\helpers\VarDumper;

$this->title = Yii::t('app', 'Model Details');
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
.container{width:10o%}
form label{color:blue; font-size:11px}
#i_console{background-color:black; color:white}
#hint{background: rgb(12, 84, 121); padding:2px 10px; margin:auto;color: white; text-align:center; min-width:500px; max-width:800px;display:none}
#hint code {font:11px "Courier New", Monaco, Courier, monospace; color: aqua}
#querycode span, code{font-size:12px; font-weight:bold; font-family: monospace}
#querycode span{color: brown}
#querycode span code{color: green}
pre.result{padding: 5px 10px; margin:3px;min-width:300px;max-width:550px;height:500px; color: burlywood; background-color:purple;display:none;overflow:auto}
.json-key {color: white;}
.json-value {color: lightblue;}
.json-string {color: lightgreen; }
</style>

    <div id="querycode">
        <span id="qmodel"> Model </span>
        <span id="qfind"> .find({}) </span>
    </div>
        
    <div id="hint">
        <div id="hint_msg" style="font-size:11px"></div>
    </div>
    
    <div style="float:left">
        <div id="console" style="padding:2px; margin-top:2px; border:1px solid gray">
            <textarea id="i_console" type='text' placeholder='Type your query' name='console' cols=30, rows=3></textarea>
            <br>
            <button onclick="query_c();"> Submit </button>
        </div>
        
        <form style="padding:2px; margin-top:2px; border:1px solid gray">                    
            select#model.form-control(name='model')
            <?= Html::dropDownList('mode', 'ar', ['db'=>'DB Connections', 'ar'=>'Active Record (model)'], ['id'=>'mode', 'class'=>"form-control"] ); ?>
            <?= Html::dropDownList('model', 'ar', ['Users'=>'Users', 'Profile'=>'Profile'], ['id'=>'model', 'class'=>"form-control"] ); ?>

            <div class="find" style="display:none">
                label(for="find") Find conditions
                <br>
                input#find.form-control(type='text', placeholder='Find Conditions {}', name='find', size=40)
                <br>
            </div>

            <button class="btn.btn-primary" type="submit" data-dismiss="modal" onclick="query(); return false;"> Submit </button>
        </form>
    </div>
    
    <div style="float:right; margin-left:5px; max-width:800px">
        <div id="query_req" style="float:left; margin:3px; padding: 5px; background-color:lightgreen;display:none"></div>
        <pre class="result" style="float:right;font-size:11px"></pre>
    </div>
    
    <div style="clear:both"></div>
    
<?php
$this->registerJs("
        $('#model').on('change', function(){	
            $('#qmodel').text( $(this).val() );
        });
        $('#find_s').on('change', function(){	
            if( $(this).val() !='distinct(criteria, field)' )
                $('.find').show(100).children().attr('disabled',false)
            else{
                $('#distinct').show(100).attr('disabled',false)
                $('.find').hide(100).children().attr('disabled',true)
            }
        });
        $('#lean').on('change', function(){	
            val = ($(this).val()=='on') ? 'true' : 'false';
            $('#qlean').html( '.lean(<code>'+ val +'</code>)'  );
        });
        
        var q_list = ['find', 'select', 'skip', 'limit', 'sort', 'where', 'populate'];
        $.each( q_list, function( key, obj ) {
            $('#'+ obj).on('change paste', function(){	//alert( $(this).val() );
                $('#q'+obj).html('.'+obj+'(<code>'+$(this).val()+'</code>)');
            });
        });

        $.each( q_list, function( key, obj ) {
            $('#'+ obj).on('focus', function(){	//alert( $(this).val() );
                $('#hint').show(200).delay(5000*10).slideUp('slow');
                getHint(obj);
            });
        });
    
        function query(){
            var data = $('form').serializeArray() ;             console.log('SERIALIZE FORM : ', data);
            var qinput=[];
            $.each(data, function(i, input){
                if(input.name=='model')
                    qinput.push(input.value);
                else if(input.name=='lean')
                    qinput.push( '.'+input.name+'('+ (input.value==='on') +')');
                else if((input.value !='' || input.name=='find') && input.name !='find_s')
                    qinput.push( '.'+input.name+'('+input.value+')');
            });
            var query = qinput.join('');    console.log('==>', query );
            send(query);
        }
        
        function query_c(){
            send( $('#i_console').val() );
        }
    
        function send(query){   
             $.ajax({
                type:'POST',
                //dataType:'json',
                data: {query: query},
                //contentType : 'text/plain; charset=UTF-8',
                beforeSend: function(xhr) {
                    $('#hint').hide();
                    q_format(query);  // prety print format query
                }, 
                success: function (data, status, xhr) {   //console.log(xhr + status + data);
                    //$('.result').text(JSON.stringify(data, null, 2));
                    $('.result').show(200).html( library.json.prettyPrint(data) );
                },
                error: function (xhr, status, errorThrown) { 
                    $('.result').show(200).html('<pre>'+ xhr.responseText + '</pre>'); 
                }
            });
        }
        
        function q_format(query){
            var q_arr= query.split(/\.(?=(?:[^']*'[^']*')*[^']*$)/); // SPLIT '.' EXCEPT IN QUOTE - JUST '.' query.split('.'); 
            var qstr='';
            $.each(q_arr, function(i, q){  //console.log('xxx-->', q);
                if(i ==0) qstr += q;
                else qstr += '<br>.'+q;
            });    console.log(qstr);
            $('#query_req').show(200).html(qstr);
        }
        
        function getHint(obj){
            var hint_list = {
                'find' : 'Object {} like conditions. ie',
                'select': 'Field on model. <code>x</code>',
            };
            var msg = hint_list[obj];
            $('#hint_msg').html(msg);
        }
        
        //  JSON PRETY PRINT
        if (!library)
           var library = {};

        library.json = {
           replacer: function(match, pIndent, pKey, pVal, pEnd) {
              var key = '<span class=json-key>';
              var val = '<span class=json-value>';
              var str = '<span class=json-string>';
              var r = pIndent || '';
              if (pKey)
                 r = r + key + pKey.replace(/[': ]/g, '') + '</span>: ';
              if (pVal)
                 r = r + (pVal[0] == ' \"' ? str : val) + pVal + '</span>';
              return r + (pEnd || '');
              },
           prettyPrint: function(obj) {
              var jsonLine = /^( *)('[\w]+': )?('[^']*'|[\w.+-]*)?([,[{])?$/mg;
              return JSON.stringify(obj, null, 3)
                 .replace(/&/g, '&amp;').replace(/\\'/g, '&quot;')
                 .replace(/</g, '&lt;').replace(/>/g, '&gt;')
                 .replace(jsonLine, library.json.replacer);
              }
        };
        "
    , \yii\web\View::POS_END
);
?>

<PRE>
Yii::app()->db->createCommand('SELECT * FROM tbl_user_mysql');
TblUserMysql::model()->findByPk($select); 

</PRE>