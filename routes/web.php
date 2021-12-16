<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register' => false]);

Route::get('/select-warehouse', 'HomeController@selectWarehouse')->name('select-warehouse');
Route::post('/select-warehouse', 'HomeController@storeWarehouse')->name('store-warehouse');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/calculateMemberBenefit', 'MemberBenefitController@calculateBenefitHSSku');
Route::get('/calculateMemberPoint', 'MemberPointController@calculateMemberPoint');


Route::middleware(['auth', 'warehouse'])->group(function () {
    Route::get('/select-program', 'HomeController@selectProgram')->name('select-program');
    Route::get('/stock-table', 'HomeController@getStockTable')->name('stock-table');
    Route::get('/red-label-table', 'HomeController@getRedLabelTable')->name('red-label-table');
    Route::get('/good-table', 'HomeController@getGoodTable')->name('good-table');

    Route::prefix('board')->name('board.')->group(function () {
        Route::get('/dashboard', 'Board\DashboardController@dashboard')->name('dashboard');
        Route::get('/summary-sale/current-day', 'Board\DashboardController@summarySaleCurrentDay')->name('summary-sale.current-day');
    });

    Route::prefix('whs')->name('whs.')->group(function () {
        Route::get('/dashboard', 'Whs\DashboardController@dashboard')->name('dashboard');

        //Pr
        Route::get('/prs-approve', 'Whs\PrController@approveIndex')->name('prs-approve.index');
        Route::get('/prs-approve/{id}', 'Whs\PrController@approveShow')->name('prs-approve.show');
        Route::put('/prs-approve/{id}/update', 'Whs\PrController@approveUpdate')->name('prs-approve.update');
        Route::get('/prs-report', 'Whs\PrController@reportIndex')->name('prs-report.index');
        Route::get('/prs-report/{id}', 'Whs\PrController@reportShow')->name('prs-report.show');
        Route::resource('prs', 'Whs\PrController');

        //WithdrawRedLabel
        Route::get('/withdraw-red-label/approve', 'Whs\WithDrawRedLabelController@approveIndex')->name('withdraw-red-label.approve.index');
        Route::get('/withdraw-red-label/approve/{id}', 'Whs\WithDrawRedLabelController@approveShow')->name('withdraw-red-label.approve.show');
        Route::post('/withdraw-red-label/approve/{id}', 'Whs\WithDrawRedLabelController@approveStore')->name('withdraw-red-label.approve.store');
        Route::resource('withdraw-red-label', 'Whs\WithDrawRedLabelController');

        //Requisition
        Route::get('/requisitions/approve', 'Whs\RequisitionController@approveIndex')->name('requisitions.approve.index');
        Route::resource('requisitions', 'Whs\RequisitionController');
    });

    Route::prefix('whs-center')->name('whs-center.')->group(function () {
        Route::get('/dashboard', 'WhsCenter\DashboardController@dashboard')->name('dashboard');
        Route::get('/pos-select-pr', 'WhsCenter\Pocontroller@selectPr')->name('pos.select-pr');
        Route::get('/pos-select-vendor', 'WhsCenter\Pocontroller@selectVendor')->name('pos.select-vendor');
        Route::delete('/pos-cancel-pr/{id}', 'WhsCenter\PoController@cancelPr')->name('pos.cancel-pr');
        Route::delete('/pos-clear-pr/{id}', 'WhsCenter\PoController@clearPr')->name('pos.clear-pr');
        Route::resource('pos', 'WhsCenter\PoController');
        Route::resource('vendors', 'WhsCenter\VendorController');

        Route::get('/goods/set-check-goods', 'WhsCenter\SetCheckGoodController@index')->name('goods.set-check-goods.index');
        Route::post('/goods/set-check-goods', 'WhsCenter\SetCheckGoodController@store')->name('goods.set-check-goods.store');
        Route::get('/goods/{id}/set-check-goods', 'WhsCenter\SetCheckGoodController@show')->name('goods.set-check-goods.show');
        Route::post('/goods/{id}/set-check-goods', 'WhsCenter\SetCheckGoodController@setMinMax')->name('goods.set-check-goods.setMinMax');

        Route::get('/goods/set-ratio-goods', 'WhsCenter\SetRatioGoodController@index')->name('goods.set-ratio-goods.index');
        Route::post('/goods/set-ratio-goods', 'WhsCenter\SetRatioGoodController@showGoodRatio');
        Route::post('/goods/set-ratio-goods/showBaseRatio', 'WhsCenter\SetRatioGoodController@showBaseRatio');
        Route::post('/goods/set-ratio-goods/storeBaseRatio', 'WhsCenter\SetRatioGoodController@storeBaseRatio');
        Route::post('/goods/set-ratio-goods/showGoodModal', 'WhsCenter\SetRatioGoodController@showGoodModal');
        Route::post('/goods/set-ratio-goods/storeGoodRatio', 'WhsCenter\SetRatioGoodController@storeGoodRatio');
        Route::post('/goods/set-ratio-goods/deleteGoodRatio', 'WhsCenter\SetRatioGoodController@deleteGoodRatio');
        Route::post('/goods/set-ratio-goods/checkOutGood', 'WhsCenter\SetRatioGoodController@checkOutGood')->name('goods.set-ratio-goods.checkOutGood');

        Route::get('/goods/set-price-goods', 'WhsCenter\SetPriceGoodController@index')->name('goods.set-price-goods.index');
        Route::post('/goods/set-price-goods', 'WhsCenter\SetPriceGoodController@showGood');
        Route::post('/goods/set-price-goods/showGoodModal', 'WhsCenter\SetPriceGoodController@showGoodModal');
        Route::post('/goods/set-price-goods/checkOutGood', 'WhsCenter\SetPriceGoodController@checkOutGood')->name('goods.set-price-goods.checkOutGood');
        Route::get('/goods/set-price-goods/{good_id}', 'WhsCenter\SetPriceGoodController@showWarehouse')->name('goods.set-price-goods.showWarehouse');
        Route::post('/goods/set-price-goods/deleteGood', 'WhsCenter\SetPriceGoodController@deleteGood');
        Route::post('/goods/set-price-goods/{good_id}/setBasePrice', 'WhsCenter\SetPriceGoodController@setBasePrice')->name('goods.set-price-goods.setBasePrice');
        Route::post('/goods/set-price-goods/{good_id}/infoGood', 'WhsCenter\SetPriceGoodController@infoGood');
        Route::post('/goods/set-price-goods/{good_id}/setPrice', 'WhsCenter\SetPriceGoodController@setPrice');

        Route::get('/members/set-members', 'WhsCenter\MemberController@index')->name('members.set-members.index');
        Route::post('/members/set-members/showCustomer', 'WhsCenter\MemberController@showCustomer');
        Route::post('/members/set-members/checkOutCustomer', 'WhsCenter\MemberController@checkOutCustomer')->name('members.set-members.checkOutCustomer');
        Route::post('/members/set-members/showMember', 'WhsCenter\MemberController@showMember');
        Route::post('/members/set-members/randomCode', 'WhsCenter\MemberController@randomCode');
        Route::post('/members/set-members/showWarehouse', 'WhsCenter\MemberController@showWarehouse');
        Route::post('/members/set-members/showMemberType', 'WhsCenter\MemberController@showMemberType');
        Route::post('/members/set-members/showBank', 'WhsCenter\MemberController@showBank');
        Route::post('/members/set-members/checkMember', 'WhsCenter\MemberController@checkMember');
        Route::post('/members/set-members/saveMember', 'WhsCenter\MemberController@saveMember');
        Route::post('/members/set-members/uploadAvatar', 'WhsCenter\MemberController@uploadAvatar')->name('members.set-members.uploadAvatar');
        Route::post('/members/set-members/destroyMember', 'WhsCenter\MemberController@destroyMember');
        Route::get('/members/set-members/showProfile/{member_id}', 'WhsCenter\MemberController@showProfile')->name('members.set-members.showProfile');
        Route::get('/members/set-members/showProfile/{member_id}/showPointDetail', 'WhsCenter\MemberController@showPointDetail');
        Route::get('/members/set-members/showProfile/{member_id}/showBenefitDetail', 'WhsCenter\MemberController@showBenefitDetail');
        Route::get('/members/set-members/showProfile/{member_id}/hs-bill-point/{h_s_id}', 'WhsCenter\MemberController@showHsBillPoint');
        Route::get('/members/set-members/showProfile/{member_id}/hs-bill-benefit/{h_s_id}', 'WhsCenter\MemberController@showHsBillBenefit');
        Route::post('/members/set-members/exportExcel', 'WhsCenter\MemberController@exportSummaryMemberPointToExcel')->name('members.set-members.exportExcel');

        Route::get('/members/set-member-types', 'WhsCenter\MemberTypeController@index')->name('members.set-member-types.index');
        Route::post('/members/set-member-types', 'WhsCenter\MemberTypeController@getMemberType');
        Route::post('/members/set-member-types/storeMemberType', 'WhsCenter\MemberTypeController@storeMemberType');
        Route::post('/members/set-member-types/editMemberType', 'WhsCenter\MemberTypeController@editMemberType');
        Route::post('/members/set-member-types/deleteMemberType', 'WhsCenter\MemberTypeController@deleteMemberType');

        Route::get('/repost/amount-goods/index', 'WhsCenter\ReportAmountGoodController@indexBalance')->name('report.amount-goods.index');
        Route::post('/repost/amount-goods/search-good-form-type', 'WhsCenter\ReportAmountGoodController@ajaxSearchGoodFormType')->name('search-good-form-type');
        Route::post('/repost/amount-goods/check-amount', 'WhsCenter\ReportAmountGoodController@ajaxCheckAmount')->name('check-amount');
    });

    Route::prefix('kc-inv')->name('kc-inv.')->group(function () {
        Route::get('/dashboard', 'KcinvController@dashboard')->name('dashboard');
        Route::get('/requisitions', 'KcinvController@index')->name('requisitions');
        Route::get('/requisitions/view-create', 'KcinvController@viewCreate')->name('requisitions.view-create');

        Route::post('/type/list/goods', 'KcinvController@listGoods')->name('list.goods');
        Route::post('/config/goods', 'KcinvController@configGoods')->name('config.goods');
        Route::post('/config/store', 'KcinvController@store')->name('config.store');
    });

});
