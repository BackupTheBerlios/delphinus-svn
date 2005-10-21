{include file="header.tpl"}
<div class="section">
<h2>Feed Control</h2>
<ul>
{foreach from=$app.rss_list item=item}
<li>[<a href="{$base_url}/regist/{$item.id}">Edit</a>]&nbsp;<a href="{$base_url}/detail/{$item.id}">{$item.name|stripslashes}</a></li>
{/foreach}
</ul>

<h2>Crawler</h2>
<p>
<a href="{$base_url}/admin/crawl">Crawl Feeds</a>
</p>
</div>
{include file=footer.tpl}
