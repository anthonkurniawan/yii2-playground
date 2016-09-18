<?php
// use yiiExt\SrcCollect;
// print_r(SrcCollect); //SrcCollect->setStart(__LINE__); 

/* @var $this yii\web\View */
$this->title = 'My Yii Application';
?>
http://127.0.0.1/yii2doc/

<!----------------------------- TEST HTMLPURIFIER -------------------->

Accesss YII Base <br>
Get Alias : <code>Yii::getAlias('@vendor');</code> <br>

<br>
<?php echo Yii::$app->basePath; ?>
<?php echo Yii::$app->homeUrl; ?>
To get component <code>\Yii::$app->componentID</code> <br>
To get All component <code> \Yii::$app->getComponents(true);</code> <br>
 
$request = Yii::$app->request;
$request->getBodyParams()   or $request->bodyParams;  // returns all parameters
$request->getBodyParam('param') 
<?php 
echo Yii::getAlias('@vendor');

// $object = \Yii::createObject('MyClass', [$param1, $param2]);


$components = \Yii::$app->getComponents(false);	//print_r($components); 
foreach($components as $key=>$value){
    echo $key."<br>";
}
?>

<pre>
<b> Using Widget</b>
begin($config = [])
 
</pre>
 
<span class="glyphicon glyphicon-info-sign" title="Test Link2"></span>

<a href="#" data-toggle="tooltip" data-placement="top"  title="Tooltip default">tooltip default</a> <br>

<a href="#" data-toggle="popover" role="button" data-placement="top"  title="Popover default" data-content="popover content">popover default</a> 
<a tabindex="0" class="btn btn-xs btn-success" role="button" data-toggle="popover" data-trigger="focus" title="Dismissible popover" data-content="content..">Dismissible popover</a>
<button id="popover_custom" type="button" title="Popover Custom" data-placement="bottom">popover cutom</button>

<button type="button" class="close" data-dismiss="alert" aria-label="Close">
  <span aria-hidden="true">&times;</span>
</button>

<button type="button" id="myButton" data-loading-text="Loading..." class="btn btn-primary" autocomplete="off">
  Loading state
</button>

<button type="button" id="myStateButton" data-complete-text="finished!" class="btn btn-primary" autocomplete="off">Start</button>
<!--
<div data-spy="affix" data-offset-top="60" data-offset-bottom="200">
<br>1<br>1<br>1<br>1<br>1<br>1<br>1<br>1<br>1<br>1<br>1<br>1<br>1<br>1<br>1<br>1<br>1
</div>
Via JavaScript
Call the affix plugin via JavaScript:

Copy
$('#myAffix').affix({
  offset: {
    top: 100,
    bottom: function () {
      return (this.bottom = $('.footer').outerHeight(true))
    }
  }
})
-->
<?php 
$this->registerJs('
    $(function () {
        // TOOLTIP SAMPLE
        $(\'[data-toggle="tooltip"]\').tooltip();
        
        //TOOLTIP JQUERY-UI
        $(".tooltip1").tooltip({
        //items: "img, [data-geo], [title]",
        // position: {
			// my: "center bottom-20",
			// at: "center top",
			// using: function( position, feedback ) {
                // $( this ).css( position );
                // $( "<div>" )
					// .addClass( "arrow" )
                    // .addClass( feedback.vertical )
                    // .addClass( feedback.horizontal )
                    // .appendTo( this );
            // }
		// },
        content: function() {
            return "<h1>bol</h1>";
        },
	});
      
        // POPOVER SAMPLE
        $(\'[data-toggle="popover"]\').popover();
        
        //POPOVER CUSTOM
        $("#popover_custom").popover({
            trigger: "hover",  // click | hover | focus 
            html: true,
            placement: "auto", // top | bottom | left | right | auto.
            delay: 0, // using object { "show": 500, "hide": 100 }
            title: "string", // or function
            content: function(){
                return "<h1>content..</h1>";
            }
        });
        
        //ALERT
        $().alert();
        
        // BUTTON LOADING
        $("#myButton").on("click", function () { 
            var btn = $(this).button("loading");
            // business logic...
            btn.button("reset");
        });
        
        $("#myStateButton").on("click", function () {
            $(this).button("complete") // button text will be "finished!"
        });
    });

'
);
?>
                                               
<?php
echo yii\jui\Accordion::widget([
    'items' => [
        [
            'header' => 'Section 1',
            'content' => 'Mauris mauris ante, blandit et, ultrices a, suscipit eget...',
        ],
        [
            'header' => 'Section 2',
            'headerOptions' => ['tag' => 'h3'],
            'content' => 'Sed non urna. Phasellus eu ligula. Vestibulum sit amet purus...',
            'options' => ['tag' => 'div'],
        ],
    ],
    'options' => ['tag' => 'div'],
    'itemOptions' => ['tag' => 'div'],
    'headerOptions' => ['tag' => 'h3'],
    'clientOptions' => ['collapsible' => false],
]);
?>

<ul class="nav nav-tabs">
  <li role="presentation" class="active"><a href="#">Home</a></li>
  <li role="presentation"><a href="#">Profile</a></li>
  <li role="presentation"><a href="#">Messages</a></li>
</ul>

<ul class="nav nav-pills">  .nav-stacked.
  <li role="presentation" class="active"><a href="#">Home</a></li>
  <li role="presentation"><a href="#">Profile</a></li>
  <li role="presentation"><a href="#">Messages</a></li>
</ul>

<ul class="list-group">
  <li class="list-group-item">Cras justo odio</li>
  <li class="list-group-item">Dapibus ac facilisis in</li>
  <li class="list-group-item">Morbi leo risus</li>
  <li class="list-group-item">Porta ac consectetur ac</li>
  <li class="list-group-item">Vestibulum at eros</li>
</ul>

<ul class="list-group">
  <li class="list-group-item">
    <span class="badge">14</span>
    Cras justo odio
  </li>
</ul>

<div class="list-group">
  <a href="#" class="list-group-item active">
    Cras justo odio
  </a>
  <a href="#" class="list-group-item">Dapibus ac facilisis in</a>
  <a href="#" class="list-group-item">Morbi leo risus</a>
  <a href="#" class="list-group-item">Porta ac consectetur ac</a>
  <a href="#" class="list-group-item">Vestibulum at eros</a>
</div>

<div class="list-group">
  <a href="#" class="list-group-item disabled">
    Cras justo odio
  </a>
  <a href="#" class="list-group-item">Dapibus ac facilisis in</a>
  <a href="#" class="list-group-item">Morbi leo risus</a>
  <a href="#" class="list-group-item">Porta ac consectetur ac</a>
  <a href="#" class="list-group-item">Vestibulum at eros</a>
</div>

<ul class="list-group">
  <li class="list-group-item list-group-item-success">Dapibus ac facilisis in</li>
  <li class="list-group-item list-group-item-info">Cras sit amet nibh libero</li>
  <li class="list-group-item list-group-item-warning">Porta ac consectetur ac</li>
  <li class="list-group-item list-group-item-danger">Vestibulum at eros</li>
</ul>
<div class="list-group">
  <a href="#" class="list-group-item list-group-item-success">Dapibus ac facilisis in</a>
  <a href="#" class="list-group-item list-group-item-info">Cras sit amet nibh libero</a>
  <a href="#" class="list-group-item list-group-item-warning">Porta ac consectetur ac</a>
  <a href="#" class="list-group-item list-group-item-danger">Vestibulum at eros</a>
</div>

<div class="list-group">
  <a href="#" class="list-group-item active">
    <h4 class="list-group-item-heading">List group item heading</h4>
    <p class="list-group-item-text">...</p>
  </a>
</div>

 <?php
 use yii\bootstrap\Carousel;
echo Carousel::widget([
    'items' => [
        // the item contains only the image
        '<img src="http://twitter.github.io/bootstrap/assets/img/bootstrap-mdo-sfmoma-01.jpg"/>',
        // equivalent to the above
        ['content' => '<img src="http://twitter.github.io/bootstrap/assets/img/bootstrap-mdo-sfmoma-02.jpg"/>'],
        // the item contains both the image and the caption
        [
            'content' => '<img src="http://twitter.github.io/bootstrap/assets/img/bootstrap-mdo-sfmoma-03.jpg"/>',
            'caption' => '<h4>This is title</h4><p>This is the caption text</p>',
            //'options' => [...],
        ],
    ]
]);
 ?>
 
 <div class="btn-group" role="group" aria-label="...">
  <button type="button" class="btn btn-default">1</button>
  <button type="button" class="btn btn-default">2</button>

  <div class="btn-group" role="group">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
      Dropdown
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu">
      <li><a href="#">Dropdown link</a></li>
      <li><a href="#">Dropdown link</a></li>
    </ul>
  </div>
</div>

<!-- Single button -->
<div class="btn-group">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
    Action <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu">
    <li><a href="#">Action</a></li>
    <li><a href="#">Another action</a></li>
    <li><a href="#">Something else here</a></li>
    <li class="divider"></li>
    <li><a href="#">Separated link</a></li>
  </ul>
</div>

<!-- Split button -->
<div class="btn-group">
  <button type="button" class="btn btn-danger">Action</button>
  <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
    <span class="caret"></span>
    <span class="sr-only">Toggle Dropdown</span>
  </button>
  <ul class="dropdown-menu" role="menu">
    <li><a href="#">Action</a></li>
    <li><a href="#">Another action</a></li>
    <li><a href="#">Something else here</a></li>
    <li class="divider"></li>
    <li><a href="#">Separated link</a></li>
  </ul>
</div>

<div class="row">
  <div class="col-lg-6">
    <div class="input-group">
      <div class="input-group-btn">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action <span class="caret"></span></button>
        <ul class="dropdown-menu" role="menu">
          <li><a href="#">Action</a></li>
          <li><a href="#">Another action</a></li>
          <li><a href="#">Something else here</a></li>
          <li class="divider"></li>
          <li><a href="#">Separated link</a></li>
        </ul>
      </div><!-- /btn-group -->
      <input type="text" class="form-control" aria-label="...">
    </div><!-- /input-group -->
  </div><!-- /.col-lg-6 -->
  <div class="col-lg-6">
    <div class="input-group">
      <input type="text" class="form-control" aria-label="...">
      <div class="input-group-btn">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action <span class="caret"></span></button>
        <ul class="dropdown-menu dropdown-menu-right" role="menu">
          <li><a href="#">Action</a></li>
          <li><a href="#">Another action</a></li>
          <li><a href="#">Something else here</a></li>
          <li class="divider"></li>
          <li><a href="#">Separated link</a></li>
        </ul>
      </div><!-- /btn-group -->
    </div><!-- /input-group -->
  </div><!-- /.col-lg-6 -->
</div><!-- /.row -->
<pre style="">
<?php //print_r($this); ?>

Now, lets imagine we wish to deal with a ‘beforeSubmit’ event on our form:

php $form = ActiveForm::begin(
    [
        // make sure you set the id of the form
        'id' => $model->formName(),
        'action' => ['yada/create']
    ]
);
<!-- more stuff here -->
php ActiveForm::end();

In order to register your beforeSubmit event, we will have to register the event this way:

php
$js = <<<JS
// get the form id and set the event
$('form#{$model->formName()}').on('beforeSubmit', function(e) {
   var \$form = $(this);
   // do whatever here, see the parameter \$form? is a jQuery Element to your form
}).on('submit', function(e){
    e.preventDefault();
});
JS;
$this->registerJs($js);

SAMPLE NOT IMPLEMENTED FROM YII-1
=================================================
Yii::app()->clientScript->registerCoreScript('jquery.ui');
registerScriptFile(Yii::app()->baseUrl . '/js/common.js');

# ALLOW CROSS DOMAIN
if(isset($_SERVER['HTTP_ORIGIN'])){ 					//echo "HTTP_ORIGIN-->". $_SERVER['HTTP_ORIGIN'];
			header('Access-Control-Allow-Origin: *');	# for cross domain, IF WIITH "withCredentials" dont using wildcard "*"
			//header('Access-Control-Allow-Origin: http://localhost');	# ONLY RESTRICT TO "localhost"
			// respond to preflights
			if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
				// return only the headers and not the content
				// only allow CORS if we're doing a GET - i.e. no saving for now.
				if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']) && $_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'] == 'GET') {
					header('Access-Control-Allow-Headers: X_REST_CORS, X-Requested-With');	# USING "preflight" cth bisa: X_REST_CORS atau X-Requested-With
				}
				exit;
			}
		}
		
</pre>



<div class="site-index">

    <div class="jumbotron">
        <h1>Congratulations!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/doc/">Yii Documentation &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/forum/">Yii Forum &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/extensions/">Yii Extensions &raquo;</a></p>
            </div>
        </div>

    </div>
</div>
