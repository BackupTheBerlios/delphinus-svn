<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="shortcut icon" href="{$base_url}/../theme/phpugjp/images/favicon.ico" />
    <link rel="stylesheet" type="text/css" media="screen, tv, projection" href="{$base_url}/../theme/phpugjp/phpugjp.css" />
        <title>{$title}</title>
    </head>
    <body>
    <div id="header-topleft"><img src="{$base_url}/../theme/phpugjp/images/top_bar_left.png" /></div>
    <div id="header-topright"><img src="{$base_url}/../theme/phpugjp/images/top_bar_right.png" /></div>
    <div id="header-topmain"><a href="{$base_url}"><img src="{$base_url}/../theme/phpugjp/images/top_logo.png" /></a></div>
        <div id="topmenu">
                <a href="{$base_url}">Home</a>
                <a href="{$base_url}/admin">Admin</a>
                {if $session.name}
                <a href="{$base_url}/logout">Logout</a>
                {$session.name}
                {/if}
        </div>
