{include file=header.tpl}
<div class="section">
<h2>Delete Feed</h2>
<p>
以下のフィードを削除してもよいですか?<br>
フィードを削除すると該当する記事、それに対するコメントもすべて削除されます。
</p>
<ul>
<li>{$app.rss.name}</li>
<li><a href="{$app.rss.url}">{$app.rss.url}</a></li>
<li>{$app.rss.author}</li>
</ul>
<p>
<form method="post">
{form_input name="submit"}
</form>
</p>
</div>
{include file=footer.tpl}
