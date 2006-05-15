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
            <h3 class="comment">Comment</h3>
            {foreach name=comment from=$item.comments item=comment}
            {if $smarty.foreach.comment.first}
            <dl>
            {/if}
            <dt>{$comment.name|escape} - {$comment.timestamp}</dt>
            <dd><p>{$comment.comment|escape|nl2br}</p></dd>
            {/foreach}
            {if $smarty.foreach.comment.last}
            </dl>
            {/if}

            <form method="post" action="{$base_url}/comment/{$item.id}">
            <dl>
            <dt>{form_name name="name"}</dt><dd>{form_input name="name"}</dd>
            <dt>{form_name name="url"}</dt><dd>{form_input name="url"}</dd>
            <dt>{form_name name="comment"}</dt><dd>{form_input name="comment" attr='size="60"'}</dd>
            <dt>{form_name name="submit"}</dt><dd>{form_input name="submit"}</dd>
            </dl>
            </form>

            </div>
            {/foreach}
        </div>
{include file=footer.tpl}        
