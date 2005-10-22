<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="shortcut icon" href="{$base_url}/../theme/default/images/favicon.ico" />
<link rel="stylesheet" type="text/css" media="screen, tv, projection" href="{$base_url}/../theme/default/default.css" />
<title>{$title}</title>
</head>
<body>
<div id="header">
<h1>{$title}</h1>
<div id="topmenu">
<a href="{$base_url}">Home</a>
<a href="{$base_url}/regist">Add Feed</a>
{if $session.name}
<a href="{$base_url}/logout">Logout</a>
{$session.name}
{/if}
</div>
</div>
