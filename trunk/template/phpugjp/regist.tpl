{include file="header.tpl"}
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
