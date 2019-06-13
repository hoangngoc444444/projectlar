<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    protected $table ='comments';
    protected $fillable = [
        'product_id',
        'name',
        'email',
        'content',
        'ratings'
    ];
    public function product(){
        return $this->belongsTo('App\Product','product_id','id');
    }
}
