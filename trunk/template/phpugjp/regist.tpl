{include file="header.tpl"}
<div id="sidebar">
<ul>
{foreach from=$app.rss_list item=item}
<li><a href="{$base_url}/detail/{$item.id}">{$item.name|stripslashes}</a></li>
{/foreach}
</ul>
</div>
<div class="section">
{if count($errors)}
 <ul>
  {foreach from=$errors item=error}
   <li>{$error}</li>
  {/foreach}
 </ul>
{/if}

<h2>Register New Feed</h2>
<form method="post">
{form_name name="name"}:{form_input name="name"}<br>
{form_name name="author"}:{form_input name="author"}<br>
{form_name name="url"}:{form_input name="url"}<br>
{form_input name="submit"}
</form>
</div>
{include file=footer.tpl}
