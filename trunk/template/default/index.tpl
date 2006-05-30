{include file="header.tpl"}
<div id="sidebar">
<h2 class="sitename">Feeds</h2>
<ul>
{foreach from=$app.rss_list item=item}
<li><a href="{$base_url}/detail/{$item.id}">{$item.name|stripslashes}</a></li>
{/foreach}
</ul>
<h2 class="sitename">Powered</h2>
<p><a href="http://www.php.net"><img src="{$base_url}/../res/php5-power-micro.png" alt="PHP5 powered" /></a></p>
<p><a href="http://pear.php.net"><img src="{$base_url}/../res/pear_powered.png" alt="pear powered" /></a></p>
<p><a href="http://ethna.jp"><img src="{$base_url}/../res/ethna_logo.png" alt="Ethna powered" /></a></p>
 </div>
<div class="block">
{foreach from=$app.entries key=key  item=item}
<div class="section">
<h2 class="blogtitle"><a href="{$item.link}">{$item.title}</a> - {$item.date}</h2>
<h3 class="sitename"><a href="{$app.rss_list[$item.rss_id].url}">{$app.rss_list[$item.rss_id].name|stripslashes}</a></h3>
<p>
{$app_ne.entries[$key].description|stripslashes}
</p>
{if $app.config.delphinus_comment == true}
{include file=comment.tpl}
{/if}
</div>
{/foreach}
</div>
{include file=footer.tpl}        
