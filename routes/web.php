<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginRegisterController;
use App\Http\Controllers\SellerAdminController;

//buyer side
Route::get('/search',[HomeController::class,'search'])->name('search');

Route::get('/',[HomeController::class,'home'])->name('home');
Route::get('/catalog',[HomeController::class,'productCatalog'])->name('productcatalog');
Route::get('/catalog/fulldetails/{prod_id}',[HomeController::class,'productFullDetails'])->name('productfulldetails');
Route::post('/catalog/addcart',[HomeController::class,'addCart'])->name('add.cart');
Route::get('/catalog/addcart',[HomeController::class,'addCart'])->middleware('notLogged');

Route::get('/review/{prod_id}',[HomeController::class,'reviewProduct'])->name('reviewproduct')->middleware('notLogged');
Route::post('/review/save',[HomeController::class,'saveReview'])->name('save.review');
Route::get('/review/save',[HomeController::class,'saveReview'])->middleware('notLogged');

Route::get('/dashboard/main/{status?}',[HomeController::class,'dashboard'])->name('dashboard')->middleware('notLogged');
Route::get('/dashboard/orderdetails/{order_id}',[HomeController::class,'orderDetails'])->name('orderdetails')->middleware('notLogged');
Route::post('/dashboard/orderdetails/unpaid',[HomeController::class,'orderUnpaid'])->name('order.unpaid');
Route::get('/dashboard/orderdetails/unpaid',[HomeController::class,'orderUnpaid'])->middleware('notLogged');
Route::get('/dashboard/orderdetails/cancelpage/{order_id}',[HomeController::class,'orderCancelPage'])->name('ordercancelpage')->middleware('notLogged');
Route::post('/dashboard/orderdetails/cancel',[HomeController::class,'orderCancel'])->name('order.cancel');
Route::get('/dashboard/orderdetails/cancel',[HomeController::class,'orderCancel'])->middleware('notLogged');
Route::get('/dashboard/orderdetails/complete/{order_id}',[HomeController::class,'orderComplete'])->name('orderComplete')->middleware('notLogged');
Route::get('/dashboard/profile',[HomeController::class,'profile'])->name('profile')->middleware('notLogged');
Route::get('/dashboard/addressbook',[HomeController::class,'addressBook'])->name('addressbook')->middleware('notLogged');
Route::get('/dashboard/addressbook/newaddress',[HomeController::class,'newAddress'])->name('newaddress')->middleware('notLogged');
Route::get('/dashboard/addressbook/editaddress/{add_id}',[HomeController::class,'editAddress'])->name('editaddress')->middleware('notLogged');

Route::get('/cart',[HomeController::class,'cart'])->name('cart')->middleware('notLogged');
// Route::post('/cart/update',[HomeController::class,'cart'])->name('cart.update');
// Route::get('/cart/update',[HomeController::class,'cart'])->middleware('notLogged');
Route::put('/cart/update/{cartitem_id}',[HomeController::class,'cartUpdate'])->name('cart.update')->middleware('notLogged');
Route::get('/cart/delete/{cartitem_id}',[HomeController::class,'cartDelete'])->name('cart.delete')->middleware('notLogged');

Route::get('/checkout',[HomeController::class,'checkout'])->name('checkout')->middleware('notLogged');
Route::post('/finalcheckout',[HomeController::class,'finalCheckout'])->name('final.checkout');
Route::get('/finalcheckout',[HomeController::class,'finalCheckout'])->middleware('notLogged');

Route::get('/exchange/requestpage/{order_id}',[HomeController::class,'requestExchangePage'])->name('requestexchangepage')->middleware('notLogged');
Route::post('/finalexchange',[HomeController::class,'exchange'])->name('exchange');
Route::get('/finalexchange',[HomeController::class,'exchange'])->middleware('notLogged');
Route::get('/exchange/list/{status?}',[HomeController::class,'exchangeList'])->name('exchangelist')->middleware('notLogged');
Route::get('/exchange/exchangedetails/{exchange_id}',[HomeController::class,'exchangeDetails'])->name('exchangedetails')->middleware('notLogged');
Route::get('/exchange/exchangedetails/cancel/{exchange_id}',[HomeController::class,'exchangeCancel'])->name('exchangecancel')->middleware('notLogged');

Route::post('/marknotif',[HomeController::class,'markNotif'])->name('mark.notif');
Route::get('/marknotif',[HomeController::class,'markNotif'])->middleware('notLogged');

Route::get('/ordertracking',[HomeController::class,'orderTracking'])->name('ordertracking');

Route::get('/ticketlist',[HomeController::class,'ticketList'])->name('ticketlist')->middleware('notLogged');

Route::get('/login',[LoginRegisterController::class,'login'])->name('login')->middleware('logged');
// Route::post('/login/buyer',[LoginRegisterController::class,'loginBuyer'])->name('login.buyer');
// Route::get('/login/buyer',[LoginRegisterController::class,'loginBuyer'])->middleware('logged');
Route::post('/login/user',[LoginRegisterController::class,'loginUser'])->name('login.user');
Route::get('/login/user',[LoginRegisterController::class,'loginUser'])->middleware('logged');
Route::get('/register',[LoginRegisterController::class,'register'])->name('register')->middleware('logged');
Route::post('/register/buyer',[LoginRegisterController::class,'registerBuyer'])->name('register.buyer');
Route::get('/register/buyer',[LoginRegisterController::class,'registerBuyer'])->middleware('logged');
Route::get('/logout',[LoginRegisterController::class,'logout'])->name('logout')->middleware('notLogged');    //to add loggedOut middleware (overhaul)
//Route::get('/logout2',[LoginRegisterController::class,'logout2'])->name('logout2');

// Route::get('/seller/login',[LoginRegisterController::class,'login2'])->name('sellerlogin');
// Route::post('/seller/login/processing',[LoginRegisterController::class,'loginSA'])->name('login.seller');
// Route::get('/seller/login/processing',[LoginRegisterController::class,'loginSA']);

//seller Side
Route::get('/seller/sellerprofile',[SellerAdminController::class,'sellerProfile'])->name('sellerprofile')->middleware('notLogged');
Route::post('/seller/sellerprofile/save',[SellerAdminController::class,'saveSellerDetails'])->name('save.seller.details');
Route::get('/seller/sellerprofile/save',[SellerAdminController::class,'saveSellerDetails'])->middleware('notLogged');
Route::post('/seller/sellerprofile/changepassword',[SellerAdminController::class,'changePassword'])->name('change.password');
Route::get('/seller/sellerprofile/changepassword',[SellerAdminController::class,'changePassword'])->middleware('notLogged');

Route::get('/seller/sellerprofile/addressbook',[SellerAdminController::class,'sellerAddressBook'])->name('selleraddressbook')->middleware('notLogged');
Route::get('/seller/sellerprofile/addressbook/newaddress',[SellerAdminController::class,'addSellerAddress'])->name('addselleraddress')->middleware('notLogged');
Route::post('/seller/sellerprofile/addressbook/newaddress/saveaddress',[SellerAdminController::class,'saveSellerAddress'])->name('save.seller.address');
Route::get('/seller/sellerprofile/addressbook/newaddress/saveaddress',[SellerAdminController::class,'saveSellerAddress'])->middleware('notLogged');
Route::get('/seller/sellerprofile/addressbook/editaddress/{add_id}',[SellerAdminController::class,'editSellerAddress'])->name('editselleraddress')->middleware('notLogged');
Route::post('/seller/sellerprofile/addressbook/editaddress/updateaddress',[SellerAdminController::class,'updateSellerAddress'])->name('update.seller.address');
Route::get('/seller/sellerprofile/addressbook/editaddress/updateaddress',[SellerAdminController::class,'updateSellerAddress'])->middleware('notLogged');
Route::get('/seller/sellerprofile/addressbook/deleteaddress/{add_id}',[SellerAdminController::class,'deleteSellerAddress'])->name('deleteselleraddress')->middleware('notLogged');

Route::get('/seller/product',[SellerAdminController::class,'sellerProduct'])->name('sellerproduct')->middleware('notLogged');
Route::get('/seller/rejected',[SellerAdminController::class,'rejectedProduct'])->name('rejectedproduct')->middleware('notLogged');
Route::get('/seller/rejected/rejecteddetails/{prod_id}',[SellerAdminController::class,'rejectedDetails'])->name('rejecteddetails')->middleware('notLogged');
Route::get('/seller/product/newproduct',[SellerAdminController::class,'addSellerProduct'])->name('addsellerproduct')->middleware('notLogged');
Route::post('/seller/product/newproduct/saveproduct',[SellerAdminController::class,'saveSellerProduct'])->name('save.seller.product');
Route::get('/seller/product/newproduct/saveproduct',[SellerAdminController::class,'saveSellerProduct'])->middleware('notLogged');
Route::get('/seller/product/editproduct/{prod_id}',[SellerAdminController::class,'editSellerProduct'])->name('editsellerproduct')->middleware('notLogged');
Route::get('/seller/product/editproduct/productstock/{prod_id}',[SellerAdminController::class,'productStock'])->name('product.stock')->middleware('notLogged');
Route::post('/seller/product/editproduct/productstock/addstock',[SellerAdminController::class,'addStock'])->name('add.stock');
Route::get('/seller/product/editproduct/productstock/addstock',[SellerAdminController::class,'addStock'])->middleware('notLogged');
Route::post('/seller/product/editproduct/productstock/addstock2',[SellerAdminController::class,'addStock2'])->name('add.stock2');
Route::get('/seller/product/editproduct/productstock/addstock2',[SellerAdminController::class,'addStock2'])->middleware('notLogged');
Route::post('/seller/product/editproduct/updateproduct',[SellerAdminController::class,'updateSellerProduct'])->name('update.seller.product');
Route::get('/seller/product/editproduct/updateproduct',[SellerAdminController::class,'updateSellerProduct'])->middleware('notLogged');
Route::get('/seller/product/editproduct/editvariation/{prod_id}',[SellerAdminController::class,'editProductVariation'])->name('editproductvariation')->middleware('notLogged');
Route::post('/seller/product/editproduct/editvariation/removecolor',[SellerAdminController::class,'removeColor'])->name('remove.color');
Route::get('/seller/product/editproduct/editvariation/removecolor',[SellerAdminController::class,'removeColor'])->middleware('notLogged');
Route::post('/seller/product/editproduct/editvariation/removesize',[SellerAdminController::class,'removeSize'])->name('remove.size');
Route::get('/seller/product/editproduct/editvariation/removesize',[SellerAdminController::class,'removeSize'])->middleware('notLogged');
Route::post('/seller/product/editproduct/editvariation/addcolor',[SellerAdminController::class,'addColor'])->name('add.color');
Route::get('/seller/product/editproduct/editvariation/addcolor',[SellerAdminController::class,'addColor'])->middleware('notLogged');
Route::post('/seller/product/editproduct/editvariation/addsize',[SellerAdminController::class,'addSize'])->name('add.size');
Route::get('/seller/product/editproduct/editvariation/addsize',[SellerAdminController::class,'addSize'])->middleware('notLogged');
Route::get('/seller/product/deleteproduct/{prod_id}',[SellerAdminController::class,'deleteSellerProduct'])->name('delete.seller.product')->middleware('notLogged');
Route::get('/seller/product/reviews/{prod_id}',[SellerAdminController::class,'productReviews'])->name('productreviews')->middleware('notLogged');

Route::get('/seller/order/{status?}',[SellerAdminController::class,'sellerOrder'])->name('sellerorder')->middleware('notLogged');
Route::get('/seller/orderdetails/{order_id}',[SellerAdminController::class,'sellerOrderDetails'])->name('sellerorderdetails')->middleware('notLogged');
Route::get('/seller/orderdetails/orderapprove/{order_id}',[SellerAdminController::class,'sellerOrderApprove'])->name('sellerorderapprove')->middleware('notLogged');
Route::post('/seller/orderdetails/tracknum',[SellerAdminController::class,'sellerOrderTrack'])->name('seller.order.track');
Route::get('/seller/orderdetails/tracknum',[SellerAdminController::class,'sellerOrderTrack'])->middleware('notLogged');
Route::get('/seller/orderdetails/reportuser/{some_id}/{type}',[SellerAdminController::class,'reportUser'])->name('reportuser')->middleware('notLogged');
Route::post('/seller/orderdetails/reportsend',[SellerAdminController::class,'reportSend'])->name('report.send');
Route::get('/seller/orderdetails/reportsend',[SellerAdminController::class,'reportSend'])->middleware('notLogged');

Route::get('/seller/exchange/{status?}',[SellerAdminController::class,'sellerExchange'])->name('sellerexchange')->middleware('notLogged');
Route::get('/seller/exchangedetails/{exchange_id}',[SellerAdminController::class,'sellerExchangeDetails'])->name('sellerexchangedetails')->middleware('notLogged');
Route::get('/seller/exchangedetails/approve/{exchange_id}',[SellerAdminController::class,'sellerExchangeApprove'])->name('sellerexchangeapprove')->middleware('notLogged');
Route::post('/seller/exchangedetails/addreturnitem',[SellerAdminController::class,'addReturnItem'])->name('add.return.item');
Route::get('/seller/exchangedetails/addreturnitem',[SellerAdminController::class,'addReturnItem'])->middleware('notLogged');
Route::get('/seller/exchangedetails/removereturnitem/{returnitem_id}',[SellerAdminController::class,'removeReturnItem'])->name('remove.return.item')->middleware('notLogged');
Route::get('/seller/exchangedetails/getvariations/{productId}',[SellerAdminController::class,'getVariations'])->name('getvariations')->middleware('notLogged');
Route::get('/seller/exchangedetails/receive/{exchange_id}',[SellerAdminController::class,'receive'])->name('receive')->middleware('notLogged');
Route::get('/seller/exchangedetails/reject/{exchange_id}',[SellerAdminController::class,'sellerExchangeReject'])->name('sellerexchangereject')->middleware('notLogged');

Route::post('/seller/salesreport',[SellerAdminController::class,'salesReport'])->name('sales.report');
Route::get('/seller/salesreport',[SellerAdminController::class,'salesReport'])->middleware('notLogged');


//admin Side
Route::get('/admin/adminprofile',[SellerAdminController::class,'adminProfile'])->name('adminprofile')->middleware('notLogged');
Route::post('/admin/adminprofile/save',[SellerAdminController::class,'saveAdminDetails'])->name('save.admin.details');
Route::get('/admin/adminprofile/save',[SellerAdminController::class,'saveAdminDetails'])->middleware('notLogged');
Route::post('/admin/adminprofile/updatefee',[SellerAdminController::class,'updateFee'])->name('update.fee');
Route::get('/admin/adminprofile/updatefee',[SellerAdminController::class,'updateFee'])->middleware('notLogged');

Route::get('/admin/userlist',[SellerAdminController::class,'userList'])->name('userlist')->middleware('notLogged');
Route::get('/admin/userlist/userdetails/{user_id}',[SellerAdminController::class,'userDetails'])->name('userdetails')->middleware('notLogged');
Route::get('/admin/userlist/newseller',[SellerAdminController::class,'newSeller'])->name('newseller')->middleware('notLogged');
Route::post('/admin/userlist/newseller/save',[SellerAdminController::class,'saveNewSeller'])->name('save.new.seller');
Route::get('/admin/userlist/newseller/save',[SellerAdminController::class,'saveNewSeller'])->middleware('notLogged');
Route::get('/admin/userlist/disable/{user_id}',[SellerAdminController::class,'disableUser'])->name('disableuser')->middleware('notLogged');
Route::get('/admin/userlist/enable/{user_id}',[SellerAdminController::class,'enableUser'])->name('enableuser')->middleware('notLogged');
Route::post('/admin/userlist/sendticket',[SellerAdminController::class,'sendTicket'])->name('send.ticket');
Route::get('/admin/userlist/sendticket',[SellerAdminController::class,'sendTicket'])->middleware('notLogged');
Route::get('/admin/userlist/reports',[SellerAdminController::class,'reports'])->name('reports')->middleware('notLogged');

Route::get('/admin/productlist',[SellerAdminController::class,'productList'])->name('productlist')->middleware('notLogged');
Route::get('/admin/rejectedlist',[SellerAdminController::class,'rejectedList'])->name('rejectedlist')->middleware('notLogged');
Route::get('/admin/productlist/productdetails/{prod_id}',[SellerAdminController::class,'productDetails'])->name('productdetails')->middleware('notLogged');
Route::get('/admin/productlist/productdetails/rejectpage/{prod_id}',[SellerAdminController::class,'rejectPage'])->name('rejectpage')->middleware('notLogged');
Route::post('/admin/productlist/productdetails/approve',[SellerAdminController::class,'approveProduct'])->name('approveproduct');
Route::get('/admin/productlist/productdetails/approve',[SellerAdminController::class,'approveProduct'])->middleware('notLogged');
Route::post('/admin/productlist/productdetails/reject',[SellerAdminController::class,'rejectProduct'])->name('reject.product');
Route::get('/admin/productlist/productdetails/reject',[SellerAdminController::class,'rejectProduct'])->middleware('notLogged');
Route::get('/admin/productlist/productdetails/disable/{prod_id}',[SellerAdminController::class,'disableProduct'])->name('disableproduct')->middleware('notLogged');
Route::get('/admin/productlist/productdetails/enable/{prod_id}',[SellerAdminController::class,'enableProduct'])->name('enableproduct')->middleware('notLogged');
Route::get('/admin/productlist/userdetails/disable/{user_id}',[SellerAdminController::class,'disableUser'])->name('disable.user')->middleware('notLogged');
Route::get('/admin/productlist/userdetails/enable/{user_id}',[SellerAdminController::class,'enableUser'])->name('enable.user')->middleware('notLogged');

Route::get('/admin/category',[SellerAdminController::class,'categoryList'])->name('categorylist')->middleware('notLogged');
Route::post('/admin/categorylist/newcategory/savecategory',[SellerAdminController::class,'saveCategory'])->name('save.category');
Route::get('/admin/categorylist/newcategory/savecategory',[SellerAdminController::class,'saveCategory'])->middleware('notLogged');
Route::get('/admin/categorylist/editcategory/{cat_id}',[SellerAdminController::class,'editCategory'])->name('editcategory')->middleware('notLogged');
Route::post('/admin/categorylist/editcategory/updatecategory',[SellerAdminController::class,'updateCategory'])->name('update.category');
Route::get('/admin/categorylist/editcategory/updatecategory',[SellerAdminController::class,'updateCategory'])->middleware('notLogged');
Route::get('/admin/categorylist/deletecategory/{cat_id}',[SellerAdminController::class,'deleteCategory'])->name('delete.category')->middleware('notLogged');