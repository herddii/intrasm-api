<?php
namespace App\Http\Controllers\Dashboard;
use App\User;
use App\Models\Saleskit\Programperiode; 
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Models\Saleskit\Contentnew;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Carbon\Carbon;

class DashboardController extends Controller 
{
    public function getIndex(Request $request,$id_kategori){
        try {
            $isi_portal = \DB::table('portal_berita as b')
            ->leftJoin('portal_bankfoto as c','c.id_portal','b.id_portal')
            ->where('b.id_kategori',$id_kategori)
            // ->where('b.type_video','CITRAPARIWARA') 
            ->whereNull('b.deleted_at')
            ->orderBy('b.created_at','DESC')
            ->groupBy('b.id_portal')
            ->paginate(6);
            return response($isi_portal,200);
        } catch (\Exception $e){
            return response(array('data'=>'Error at Backend'));
        } 
    }

    public function getkategori(Request $request){
        try {
            $kategori = \DB::table('portal_berita as a')->selectRaw('a.id_kategori,b.nama_kategori')
            ->leftJoin('portal_kategori as b','b.id_kategori','a.id_kategori')
            ->groupBy('a.id_kategori')->get();
            return response($kategori,200);
        } catch (\Exception $e){
            return response(array('data'=>'Error at Backend'));
        }    
    }


    public function detail_article(Request $request, $id, $id_kategori){
        try {
            $berita=\App\Models\Saleskit\Portalberita::with('portal_tag','kategori','advertiser')
            ->where('slug','=',$id_kategori)->first();

            $market=\App\Models\Saleskit\Portalberita::with('portal_tag','kategori','advertiser','brand')
            ->where('id_kategori',$id)
            ->inRandomOrder()
            ->limit(4)
            ->get();

            return response([
                'berita' => $berita,
                'market' => $market
            ],200);
        } catch (\Exception $e){
            return response(array('data'=>'Error at Backend'));
        }
    }

    public function version(Request $request){
        try {
            $url = 'https://play.google.com/store/apps/details?id=com.saleskit.app';
            $session = curl_init();
            curl_setopt($session, CURLOPT_URL, $url);
            curl_setopt($session, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($session, CURLOPT_CONNECTTIMEOUT, 5);   
            $cek = curl_exec($session);
            curl_close($session);
            preg_match('/<span[^>]+class="htlgb"[^>]*>(.*)<\/span>/', $cek, $title);
            $cg = explode('<span class="htlgb">',$title[0]);
            $res = preg_replace("/[^0-9-.]/", "", $cg[8]);
            $sendto = Array('version' => $res);
            return response($sendto,200); 
        } catch (\Exception $e){
            return response(array('data'=>'Error at Backend'));
        }    
    }

    public function getfoto(Request $request,$id){
    	try {
    		 $var=\App\Models\Saleskit\Portal_bankfoto::where('id_portal',$id)
	       ->with('portalberita')
	       ->get(); 
	       return response($var,200);   
    	} catch (\Exception $e){
    	   return response(array('data'=>'Error at Backend'));
    	}
	       
    }

   
}