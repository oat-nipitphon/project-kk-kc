<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\HSSku;
use App\HSProduct;
use App\HSDoor;
use App\MemberBenefit;
use App\GoodDetail;
use App\Good;
use App\GoodWarehouse;
use App\GoodPrice;
use App\Warehouse;

class MemberBenefitController extends Controller
{

public function calculateMemberBenefit(){
    $this->calculateBenefitHSSku();
    return 'Success';
}

public function calculateBenefitHSSku(){

    $h_s_sku_id = '';
    $h_s_id = '';
    $good_id = '';
    $warehouse_id = '';

    $results = HSSku::
        with('good', 'informationReceiptSku.informationReceipt.customer.member')
        ->where('is_calculate_price', '=', 0)
        ->orderBy('id')
        ->limit(2000)
        ->get();

    foreach ($results as $result) {
        $h_s_sku_id = $result->id;
        $h_s_id = $result->h_s_id;
        $good_id = $result->good_id;
        $warehouse_id = $result->informationReceiptSku->informationReceipt->warehouse_id;
        $member_status = '';
        $member_id = '';
        $member_type_id = '';

        $base_price = 0;
        $price = '';
        $benefit = 0;
        $amount = 0;

        if($result->informationReceiptSku->informationReceipt->customer->member){ //if is a member!

            $member_status = $result->informationReceiptSku->informationReceipt->customer->member->status;
            $member_id = $result->informationReceiptSku->informationReceipt->customer->member->id;
            $member_type_id = $result->informationReceiptSku->informationReceipt->customer->member->member_type_id;
            $amount = $result->amount;

            if($member_status == 2){// if member status is 2 -> calculate benefit

                if($result->good->is_check_price == 1){// if good have setting price

                    $good_warehouse = GoodWarehouse::with('goodPrice','warehouse')
                        ->where('warehouse_id', $warehouse_id)
                        ->where('good_id', $good_id)
                        ->first();
                    $base_price = $good_warehouse->base_price;

                    if($good_warehouse->goodPrice){
                        $goodPrices = $good_warehouse->goodPrice;
                       foreach ($goodPrices as $goodPrice) {
                           if($goodPrice->member_type_id == $member_type_id){
                               if($goodPrice->price != 0 && $base_price != 0){
                                    $price = $goodPrice->price;
                                    $benefit = number_format($base_price-$price, 2) * $result->amount;
                               }
                           }
                       }
                    }

                    $memberBenefit = MemberBenefit::with('hs')->where('h_s_sku_id', $h_s_sku_id)->where('h_s_id', $h_s_id)->where('good_id', $good_id)->first();
                    //return $memberBenefit;
                    if($memberBenefit){
                        if($memberBenefit == null || $memberBenefit == ''){
                            $memberBenefit = new MemberBenefit;
                        }

                        $memberBenefit->h_s_id = $h_s_id;
                        $memberBenefit->h_s_sku_id = $h_s_sku_id;
                        $memberBenefit->member_id = $member_id;
                        $memberBenefit->good_id = $good_id;
                        $memberBenefit->warehouse_id = $warehouse_id;
                        $memberBenefit->base_price = $base_price;
                        $memberBenefit->price = $price;
                        $memberBenefit->benefit = $benefit;

                        //$memberBenefit->save();
                    }

                    echo 'h_s_id = '.$h_s_id.'<br>';
                    echo 'h_s_sku_id = '.$h_s_sku_id.'<br>';
                    echo 'member_id = '.$member_id.'<br>';
                    echo 'good_id = '.$good_id.'<br>';
                    echo 'warehouse_id = '.$warehouse_id.'<br>';
                    echo 'base_price = '.$base_price.'<br>';
                    echo 'price = '.$price.'<br>';
                    echo 'amount = '.$amount.'<br>';
                    echo '-->benefit = '.$benefit.'<br>'.'<br>';
                }
            }
        }
        $h_s_sku = HSSku::find($h_s_sku_id);
        $h_s_sku->is_calculate_price = 1;
        //$h_s_sku->save();
    }
  echo 'Last HSSku_id : '.$h_s_sku_id .'<br>';
   return 'Success!';
}

public function calculateBenefitHSProduct(){

    $h_s_product_id = '';
    $h_s_id = '';
    $good_id = '';
    $warehouse_id = '';

    $results = HSProduct::
        with('good', 'informationReceiptProduct.informationReceipt.customer.member')
        ->where('is_calculate_price', '=', 0)
        ->orderBy('id')
        ->limit(1000)
        ->get();

    foreach ($results as $result) {
        $h_s_product_id = $result->id;
        $h_s_id = $result->h_s_id;
        $good_id = $result->good_id;
        $warehouse_id = $result->informationReceiptProduct->informationReceipt->warehouse_id;
    }
   return 'Success!';
}



}
