<?php
namespace App\Models\Intrasm;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class User extends Model{
    
    protected $connection="intrasm";
    protected $table="tbl_user";
    protected $primaryKey="ID_USER";

    use SoftDeletes;

    protected $appends = [
        'view_url',
        'delete_url'
    ];

    public function getDeleteUrlAttribute() {
        return route( 'user.delete', [ 'id' => $this->ID_USER ] );
    }
    
    public function getViewUrlAttribute() {
        return route( 'user.view', [ 'id' => $this->ID_USER ] );
    }
}