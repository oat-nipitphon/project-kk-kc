<?php

namespace App\Http\Controllers\WhsCenter;


use App\Http\Controllers\Controller;
use App\Good;
use App\GoodPrice;
use App\GoodWarehouse;
use App\Warehouse;
use App\Member_type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SetPriceGoodController extends Controller
{
   
    public function index (){
        return view('whs-center.goods.set-price-goods.index');
    }

    public function showGoodModal(){
        return datatables()->of(
           Good::query()->with('type', 'unit')->whereNotIn('type_id', [12])->where('is_check_price', 'not like', 1)
        )->toJson();
    }

    public function showGood(){
        return datatables()->of(
            Good::query()->with('type', 'unit')->where('is_check_price', 'like', 1)->orderBy('updated_at', 'desc')
        )->toJson();
    }


    public function checkOutGood(Request $req)
    {
        $this->validate($req,['id' => 'required']);
        $good_ids = $req->id;
        $warehouses = Warehouse::all();
        
        DB::beginTransaction();
        
        foreach($good_ids as $good_id) {
            $good = Good::find($good_id);
            $good->is_check_price = 1;
            $good->save();

            $checkGoodwarehouse = GoodWarehouse::where('good_id', $good_id);
            if($checkGoodwarehouse){
                $checkGoodwarehouse->delete();
            }
            foreach($warehouses as $warehouse) {
                $goodWarehouse = GoodWarehouse::where('good_id', $good_id)->first();
                $goodWarehouse = new GoodWarehouse;
                $goodWarehouse->good_id = $good_id;
                $goodWarehouse->warehouse_id = $warehouse->id;
                $goodWarehouse->save();
            }
        }

        DB::commit();
        return redirect()->route("whs-center.goods.set-price-goods.index")->with('status', 'เพิ่มข้อมูลใหม่เรียบร้อยแล้ว!');
    }

    public function showWarehouse($good_id){

        $good = Good::find($good_id);
        $goodWarehouses = GoodWarehouse::query()->where('good_id', $good_id)->with('warehouse')->get();

        return view('whs-center.goods.set-price-goods.base-price', compact('good', 'goodWarehouses'));
    }


    public function deleteGood(Request $req){

            $this->validate($req,['good_id' => 'required']);
            $good_id = $req->good_id;
    
            $good = Good::find($good_id);
            $good->is_check_price = 0;
         
            $goodWarehouses = GoodWarehouse::where('good_id', $good_id)->get();
            foreach ($goodWarehouses as $goodWarehouse) {

                $goodPrice = GoodPrice::where('good_warehouse_id', $goodWarehouse->id);
                if($goodPrice){
                    $goodPrice->delete();
                }
            }
            $goodWarehouses = GoodWarehouse::where('good_id', $good_id);
           
            if($good->save() && $goodWarehouses->delete()){
                $data = [
                    'title' => 'ลบสำเร็จ',
                    'msg' => 'ลบการตั้งค่าราคาสินค้าสำเร็จ',
                    'status' => 'success',                
                ]; 
            }else{
                $data = [
                    'title' => 'เกิดข้อผิดพลาด',
                    'msg' => 'ลบการตั้งค่าราคาสินค้าไม่สำเร็จ',
                    'status' => 'error',                
                ];
            } 
            return $data;     
    }

    public function setBasePrice(Request $req, $good_id) {
        
            $goodWarehouses = $req -> goodWarehouses;

            DB::beginTransaction();
        
            foreach($goodWarehouses as $goodWarehouse_id => $value) {
                $data = GoodWarehouse::where('id', $goodWarehouse_id)->where('good_id',$good_id)->first();
                $data->base_price = $value;
                $data->save();
            }

            DB::commit();

            return redirect()->route("whs-center.goods.set-price-goods.showWarehouse", $good_id )->with('status', 'บันทึกข้อมูลใหม่เรียบร้อยแล้ว!');
    }

    public function infoGood(Request $req){

        $good_id = $req->good_id;
        $good_warehouse_id = $req->good_warehouse_id;
        $warehouse_id = $req->warehouse_id;
        
        $mem_types = Member_type::all();
        $good = Good::find($good_id);
        $warehouse = Warehouse::find($warehouse_id);

        $good_price = []; 

        foreach ($mem_types as $mem_type) {
            $goodPrice = GoodPrice::where(['good_warehouse_id' => $good_warehouse_id,'member_type_id' => $mem_type->id])->first();
            if($goodPrice){
                $data = [
                    'good' => $good,
                    'warehouse' => $warehouse,
                    'good_warehouse_id' => $good_warehouse_id,
                    'member_type_name' => $mem_type->name,
                    'member_type_id' => $mem_type->id,
                    'good_price_id' => $goodPrice->id,
                    'good_price' => $goodPrice->price,
                ];
            }else{
                $data = [
                    'good' => $good,
                    'warehouse' => $warehouse,
                    'good_warehouse_id' => $good_warehouse_id,
                    'member_type_name' => $mem_type->name,
                    'member_type_id' => $mem_type->id,
                    'good_price_id' => '',
                    'good_price' => '',
                ];
            }
            array_push($good_price,$data);
        }
        return $good_price;
    }


    public function setPrice(Request $req)
    {
        
        $good_warehouse_id = $req->good_warehouse_id;
        $good_prices = $req->good_prices;
        $check_good_price = GoodPrice::where('good_warehouse_id', $good_warehouse_id)->first();
        if ($check_good_price) {
            $check_good_price = GoodPrice::where('good_warehouse_id', $good_warehouse_id);
            $check_good_price->delete();
        }

        $check = false;
        DB::beginTransaction();
        foreach ($good_prices as $good_price) {
            $goodPrice = GoodPrice::where('good_warehouse_id', $good_warehouse_id)->where('member_type_id', $good_price['member_type_id'])->first();
            $goodPrice = new GoodPrice;
            $goodPrice->good_warehouse_id = $good_warehouse_id ;
            $goodPrice->member_type_id = $good_price['member_type_id'];
            $goodPrice->price = $good_price['price'];
            if($goodPrice->save()){
                $check = true;
            }else{
                $check = false;
            }
        }
        DB::commit();
        if($check){
            $data = [
                'title' => 'บันทึกสำเร็จ',
                'msg' => 'บันทึกราคาสินค้าสำเร็จ',
                'status' => 'success',                
            ]; 
        }else{
            $data = [
                'title' => 'เกิดข้อผิดพลาด',
                'msg' => 'บันทึกราคาสินค้าสำเร็จไม่สำเร็จ',
                'status' => 'error',                
            ];
        } 
        
        return $data;
    }
}
