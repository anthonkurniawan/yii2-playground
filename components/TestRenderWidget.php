<?php
namespace app\components; 

use yii\base\Widget;
use yii\helpers\Html;

/**
Rendering in Widgets
Within widgets, you may call the following widget methods to render views.

render(): renders a named view.
renderFile(): renders a view specified in terms of a view file path or alias.
For example,
**/
class TestRenderWidget extends Widget
{
    public $items = [];

    public function run()
    {
        // renders a view named "list"
        return $this->render('list', [
            'items' => $this->items,
        ]);
    }
}

?>