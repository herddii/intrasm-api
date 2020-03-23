<?php
namespace App\Http\Controllers\Master;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Intrasm\Userclient;
use App\Models\Intrasm\User;
class UserCOntroller extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function index(Request $request)
    {
        $model=User::whereNull('deleted_at')
        ->where('active', 1);
        if($request->has('q')){
            $model=$model->where('firstname','like','%'.$request->input('q').'%');
        }
        $model=$model->paginate(25);
        
        return response()->json($model);
    }
    public function show(Request $request)
    {
        // return $request->input('user');
        
        $user = implode('","',$request->input('user'));
        
        $asur = '"'.$user.'"';
        
        $userget = user($request->bearerToken());
        
        $posisi= $userget->POSITION;

        if($posisi=="AM"){
            $pilih1="data_1.id_am";
            $pilih2="b.id_am";
        }else if($posisi=="SGM"){
            $pilih1="data_1.id_am";
            $pilih2="b.id_am";
        }else if($posisi=="SM"){
            $pilih1="data_1.id_sm";
            $pilih2="b.id_sm";
        }else{
            $pilih1="data_1.id_gm";
            $pilih2="b.id_gm";
        }


        return $model=\DB::select("select 
        a.*, 
        sum(if(data1.type='PLAN',data1.plan,0)) as plan, 
        sum(if(data1.type='REPORT',data1.report,0)) as report,
        c.POSITION,
        c.USER_NAME,
        c.IMAGES
        from tbl_user_structure a
        left join (select a.* ,b.id_am, b.id_sgm,
           IF(b.type = 'PLAN', count(b.id_cam), 0) as plan, 
           IF(b.type = 'REPORT', count(b.id_cam), 0) as report,
           b.`type` from tbl_user a 
           left join cam b on b.id_am = a.USER_ID
           where a.USER_ID in (".$asur.")
           and b.deleted_at is null
           group by b.`type`, a.USER_ID) as data1 on data1.id_am = a.staff
        left join tbl_user c on c.USER_ID = a.staff
        where a.staff in (".$asur.")
        group by a.staff");
        // return $model=\DB::table('tbl_user as a')->selectRaw('*')->whereIn('a.USER_ID',$request->input('user'))->get();
    }
    public function destroy($id)
    {
        $model=User::find($id);
        $del=$model->delete();
        if($del){
            $data=array(
                'success'=>true,
                'message'=>'Data deleted'
            );
        }else{
            $data=array(
                'success'=>false,
                'message'=>'Data failed to deleted'
            );
        }
        return response()->json($data);
    }
}