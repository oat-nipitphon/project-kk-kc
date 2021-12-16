<?php

namespace App\Http\Controllers\WhsCenter;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Type;
use App\Warehouse;
use App\Good;
use App\GoodView;
use Illuminate\Support\Facades\View;



class ReportAmountGoodController extends Controller
{
    public function index()
    {
        $types = Type::all();
        $wherehouses = Warehouse::all();
        $goods = Good::all();
        return view('whs-center.amount-goods.index',compact('types','wherehouses','goods'));
    }
    public function ajaxSetWh(Request $request)
    {
        $whSelects = Warehouse::where('warehouse_id', $request->whSelect)->get();
        return view('whs-center.amount-goods.index',compact('whSelects'));
    }
    public function ajaxrequestpost(Request $request)
    {
        $goods = Good::where('type_id', $request->setype)->get();
        $wherehouses = Warehouse::all();
        $view = View::make('whs-center.amount-goods.goodview', compact('goods','wherehouses'))->render();
            return response()->json([
                'html' => $view,
        ]);
    }

    public function amountGoodView(Request $request)
    {
        $warehouses = Warehouse::all();
        $goodviews = GoodView::where('good_id', $request->goodId)->get();

        $view = View::make('whs-center.amount-goods.warehousegoodview', compact('goodviews','warehouses'))->render();
            return response()->json([
                'html' => $view,
        ]);
    }
    public function balanceGoods()
    {
        $types = Type::all();
        $wherehouses = Warehouse::all();
        return view('whs-center.amount-goods.view-balance-good', compact('types','wherehouses'));
    }

    public function viewBalance(Request $request)
    {
        $goods = Good::where('type_id', $request->setType)->get();
        $wherehouses = Warehouse::all();
        $view = View::make('whs-center.amount-goods.view-balance', compact('goods','wherehouses'))->render();
            return response()->json([
                'html' => $view,
        ]);
    }

    public function viewBalanceGoods(Request $request)
    {
        $settype = $request->setType;
        return datatables()->of(
            GoodView::query()->with('good')->where('warehouse_id' ,$request->setWareHouse)
            ->whereHas('good', function ($query) use ($settype)
            {
                $query->where('type_id', $settype);
            })
        )->toJson();

        // $settype = $request->setType;
        // GoodView::with('good')->where('warehouse_id' ,$request->setWareHouse)
        // ->whereHas('good', function ($query) use ($settype) {
        // $query->where('type_id', $settype);
        // })->get();

        //return GoodView::with('good')->where('warehouse_id' ,$request->setWareHouse)->get();
    }

    public function amountBalances()
    {
        $types = Type::all();
        $wareHouses = Warehouse::all();
        return view('whs-center.amount-goods.amountBalances', compact('viewGoods','wareHouses','types'));
    }
    public function viewAmountBalances(Request $request)
    {
        $viewGoods = Good::where('type_id', $request->configType)->get();
        $view = View::make('whs-center.amount-goods.viewTableBalances', compact('viewGoods'))->render();
            return response()->json([
                'html' => $view,
        ]);
    }

    public function indexBalance()
    {
        $types = Type::all();
        return view('whs-center.amount-goods.index', compact('types'));
    }

    public function ajaxSearchGoodFormType(Request $request)
    {
        $goods = Good::where('type_id', $request->type_id)->get();
        $warehouses = Warehouse::all();
        $view = View::make('whs-center.amount-goods.data-table-goods', compact('goods','warehouses'))->render();
        return response()->json([
            'html' => $view
        ]);
    }

    public function ajaxCheckAmount(Request $request)
    {
        $warehouses = Warehouse::with(['goodview' => function ($query) use ($request) {
            $query->where('good_id', $request->good_id);
        }])->get();

        return $warehouses;
    }

}
