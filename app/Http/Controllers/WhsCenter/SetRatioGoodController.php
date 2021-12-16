<?php
namespace App\Http\Controllers\WhsCenter;

use App\Http\Controllers\Controller;
use App\Good;
use App\GoodRatio;
use App\BaseRatio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetRatioGoodController extends Controller
{

     public function index (){
        return view('whs-center.goods.set-ratio-goods.index');
    }

    public function showGoodModal(){
        return datatables()->of(
            Good::query()->with('type', 'unit')->whereNotIn('type_id', [12])->where('is_check_ratio', 'not like', 1)
        )->toJson();
    }

    public function showGoodRatio(){
        return datatables()->of(
            GoodRatio::query()->with('good', 'good.unit', 'good.type')->orderBy('updated_at', 'desc')
        )->toJson();
    }

    public function storeGoodRatio(Request $req){

        $this->validate($req,['good_id' => 'required', 'good_ratio' => 'required']);
        $check = false;
      
        $good_ratio = GoodRatio::where('good_id', '=', $req->good_id)->firstOrFail();
        if($good_ratio){
            $good_ratio->ratio = $req->good_ratio;
            if($good_ratio->save()){$check = true;}

        }else{
            $good_ratio = new GoodRatio;
            $good_ratio->good_id = $req->good_id;
            $good_ratio->ratio = $req->good_ratio;
            if($good_ratio->save()){$check = true;}
        }
        if($check){
            $data = [
                'title' => 'บันทึกสำเร็จ',
                'msg' => 'บันทึกแต้มสินค้าสำเร็จ',
                'status' => 'success',                
            ]; 
        }else{
            $data = [
                'title' => 'เกิดข้อผิดพลาด',
                'msg' => 'บันทึกแต้มสินค้าไม่สำเร็จ',
                'status' => 'error',                
            ];
        }      
       return $data;
    }

    
    public function deleteGoodRatio(Request $req){
        $this->validate($req,['good_id' => 'required']);
        $check = false;
        $good = Good::find($req->good_id);
        $good->is_check_ratio = 0;

        $good_ratio = GoodRatio::where('good_id', '=', $req->good_id)->firstOrFail();
        if($good->save() && $good_ratio->delete()){ $check = true;}

        if($check){
            $data = [
                'title' => 'ลบสำเร็จ',
                'msg' => 'ลบแต้มสินค้าสำเร็จ',
                'status' => 'success',                
            ]; 
        }else{
            $data = [
                'title' => 'เกิดข้อผิดพลาด',
                'msg' => 'ลบแต้มสินค้าไม่สำเร็จ',
                'status' => 'error',                
            ];
        } 
        return $data;     
    }

    public function checkOutGood(Request $req)
    {
        $this->validate($req,['id' => 'required']);
        DB::beginTransaction();
        foreach($req->id as $value) {
            $good = Good::find($value);
            $good->is_check_ratio = 1;
            $good->save();

            if(GoodRatio::find($value)){
                $good_ratio = GoodRatio::find($value);
                $good_ratio->ratio = 0;
                $good_ratio->save();
            }else{
                $good_ratio = new GoodRatio;
                $good_ratio->good_id = $value;
                $good_ratio->ratio = 0;
                $good_ratio->save();
            }
        }
        DB::commit();
        return redirect()->route("whs-center.goods.set-ratio-goods.index");
    }

    public function showBaseRatio(){

        $base_ratios = BaseRatio::find(1);
        $base_ratio =  $base_ratios->ratio;

        return $base_ratio;
    }

    
    public function storeBaseRatio(Request $req){

        $this->validate($req,['ratio' => 'required']);
        $ratio = $req->ratio;
        $base_ratio = BaseRatio::find(1);
        $base_ratio->ratio = $ratio;

        if($base_ratio->save()){
            $data = [
                'title' => 'บันทึกสำเร็จ',
                'msg' => 'บันทึกแต้มมาตรฐานสำเร็จ',
                'status' => 'success',                
            ]; 
        }else{
            $data = [
                'title' => 'เกิดข้อผิดพลาด',
                'msg' => 'บันทึกแต้มมาตรฐานไม่สำเร็จ',
                'status' => 'error',                
            ];
        } 
       
        return $data;
    }


}
