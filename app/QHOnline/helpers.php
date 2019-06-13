<?php
use App\QHOnline\Facades\Tool;
if (!function_exists('get_thumbnail')) {
    function get_thumbnail($filename, $suffix = '_thumb') {
        return Tool::getThumbnail($filename, $suffix);
    }
}


