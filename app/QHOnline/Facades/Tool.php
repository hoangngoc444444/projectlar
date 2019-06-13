<?php
namespace App\QHOnline\Facades;
use App\QHOnline\ToolFactory;
use Illuminate\Support\Facades\Facade;

class Tool extends Facade {
    protected static function getFacadeAccessor() {
        return ToolFactory::class;
    }
}