<?php
use yii\helpers\Html;
use conquer\codemirror\CodemirrorWidget;
use yii\web\JsExpression;

$this->title = Yii::t('app', 'Code Mirror');
$this->params['breadcrumbs'][] = $this->title;
?>

<style type="text/css">
      .CodeMirror {
        border: 1px solid #eee;
        height: auto;
      }
      /*placeholder*/
       .CodeMirror { border: 1px solid silver; }
      .CodeMirror-empty { outline: 1px solid #c22; }
      .CodeMirror-empty.CodeMirror-focused { outline: none; }
      .CodeMirror pre.CodeMirror-placeholder { color: #999; }
</style>

<?php 
// use yii\widgets\ActiveForm;
// $model = new app\models\ContactForm();
// $form = ActiveForm::begin(['id' => 'contact-form']); 
 
// echo $form->field($model, 'name')->widget(
    // CodemirrorWidget::className(),
    // [
        // 'preset'=>'javascript',
        // 'options'=>['rows' => 10],
    // ]
// );
?>

<?php
    CodemirrorWidget::begin(
    [
        'id'=>'code',
        'name'=>'code',
        'value'=>'test',
        'preset'=>'php',
        'options'=>['rows' => 10, 'placeholder'=>"Code goes here..."],
        'assets'=>[
            'ADDON_DISPLAY_PLACEHOLDER',
            'ADDON_HINT_SHOW_HINT',
            'ADDON_HINT_PHP_HINT',  // MASIH ERROR
            'ADDON_HINT_ANYWORD_HINT',
            'ADDON_SELECTION_ACTIVE_LINE',
            'THEME_3024_DAY', 'THEME_3024_NIGHT'
            //CodemirrorAsset::KEYMAP_EMACS,
            // CodemirrorAsset::ADDON_EDIT_MATCHBRACKETS,
            // CodemirrorAsset::ADDON_COMMENT,
            // CodemirrorAsset::ADDON_DIALOG,
            // CodemirrorAsset::ADDON_SEARCHCURSOR,
            // CodemirrorAsset::ADDON_SEARCH,
        ],
        'settings'=>[
            'lineNumbers' => true,
            'mode' => 'application/x-httpd-php',  // mode: "application/xml",
            'styleActiveLine'=> true,
            'lineWrapping'=> true,
            'viewportMargin'=>new JsExpression("Infinity"),
            'extraKeys'=> new JsExpression('{"Ctrl-Space": "autocomplete"}'),
            //'keyMap' => 'emacs'
        ],
    ]
);
?>
<?php CodemirrorWidget::end(); ?>

<p>Select a theme: <select onchange="selectTheme()" id=select>
    <option selected>default</option>
    <option>3024-day</option>
    <option>3024-night</option>
    <option>ambiance</option>
    <option>base16-dark</option>
    <option>base16-light</option>
    <option>blackboard</option>
    <option>cobalt</option>
    <option>colorforth</option>
    <option>eclipse</option>
    <option>elegant</option>
    <option>erlang-dark</option>
    <option>lesser-dark</option>
    <option>liquibyte</option>
    <option>mbo</option>
    <option>mdn-like</option>
    <option>midnight</option>
    <option>monokai</option>
    <option>neat</option>
    <option>neo</option>
    <option>night</option>
    <option>paraiso-dark</option>
    <option>paraiso-light</option>
    <option>pastel-on-dark</option>
    <option>rubyblue</option>
    <option>solarized dark</option>
    <option>solarized light</option>
    <option>the-matrix</option>
    <option>tomorrow-night-bright</option>
    <option>tomorrow-night-eighties</option>
    <option>twilight</option>
    <option>vibrant-ink</option>
    <option>xq-dark</option>
    <option>xq-light</option>
    <option>zenburn</option>
</select>
</p>

<?php
$this->registerJs('
    var input = document.getElementById("select");
    function selectTheme() {
        var theme = input.options[input.selectedIndex].innerHTML;
        editor.setOption("theme", theme);
    }
    var choice = document.location.search && decodeURIComponent(document.location.search.slice(1));
    if (choice) {
        input.value = choice;
        editor.setOption("theme", choice);
    }
  
    CodeMirror.commands.autocomplete = function(cm) {
        cm.showHint({hint: CodeMirror.hint.anyword});   
    }
    
    // BISA DI OVERRIDE DI SINI
    // var editor = CodeMirror.fromTextArea(document.getElementById("code"), {
        // lineNumbers: true,
        // extraKeys: {"Ctrl-Space": "autocomplete"}
    // });
   ',  \yii\web\View::POS_END);
?>
<pre>
CodeMirror.fromTextArea( document.getElementById('w0'), {
        "lineNumbers":true,
        "matchBrackets":true,
        "mode":"application/x-httpd-php-open",  // mode: "application/x-httpd-php",
        "indentUnit":4,
        "indentWithTabs":true,
        "extraKeys":{
            "F11":function(cm){cm.setOption('fullScreen', !cm.getOption('fullScreen'));},
            "Esc":function(cm){if(cm.getOption('fullScreen')) cm.setOption('fullScreen', false);}
            }
})
</pre>
    