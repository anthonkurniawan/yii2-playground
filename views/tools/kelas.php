<?php
/* @var $this yii\web\View */
use yii\widgets\menu;
use tona\sideMenu\SideMenu;
use tona\fileTree\FileTree;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\VarDumper;

$this->registerJsFile(Yii::$app->homeUrl.'/js/underscore.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->title = Yii::t('app', 'Fetch Class');
?>

<style>
.container{ width: 100% }
div.left_menu, div.right_menu{ top:0px; width: auto;}
div.left_menu, div.right_menu ul{ display: block; list-style-type: disc;}
.cmd_line{margin:0px; overflow:hidden}
.box1 { border:1px solid beige; font-size:11px}
#tree_obj ul{font-size:12px}
#tree_obj li.text-primary{ cursor: pointer}
.label {cursor: pointer}
.badge{cursor:pointer}
ul{-webkit-padding-start:20px}
ul li {white-space: nowrap}
.ul li{ font-size:10px}
ul li a.active{padding:1px}
ul li a.text-primary.active{ color: #fff;background-color: #286090;border-color: #204d74;}
ul li a.text-success.active{ color: #fff;background-color: #5cb85c;border-color: #4cae4c;}
ul li a.text-danger.active{ color: #fff;background-color: #c9302c;border-color: #ac2925;}
.nowrap{white-space: nowrap}
.check span{cursor: pointer}
.code_result{background-color:purple; color:white; font-size:11px; overflow:auto}
.code_result a{ color: yellow; text-decoration: underline;}
#properties{color:white; background-color: darkred}   /* blueviolet, darkred, chocolate */
#properties ul li  span.text-primary{font-size:12px;color:darkorange;}
/*default{color: aqua} */
.tooltip-inner{max-width: 600px; text-align:left;word-wrap: break-word; }
.tooltip-inner ul li ref_type{ color:chartreuse;white-space: normal}
.tooltip-inner ul li ref_doc, #properties ref_doc{ color:yellow;white-space: normal}
/*-----OVERRIDE FILE-TREE -----*/
ul.jqueryFileTree a{ font-weight: normal; }
ul.jqueryFileTree span.glyphicon{ margin-right:2px }
ul.jqueryFileTree a.hidden_file, ul.jqueryFileTree li.hidden_file {display:none }
ul.jqueryFileTree a.muted_file{ color: #777; }
ul.jqueryFileTree a.need_param_cons{ color: orange}
ul.jqueryFileTree a.not_instantiable_class{ color: red}
ul.jqueryFileTree a.bold, ul.jqueryFileTree li.bold { font-weight: bold }
pre.tips {
  padding: 0px;
  margin: 0px;
  font-size: 12px;
  line-height: normal;
  color: lawngreen;
  word-break: normal;
  word-wrap: break-word;
  background-color: transparent;
  border: none;
  border-radius: 0px;
  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
  font-weight:normal;
}
</style>
<?php   
//VarDumper::dump($this, 10, true); 
//VarDumper::dump(Yii::$app->extensions, 10, true); 
?>

<?php 	
#component definitions or the loaded component instances., TRUE : definition, FALSE : instance, def-->true
$components = Yii::$app->getComponents(true);	//VarDumper::dump($components,10, true);  die();
$models = $this->context->myModels;
$controllers = $this->context->myControllers;
$modules = Yii::$app->getModules();

$sub_component['db'] = ['schema'=>'Schema', 'queryBuilder'=>'Query Builder']; //print_r($sub_component); die();

$arr['yii'] = ['label'=>'YII Class - new Yii', 'url'=>Url::to(['tools/kelas', 'obj'=>'return new Yii;']), 'items'=>[] ]; 
$arr['yii']['items']['base'] = ['label'=>'YII Base', 'items'=>[] ];  // Html::encode(Url::to($item['url'])),
$arr['yii']['items']['base']['items']['app'] = [ 'label'=>'Application - Yii::app()', 'url'=>Url::to(['tools/kelas', 'obj'=>'return Yii::$app;']), 'options'=>[]];
$arr['yii']['items']['base']['items']['components'] = ['label'=>'Application Component',  'options'=>[], 'activateParents'=>true, 'submenuTemplate'=> "\n<ul style='display:none'>\n{items}\n</ul>\n"];

foreach($components as $key=>$value){		//echo "===>".$key; VarDumper::dump($value,10, true); die();
    $label = is_object($value) ? get_class($value) : $value['class'];
	$arr['yii']['items']['base']['items']['components']['items'][$key] = ['label'=>"{$key} ({$label})", 'url'=>Url::to(['tools/kelas', 'obj'=>$key]), 'options'=>[], 'visible'=>true, 'linkOpsi'=>'x' ];
    if(array_key_exists($key, $sub_component)){
        foreach($sub_component[$key] as $k=>$v)  //{echo "<br>".$k ."->"; print_r($v);}
           $arr['yii']['items']['base']['items']['components']['items'][$key]['items'][$k] = ['label'=>"{$v} ({$k})", 'url'=>Url::to(['tools/kelas', 'obj'=>"return Yii::\$app->{$key}->{$k}"]), 'options'=>[]];
    }
}
# YII HELPER
$arr['helper']= ['label'=>'Yii Helpers','submenuTemplate'=> "\n<ul style='display:none'>\n{items}\n</ul>\n", 'items'=>[
    'ArrayHelper' => ['label'=>"ArrayHelper", 'url'=>Url::to(['tools/kelas', 'obj'=>"return new yii\helpers\ArrayHelper;"]), 'options'=>[]],
    'Html' => ['label'=>"Html", 'url'=>Url::to(['tools/kelas', 'obj'=>"return new yii\helpers\Html;"]), 'options'=>[]],
    'Url' => ['label'=>"Url", 'url'=>Url::to(['tools/kelas', 'obj'=>"return new yii\helpers\Url;"]), 'options'=>[]],
    'VarDumper' => ['label'=>"VarDumper", 'url'=>Url::to(['tools/kelas', 'obj'=>"return new yii\helpers\VarDumper;"]), 'options'=>[]],
    'Console' => ['label'=>"Console", 'url'=>Url::to(['tools/kelas', 'obj'=>"return new yii\helpers\Console;"]), 'options'=>[]],
    'FileHelper' => ['label'=>"FileHelper", 'url'=>Url::to(['tools/kelas', 'obj'=>"return new yii\helpers\FileHelper;"]), 'options'=>[]],
    'FormatConverter' => ['label'=>"FormatConverter", 'url'=>Url::to(['tools/kelas', 'obj'=>"return new yii\helpers\FormatConverter;"]), 'options'=>[]], 
    'HtmlPurifier' => ['label'=>"HtmlPurifier", 'url'=>Url::to(['tools/kelas', 'obj'=>"return new yii\helpers\HtmlPurifier;"]), 'options'=>[]], 
    'Inflector' => ['label'=>"Inflector", 'url'=>Url::to(['tools/kelas', 'obj'=>"return new yii\helpers\Inflector;"]), 'options'=>[]], 
    'Json' => ['label'=>"Json", 'url'=>Url::to(['tools/kelas', 'obj'=>"return new yii\helpers\Json;"]), 'options'=>[]], 
    'Markdown' => ['label'=>"Markdown", 'url'=>Url::to(['tools/kelas', 'obj'=>"return new yii\helpers\Markdown;"]), 'options'=>[]], 
    'StringHelper' => ['label'=>"StringHelper", 'url'=>Url::to(['tools/kelas', 'obj'=>"return new yii\helpers\StringHelper;"]), 'options'=>[]], 
]];
# LOAD ALL CONTROLLERS
$arr['controllers'] = ['label'=>'All Controller', 'submenuTemplate'=> "\n<ul style='display:none'>\n{items}\n</ul>\n"];
$arr['controllers']['items']['this'] = ['label'=>"this (Yii::\$app>controller)", 'url'=>Url::to(['tools/kelas', 'obj'=>"this"]), 'options'=>[]];
foreach ($controllers as $key=>$controller)
    $arr['controllers']['items'][$key] = ['label'=>"{$controller}", 'url'=>Url::to(['tools/kelas', 'obj'=>"return new app\\controllers\\{$controller}(\"fakeID-{$key}\",Yii::\$app);"]), 'options'=>[]];
# LOAD ALL MODELS
$arr['models'] = ['label'=>'All Models (Active Record)', 'submenuTemplate'=> "\n<ul style='display:none'>\n{items}\n</ul>\n"];
foreach ($models as $key=>$model)
    $arr['models']['items'][$key] = ['label'=>"{$model}", 'url'=>Url::to(['tools/kelas', 'obj'=>"return new app\\models\\{$model};"]), 'options'=>[], 'visible'=>true ];
# LOAD ALL MODULES  
$arr['modules'] = ['label'=>'All Modules', 'submenuTemplate'=> "\n<ul style='display:none'>\n{items}\n</ul>\n"];
foreach ($modules as  $key=>$val){
    $label = is_object($val) ? get_class($val) : $val['class'];
    $arr['modules']['items'][$key] = ['label'=>"{$key} ({$label})", 'url'=>Url::to(['tools/kelas', 'obj'=>$key]), 'options'=>[] ];
}
# DECLARE CLASS PHP
$arr['declareClass'] = ['label'=>'All Declare Class', 'items'=>[
    'dec_class'=>[ 'label'=>'Declare Class', 'url'=>Url::to(['tools/kelas', 'dec'=>1, 'obj'=>"class"]),  'options'=>[], ],
    'dec_interface'=>[ 'label'=>'Declare Interface', 'url'=>Url::to(['tools/kelas', 'dec'=>1, 'obj'=>"interface"]),  'options'=>[], ],
    'dec_traits'=>[ 'label'=>'Declare Traits', 'url'=>Url::to(['tools/kelas', 'dec'=>1, 'obj'=>"traits"]),  'options'=>[], ],
] ]; 

//VarDumper::dump($arr,10, true); die();
// .replace(/[/]/g,'\\')
// array_multisort($link['yii-base']['items'], SORT_ASC);  echo "<pre>"; print_r($link); echo "</pre>";			
?>
 
<?php
#echo SideMenu::widget([]);
SideMenu::begin([
    'id'=>'menuLoadedYii',
	'title'=>'Menu',
	'collapsed'=>false,
	'position'=>'left',
 ]);

echo Menu::widget([
    'items' => $arr,
	'linkTemplate' => '<a href="{url}" onclick="classRequest($(this));">{label}</a>',
    'labelTemplate' => '<span onclick="$(this).siblings().slideToggle();">{label}</span>',
	// 'itemOptions'=>['class'=>'test'],  # to include html attr to each items
	// 'options'=>['style'=>'color:red'], # include html attr to container
]);

SideMenu::end(); 
 ?>
 
 <?php
SideMenu::begin([
    'id'=>'menuFiletree',
	'title'=>'YII2 Structure',
	'collapsed'=>false,
	'position'=>'right',
    'dependElement'=>'yii2-filetree',
 ]);
?>
<!-- toolbar tambahan -->
<div style='padding-left: 15px;padding-right: 5px;'>
    <?= Html::checkbox('hiden_fies', false, ["id"=>"toggleDir", "onclick"=>"if($('#toggleDir').is(':checked') ) $('.hidden_file').show(100); else $('.hidden_file').hide(100);"]); ?> 
    <small class='text-muted'>show hiden file</small>
</div>

 <?php
    FileTree::begin([
        'id'=>'yii2-filetree',
        'clientOptions'=>[
            "root"=> "/", 
            "script"=>"../tools/get-file-tree",
            /* optional */
            "folderEvent"=>"click",
            "expandSpeed"=>500, # Speed to expand branches (in ms); use -1 for no animation. def: 500
            "collapseSpeed"=> 500, # Speed to collapse branches (in ms); use -1 for no animatio. def: 500
            "expandEasing"=> null, # Easing function to use on expand. def: None
            "collapseEasing"=>null, # Easing function to use on collapse. def: None
            "multiFolder"=>true, # Whether or not to limit the browser to one subfolder at a time. def: true
            "loadMessage"=>"Loading..", # Message to display while initial tree loads (can be HTML). def: "Loading..."
            "multiSelect"=>false, # Append checkbox to each line item to select more than one. def: false
            "onlyFolders"=>false,  # show only folders
        ],
        "callback"=>"function(file) { console.log('File : ', file); }",
        "eventCallback"=>[
            "filetreeexpand"=>"function (e, data)	{    //console.log('filetreeexpand DATA :', data, data.li.children(), data.li.find('ul.jqueryFileTree li a').length); 
                setTimeout( function(){ 
                    if($('#toggleDir').is(':checked') )
                        data.li.find('ul.jqueryFileTree li, ul.jqueryFileTree li a').show()
                },800);
            }",
            "filetreeclicked"=>"function(e, data){ 
                console.log('filetreeclicked DATA :', data); 
            }",
            "filetreeexpand"=>"function (e, data)	{ console.log('filetreeexpand DATA :', data); }",
            "filetreeexpanded"=>"function (e, data)	{ console.log('filetreeexpanded DATA :', data); }",
        ],
        //'htmlOptions'=>['style'=>'display:none'],
    ]);
    FileTree::end();
    
    SideMenu::end(); 
?>


<div class="check form-group-sm" style="margin:0px 0px 10px">  
    <?= Html::dropDownList('mode', 'show', ['show'=>'Show', 'code'=>'Code'], ['id'=>'mode'] ); ?>
    <?= Html::checkbox('show_dump_obj', true, ['id'=>'show_dump_obj', "class"=>"opt_check"]);?>
    <span style="margin-right:10px" onclick="toggleCheck('show_dump_obj')">Show dump object </span>
    <?= Html::checkbox('show_tree_obj', true, ['id'=>'show_tree_obj', "class"=>"opt_check"]);?>
    <span style="margin-right:10px" onclick="toggleCheck('show_tree_obj')">Show tree object </span>
    <?= Html::checkbox('show_method', true, ['id'=>'show_method', "class"=>"opt_check"]);?>
    <span style="margin-right:10px" onclick="toggleCheck('show_method')">Show method</span>
    <?= Html::checkbox('show_method_val', true, ['id'=>'show_method_val', "class"=>"opt_check"]);?>
    <span style="margin-right:10px" onclick="toggleCheck('show_method_val')">Show method value</span>
    <?= Html::checkbox('show_method_ref', true, ['id'=>'show_method_ref', "class"=>"opt_check"]);?>
    <span style="margin-right:10px" onclick="toggleCheck('show_method_ref')">Show method reference</span>
    <?= Html::checkbox('show_cmd', true, ['id'=>'show_cmd', "class"=>"opt_check"]);?>
    <span style="margin-right:10px" onclick="toggleCheck('show_cmd')">Show command console</span>
    
    <!--- TOGGLE ACTIVE/DEACTIVE SIDE-MENU -->
    <span class="glyphicon glyphicon-th-list text-primary" onclick="toggleSideMenu($(this));"></span>
    
    <div class="input-group pull-left">
        <?php //echo Html::input('text', 'obj_requested', false, ['id'=>'obj_requested', 'size'=>70, 'class'=>'pull-left form-control', 'style'=>'width:auto']); ?>
        <?= Html::textarea('obj_requested', '', ['id'=>'obj_requested', 'class'=>'pull-left form-control', 'style'=>'width:400px']); ?>
        <div class="input-group-btn" style="width:auto">
        <?= Html::button('Send', ['id'=>'resend_obj', 'class'=>'btn btn-sm btn-primary pull-left', 'style'=>'display:none', 'onclick'=>'getClass( $("#obj_requested").val() );']); ?>
        </div>
    </div>
    
    <div id="sample" class="pull-left input-group" style="margin-left:5px"></div>
    
    <div id="toggleShow" class="btn-group btn-group-sm pull-left" style="margin: 0px 5px 0px 5px; display:none"></div>
    <!--<button onclick="sortClass()" class="pull-left">test sort</button>-->
    <div class="clearfix"></div>
</div>  

<!----------------------------------------------------------------- MAIN RESULT -------------------------------------------------->
<div id="load" class="pull-left loading box-load" style="width:50px;display:none">&nbsp;&nbsp;</div>
<div id="mainBox" style="display:none">
    <div id="dec_class"></div>
    <div id="panel_class"></div> <!-- panel label class -->
    <div class="input-group" style="display:none">
        <?= Html::hiddenInput('cmd_hidden', null, ['id'=>'cmd_hidden']); ?> 
        <?= Html::hiddenInput('cmd_hidden', null, ['id'=>'codesample-id']); ?>
        <?= Html::textarea("sourcecode", '', ["id"=>"cmd", 'placeholder'=>"type code..", "rows"=>"0", "style"=>"width:100%;", "class"=>"cmd_line form-control"] ); ?>
        <div id="btn-code-action" class="input-group-btn">
            <?= Html::button('Save?', ['id'=>'btn-saveCode', 'class'=>'btn btn-success no_dis', 'style'=>'padding: 8px 12px;', 'onclick'=>"sendAction('create');"]); ?>
            <?= Html::button('Update?', ['id'=>'btn-updateCode', 'class'=>'btn btn-danger no_dis', 'style'=>'padding: 8px 12px;', 'onclick'=>"sendAction('update');"]); ?>
            <?= Html::button('Send', ['class'=>'btn btn-primary aktif', 'style'=>'padding: 8px 12px;', 'onclick'=>'tryCode();']); ?>
            <!--<div class="btn-group">
              <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                Action <span class="caret"></span>
              </button>
              <ul class="dropdown-menu" role="menu">
                <li><a href="#">Update</a></li>
                <li><a href="#">Create</a></li>
              </ul>  
            </div>-->
            
        </div>
    </div>

    <div id="dump_obj" class="pull-left" style="margin:0;margin-bottom:2px; max-width:100%;overflow:auto"></div> <!--- box dump obj --->
    <div id="tree_obj" class="pull-left" style="overflow: auto; display:none"></div>
    <div id="properties" class="pull-left" style="overflow: auto; display:none"></div>
    <!--<div class="clearfix"></div>-->

    <div id="method" class="pull-left" style="overflow:auto;display:none"></div>
    <div id="method_val" class="pull-left" style="width:80%;  overflow-x:hidden; overflow-y: auto;display:none"></div>
    <div id="method_ref" class="pull-left" style="margin-top:2px; overflow: auto;display:none"></div> 
</div>
<div class="clearfix"></div>              

<!----- FORM SAVE CODE --->
<?= $this->render('_modal');?>

<?php 
$this->registerJs(" 
    window.onload = function(){  
        loadAllSample();
    }
    
    $('body').tooltip({
        selector: '.tooltip_obj',
        html: true,
        template: '<div class=\"tooltip\" role=\"tooltip\"><div class=\"tooltip-arrow\"></div><div class=\"tooltip-inner\"></div></div>',
        placement: 'bottom', // top | bottom | left | right | auto.
    });
    $('body').tooltip('show');
    
    // GET QUERY PARAM IF EXIST
    $('body').ready( function(){
        var param = getQueryParam();		console.log('PARAMS : ', param);
        if(param.obj)
            getClass(param.obj);
    }); 
    
    $('#obj_requested').on('change paste focus', function(){	
        $('#resend_obj').show();
    });
    
    $('#cmd').on('keyup cut paste focus', function(e){	console.log('EVENT - keyCode: '+e.keyCode, e);    //console.log('scrollHeight : '+obj.scrollHeight, $(this))
        if(e.keyCode==13 || e.keyCode==8 || e.Code==46 || e.type=='focus' || e.type=='paste' || e.type=='cut' || (e.keyCode==90 && e.ctrlKey) || (e.keyCode==86 && e.ctrlKey)){       
            $(this).css('min-height', 'inherit');
            $(this).css('min-height', $(this)[0].scrollHeight);
            if(e.type=='paste' || e.type=='cut' || (e.keyCode==90 && e.ctrlKey) || (e.keyCode==86 && e.ctrlKey))
                setTimeout( function(){ activeBtnCmdResponse(); }, 50);        // set delay for dom complete first
            else
                activeBtnCmdResponse();
            
            if( $(this).outerHeight() > 400) $(this).css('font-size', '11px');  else $(this).css('font-size', '12px');
        }
    });
    
    $('.opt_check').change(function(){  
        if($('#obj_requested').val()) $('#resend_obj').show();
    });
    
    $('#mode').change(function(){  //alert( $(this).val());
        var list_uncode = ['show_dump_obj', 'show_tree_obj', 'show_method_val', 'show_method_ref'];
        if($('#obj_requested').val()) 
            $('#resend_obj').show();
        if($(this).val()=='code')
            $.each(list_uncode, function(i, id){ $('#'+id).prop('checked', false); });
        else
            $.each(list_uncode, function(i, id){ $('#'+id).prop('checked', true); });
    });"
);
?>

 <?php 
//$this->registerJs($script, $position);
// where $position can be View::POS_READY (the default), 
// or View::POS_HEAD, View::POS_BEGIN, View::POS_END

#GET DECLARED CLASS
$this->registerJs("
    var codeSample = {};
    var list_obj; // list object class
    var list_ref_method;  // list method reef collections
    var class_name;  // class name will fetched
    var class_parent;
    var con_method = {id: 'method', label:'Class Method'};  // for generate id tag panel
    var con_method_val = {id: 'method_val', label: 'Value get-method', column: ['method','value','class']}
    var con_method_ref = {id: 'method_ref', label: 'Refrection of Class Method'}
        con_method_ref.column = ['name', {label: 'Par.req.', name:'NumberOfRequiredParameters'}, {label:'Par.num.',name:'NumberOfParameters'}, {label:'Params', name:'Parameters', 'min-width':'150px'}, {label:'Document Comment', name:'DocComment'}, 'class', 'types'];
        
    function classRequest(obj){   //console.log('====>', obj, obj.attr('href'));
          //console.log(this.href); console.log( decodeURIComponent(this.href));
        event.preventDefault();
        var param = getQueryParam(obj.attr('href'));		console.log('PARAMS : ', param, Object.keys(param).length);
        if(param){ 
            if( Object.keys(param).length==1)
                getClass(param.obj);
            else
                decClass(param.obj);
        }
    }
    
    function getQueryParam(url) {  // console.log('URL : ',url);
        var args = {}
        var url_pairs = (url) ? url.split('?').slice(-1).toString().split('&') : location.search.substring(1).split('&');    //console.log('URL PAIR : ', url_pairs);

        for(var i = 0; i < url_pairs.length; i++) {
            var pos = url_pairs[i].indexOf('=');          // Look for 'name=value'
            if (pos == -1) continue;                  // If not found, skip
            var argname = url_pairs[i].substring(0,pos);  // Extract the name
            var value = url_pairs[i].substring(pos+1);    // Extract the value
            value = decodeURIComponent(value);        // Decode it, if needed
            args[argname] = value;                    // Store as a property
        }                            
       return args;                          
    };
    
	// GET RESULT
	function getClass(val){ 	  
        event.preventDefault();
        $('#obj_requested').val(val);  $('#resend_obj').hide();
        
        var opt ={
            show_properties : 1,
            show_cmd : ($('#show_cmd').is(':checked')) ? 1 : 0,
            show_dump_obj : ($('#show_dump_obj').is(':checked')) ? 1 : 0,
            show_tree_obj : ($('#show_tree_obj').is(':checked')) ? 1 : 0,
            show_method : ($('#show_method').is(':checked')) ? 1 : 0,
            show_method_val : ($('#show_method_val').is(':checked')) ? 1 : 0,
            show_method_ref : ($('#show_method_ref').is(':checked')) ? 1 : 0,
        }
        
        var reset_list_box = ['dump_obj', 'tree_obj', 'properties', 'method', 'method_ref', 'method_val'];   console.log('RESET LIST BOX', reset_list_box);
        var active_list_box =[ ];
        $.each(opt, function(i, v){ if(v && i !=='show_cmd')   active_list_box.push( i.replace('show_', '') ); });   console.log('ACTIVE LIST BOX', active_list_box);
        
		$.ajax({
            url: 'fetch',
            type: 'POST',
			dataType: 'json',
            data : { kelas : val, option: opt },
            beforeSend :function(){
                class_name=null;  //reset class name for next request
                $('#load').show();
                $('#panel_dump_obj').remove();  // remove panel if exist
                $('#panel_dec_class').remove();
                $('#code_result').remove();
                $('#mainBox').hide();
                $('#toggleShow').html('').hide();
				$.each(reset_list_box, function(x, id){  $('#'+id).css({'padding':0, 'height' : '0px', 'min-width':'0px', 'width': '0px'}).html('') });
                initBtnAction();
            },
			success: function(data){ 	 console.log('sucess', data);  //alert(data.parent_label);
                /* MAIN RESULT */
				if(data.class !== undefined){
                    class_name = data.class.class_name;
                    class_parent = data.class.parent_class;
                    loadSample( getAliasClassCode() );
                    $('#form-new-code').trigger('reset.yiiActiveForm');
                    $('#codesample-id').val('');
                    
                    list_ref_method = data.method_ref;    // save for search method later    
                    list_obj = data.obj;
                    $.each(active_list_box, function(x, id){       
                        $('#'+id).css({'padding': '5px', 'height': '200px', 'min-width':'350px'}).addClass('box1').show(); 
                        $('#toggleShow').append('<span class=\"btn btn-default\" onclick=\"$(\'#'+id+'\').toggle(); $(this).toggleClass(\'active\'); \">'+id+'</span>').show(300); 
                    });
                
                    /* Object dump */
                    if(opt.show_dump_obj){
                        $('#dump_obj').html(data.dump_obj);  					
                        setTimeout( function(){ panelBtn_float('dump_obj', '200px', false); }, 500);
                    }
                
                    /* Badge class request with parent class documentatios */
                    $('#panel_class').html( formattBadgeClassDoc(data.parent_label) );

                    /* Class properties & Output result */
                    var output = (data.output !='') ? '<p><span class=\'badge\'>Output Result </span><br>' + data.output.toString() +'</p>' : '';
                    $('#properties').css({'min-width':'', 'width':'auto', 'max-width':'500px'}).html( 
                        output + formatProperties(data.class_properties, 'properties', 'Class Properties') 
                    );
                      
                    if(opt.show_method)
                        fetch_method( data.method_ref, null);   // fetch_method( obj, sort);
						
					/* get table for all method */
                    if(opt.show_method_ref)
                        format_table( data.method_ref, con_method_ref, true, null);  // format_table(obj, id, sort, query);
                    
					/* get table for get method & result */
                    if(opt.show_method_val)
                        format_table( data.method_val, con_method_val, false, null);  // format_table(obj, id, sort, query)
                    
                    if(opt.show_cmd){    
                        $('#cmd').val(data.cmd).parent().show();
                        $('#cmd_hidden').val(data.cmd);   // FOR REFRESH FETCH KELAS
                    }
                    if(opt.show_tree_obj)
                        $('#tree_obj').css({'min-width':'', 'width':'auto', 'max-width':'500px'}).html( formatTree(data.obj, 'tree_obj', 'Tree Object') );			// jQuery.parseJSON('response from server')
                }
				else{ 
                    var output = (data.output !='') ? '<p><span class=\'badge\'>Output Result </span><br>' + data.output.toString() +'</p>' : '';
                    $('#properties').show().css({'padding': '5px', 'height': '200px', 'width':'100%'}).html( output );
                }
			},
			error: function(xhr, status, errorThrown) {	console.log('error ', xhr); //Type: Function( jqXHR jqXHR, String textStatus, String errorThrown )
				$('#dump_obj').show().css({'height':'auto', width:'auto'}).html( '<b class=text-danger>' + status +' : '+ xhr.responseText + '</b>');
			},
            complete: function(data){           // #dump_obj, #tree_obj, #properties, #method, #method_val, #method_ref
                $('#load').hide();  
                $('#mainBox').show();
                if( $('#method_ref')[0].hide!=false)
                    $('#method_ref').width( $('body').width() - $('#method_val').innerWidth()-50);
                if( $('#cmd').length==1)
                    $('#cmd').focus();
            }
        });
	}

	function decClass(val){	//alert(val);
        $('#panel_dec_class').remove();
        $('#dec_class').html('<div class=\'loading\'>loading..</div>');
                
        $.get('declare_class', {var: val}, function(data){ 
            panelBtn_float('dec_class', '100px', false);
            $('#dec_class').addClass('well well-sm').html('<span class=badge badge-inverse>Declared Classes</span> <pre>'+ data +'</pre>');
        } );
	}"	,$this::POS_END   # Defaults to View::POS_READY.
);

$global_script = <<< JS
    function fetch_method(obj, sort){  console.log('DATA : ', sort, obj)
        obj = (obj !=undefined) ? obj : list_ref_method;
        data = sortClass(sort, obj);     console.log('SORT : ', data);
         
        var items = [];      
        $.each(data, function(key, val) {	   //console.log('============>', key);
            pars = (val.detail.Parameters) ? formatParam(val.detail.Parameters) : null; 
            css = (val.detail.class==class_name) ? "text-primary" : "text-success";
            // JIKA BUKAN PUBLIC METHOD CMD LINE TIDAK PERLU DI UPDATE !!!!!!!!!!!!!!!
            isPublic = val.detail.types.isPublic;
                 if( ! isPublic) css = "text-danger";

            res =  '<li><a href="#" class='+css+' onclick="getValue(\$(this), \''+ val.detail.name +'\',\'method\', '+ isPublic +');">' + val.detail.name + '(' + pars+ ')</a></li>';
            items.push(res);
        });              //console.log('TABLE : ', items)

        container_id = 'con_'+con_method.id;

        if( $('#'+container_id).length==0){
            panel_tpl = panelBtn_fixed(container_id, con_method.label, true);   // panelBtn_fixed(id_tag, label, sort)
            content = panel_tpl + '<div class="clearfix"></div> <ul id=\"'+container_id+'\" style="">' + items.join("") +'</ul>';
            $('#method').css({'min-width':'', 'width':'auto'}).html( content );	
        }
        else $('#'+container_id).html( items.join("") );	
    }
    
	function format_table(obj, con, sort, q){  //console.log('===>', con, sort, obj);	//alert(obj.length);
        var css = 'simpleTbl';   // return "Data Kosong (check on server)";
        obj = (obj !=undefined) ? obj : list_ref_method;
        obj = (sort && q!=null) ? sortClass(q, obj) : obj;
        
        var items = [];    
        items.push('<table class=\"'+css+'\">');
        if(con.column) 
            $.each(con.column, function(key, col) {	
                icon_toggle = '<span class="glyphicon glyphicon-remove-circle pull-right" aria-hidden="true" onclick="toggleCol(\'.td-'+key+'\');"></span>';
                if( typeof col==='object' )
                    items.push('<th nowrap class=\"td-'+key+'\" width='+ (col.width || 'auto') +' onClick="$(\'.td-'+key+'\').toggleClass(\'nowrap\');">'+ col.label + icon_toggle +'</th>');
                else
                    items.push('<th nowrap class=\"td-'+key+'\" onClick="$(\'.td-'+key+'\').toggleClass(\'nowrap\');">'+ col + icon_toggle +'</th>');
            });	
                
        $.each( obj, function(key, val) {	  
            val = (con.id=='method_ref') ? val.detail : val;
            items.push('<tr>');
            if(con.column){                       //console.log(con.column);
                var i =1;
                var obj_class = 'col'+i+'-'+key;
                    
                $.each(con.column, function(x, col) {	
                    data = (typeof col =='object') ? col.name : col;	//console.log(data);
                    
                    if( typeof val[data]==='object' ){
                        if(! $.isEmptyObject(val[data])){
                            label = (val.class_value !=undefined) ? val.class_value : '';
                            res = '<td class=\"td-'+x+'\">'
                                + '<ul style=\"list-style-type:square\">'
                                + '<li class=\'label label-success\' onClick=$(\".'+obj_class+'\").slideToggle(\"slow\");>'+label+' ('+typeof val[data]+')</li>'
                                + '<ul class=\"'+obj_class+'\" style=\'display:none;list-style-type:decimal\'> '+ getObject( val[data], obj_class ) + '</ul></ul>'+
                                '</td>' 
                        } else res = '<td>('+typeof val[data]+') -- null </td>';
                        items.push(res);
                    }
                    else{
                        var res;
                        if(data=='method') res = '<strong style="color:coral">' + val[data] + '</strong>';
                        else if (data=='class') res = '<strong class=text-primary>' + val[data] + '</strong>';
                        else if (data=='Parameters') res = (val[data]) ? formatParam(val[data]) : null;
                        else if(data=='DocComment') res = getFormatDoc(val[data]); 
                        else res = val[data];
                            
                        res =  '<td class=\"td-'+x+'\">' + res + '</td>';
                        items.push(res);
                    }
                    i++;
                });
            }
            // else
                // items.push('<td>' + val + '</td>');
            items.push('</tr>');
        });
        items.push('</table>');
            
        container_id = 'con_'+con.id;
        if( $('#'+container_id).length==0){
            panel_tpl = panelBtn_fixed(container_id, con.label, sort);  // panelBtn_fixed(id_tag, label, sort)
            content = panel_tpl + '<div class="clearfix"></div> <div id=\"'+container_id+'\">' + items.join("") +'</div>';
            $('#'+con.id).css({'min-width':'', 'width':'auto'}).html( content );	
        }
        else $('#'+container_id).html( items.join("") );
	}
    
    function getMethodRef(method){
        var except = ['name','NumberOfRequiredParameters','NumberOfParameters'];  // except detail method of ..
        var class_details = list_ref_method[method].detail;    console.log('==>', class_details);
        var list=[]
        $.each(class_details, function(key,val){
            if( $.inArray( key, except ) == -1) {
                if(key=='class') res = '<li><strong style="color:chartreuse">'+key+' : </strong><strong style="color:coral">'+ val +'</strong></li>';
                else if(key=='Parameters') res = '<li><strong style="color:chartreuse">'+key+' : </strong><strong style="color:aqua">'+formatParam(val)+'</strong></li>';
                else if(key=='DocComment') res = '<li><strong style="color:chartreuse">'+key+' : </strong><ul style="color:yellow;padding-left:10px;line-height:1.2">' + getFormatDoc(val) +'</ul></li>';
                else if(key=='types') res = '<li style="color:chartreuse"><strong>'+key+' : </strong> <span style="color:magenta">'+ JSON.stringify( filterMethodType(val), null,2) +'</li>';
                else if(typeof val=='object' && ! $.isEmptyObject(val) ) res = '<li><strong>'+key+' : '+JSON.stringify(val,null,2)+'</strong></li>';
                else res = '<li> <strong>'+key+' : </strong>'+val+'</li>';
                list.push(res);
            }
        });
        return list.join("");
    }
    
    function getPropertyRef(prop){
        except =['Pname', 'value'];
        classname = class_name.replace(/[\\\]/g,'');
        prop_details = list_obj[classname][prop];    console.log('==>', prop_details);
        var list=[]
        $.each(prop_details, function(key,val){
            if( $.inArray( key, except ) == -1) {
                if(key=='Pexp') res = '<li><strong style="color:chartreuse">'+key+' : </strong><strong style="color:aqua">'+formatParam(val)+'</strong></li>';
                else if(key=='DocComment') res = '<li><strong style="color:chartreuse">'+key+' : </strong><ul style="color:yellow;padding-left:10px">' + getFormatDoc(val) +'</ul></li>';
                else res = '<li> <strong>'+key+' : </strong>'+val+'</li>';
                list.push(res);
            }
        });
        return list.join("");
    }
    
    function getValue(el, p, type, isPublic){     console.log('GET VALUE METHOD');
        $('#code_result').remove();
        if( !el.hasClass('active')){
            el.parents('ul').find('a.active').toggleClass('active');
            el.toggleClass('active'); 
            var cmdSet = $('#cmd').val();
            
            // TRY GET VALUE PROPERTY
            if(type=='property'){
                name = list_obj[class_name.replace(/[\\\]/g,'')][p].Pname;
                c = name.split('_');

                if(c.length > 1){
                    obj = '\$obj=' + cmdSet.replace(/return/,'').trim();
                    obj = obj + '\\n\$ref=new ReflectionProperty(\''+ c[0] +'\',\"_'+ c[1] +'\");';
                    cmd = obj + '\\n\$ref->setAccessible(true);\\nreturn \$ref->getValue(\$obj);';  //console.log('------>', c[0] , c, cmd);
                }
                else{
                    if(class_name=='Yii')
                        cmd ='return Yii::\$'+ p +';';
                    else{
                        var pos = cmdSet.length -1;    //console.log(pos);
                        cmd= cmdSet.trim().substring(0, pos)+ '->'+ p +';';
                    }
                }
            }
            // TRY GET VALUE METHOD
            if(type=='method' && isPublic){
                // if(cmdSet.match(/use/)){
                    // cmdSet = cmdSet.replace(/return /,'\\n\$var =').trim();
                    // cmd= cmdSet+ ' \\nreturn \$var->'+p+'();';    console.log('COMMAND RES1: ', cmd);
                // }
                // else if(cmdSet.match(/new/)){
                    // cmdSet = cmdSet.replace(/return /,'\$class = ').trim();    console.log('COMMAND : ', cmdSet);
                    // cmd= cmdSet+ ' \\nreturn \$class->'+p+'();';     console.log('COMMAND RES2: ', cmd);
                // }
                //else{
                    //var pos = cmdSet.length -1;    //console.log(pos);
                    //cmd= cmdSet.trim().substring(0, pos)+ '->'+ p +'();';   
                    cmdSet = cmdSet.replace(/return /, '').trim();
                    vars = cmdSet.match(/\\$\w+/);
                    cmd= cmdSet+ '\\nreturn '+ '\$' + p + ' = ' + vars +'->'+p+'();';    console.log('COMMAND RES3: ', cmd);
                //}
            }
            $('#cmd').val( cmd ).focus();
        }
        
        ref = (type=='method') ? getMethodRef(p) : getPropertyRef(p);
        notif = ( isPublic) ? '' : '(<strong style="margin-left:5px; color:red; text-decoration:underline">Non public Method is not allowed</strong>)';
        label = '<strong style="font-size:14px;margin-left:5px">'+ (type +' detail of ').toUpperCase() +p + notif + '</strong>'
        con = (type=='method') ? '#method' :  '#tree_obj';
        var tpl ='<pre id="code_result" class="code_result">' + label +'<ul>'+ ref +'</ul></pre>';
        // con.css({'height': $('#code_result').height() }).after( tpl );  
        $(con).after( tpl );  
        snc_resize(con, '#code_result');
    }
    
    function snc_resize(id1, id2){
        h1 = $(id1).innerHeight();
        h2 = $(id2).innerHeight();   console.log('TINGGI '+id1+ ':' +h1+ ', TINGGI '+id2+':'+h2);
        if(h1 > h2)
            $(id2).css({'height': h1});
        else
            $(id1).css({'height': h2});
    }
    
    function tryCode(){
        $.ajax({
			url: 'code_test', type: 'POST',
			data : { cmd: $('#cmd').val().replace(/[\\x00]/g,"") },
            //dataType: 'json',
			beforeSend :function(){
                $('#panel_dec_class').remove();
				$('#code_result').html('<div class=\'loading\'>loading..</div>') 
			},
			success: function(data){           //console.log('RESULT : ', typeof data, data);
                if( $('#code_result').length==1)  $('#code_result').html(data);
                else $('#method').after( '<pre id="code_result" class="code_result">'+data+'</pre>' );
                $('#code_result').css('height','auto');
               
                initBtnAction('show');
                setTimeout( function(){ activeBtnCmdResponse()}, 0);
			},
            error: function(xhr, status, errorThrown) {	//console.log(errorThrown, xhr);
				if( $('#code_result').length==1)  $('#code_result').html( status +' - '+errorThrown+' - '+xhr.status+' : '+ xhr.responseText );
                else $('#method').after( '<pre id="code_result" class="code_result">'+status +' : '+ xhr.responseText+'</pre>' );
			},
		});
    }
    
    function formatParam(data){     //console.log('PARAMS :', data)
        var params=[];
        pars = data.split(',');
        $.each(pars, function(i, par){
            p = (par) ? /[\[\]][^\[\]]*[\[\]]/.exec(par) : 'kosong';
            params.push(p);
        });    
        params = params.join('');  //console.log('PARAMS RESULT =>', params);
        return params;
    }

    function filterMethodType(obj){
        var res={}; 
        _.filter(obj, function(val,type){ 
            if(val==true)return res[type]= val;
        });     //console.log(res)
        return res;
    }
    
    function sortClass(order, obj){  console.log('SORT ORDER :', order);
        data = (obj !=undefined) ? obj : list_ref_method;   console.log('===>', Object.keys(data).length, data);
        var class_items={};
        var exists=[];

        if(order==undefined){
            order =['isUserDefined','isPublic','isProtected','isStatic','isPrivate'];  // SORT FOR ORDER ARRAY"_public"..etc
            $.each(order, function(i, sort){
                $.each(data, function(key, val){    //console.log('+++++++', key+' : ',val);
                    if( val[sort] && $.inArray( key, exists ) == -1){
                        class_items.types[key]=val; 
                        exists.push(key);
                    }
                    else if( $.inArray( val.params, order ) == -1){   
                        class_items[key]=val; 
                        //remains.push(key);  
                    }
                });
            });  
        }
        else{   // ORDER BY USER DEFINE USING REGEXP. CONTOH: /find/ 
            pattern = new RegExp( order );
            // $.each(data, function(key, val){
                // if( pattern.exec(key)){ console.log('FOUND : ', key);
                    // class_items[key]=val; 
                    // exists.push(key);
                // }
                // else
                    // class_items[key]=val; 
            //});
            var class_items = _.sortBy(data, function(key,val){ if( pattern.exec(val)) return val; }); 
        }                 console.log('SORT RESULT : ', Object.keys(class_items).length, class_items);
        return class_items;
    }
    
	function getObject( obj, id_tag, items ) {		 //alert($.isEmptyObject(obj));
		if( $.isEmptyObject(obj) ) 
			return "empty";

		if(! items) var items=[];
		var items=[];
		var i =1;

		$.each( obj, function(key, val) {	//alert(key);
			var kelas = id_tag + '-' + i;

            // if( typeof val =='function'){		alert(key);    // JIKA OBJECT JS FUNCTION (GA DI PAKE DI SINI)
                     // if($.inArray( key, arr_except ) == -1) {  
                        // if( obj[key]()=='[object Object]') { 	
                            // items.push( '<li class=\'text-primary\' onClick=$(\".'+kelas+'\").slideToggle(); > ('+ x +')' + key + ' : ' + obj[key]() + 
                                // '<b class="text-muted">('  + typeof val +') </b></li> <ul class='+kelas+' style=\'display:none\'>' );
                            // getObject( obj[key](), items, x, id_tag );			//alert('isArray!'); 
                            // items.push('</ul>');
                    // }
                    // else { items.push( '<li> <b>' + key + ' :</b> ' + obj[key]() + '</li>' ); }
                // }
            // }
            if( typeof val=='object'  && ! $.isEmptyObject(val) ){  			
				items.push(' <li onClick=$(\".'+kelas+'\").slideToggle(\"slow\");><span class=\'text-primary\'>  ('+ kelas +') ' + key +' : </span><i style=\"color:coral\">' + JSON.stringify(val).substr(0,10) + '</i> <small class="text-muted">('  + typeof val +')</small></li>' +
					'<ul class='+kelas+' style=\'display:none\'>' );		
				items.push( getObject( val, kelas, items ) );
				items.push('</ul>');
			}
			else{
                if(key=='DocComment')
                    text = '<li><b>Document :</b> <ref_doc>'+ getFormatDoc(val) + '</ref_doc></li>';
                else
                    text = '<li> <b>' + key + '</b> :</b> ' + val + '<small class="text-muted">('  + typeof val +')</small></li>';
				items.push( text );  
            }
			i++;
		});		
		items = items.join(""); 
		return items;
	}
	
	function formatTree(obj, id_tag, label, items, counter) {	//alert(label + $.isEmptyObject(obj)); //alert(id_tag + 'id :' + obj.id);
        if(! $.isEmptyObject(obj))
        {
            if(! items) var items=[];
            if(!subClass) var subClass=1;  
            counter = counter || 1;      //console.log(counter, subClass);
                                                   
            $.each( obj, function(key, val) {	                    //console.log( '++++++++++++>', obj, key, val);
                var kelas = id_tag + (subClass+counter);								

                if( typeof val=='object' && ! $.isEmptyObject(val)){         //console.log(typeof val, val, $.isEmptyObject(val), '---------> OBJECT');
                    key_label = (val.Pname !=undefined) ? val.Pname : key;             
                    tooltip = (val.DocComment || val.Pexp) ? tooltipInfo(val.Pexp, val.DocComment) : "";
                                                             //console.log('KEY-->'+key, 'KEY LABEL : '+key_label, 'TYPE : ', typeof val, 'VALUE : ' ,val);	
                                                             
                    if(counter==1){  	
                        items.push( '<li class=\'text-success\' onclick= >  ('+ subClass +')' + key_label + ' : ' + val + tooltip + '</li>'
                        +'<ul class='+kelas+' style=\'-webkit-padding-start: 20px;\'>' );
                            formatTree( val, kelas, label, items, counter+1); 			
                        items.push('</ul>');
                    }
                    else{  	
                        nilai = (val.nilai !=undefined) ? val.nilai : val;

                        if( typeof nilai !='object' ){          //console.log( '---------> BUKAN OBJECT');
                        items.push( '<li> <b>' + key_label + ' : </b>' + nilai.toString() + tooltip +'</li>' ); }
                        else if( $.isEmptyObject(nilai) ){                             // console.log( '--------->OBJECT TAPI KOSONG');
                            items.push( '<li> <b>' + key_label + '  : </b>' + JSON.stringify(nilai) + tooltip + '</li>' ); 
                        }
                        else{            //console.log(key, typeof nilai, Object.keys(nilai), nilai, subClass, key_label, tooltip,' =============================================');
                            items.push( '<li class=\'text-primary\' onClick=$(\".'+kelas+'\").slideToggle();> ('+ subClass +')' +key_label+ ' : '  + tooltip + '</li>'
                                +'<ul class='+kelas+' style=\'display:none; -webkit-padding-start: 20px;\'>' );
                                    formatTree( nilai, kelas, label, items,counter++); 			
                            items.push('</ul>');
                        }
                    }
                }
                else {                         // console.log( '---------> OTHERS');
                    if(key!='exp' && key!='DocComment' && key!='Pexp' && key!='Pname'){
                        val = ( typeof key=='string' && key.match(/_memoryReserve/)) ? '<span class="text-">'+ val.substr(1,10)+'...('+val.length+' bytes) </span>' : val;
                        items.push( '<li> <b>' + key + ' :</b> ' + val + tooltip + '</li>' ); 
                    }
                }
                subClass++;
            });
            items = items.join("");   //alert(items);
            
            panel = panelBtn_fixed('panel_'+id_tag, label);
            return panel + '<div class="clearfix"></div> <ul id=\"panel_'+id_tag+'\" style="margin:0px; -webkit-padding-start:20px">' + items +'</ul>';
        }
        else return "Empty";
	}
    
    function tooltipInfo(exp, doc){
        c = '<ul> <li>Type : <ref_type>'+ exp+'</ref_type></li> <li>Document : <ref_doc>'+ getFormatDoc(doc) + '</ref_doc></li> </ul>';
        return '<span class="tooltip_obj  glyphicon glyphicon-info-sign" title=\"'+c+'\" aria-hidden=\"true\" style=\"margin-left:5px\"></span>';
    }
    
    function formatProperties(obj, id_tag, label){
        items = getObject(obj, 'properties', null);
        panel = panelBtn_fixed('panel_'+id_tag, label);
        return panel + '<div class="clearfix"></div> <ul id=\"panel_'+id_tag+'\" style="margin:0px; -webkit-padding-start:20px">' + items +'</ul>';
    }
    
    function getFormatDoc(data){  
        return (data) ? data.replace(/\\n/g,'<br>').replace(/<br>/,'').replace(/[\/\*\\n\"]/g,'').trim() : 'null';
    }
    
    // PANEL BUTTON FOR HEADER 
	function panelBtn_float(id_tag, height, panelOff){	
		var par = "#"+id_tag;  
        var id_tag_attr = 'panel_'+id_tag;
		var box_btn = ($('#'+id_tag_attr).width()) ? $('#'+id_tag_attr).width() : 118;		//alert($(par).width() +" | "+ $('#'+id_tag_attr).width());
		var margin_left = ($(par).width()) ? ($(par).width() - box_btn)+'px' : '98%';  		//alert( box_btn );//alert( $('#'+id_tag).width() );

		//$(par).css({'height' : height, 'overflow' : 'auto'});

		var panel = '<div  id="'+id_tag_attr+'" class="btn-group pull-right" style="position: absolute; margin-left:' + margin_left+ ';">'+
			'<div class="task_'+id_tag_attr+' glyphicon glyphicon-zoom-in btn btn-default" title="Maximize" onClick="$(\'#'+id_tag+'\').animate({height:\'100%\'}, \'slow\');"></div>'+
			'<div class="task_'+id_tag_attr+' glyphicon glyphicon-zoom-out btn btn-default" title="Miximize" onClick="$(\'#'+id_tag+'\').css({\'height\': \'100px\', \'overflow\':\'auto\'}).scrollTop($(\'#'+id_tag+'\').height());"></div>'+
			//'<div class="glyphicon glyphicon-remove btn btn-default" title="close" onClick="$(\'#'+id_tag+'\').slideUp(); $(\'#toggle-'+id_tag_attr+'\').show(1000);"></div>'+
			'<div class="task_'+id_tag_attr+' glyphicon glyphicon-off btn btn-default" title="close" onClick="$(\'#'+id_tag_attr+'_toggle\').show(); $(\'.task_'+id_tag_attr+'\').slideUp(); $(\'#'+id_tag_attr+'\').css({\'position\': \'relative\'}); $(\'#'+id_tag+'\').slideUp();"></div>'+
			'<div id="'+id_tag_attr+'_toggle" class="glyphicon glyphicon-refresh" title="Show" onClick="$(\'#'+id_tag+'\').slideToggle(); $(this).hide(); $(\'.task_'+id_tag_attr+'\').show(); $(\'#'+id_tag_attr+'\').css({\'position\': \'absolute\'});" style="display:none"></div>'+
			'</div>';
		if(!panelOff)
			$(par).before(panel);
		else
			return panel;
	};
	
	function panelBtn_fixed(id_tag, label, sort){	
        var sorter = (sort) ?  '<div class="pull-left" style="margin-left:5px"> SortBy Name : '+ getSorter(id_tag) + '</div>' : ""; 
		var panel =  '<div class="badge pull-left" onClick="$(\'#'+id_tag+'\').slideToggle();">'+ label +'</div>'+ sorter +
			'<div class="btn-group-xs pull-right" style="padding:0px;">'+
			'<div class="glyphicon glyphicon-expand" title="Maximize" onClick="$(\'#'+id_tag+'\').parent().animate({height:\'100%\', \'max-width\':\'100%\'}, \'slow\');"></div>'+
			'<div class="glyphicon glyphicon-collapse-down" title="Miximize" onClick="$(\'#'+id_tag+'\').parent().css({\'height\': \'200px\', \'overflow\':\'auto\'});"></div>'+
            '<div class="glyphicon glyphicon-collapse-up" title="close" onClick=" $(\'#'+id_tag+'\').slideToggle().parent().css({\'height\':\'auto\'});"></div>'+
			'</div>';
		return panel;
	}
	
    function getSorter(id_tag){    
        switch(id_tag){
            case 'con_method' : return '<input type="text" id="sorter-' +id_tag+'" onchange="fetch_method(list_ref_method, this.value);" style="line-height: normal;width:100px"/>';
            // case 'panel_tree_obj' : return '<input type="text" id="sorter-' +id_tag+'" onchange="fetch_method(list_ref_method, this.value);"/>';
            case 'con_method_ref' : return '<input type="text" id="sorter-' +id_tag+'" onchange="format_table(list_ref_method, con_method_ref, true, this.value);" style="line-height: normal;"/>';
        }
    }
    
	function formattBadgeClassDoc(obj){	//alert(obj);      yii\BaseYii => yii-baseyii.html, yii\web\Application => yii-base-application.html
		if( ! $.isEmptyObject(obj)) {  
		var labels=[];
		$.each(obj, function(key, val){ 
            if(key==class_name)
                labels.push('<a href=\"'+ val.doc_url + '\" class=\"label label-warning\" target=_blank>' + val.class_alias + '</a>');
            else{
                labels.push(' &#9002;&#9002; ');
                labels.push('<a href=\"'+ val.doc_url + '\" class=\"label label-success\" target=_blank>' + val.class_alias + '</a>'); 
            }
		});
		return labels.join("");
	}}
	
    function toggleCol(obj){  //console.log(obj);
        $(obj).hide(100);
    }
    
    function toggleCheck(obj){
        if( $('#'+obj).is(':checked') ) $('#'+obj).prop('checked', false);  
        else $('#'+obj).prop('checked', true);  
        if($('#obj_requested').val()) $('#resend_obj').show();
    }
    
    function getAliasClassCode(){
        var pattern = new RegExp('controllers|models|modules');
        return (pattern.test(class_name)) ? class_parent : class_name;  // if models, controllers using class parent
    }
    
    function loadSample(class_name){
        var class_alias = class_name.replace(/\\\/g,'');
        var sample = codeSample[class_alias];    console.log('LOAD SAMPLE :', class_alias, typeof sample, sample);
        if(!sample){
            class_alias = class_parent.replace(/\\\/g,'');
            sample = codeSample[class_alias]; 
        }
        return (sample !=undefined) ? getFormatSample(sample, class_alias) : getFormatSample(codeSample);
    }
    
    function loadAllSample(){
        $.getJSON( "load-sample",null, 
            function(res){      //console.log('----------->', res);
                if(Object.keys(res).length !=0){          
                    codeSample = res;
                    getFormatSample(res);
                } 
            } 
        );        
    }
    
    function getFormatSample(samples, specific_class){
        var item=[];
        var title = (specific_class) ? '-sample-' : '-all sample-';
        item.push("<select class='form-control' onchange='getCode( $(this).val() );'> <option value=''>"+ title + "</option>");
        if(specific_class)
            $.each(samples, function(i, q){ item.push('<option value="'+specific_class+'_'+i+'">'+q.title+'</option>') });
        else{
            $.each(samples, function(key, items){   //console.log(key, items);
                item.push('<option disabled>'+key+'</option>'); 
                $.each(items, function(i, q){ item.push('<option value="'+key+'_'+i+'">'+q.title+'</option>') });
            });
        }
        item.push("</select>");  
        btn = (specific_class) ?  item.push('<div class="input-group-btn" style="width:auto"> <button type="button" class="btn btn-sm btn-primary" onclick="loadAllSample();">load all sample</button></div>') : '';
        $("#sample").html(item.join(""));
    }
    
    function getCode(val){      //console.log( val );
        var i = val.split('_');
        code = codeSample[i[0]][i[1]];

        $('#form-new-code').trigger('reset.yiiActiveForm'); //$('#form-new-code').trigger('reset');
        
        $('#codesample-id').val(code.id);
        $('#codesample-title').val(code.title);
        $('#codesample-tags').val(code.tags);
        
        initBtnAction('hide');
        activeBtnCmdResponse();
        
        if($('#mainBox')[0].style.display=='none')
            $('#obj_requested').val(code.content).focus();
        else
            $('#cmd').val(code.content).focus();
    }
    
    function sendAction(action){
        var class_code = (classCode = getAliasClassCode()) ? classCode.replace(/\\\/g,'')  : null;
        $('#title-class').text(' - '+ class_code);
        $('#codeclass-classname').val( class_code );
        $('#codesample-content').val( $('#cmd').val() );
        
        if(action=='create') 
            $("#modal-new-code").modal("toggle");
        else
            submitNewCode( '../code-sample/update?id='+ $('#codesample-id').val() );
    }
    
    function initBtnAction(action){      console.log('INIT BUTTON ACTIVE', action);
        if(action=='show'){
            $('#btn-saveCode').addClass('aktif').show(); 
            if( $('#codesample-id').val() !=='') $('#btn-updateCode').addClass('aktif').show();  
        }
        else if(action=='hide'){
            $('#btn-saveCode').removeClass('aktif').hide();
            if( $('#codesample-id').val() !=='') $('#btn-updateCode').removeClass('aktif').hide();  
        }
        else{
            $('#btn-saveCode').removeClass('aktif').hide();
            $('#btn-updateCode').removeClass('aktif').hide();
        }
    }
    
    function activeBtnCmdResponse(){                
        var hsize = $('#cmd').outerHeight();
        var activeBtn = $('.aktif').length;   // jumlah aktif button
                                                                console.log('ACTIVE RESPONSE BUTTON', hsize, activeBtn);
        if(hsize > 114){
            $('#btn-code-action').children('.aktif').css({'height': hsize / activeBtn, 'display': 'block','float': 'none','width': '100%','max-width': '100%'})
            
            // $.each( $('#btn-code-action').children(), function(i,el){ 
                // if( $(this).css('display') !=='none') $(this).css({'display': 'block','float': 'none','width': '100%','max-width': '100%'});
            // });
        }
        else
            $('#btn-code-action').children('.aktif').css({'height': hsize, 'display': 'inline-block','float': 'none','width': 'auto','max-width': 'none'});
    }
    
    function toggleSideMenu(el){
        el.toggleClass('text-primary');
        menuLoadedYii.collapsed= (el.hasClass('text-primary')) ? false : true;
        menuFiletree.collapsed = (el.hasClass('text-primary')) ? false : true;
    }
JS;

$this->registerJs($global_script ,$this::POS_END );
?>

<!------------------------------------------------------------------------------------------------------------------------------------------>
<pre>
Use the new AssetBundle and AssetManager functionality with the View object in Yii2, to manage these assets and how they are loaded.

Alternatively, inline assets (JS/CSS) can be registered at runtime from within the View. For example you can clearly simulate the ajaxLink feature using a inline javascript. Its however recommended if you can merge where possible, client code (JS/CSS) into separate JS/CSS files and loaded through the AssetBundle. Note there is no more need of a CClientScript anymore:

$script = <<< JS
$('#el').on('click', function(e) {
    $.ajax({
       url: '/path/to/action',
       data: {id: '<id>', 'other': '<other>'},
       success: function(data) {
           // process data
       }
    });
});
JS;
$this->registerJs($script, $position);
// where $position can be View::POS_READY (the default), 
// or View::POS_HEAD, View::POS_BEGIN, View::POS_END
Total 1 comment
#18293report it01
chrisvoo at 2014/10/12 03:20pm
For javascript IDE support
Supposing you want to use a jquery plugin in your view, you can:

put all your CSS/JS file in app\assets\AppAsset;
create a js folder in the web folder (or in another location of your choice)
put in the previously created folder a js file for your view;
include the content of the file and register the javascript
$jsView = file_get_contents(Yii::getAlias('@webroot/js/myfile.js'));
$this->registerJs($jsView, View::POS_END);
(optional) you can use a naming convention for your js files: name them as <ID_CONTROLLER>_<ID_ACTION> and include it with:
$contrAction = Yii::$app->urlManager->parseRequest(Yii::$app->request)[0];
$contrAction = str_replace("/", "_", $contrAction);
$jsView = file_get_contents(Yii::getAlias('@webroot/js/'.$contrAction.".js"));
 </pre>