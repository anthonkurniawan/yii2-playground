<?php
use yii\helpers\Markdown;
$text = '<?php $x="a"; ?>';
echo "\n", Markdown::process($text); 
echo "\n", Markdown::process($text); // use original markdown flavor
echo "\n", Markdown::process($text, 'gfm'); // use github flavored markdown
echo "\n", Markdown::process($text, 'extra'); // use markdown extra
echo "\n", Markdown::processParagraph($text, $flavor = 'original');
?>

<pre>
 * Markdown provides an ability to transform markdown into HTML.
 *
 * Basic usage is the following:
 *
 * ```php
 * $myHtml = Markdown::process($myText); // use original markdown flavor
 * $myHtml = Markdown::process($myText, 'gfm'); // use github flavored markdown
 * $myHtml = Markdown::process($myText, 'extra'); // use markdown extra
 * ```
 *
 * You can configure multiple flavors using the [[$flavors]] property.
 *
 * For more details please refer to the [Markdown library documentation](https://github.com/cebe/markdown#readme).
 </pre>