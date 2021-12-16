<?php

namespace App\Http\Controllers;

use App\HSSku;
use App\HSProduct;
use App\HSDoor;
use App\MemberPoint;
use App\GoodDetail;
use App\Good;
use App\BaseRatio;

class MemberPointController extends Controller
{
    public function calculateMemberPoint(){
        $this->calculatePointHSDoor();
        $this->calculatePointHSProduct();
        $this->calculatePointHSSku();
        return 'Success';
    }

    private function calculatePointHSDoor(){
        $base_ratios = BaseRatio::find(1);
        $base_ratio =  $base_ratios->ratio;
        $h_s_door_id = '';
        $results = HSDoor::
            with('good.goodRatio')
            ->with('informationReceiptDoor.goodDetail')
            ->with('informationReceiptDoor.informationReceipt.customer.member')
            ->where('is_calculate_point', '=', 0)
            ->orderBy('id')
            ->limit(500)
            ->get();

            foreach ($results as $result) {
                $h_s_door_id = $result->id;
                $price_no_vat = $result->total_price_vat;
                $h_s_id = $result->h_s_id;
                $good_id = '';
                $ratio = '';
                $member_status = '';
                $member_id = '';
                $point = '';

                if($result->informationReceiptDoor->informationReceipt->customer->member){//if customer is member(have row in Member table)

                    $member_status = $result->informationReceiptDoor->informationReceipt->customer->member->status;
                    $member_id = $result->informationReceiptDoor->informationReceipt->customer->member_id;
                    //echo 'Found in member!!'.$member_id .'<br>';
                    if($member_status == 2){//if member have status == 2  -> calculate point
                        //echo 'Is member!! status : '.$member_status .'<br>';
                        if($result->good){ //if informationReceiptDoor have good_id

                            $good_id = $result->good_id;
                            //echo 'good_id = '.$good_id .'<br>';

                            if($result->good->goodRatio){// if good have good_ratio (handle null)
                                $ratio = $result->good->goodRatio->ratio;
                                if($ratio == 0){//if good have good_ratio but ratio is 0, not set ratio and not cal point
                                    $ratio = null;
                                    $point = 0;
                                }else{
                                    $point = bcadd(0,$price_no_vat/$ratio,2);
                                }
                            }else{//if good not have good_ratio set ratio = 500
                                $ratio = $base_ratio;
                                $point = bcadd(0,$price_no_vat/$ratio,2);
                            }

                        }else{
                            //echo 'good_id = null';
                            $goodDetail = GoodDetail::
                            where('thick', $result->informationReceiptDoor->thick)
                            ->where('color', $result->informationReceiptDoor->color)
                            ->where('type_product', $result->informationReceiptDoor->type_door)
                            ->where('type_roof', $result->informationReceiptDoor->name)
                            ->first();

                            if($goodDetail){//if columns[g, thick, type_product, type_roof, cover, color] in informationRecieptDoor like columns[g, thick, type_product, type_roof, cover, color]  in good_detail

                                $good_id = $goodDetail->good_id;
                                //echo 'Found good_id in GoodDetail ====> good_id : '.$good_id.'<br>';
                                $good = Good::with('goodDetail', 'goodRatio')->where('id',$good_id)->first();


                                if($good->goodRatio){// if good have good_ratio (handle null)
                                    $ratio = $good->goodRatio->ratio;
                                    if($ratio == 0){//if good have good_ratio but ratio is 0, not set ratio and not cal point
                                        $ratio = null;
                                        $point = 0;
                                    }else{
                                        $point = bcadd(0,$price_no_vat/$ratio,2);
                                    }
                                }else{//if good not have good_ratio set ratio = 500
                                    $ratio = $base_ratio;
                                    $point = bcadd(0,$price_no_vat/$ratio,2);
                                }

                            }else{//if columns in informationRecieptDoor not like columns in good_detail
                                //echo 'Not Found in GoodDetail ====> HAVE A PROBLEM'.'<br>';
                                $h_s_door= HSProduct::find($h_s_door_id);
                                $h_s_door->is_problem = 1;
                                $h_s_door->save();
                            }
                        }
                        $memberPoint = MemberPoint::where('h_s_door_id', $h_s_door_id)->where('h_s_id', $h_s_id)->where('good_id', $good_id)->first();
                        if($memberPoint == null || $memberPoint == ''){
                            $memberPoint = new MemberPoint;
                        }
                        $memberPoint->h_s_id = $h_s_id;
                        $memberPoint->h_s_door_id = $h_s_door_id;
                        $memberPoint->member_id = $member_id;
                        $memberPoint->good_id = $good_id;
                        $memberPoint->point = $point;
                        $memberPoint->ratio = $ratio;
                        $memberPoint->price_no_vat = $price_no_vat;
                        $memberPoint->save();
                       // echo 'Saved data in memberPoint table !!'.'<br>';

                    }
                    $h_s_door = HSDoor::find($h_s_door_id);
                    $h_s_door->is_calculate_point = 1;
                    $h_s_door->save();
                    //echo 'Member calculate point success' .'<br>';

                }else{//if customer is not member(have not row in Member table) -> not calculate point
                    //echo 'Is Not member!!'.'<br>';
                    $h_s_door = HSDoor::find($h_s_door_id);
                    $h_s_door->is_calculate_point = 1;
                    $h_s_door->save();
                }

                //echo 'h_s_door_id : '. $h_s_door_id . '<br>';
                // echo 'h_s_id : ' . $h_s_id . '<br>';
                // echo 'price_no_vat : ' . $price_no_vat . '<br>';
                // echo 'good_id : ' . $good_id . '<br>';
                // echo 'ratio : ' . $ratio . '<br>';
                // echo 'point : ' .$point . '<br>';
                // echo 'member_id : ' . $member_id . '<br>';
                // echo 'member_status : ' . $member_status . '<br>';
                // echo '============================================';
                // echo '<br>';

                // ==============================================================================================================
        }
        echo 'Last HSDoor_id : '.$h_s_door_id .'<br>';
        //return $results;
    }

    private function calculatePointHSProduct(){

        $base_ratios = BaseRatio::find(1);
        $base_ratio =  $base_ratios->ratio;
        $h_s_product_id = '';
        $results = HSProduct::
            with('informationReceiptProduct.goodDetail')
            ->with('informationReceiptProduct.good.goodRatio')
            ->with('informationReceiptProduct.informationReceipt.customer.member')
            ->where('is_calculate_point', '=', 0)
            ->orderBy('id')
            ->limit(1000)
            ->get();

        foreach ($results as $result) {
            $h_s_product_id = $result->id;
            $price_no_vat = $result->total_price_vat;
            $h_s_id = $result->h_s_id;
            $good_id = '';
            $ratio = '';
            $member_status = '';
            $member_id = '';
            $point = '';

            if($result->informationReceiptProduct->informationReceipt->customer->member){//if customer is member(have row in Member table)
                //echo 'Found in member!!'.$member_id .'<br>';
                $member_status = $result->informationReceiptProduct->informationReceipt->customer->member->status;
                $member_id = $result->informationReceiptProduct->informationReceipt->customer->member_id;

                if($member_status == 2){//if member have status == 2  -> calculate point
                    //echo 'Is member!! status : '.$member_status .'<br>';

                    if($result->informationReceiptProduct->good){ //if informationReceiptProduct have good_id
                        //echo 'good_id != null'.'<br>';
                        $good_id = $result->informationReceiptProduct->good_id;

                        if($result->informationReceiptProduct->good->type_id == 4){// if good is type = 4 -> no point
                            $point = 0;
                        }else{
                            if($result->informationReceiptProduct->good->goodRatio){// if good have good_ratio (handle null)
                                $ratio = $result->informationReceiptProduct->good->goodRatio->ratio;
                                if($ratio == 0){//if good have good_ratio but ratio is 0, not set ratio and not cal point
                                    $ratio = null;
                                    $point = 0;
                                }else{
                                    $point = bcadd(0,$price_no_vat/$ratio,2);
                                }
                            }else{//if good not have good_ratio set ratio = 500
                                $ratio = $base_ratio;
                                $point = bcadd(0,$price_no_vat/$ratio,2);
                            }
                        }

                    }else{//if informationReceiptProduct not have good_id
                        //echo 'good_id == null'.'<br>';

                        $goodDetail = GoodDetail::
                            where('g', $result->informationReceiptProduct->g)
                            ->where('thick', $result->informationReceiptProduct->thick)
                            ->where('type_product', $result->informationReceiptProduct->type_product)
                            ->where('type_roof', $result->informationReceiptProduct->type_roof)
                            ->where('cover', $result->informationReceiptProduct->cover)
                            ->where('color', $result->informationReceiptProduct->color)
                            ->first();

                            if($goodDetail){//if columns[g, thick, type_product, type_roof, cover, color] in informationReceiptProduct like columns[g, thick, type_product, type_roof, cover, color]  in good_detail
                                $good_id = $goodDetail->good_id;
                                //echo 'Found good_id in GoodDetail ====> good_id : '.$good_id.'<br>';
                                $good = Good::with('goodDetail', 'goodRatio')->where('id',$good_id)->first();

                                if($good->type_id == 4){// if good is type = 4 -> no point
                                    $point = 0;
                                }else{
                                    if($good->goodRatio){// if good have good_ratio (handle null)
                                        $ratio = $good->goodRatio->ratio;
                                        if($ratio == 0){//if good have good_ratio but ratio is 0, not set ratio and not cal point
                                            $ratio = null;
                                            $point = 0;
                                        }else{
                                            $point = bcadd(0,$price_no_vat/$ratio,2);
                                        }
                                    }else{//if good not have good_ratio set ratio = 500
                                        $ratio = $base_ratio;
                                        $point = bcadd(0,$price_no_vat/$ratio,2);
                                    }
                                }

                            }else{//if columns in informationReceiptProduct not like columns in good_detail
                                //echo 'Not Found in GoodDetail ====> HAVE A PROBLEM'.'<br>';
                                $h_s_product = HSProduct::find($h_s_product_id);
                                $h_s_product->is_problem = 1;
                                $h_s_product->save();
                            }
                    }

                    $memberPoint = MemberPoint::where('h_s_product_id', $h_s_product_id)->where('h_s_id', $h_s_id)->where('good_id', $good_id)->first();
                    if($memberPoint == null || $memberPoint == ''){
                        $memberPoint = new MemberPoint;
                    }
                    $memberPoint->h_s_id = $h_s_id;
                    $memberPoint->h_s_product_id = $h_s_product_id;
                    $memberPoint->member_id = $member_id;
                    $memberPoint->good_id = $good_id;
                    $memberPoint->point = $point;
                    $memberPoint->ratio = $ratio;
                    $memberPoint->price_no_vat = $price_no_vat;
                    $memberPoint->save();
                    //echo 'Saved data in memberPoint table !!'.'<br>';

                }
                $h_s_product = HSProduct::find($h_s_product_id);
                $h_s_product->is_calculate_point = 1;
                $h_s_product->save();
                //echo 'Member calculate point success' .'<br>';
            }else{//if customer is not member(have not row in Member table) -> not calculate point
                //echo 'Is Not member!!'.'<br>';
                $h_s_product = HSProduct::find($h_s_product_id);
                $h_s_product->is_calculate_point = 1;
                $h_s_product->save();
            }

            // echo 'h_s_product_id : '. $h_s_product_id . '<br>';
            // echo 'h_s_id : ' . $h_s_id . '<br>';
            // echo 'price_no_vat : ' . $price_no_vat . '<br>';
            // echo 'good_id : ' . $good_id . '<br>';
            // echo 'ratio : ' . $ratio . '<br>';
            // echo 'point : ' . $point . '<br>';
            // echo 'member_id : ' . $member_id . '<br>';
            // echo 'member_status : ' . $member_status . '<br>';
            // echo '============================================';
            // echo '<br>';

            // ==============================================================================================================
    }
    echo 'Last HSProduct_id : '.$h_s_product_id .'<br>';
}

private function calculatePointHSSku(){

        $base_ratios = BaseRatio::find(1);
        $base_ratio =  $base_ratios->ratio;
        $h_s_sku_id = '';
        $results = HSSku::
            with('good.goodRatio', 'informationReceiptSku.informationReceipt.customer.member')
            ->where('is_calculate_point', '=', 0)
            ->orderBy('id')
            ->limit(500)
            ->get();

        foreach ($results as $result) {
            $h_s_sku_id = $result->id;
            $h_s_id = $result->h_s_id;
            $good_id = $result->good_id;
            $price_no_vat = $result->total_price_vat;
            $ratio = '';
            $member_status = '';
            $member_id = '';
            $point = '';

            if($result->informationReceiptSku->informationReceipt->customer->member){//if customer is member(have row in Member table)
                $member_status = $result->informationReceiptSku->informationReceipt->customer->member->status;
                $member_id = $result->informationReceiptSku->informationReceipt->customer->member->id;
                if($member_status == 2){//if member have status == 2  -> calculate point

                    $memberPoint = MemberPoint::where('h_s_sku_id', $h_s_sku_id)->where('h_s_id', $h_s_id)->where('good_id', $good_id)->first();
                    if($memberPoint == null || $memberPoint == ''){
                        $memberPoint = new MemberPoint;
                    }

                    if($result->good->type_id != 4){// if good is type = 4 -> no point
                        if($result->good->goodRatio){// if good have good_ratio (handle null)
                            $ratio = $result->good->goodRatio->ratio;
                            if($ratio == 0){//if good have good_ratio but ratio is 0, not set ratio and not cal point
                                $point = 0;
                            }else{
                                $point = bcadd(0,$price_no_vat/$ratio,2);
                            }
                        }else{//if good not have good_ratio set ratio = 500
                            $ratio = $base_ratio;
                            $point = bcadd(0,$price_no_vat/$ratio,2);
                        }
                    }else{
                        $point = 0;
                    }
                    $memberPoint->h_s_id = $h_s_id;
                    $memberPoint->h_s_sku_id = $h_s_sku_id;
                    $memberPoint->member_id = $member_id;
                    $memberPoint->good_id = $good_id;
                    $memberPoint->price_no_vat =$price_no_vat;
                    $memberPoint->point = $point;
                    $memberPoint->ratio = $ratio;
                    $memberPoint->save();

                }
                $h_s_sku = HSSku::find($h_s_sku_id);
                $h_s_sku->is_calculate_point = 1;
                $h_s_sku->save();

            }else{//if customer is not member(have not row in Member table) -> not calculate point
                $h_s_sku = HSSku::find($h_s_sku_id);
                $h_s_sku->is_calculate_point = 1;
                $h_s_sku->save();
            }
        }
       // return 'Success!';
       echo 'Last HSSku_id : '.$h_s_sku_id .'<br>';
    }
}


// $hs = HS::with('hsSku.informationReceiptSku', 'hsSku.good', 'hsProduct.informationReceiptProduct', 'hsProduct.good', 'hsDoor.informationReceiptDoor.good', 'warehouse', 'memberPoint')
// ->where('customer_id', $customer->id)
// ->where('id', $h_s_id)
// ->orderBy('id', 'desc')
// ->first();
// $hs_id = $hs->id;
// return $hs;
// //if get req not invalid
// if($hs_id == '' || $hs_id = null){
// return abort(404);
// }

// //decare var for recieve data from database
// $hs_id = '';
// $hs_code = '';
// $hs_date = '';
// $warehouse = '';
// $amount = '';
// $price_unit = '';
// $total_price = '';
// $total_price_vat = '';
// $good_name = '';
// $good_code = '';
// $good_unit = '';
// $totalPriceVatHS = 0;
// $totalPriceHs = 0;
// $pointInBill = 0;
// $goods = array();
// $count = 0;
// $sumPoint = 0;
// try {
//     $hs_id = $hs->id;
//     $hs_code = $hs->code;
//     $hs_date = $hs->doc_date;
//     $warehouse = $hs->warehouse->name;

//     $hs_date = date("d/m/Y", strtotime($hs_date));
//     $header = array();
//     $header = [
//         'hs_id' => $hs_id,
//         'hs_code' => $hs_code,
//         'hs_date' => $hs_date,
//         'warehouse' => $warehouse,
//     ];

//     if($hs->hsSku){
//         foreach ($hs->hsSku as $hsSku) {
//             $count++;
//             $amount = $hsSku['amount'];
//             $price_unit = $hsSku['price_unit'];
//             $total_price = $hsSku['total_price'];
//             $total_price_vat = $hsSku['total_price_vat'];
//             $good_name = $hsSku['good']->name;
//             $good_code = $hsSku['good']->code;
//             $good_unit = $hsSku['good']->unit->name;
//             $data = array(
//                 'no' => $count,
//                 'good_name' => $good_name,
//                 'good_code' =>  $good_code,
//                 'good_unit' => $good_unit,
//                 'amount' =>  $amount,
//                 'price_unit' =>  $price_unit,
//                 'total_price' =>  $total_price,
//                 'total_price_vat' =>  $total_price_vat,
//             );
//             array_push($goods,$data);
//         }
//     }
//     if($hs->hsProduct){
//         foreach ($hs->hsProduct as $hsProduct) {
//             $count++;
//             $amount = $hsProduct['amount'];
//             $price_unit = $hsProduct['price_unit'];
//             $total_price = $hsProduct['total_price'];
//             $total_price_vat = $hsProduct['total_price_vat'];
//             if($hsProduct['good']){
//                 $good_name = $hsProduct['good']->name;
//                 $good_code = $hsProduct['good']->code;
//                 $good_unit = $hsProduct['good']->unit->name;
//             }else{

//             }
//             $data = array(
//                 'no' => $count,
//                 'good_name' => $good_name,
//                 'good_code' =>  $good_code,
//                 'good_unit' => $good_unit,
//                 'amount' =>  $amount,
//                 'price_unit' =>  $price_unit,
//                 'total_price' =>  $total_price,
//                 'total_price_vat' =>  $total_price_vat,
//             );
//             array_push($goods,$data);
//         }
//     }
//     if($hs->hsDoor){
//         foreach ($hs->hsDoor as $hsDoor) {
//             $count++;
//             $amount = $hsDoor['amount'];
//             $price_unit = $hsDoor['price_unit'];
//             $total_price = $hsDoor['total_price'];
//             $total_price_vat = $hsDoor['total_price_vat'];
//             $good_name = $hsDoor['good']->name;
//             $good_code = $hsDoor['good']->code;
//             $good_unit = $hsDoor['good']->unit->name;
//             $data = array(
//                 'no' => $count,
//                 'good_name' => $good_name,
//                 'good_code' =>  $good_code,
//                 'good_unit' => $good_unit,
//                 'amount' =>  $amount,
//                 'price_unit' =>  $price_unit,
//                 'total_price' =>  $total_price,
//                 'total_price_vat' =>  $total_price_vat,
//             );
//             array_push($goods,$data);
//         }
//     }



// } catch (\Throwable $th) {
// return abort(404);
// }
// return $goods;
// return view('whs-center.members.set-members.hs-bill-point', compact('member_id','customer','header','bodies','footer'));
// here('type_product', $result->informationReceiptProduct->type_product)
//                 ->where('type_roof', $result->informationReceiptProduct->type_roof)
//                 ->where('cover', $result->informationReceiptProduct->cover)
//                 ->where('color', $result->informationReceiptProduct->color)
//                 ->first();
