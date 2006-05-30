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
