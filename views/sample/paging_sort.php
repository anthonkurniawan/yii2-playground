<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = Yii::t('app', 'Paging & Sort Sample');
$this->params['breadcrumbs'][] = $this->title;
?>
<h2>Pagination and Sort</h2>

Sort : <?php echo $sort->link('id') . ' | ' . $sort->link('username'); ?>   

<ul>
<?php foreach ($data as $user): ?>
    <li>
        <?= Html::encode("{$user->username} ({$user->email})") ?>:
        <?= $user->username ?>
    </li>
<?php endforeach; ?>
</ul>

<?php
// \Yii::$container->set('yii\widgets\LinkPager', [
    // 'maxButtonCount' => 1,
// ]);
?>

<?= LinkPager::widget([
    'pagination' => $pagination,
    'options'=> ['class' => 'pagination'],
    'linkOptions'=> [],
    'firstPageCssClass'=> 'first',
    'lastPageCssClass'=> 'last',
    'prevPageCssClass'=> 'prev',
    'nextPageCssClass'=> 'next',
    'activePageCssClass'=> 'active',
    'disabledPageCssClass'=> 'disabled',
    'maxButtonCount'=> 10,
    'nextPageLabel'=> '&raquo,',
    'prevPageLabel'=> '&laquo,',
    'firstPageLabel'=> false,
    'lastPageLabel'=> false,
    'registerLinkTags'=> false,
    'hideOnSinglePage'=> true,
]) ?>

<br>

<span class="btn btn-sm btn-default pull-left" onclick="$('.sort').toggle();">Show short obj</span>
<span class="btn btn-sm btn-default pull-left" onclick="$('.paging').toggle();">Show pagination obj</span>
<div class="clearfix"></div>

<pre class="no_dis sort"><?php print_r($sort); ?></pre>
<pre class="no_dis paging"><?php print_r($pagination); ?></pre>

<pre>
<b> NOTE : </b>
- You can use default like : <code>LinkPager::widget(['pagination' => $pagination])</code>
- use with "maxButtonCount" <code>LinkPager::widget(['pagination' => $pagination, 'maxButtonCount' => 1])</code>
- OR set "maxButtonCount" as global in "bootstrap" in config/web.php
<code>
 'bootstrap' => ['log', 'linkPager'],
 
 'components' => [
    'linkPager' => \Yii::$container->set('yii\widgets\LinkPager', [
        'maxButtonCount' => 1,
    ]),
    ...
]
</code>    
<?php
print_r($pagination);
?>
</pre>