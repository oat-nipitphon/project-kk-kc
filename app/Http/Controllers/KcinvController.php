<?php

namespace App\Http\Controllers;
use App\GoodAudit;
use App\Type;
use App\Good;
use App\GoodView;
use App\Warehouse;
use App\Requisition;
use App\WarehouseGood;
use App\WarehouseGoodBalance;
use App\RequisitionGood;
// use DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KcinvController extends Controller
{
    public function dashboard(){
        $goodAudits = GoodAudit::all();
        return view('kc-inv.dashboard', compact('goodAudits'));
    }

    public function index(){
        $goodAudits = GoodAudit::all();
        return view('kc-inv.requisition.index', compact('goodAudits'));
    }

    public function viewCreate(){
        $types = Type::all();
        $warehouses = Warehouse::all();
        return view('kc-inv.requisition.create', compact('types','warehouses'));
    }

    public function listGoods(Request $req){
        // $data['data'] =''
        $type_id = $req->type_id;
        $list = $data['data'] = GoodView::with('good.unit')
        ->whereHas('good', function ($query) use ($type_id){
            $query->where('type_id', $type_id);
        });
        $list->where('warehouse_id', session('warehouse')['id'] );
        $data['data'] = $list->get();
        return $data;
    }

    public function configGoods(Request $req){

        $id = $req->select;

        $list = $data['data'] = GoodView::with('good.unit')
        ->whereHas('good', function ($query) use ($id){
            $query->whereIn('warehouse_good_id', $id);
        });
        // $list->('type_id', );
        $data['data'] = $list->get();
        return $data;
    }

    public function store(Request $req)
    {
        foreach($req->good_code as $value){
            $requisition = new Requisition;
            $requisition->code = $value;
            $requisition->save();
        }
        // DB::beginTransaction();
        // return $req->all();
        // foreach ($req->warehouse_good_id as $key => $value) {
        //     $goodview = GoodView::where('warehouse_good_id', $value)->first();
        //     if ($goodview->balance_amount < $req->amount[$key]) {
        //         return back()->withErrors('สินค้าไม่เพียงพอ');
        //     }
        // }
        //     foreach($req->good_code)
        // Requisition
        // //$warehouse = Warehouse::find($req->warehouse_id);
        // foreach($req->good_code as  $value){
        //     $requisition = Requisition::find($value);
        // $requisition->code = $req->good_code;
        // $requisition->document_at = Carbon::createFromFormat('d/m/Y', $req->document_at);
        // $requisition->take_id = $req->take_id;
        // $requisition->department_id = $warehouse->department_id;
        // $requisition->warehouse_id = $warehouse->id;
        // $requisition->detail = $req->detail;
        // $requisition->created_user_id = session('user')['id'];
    //     $requisition->save();
    // }

        // foreach ($req->warehouse_good_id as $key => $value) {
        //     $warehouse_good = WarehouseGood::find($value);

        //     $requisition_good = new RequisitionGood;
        //     $requisition_good->requisition_id = $requisition->id;
        //     $requisition_good->warehouse_good_id = $warehouse_good->id;
        //     $requisition_good->good_id = $warehouse_good->good_id;
        //     $requisition_good->amount = $req->amount[$key];
        //     $requisition_good->unit_id = $warehouse_good->good->unit_id;
        //     $requisition_good->save();

        //     $warehouse_good_balance = new WarehouseGoodBalance;
        //     $warehouse_good_balance->warehouse_good_id = $warehouse_good->id;
        //     $warehouse_good_balance->amount = -$req->amount[$key];
        //     $warehouse_good_balance->requisition_good_id = $requisition_good->id;

        //     if ($warehouse_good->warehouseGoodBalances->last()->amount_balance - $req->amount[$key] == 0) {
        //         $warehouse_good_balance->cost = -$warehouse_good->warehouseGoodBalances->last()->cost_balance;
        //         $warehouse_good_balance->amount_balance = 0;
        //         $warehouse_good_balance->cost_balance = 0;
        //         $warehouse_good_balance->ratio_cost = $warehouse_good->warehouseGoodBalances->last()->cost_balance / $req->amount[$key];
        //     } else {
        //         $warehouse_good_balance->ratio_cost = $warehouse_good->warehouseGoodBalances->last()->ratio_cost;
        //         $warehouse_good_balance->cost = bcmul(-1.00, $warehouse_good->warehouseGoodBalances->last()->ratio_cost * $req->amount[$key], 2);
        //         $warehouse_good_balance->amount_balance = $warehouse_good->warehouseGoodBalances->last()->amount_balance - $req->amount[$key];
        //         $warehouse_good_balance->cost_balance = $warehouse_good->warehouseGoodBalances->last()->cost_balance - $warehouse_good_balance->cost;
        //     }

        //     $warehouse_good_balance->save();
        // }

        // // DB::commit();

        // return redirect()->route('kc-inv.requisition.index');
    }
}
