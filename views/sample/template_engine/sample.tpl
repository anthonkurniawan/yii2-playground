<h2> Basic Twig </h2>

<p>
    <b>A variable : </b> {$var}
</p>

 <b>Array : </b> 
<ul id="navigation">
    {section name=item loop=$items}
        <li><a href="{$items[item].href}">{$items[item].label}</a></li>
    {/section}
 </ul>
