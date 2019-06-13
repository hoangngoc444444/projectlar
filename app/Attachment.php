<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    //
    protected $table = 'attachments';
    protected $fillable = [
        'product_id',
        'mine',
        'type',
        'path'
    ];
    public function product(){
        return $this->belongsTo('App\Product','product_id','id');
    }
   
}
