{include file="header.tpl"}
{if count($errors)}
<ul>
{foreach from=$errors item=error}
<li>{$error}</li>
{/foreach}
</ul>
{/if}
