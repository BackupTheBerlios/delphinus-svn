{include file="header.tpl"}
        <div id="sidebar">
        <h2 class="sitename">Feeds</h2>
        <ul>
        {foreach from=$app.rss_list item=item}
        <li><a href="{$base_url}/detail/{$item.id}">{$item.name|stripslashes}</a></li>
        {/foreach}
        </ul>
   <h2 class="sitename">はてなブックマーク - タグ「php」を含む注目エントリー</h2>
{rss encoding_to="UTF-8" url="http://b.hatena.ne.jp/t/php?mode=rss&sort=hot"}
   <h2 class="sitename">del.icio.us - タグ「php」を含む注目エントリー</h2>
{rss encoding_to="UTF-8" url="http://del.icio.us/rss/tag/php"}
<h2 class="sitename">Powered</h2>
    <p><a href="http://www.php.net"><img src="{$base_url}/../res/php5-power-micro.png" alt="PHP5 powered" /></a></p>
    <p><a href="http://pear.php.net"><img src="{$base_url}/../res/pear_powered.png" alt="pear powered" /></a></p>
    <p><a href="http://ethna.jp"><img src="{$base_url}/../res/ethna_logo.png" alt="Ethna powered" /></a></p>
 
        </div>
        <div class="block">
            
            {foreach from=$app.entries key=key  item=item}
            <div class="section">
            <h2 class="sitename"><a href="{$app.rss_list[$item.rss_id].url}">{$app.rss_list[$item.rss_id].name|stripslashes}</a></h2>
            <h3 class="blogtitle"><a href="{$item.link}">{$item.title}</a> - {$item.date}</h3>
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
