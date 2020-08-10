<?php

function current_page($uri = '/')
{
    return request()->is($uri . '*');
}
