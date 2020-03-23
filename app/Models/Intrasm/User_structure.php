<?php
namespace App\Models\Intrasm;
use Illuminate\Database\Eloquent\Model;
class User_structure extends Model
{
    protected $connection="intrasm";	
    protected $table="tbl_user_structure";
    protected $primaryKey="id";

    public function name_ae(){
        return $this->belongsTo('App\User','staff', 'USER_ID')
        ->selectRaw('USER_ID, USER_NAME, POSITION, IMAGES');
    }

    public function name_sgm(){
        return $this->belongsTo('App\User','head', 'USER_ID')
        ->selectRaw('USER_ID, USER_NAME, POSITION, IMAGES');
    }

    public function name_sm(){
        return $this->belongsTo('App\User','manager', 'USER_ID')
        ->selectRaw('USER_ID, USER_NAME, POSITION, IMAGES');
    }

    public function name_gm(){
        return $this->belongsTo('App\User','gm', 'USER_ID')
        ->selectRaw('USER_ID, USER_NAME, POSITION, IMAGES');
    }
}
