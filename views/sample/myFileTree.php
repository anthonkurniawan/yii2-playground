<?php
use yii\helpers\Html;
use tona\fileTree\FileTree;
use yii\web\JsExpression;

$this->title = Yii::t('app', 'Code Mirror');
$this->params['breadcrumbs'][] = $this->title;
?>


<?php
    FileTree::begin([
        'id'=>'test-filetree',
        'clientOptions'=>[
            "root"=> "", 
            "script"=> "", # "" : curent url,  diffrent url sample: "../tools/get-file-tree",
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
            "filetreeexpand"=>"function (e, data)	{ console.log('filetreeexpand DATA :', data); }",
            "filetreeexpanded"=>"function (e, data)	{ console.log('filetreeexpanded DATA :', data); }",
            "filetreecollapsed"=>"function (e, data)	{ console.log('filetreecollapsed DATA :', data); }",
            "filetreecollapse"=>"function (e, data)	{ console.log('filetreecollapse DATA :', data); }",
            "filetreechecked"=>"function (e, data)	{ console.log('filetreechecked DATA :', data); }",
            "filetreeunchecked"=>"function (e, data) { console.log('filetreeunchecked DATA :', data); }",
            "filetreeclicked"=>"function(e, data)	{ console.log('filetreeclicked DATA :', data); }",
        ],
        'htmlOptions'=>['class'=>'well'],
    ]);
    FileTree::end();
?>

<?php
// $this->registerJs("
// $('#test-filetree')
	// .on('filetreeexpand', function (e, data)	{ console.log(data); })
	// .on('filetreeexpanded', function (e, data)	{ console.log(data); })
	// .on('filetreecollapsed', function (e, data)	{ console.log(data); })
	// .on('filetreecollapse', function (e, data)	{ console.log(data); })
	// .on('filetreechecked', function (e, data)	{ console.log(data); })
	// .on('filetreeunchecked', function (e, data)	{ console.log(data); })
	// .on('filetreeclicked', function(e, data)	{ console.log(data); });
// ",  \yii\web\View::POS_END);
?>

<pre>
CUSTOM CONNECTOR SCRIPTS
========================
You can create a custom connector script to extend the functionality of the file tree. The easiest way to do this is probably by modifying one of the scripts supplied in the download. If you want to start from scratch, your script should accept one POST variable (dir) and output an unsorted list in the following format:

	<ul class="jqueryFileTree">
		<li class="directory collapsed"><a href="#" rel="/this/folder/">Folder Name</a></li>
		(additional folders here)
		<li class="file ext_txt"><a href="#" rel="/this/folder/filename.txt">filename.txt</a></li>
		(additional files here)
	</ul>

Note that the corresponding file extension should be written as a class of the li element, prefixed with ext_. (The prefix is used to prevent invalid class names for file extensions that begin with non-alpha characters.)

Additionally you may choose to enable multi-select, which appends a checkbox to each item. Visible child elements will automatically be checked/unchecked along with the parent. Currently this is only supported in PHP; feel free to update other connectors to reflect the following format:

	<ul class="jqueryFileTree">
		<li class="directory collapsed"><input type='checkbox' /><a href="#" rel="/this/folder/">Folder Name</a></li>
		(additional folders here)
		<li class="file ext_txt"><input type='checkbox' /><a href="#" rel="/this/folder/filename.txt">filename.txt</a></li>
		(additional files here)
	</ul>

    
CALLBACK HOOKS
=========================
jQuery File Tree now supports binding event listeners to the file tree element

```javascript
$('.filetree')
	.on('filetreeexpand', function (e, data)	{ console.log(data); })
	.on('filetreeexpanded', function (e, data)	{ console.log(data); })
	.on('filetreecollapsed', function (e, data)	{ console.log(data); })
	.on('filetreecollapse', function (e, data)	{ console.log(data); })
	.on('filetreechecked', function (e, data)	{ console.log(data); })
	.on('filetreeunchecked', function (e, data)	{ console.log(data); })
	.on('filetreeclicked', function(e, data)	{ console.log(data); });
```

All return the data object with the following properties

<table>
<tr>
	<th>Property</th><th>Value</th>
</tr>
<tr> <th>li</th><th>LI jQuery object</th> </tr>
<tr> <th>type</th><th>file | directory</th> </tr>
<tr> <th>value</th><th>name of the file or directory</th> </tr>
<tr> <th>rel</th><th>relative path to file or directory</th> </tr>
<tr> <th>trigger</th><th>type of trigger called</th> </tr>
</table>
</pre>