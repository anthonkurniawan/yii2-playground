<?php 
use yii\helpers\Html; 
use yii\helpers\Url;
?>

<?php foreach($comments as $comment): ?>
<div class="comment" id="c<?php echo $comment->id; ?>">

	<?php 
    // echo Url::current();
    // echo "<br>". Url::to( [ Url::current(), '#'=>'c'.$comment->id] );
    //echo Html::a( '#'.$comment->id, Url::to( [ Url::current(), '#'=>'c'.$comment->id] ));
    echo Html::a( '#'.$comment->id,  '#comment');
    // echo Html::link("#{$comment->id}", $comment->getUrl($post), array(
		// 'class'=>'cid',
		// 'title'=>'Permalink to this comment',
	// )); 
    ?>

	<div class="author">
		<?php echo $comment->authorName; ?> says:
	</div>

	<div class="time">
		<?php echo date('F j, Y \a\t h:i a',$comment->create_time); ?>
        <?= Yii::$app->formatter->asDatetime( $comment->create_time, 'medium'); ?>
	</div>

	<div class="content">
		<?php echo nl2br(Html::encode($comment->content)); ?>
	</div>

</div><!-- comment -->
<?php endforeach; ?>