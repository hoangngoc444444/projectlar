<?php
namespace App\QHOnline;

class ToolFactory {
    public function getThumbnail($filename, $suffix = '_thumb') {
        if ($filename) {
//            2017-04/b2d497b69515658e67d80d135b7d0b54.png
            return preg_replace("/(.*)\.(.*)/i", "$1{$suffix}.$2", $filename);
        }
        return '';
    }
}