<?php

namespace App\Http\Controllers\WhsCenter;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Customer;
use App\Member;
use App\Member_type;
use App\MemberPoint;
use App\Bank;
use App\MemberBenefit;
use App\Warehouse;
use App\HS;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    public function index (){
        return view('whs-center.members.set-members.index');
    }

    public function showCustomer(){
        return datatables()->of(
            Customer::where('member_id',null)
        )->toJson();
    }

    public function showMember(){
        return datatables()->of(
            Member::query()->with('customer', 'member_type')->orderBy('updated_at', 'desc')
        )->toJson();
    }

    public function checkOutCustomer(Request $req){

        $this->validate($req,['id' => 'required']);
        $customer_id = $req->id;

        DB::beginTransaction();

        foreach($customer_id as $value) {
            $member = new Member;
            $member->status = 1;
            $member->save();

            $customer = Customer::find($value);
            $customer->member_id = $member->id;
            $customer->save();
        }
        DB::commit();

        return redirect()->route("whs-center.members.set-members.index");

   }

    public function saveMember(Request $req){

        $this->validate($req,['member_id' => 'required', 'memberCode' => 'required', 'memberType' => 'required']);

        $checkCode = Member::where('code',$req->memberCode)->where('id','!=',$req->member_id)->first();
        if($checkCode){
            $data = [
                'title' => 'เกิดข้อผิดพลาด',
                'msg' => 'รหัสสมาชิกซ้ำกับสมาชิกอื่น กรุณาสุ่มใหม่',
                'status' => 'error',
            ];
        }else{
            if($req->idCard){
                $customer = Customer::where('member_id' ,$req->member_id)->first();
                $customer->vat_code = $req->idCard;
                $customer->save();
            }

            $member = Member::find($req->member_id);
            $member->code = $req->memberCode;
            $member->member_type_id = $req->memberType;
            $member->status = 2;
            $member->bank_id = $req->bankName;
            $member->bank_account_number = $req->bankAccountNumber;
            if($member->save()){
                $data = [
                    'title' => 'บันทึกสำเร็จ',
                    'msg' => 'บันทึกข้อมูลสมาชิกสำเร็จ',
                    'status' => 'success',
                ];
            }else{
                $data = [
                    'title' => 'เกิดข้อผิดพลาด',
                    'msg' => 'บันทึกไม่สำเร็จ',
                    'status' => 'error',
                ];
            }
        }
        return $data;
    }

    public function uploadAvatar(Request $req){

        $this->validate($req,['member_id' => 'required','inpufile' => 'mimes:jpeg,jpg,png,gif|max:2048']);
        $member_id = $req->member_id;
        $picName = 'mem-'.$member_id;
        if($req->hasFile('inpufile'))
        {
            $avatar = $req->file('inpufile');
            $filename = 'avatar'.'.'.$avatar->getClientOriginalExtension();

            if($avatar->storePubliclyAs('public/image/member/'.$picName, $filename)){
                $member = Member::find($member_id);
                $member->avatar = $picName.'/'.$filename;
                if($member->save()){
                    return redirect()->back()->with('status', 'บันทึกข้อมูลรูปภาพใหม่เรียบร้อยแล้ว!');
                }
            }
            return redirect()->back()->with('status', 'บันทึกข้อมูลรูปภาพไม่สำเร็จ!');
        }else{
            return redirect()->back()->with('status', 'บันทึกข้อมูลรูปภาพไม่สำเร็จ!');
        }
        return redirect()->back()->with('status', 'มีบางอย่างผิดพลาดกรุณาติดต่อเจ้าหน้าที่!');
    }

    function destroyMember(Request $req){

        $this->validate($req,['member_id' => 'required']);

        $member_id = $req->member_id;

        $member = Member::find($member_id);
        $customer = Customer::where('member_id', $member_id)->first();
        $customer->member_id = null;

        if($customer->save() && $member->delete()){
            $data = [
                'title' => 'ลบสำเร็จ!',
                'msg' => 'ลบข้อมูลสมาชิกสำเร็จ',
                'status' => 'success',
            ];
        }else{
            $data = [
                'title' => 'เกิดข้อผิดพลาด',
                'msg' => 'ลบไม่สำเร็จ',
                'status' => 'error',
            ];
        }

        return $data;
    }

    public function checkMember(Request $req){
        $member = Member::find($req->member_id);
        $member_type = Member_type::find($member->member_type_id);
        $customer = Customer::where('member_id', $req->member_id)->first();
        $bank = Bank::find($member->bank_id);
        $data = [
            'member' => $member,
            'member_type' => $member_type,
            'customer' => $customer,
            'bank' => $bank,
        ];
        return $data;
    }

    public function showMemberType(){
        return Member_type::get();
    }

    public function showBank(){
        return Bank::get();
    }

    public function randomCode(){
        $code = 'MEM'.rand(123456,654321);
        return $code;
    }

    public function showWarehouse(){
        return Warehouse::get();
    }

    public function showProfile($member_id){

        $member = Member::where('id',$member_id)->with('customer', 'member_type', 'bank')->first();

        if($member == null){
            return abort(404);
        }

        $results = HS::with('customer.member', 'memberPoint', 'memberBenefit')
            ->where('customer_id', $member->customer->id)
            ->orderBy('id', 'desc')
            ->get();

        $totalPoint = 0;
        $totalAmount = 0;
        $totalBenefit = 0;
        foreach ($results as $result ) {
            $totalAmount = $totalAmount+$result->total_amount;
            if($result->memberPoint){
                foreach ($result->memberPoint as $value) {
                    $totalPoint = $totalPoint+$value->point;
                }
                $totalPoint = bcadd(0,$totalPoint,0);
            }else{
                $totalPoint = 0;
            }

            if($result->memberBenefit){
                foreach ($result->memberBenefit as $value) {
                    $totalBenefit = $totalBenefit+$value->benefit;
                }
            }
        }
        $totalPoint = number_format($totalPoint);
        $totalBenefit = number_format($totalBenefit,2);
        $totalAmount = number_format($totalAmount,2);//total paid all bill

        return view('whs-center.members.set-members.profile', compact('member','totalPoint','totalAmount', 'totalBenefit'));
    }

    public function showPointDetail($member_id){

        $member = Member::where('id',$member_id)->with('customer', 'member_type')->first();

        $results = HS::with('customer.member', 'memberPoint')
            ->where('customer_id', $member->customer->id)
            ->orderBy('id', 'desc')
            ->get();

        $totalPoint = 0;
        $totalAmount = 0;
        $resutls_array = array();
        $pointAllBill = 0;

        foreach ($results as $result ) {
            $totalAmount = $totalAmount+$result->total_amount;
            if($result->memberPoint){
                foreach ($result->memberPoint as $value) {
                    $totalPoint = $totalPoint+$value->point;
                }
                $data = array(
                    'hs' => $result,
                    'totalPoint' => bcadd(0,$totalPoint,0),
                );
            }else{
                $totalPoint = 0;
                $data = array(
                    'hs' => $result,
                    'totalPoint' => bcadd(0,$totalPoint,0),
                );
            }
            array_push($resutls_array,$data);
            $pointAllBill = $pointAllBill + bcadd(0,$totalPoint,0);
            $totalPoint = 0;
        }

        $totalAmount = number_format($totalAmount, 2);

        return view('whs-center.members.set-members.point-detail', compact('member','results','resutls_array','totalAmount','pointAllBill'));
    }

    public function showHsBillPoint($member_id,$h_s_id){

        $results = MemberPoint::with('good.unit','hs.warehouse','hsSku.informationReceiptSku','hsProduct.informationReceiptProduct', 'hsDoor.informationReceiptDoor')->where('member_id',$member_id)->where('h_s_id',$h_s_id)->get();

        $customer = Customer::with('member.member_type','customerBillAddress')->where('member_id',$member_id)->first();
        //return $results;

        //if get req not invalid
        if($results == '[]'){
            return abort(404);
        }
        //decare var for recieve data from database
        $hs_id = '';
        $hs_code = '';
        $hs_date = '';
        $warehouse = '';
        $amount = '';
        $price_unit = '';
        $total_price = '';
        $total_price_vat = '';

        $totalPriceVatHS = 0;
        $totalPriceHs = 0;
        $pointInBill = 0;
        $bodies = array();
        $count = 0;
        $sumPoint = 0;
        try {
            foreach ($results as $result ) {
                $hs_id = $result->hs->id;
                $hs_code = $result->hs->code;
                $hs_date = $result->hs->updated_at;
                $warehouse = $result->hs->warehouse->name;

                if($result->hsSku){
                    $amount = $result->hsSku->amount;
                    $price_unit = $result->hsSku->price_unit;
                    $total_price = $result->hsSku->total_price;
                    $total_price_vat = $result->price_no_vat;
                }else if($result->hsProduct){
                    $amount = $result->hsProduct->amount;
                    $price_unit = $result->hsProduct->price_unit;
                    $total_price = $result->hsProduct->total_price;
                    $total_price_vat = $result->price_no_vat;
                }else if($result->hsDoor){
                    $amount = $result->hsDoor->amount;
                    $price_unit = $result->hsDoor->price_unit;
                    $total_price = $result->hsDoor->total_price;
                    $total_price_vat = $result->price_no_vat;
                }else{
                    return abort(500);
                }
                $totalPriceHs = $totalPriceHs+$total_price;
                $pointInBill = $pointInBill+$result->point;
                $totalPriceVatHS = $totalPriceVatHS+$total_price_vat;

                $count ++ ;
                $data = array(
                    'no' => $count,
                    'good_name' => $result->good->name,
                    'good_code' =>  $result->good->code,
                    'good_unit' => $result->good->unit->name,
                    'amount' =>  bcadd(0,$amount,0),
                    'price_unit' =>  number_format($price_unit,2),
                    'total_price' =>  $total_price,
                    'total_price_vat' =>  $total_price_vat,
                    'ratio' => $result->ratio,
                    'point' => $result->point,
                );
                array_push($bodies,$data);
            }
            $hs_date = date("d/m/Y", strtotime($hs_date));
            $header = array();
            $header = [
                'hs_id' => $hs_id,
                'hs_code' => $hs_code,
                'hs_date' => $hs_date,
                'warehouse' => $warehouse,
            ];
            $sumPoint = $pointInBill;
            $pointInBill = bcadd(0,$pointInBill,0);
            $totalPriceHs = number_format($totalPriceHs ,2);
            $totalPriceVatHS = number_format($totalPriceVatHS ,2);
            $discount = $result->hs->discount_all;
            $netPrice = $result->hs->total_amount;

            $results = MemberPoint::with('hs')
                ->where('member_id', $member_id)
                ->where('h_s_id', '<=', $hs_id)
                ->groupBy('h_s_id')
                ->selectRaw('sum(point) as totalPoint, h_s_id')
                ->get();

            $pointAllBill = 0;

            foreach ($results as $result ) {
                if($result->hs){
                    $pointAllBill = $pointAllBill+bcadd(0,$result->totalPoint,0);
                }
            }

            $footer = [
                'sumPoint' => $sumPoint,
                'pointInBill' => $pointInBill,
                'totalPriceHs' => $totalPriceHs,
                'totalPriceVatHS' => $totalPriceVatHS,
                'pointAllBill' => $pointAllBill,
                'discount' => $discount,
                'netPrice' => $netPrice,
            ];

        } catch (\Throwable $th) {
            return abort(404);
        }

        return view('whs-center.members.set-members.hs-bill-point', compact('member_id','customer','header','bodies','footer'));
    }

    public function showBenefitDetail($member_id){

        $member = Member::where('id',$member_id)->with('customer', 'member_type')->first();

            $results = HS::with('customer.member', 'memberBenefit')
                ->where('customer_id', $member->customer->id)
                ->orderBy('id', 'desc')
                ->get();

            $totalBenefit = 0;
            $totalAmount = 0;
            $resutls_array = array();
            $benefitAllBill = 0;

            foreach ($results as $result ) {
                $totalAmount = $totalAmount+$result->total_amount;
                if($result->memberBenefit){
                    foreach ($result->memberBenefit as $value) {
                        $totalBenefit = $totalBenefit+$value->benefit;
                    }
                    $data = array(
                        'hs' => $result,
                        'totalBenefit' => $totalBenefit,
                    );
                }else{
                    $totalPoint = 0;
                    $data = array(
                        'hs' => $result,
                        'totalBenefit' => $totalBenefit,
                    );
                }
                array_push($resutls_array,$data);
                $benefitAllBill = $benefitAllBill + $totalBenefit;
                $totalBenefit = 0;
            }

            $totalAmount = number_format($totalAmount, 2);

        return view('whs-center.members.set-members.benefit-detail', compact('member','results','totalBenefit','totalAmount','resutls_array'));
    }

    public function showHsBillBenefit($member_id,$h_s_id){


    }



    public function exportSummaryMemberPointToExcel (Request $req){
        $month = $req->month;
        $warehouse = Warehouse::find($req->warehouseAll)->name;
        $memberType = Member_type::find($req->memberTypeAll)->name;
        $thead = array(
            'month' => $month,
            'warehouse' => $warehouse,
            'memberType' => $memberType,
        );

        $results = Member::with('customer', 'member_type', 'bank')->orderBy('member_type_id', 'desc')->get();

        $tbodies = array();
        $count = 1;
        $bank = '';
        foreach ($results as $result) {
            if($result->bank){
                $bank = $result->bank->name;
            }else{
                $bank = '';
            }

            $memberPoints = MemberPoint::with('hs')
                ->where('member_id', $result->id)
                ->groupBy('h_s_id')
                ->selectRaw('sum(point) as totalPoint, h_s_id')
                ->get();
            $totalPoint = 0;
            $totalBalance = 0;

            foreach ($memberPoints as $memberPoint ) {
                if($memberPoint->hs){
                    $totalPoint = $totalPoint+bcadd(0,$memberPoint->totalPoint,0);
                    $totalBalance = $totalBalance+$memberPoint->hs->total_amount;
                }

            }
            $totalBalance = number_format($totalBalance,2);//total paid all bill

            $data = array(
                'no' => $count,
                'member_code' => $result->code,
                'member_type' => $result->member_type->name,
                'name' => $result->customer->name,
                'vat_code' => $result->customer->vat_code,
                'bank' => $bank,
                'bank_account_number' => $result->bank_account_number,
                'total_balance' => $totalBalance,
                'total_point' => $totalPoint,
            );
            array_push($tbodies,$data);
            $count++;
        }

        return view('whs-center.members.set-members.export-excel', compact('thead','tbodies'));
    }

}
