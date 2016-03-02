<?php
function siteHeader($_linkCss)
{
  $_link = '';
  foreach($_linkCss as $link)
    $_link .= '
    <link href="'. $link .'" rel="stylesheet">';

  include TEMPLATE . 'header.php';
}

siteHeader($_linkCss);