<?php
use Illuminate\Support\Facades\Route;

Route::get('youDoNotHaveAccess', 'AdminPanelController@notAccessToPage')->name('dash.notAccessToPage');

Route::post('waiterCheckForCheckin', 'AdminPanelController@waiterCheckForCheckin')->name('dash.waiterCheckForCheckin');

// Admin Panel Controll
Route::get('dashboard', 'AdminPanelController@index')->name('dash.index');
Route::get('dashboard2', 'AdminPanelController@index2')->name('dash.index2');
Route::get('dashboard3', 'AdminPanelController@index3')->name('dash.index3');
Route::get('dashboardList', 'AdminPanelController@indexList')->name('dash.indexList');
Route::get('dashboardFreieTische', 'AdminPanelController@index')->name('dash.indexFreeTables');

Route::post('getMoOrdersForRes', 'AdminPanelController@getMoOrdersForRes')->name('dash.getMoOrdersForRes');

Route::post('adminCloseAllProductsTab', 'AdminPanelController@closeAllProductsTab')->name('dash.closeAllProductsTab');
Route::post('adminCloseSelectedProductsTab', 'AdminPanelController@closeSelectedProductsTab')->name('dash.closeSelectedProductsTab');

Route::get('dashboardaddNewProductOrPage', 'AdminPanelController@index')->name('dash.addNewProductOrPage');
Route::post('dashboardaddNewProductOrPageStore', 'AdminPanelController@addNewProductOrPageStore')->name('dash.addNewProductOrPageStore');
Route::post('addToCartAdminsNewOrderToMe', 'AdminPanelController@addToCartAdminsNewOrderToMe')->name('dash.addToCartAdminsNewOrderToMe');
Route::post('removePaidProductCart', 'AdminPanelController@removePaidProductCart')->name('dash.removePaidProductCart');
Route::post('removePaidProductCart2', 'AdminPanelController@removePaidProductCart2')->name('dash.removePaidProductCart2');

Route::get('dashboardTakeaway', 'AdminPanelController@indexTakeaway')->name('dash.takeaway');
Route::get('dashboardDelivery', 'AdminPanelController@indexDelivery')->name('dash.delivery');

Route::get('dashboardStatistics', 'AdminPanelController@statistics')->name('dash.statistics');
Route::post('dashboard', 'AdminPanelController@filterDate')->name('dash.filter');
Route::get('recomendet', 'AdminPanelController@recomendet')->name('dash.recom');

Route::get('Rechnungsverwaltung', 'AdminPanelController@RechnungsverwaltungPage')->name('dash.rechnungPage');

Route::post('reRegisterProdsToTab', 'AdminPanelController@reRegisterProdsToTab')->name('dash.reRegisterProdsToTab');

Route::post('regNotShowVideoAd', 'AdminPanelController@regNotShowVideoAd')->name('dash.regNotShowVideoAd');

Route::post('changeAutoConfOrdTableStatus', 'AdminPanelController@changeAutoConfOrdTableStatus')->name('dash.changeAutoConfOrdTableStatus');

// Most Sales

Route::get('generateMostSalesPDF', 'AdminPanelController@generateMostSalesPDF')->name('adminP.generateMostSalesPDF');
// ------------------------------------------------------------------------------------------------------------------

Route::post('imActiveAP', 'AdminPanelController@sendActiveMSG')->name('dash.sendActiveMSG');

// Status Worker
Route::get('statusWorker', 'AdminPanelController@statusWorkerIndex')->name('dash.statusWorker');
Route::post('statusWorkerA', 'StatusWorkerController@store')->name('statusWorker.add');
Route::delete('statusWorkerD', 'StatusWorkerController@destroy')->name('statusWorker.del');
Route::post('statusWorkerU', 'StatusWorkerController@update')->name('statusWorker.upd');
// ------------------------------------------------------------------------------------------------------------------

// Statistics / Raporti
Route::get('statsDash01', 'AdminPanelController@statistics')->name('dash.dash01');
Route::get('statsDash02', 'AdminPanelController@statistics')->name('dash.dash02');
Route::get('canceledOrders', 'AdminPanelController@statisticsCanceled')->name('dash.dashCanceled');
Route::get('statsDashSalesStatistics', 'AdminPanelController@statistics')->name('dash.salesStatistics');

Route::get('statsBillsRecs', 'AdminPanelController@statBillsPage')->name('dash.statBillsPage');
Route::post('statsBillsRecsSave', 'AdminPanelController@statBillsSave')->name('dash.statBillsSave');
Route::post('statsBillsRecsGetDocs', 'AdminPanelController@statBillsGetDocs')->name('dash.statBillsGetDocs');
Route::post('statsBillsRecsGetDocsW', 'AdminPanelController@statBillsGetDocsW')->name('dash.statBillsGetDocsW');
Route::post('setNewBillsExpenseValue', 'AdminPanelController@setNewBillsExpenseValue')->name('dash.setNewBillsExpenseValue');

Route::post('statsDashSalesStatisticsProds1', 'AdminPanelController@statisticsProds1')->name('dash.salesStatisticsProds1');
Route::post('statsDashSalesStatisticsProds2', 'AdminPanelController@statisticsProds2')->name('dash.salesStatisticsProds2');
Route::post('statsDashSalesStatisticsProds3', 'AdminPanelController@statisticsProds3')->name('dash.salesStatisticsProds3');
Route::post('statsDashSalesStatisticsProds4', 'AdminPanelController@statisticsProds4')->name('dash.salesStatisticsProds4');




Route::get('statsDashDownloadExcel', 'AdminPanelController@downloadExcel')->name('dash.downloadExcel');
Route::post('statsDashDownloadPDFDayR', 'AdminPanelController@downloadPDFDayR')->name('dash.downloadPDFDayR');
Route::post('statsDashDownloadPDFWeekR', 'AdminPanelController@downloadPDFWeekR')->name('dash.downloadPDFWeekR');
Route::post('statsDashDownloadPDFMonthR', 'AdminPanelController@downloadPDFMonthR')->name('dash.downloadPDFMonthR');
Route::post('statsDashDownloadPDFMonthSelectiveR', 'AdminPanelController@downloadPDFMonthSelectiveR')->name('dash.downloadPDFMonthSelectiveR');
Route::get('statsDashDownloadEXCMonthSelectiveR', 'InvoiceController@downloadEXCMonthSelectiveR')->name('dash.downloadEXCMonthSelectiveR');
Route::post('statsDashDownloadPDFYearR', 'AdminPanelController@downloadPDFYearR')->name('dash.downloadPDFYearR');

Route::post('changeResOpenStatusTH', 'AdminPanelController@changeResOpenStatusTH')->name('dash.changeResOpenStatusTH');
// ------------------------------------------------------------------------------------------------------------------

//
Route::get('AdminStatsDeletedTAProdsPage', 'AdminPanelController@admstatsDeletedTAProdsPage')->name('dash.statsDeletedTAProdsPage');
// ---------------------------------------------------------------------------------------------------------------

//
Route::get('AdminchngPayMethodForOrdersPage', 'AdminPanelController@chngPayMethodForOrdersPage')->name('dash.chngPayMethodForOrdersPage');
Route::get('AdminchngPayMethodForOrdersPageWa', 'AdminPanelController@chngPayMethodForOrdersPageWa')->name('dash.chngPayMethodForOrdersPageWa');
Route::get('repairPayMChngIns', 'AdminPanelController@repairPayMChngIns')->name('dash.repairPayMChngIns');
// ---------------------------------------------------------------------------------------------------------------

//Covid19 - mobile
Route::get('covidsTel', 'AdminPanelController@covidsTelIndex')->name('dash.covidsTel');

Route::post('recomendetS', 'RecomendetProdController@store')->name('RecR.store');
Route::post('recomendetS2', 'RecomendetProdController@UpOne')->name('RecR.back');
Route::post('recomendetS3', 'RecomendetProdController@DownOne')->name('RecR.forward');
Route::delete('recomendetS/{recPro}', 'RecomendetProdController@destroy')->name('RecR.destroy');
Route::delete('recomendetS2/{recPro}', 'RecomendetProdController@destroy2')->name('RecR.destroy2');
Route::post('recomendetS2/{recPro}', 'RecomendetProdController@update')->name('RecR.update');
Route::get('recomendetProdSortingReset', 'RecomendetProdController@resetRecProdSorting')->name('RecR.recomendetProdSortingReset');

Route::get('callWaiterIndex', 'WaiterController@index')->name('waiter.index');
Route::post('callWaiter', 'WaiterController@callw')->name('waiter.call');
Route::post('stsWaiter', 'WaiterController@chStatus')->name('waiter.chStatus');



Route::get('showTips', 'TipController@index')->name('tips.index');
Route::get('showTipsMonth', 'TipController@indexM')->name('tips.indexM');

Route::get('dashboardContentMng', 'AdminPanelController@indexContentMng')->name('dash.indexConMng');
Route::get('adminProMngCategory', 'AdminPanelController@indexContentMng')->name('dash.indexConMngCategory');
Route::get('adminProMngProduct', 'AdminPanelController@indexContentMng')->name('dash.indexConMngProduct');
Route::get('adminProMngExtra', 'AdminPanelController@indexContentMng')->name('dash.indexConMngExtra');
Route::get('adminProMngType', 'AdminPanelController@indexContentMng')->name('dash.indexConMngType');

Route::post('adminProEditGetExtras', 'AdminPanelController@proEditGetEkstras')->name('dash.proEditGetEkstras');
Route::post('adminProEditGeTypes', 'AdminPanelController@proEditGetTypes')->name('dash.proEditGetTypes');

Route::post('adminCategoryNewSorting', 'AdminPanelController@catNewSorting')->name('dash.catNewSorting');
Route::post('adminRestaurantNewSorting', 'AdminPanelController@resNewSorting')->name('dash.resNewSorting');

Route::post('adminProUpdateTheOrder', 'AdminPanelController@proUpdateTheOrder')->name('dash.proUpdateTheOrder');
Route::post('adminCatUpdateTheOrder', 'AdminPanelController@catUpdateTheOrder')->name('dash.catUpdateTheOrder');

Route::get('dashboardContentMng/Order', 'AdminPanelController@indexContentMng')->name('dash.indexConMngRenditja');

Route::post('adminCatUpdateTheOrderTel', 'AdminPanelController@catUpdateTheOrderTel')->name('dash.catUpdateTheOrderTel');
Route::post('adminProdUpdateTheOrderTel', 'AdminPanelController@prodUpdateTheOrderTel')->name('dash.prodUpdateTheOrderTel');

// Change table from Admin 
Route::post('adminReqToChangeClTable', 'AdminPanelController@adminReqClTableChange')->name('dash.adminReqClTableChange');
Route::post('adminReqToChangeClTableCheck', 'AdminPanelController@adminReqClTableChangeCheck')->name('dash.adminReqClTableChangeCheck');
Route::post('adminReqToChangeClTableConfirm', 'AdminPanelController@adminReqClTableChangeConfirm')->name('dash.adminReqClTableChangeConfirm');
// -----------------------------------------------------------------------------------------------------------------------------------

// return the ghost Cart 
Route::post('ghostReturnGhostCToUser', 'AdminPanelController@returnGhostCToUser')->name('ghostReturn.ghostCToUserReturn');
Route::post('ghostReturnGhostCToUserCancel', 'AdminPanelController@returnGhostCToUserCancel')->name('ghostReturn.ghostCToUserReturnCancel');

// InfoPage 
Route::get('infoPageTableReservationAdminEmail', 'AdminPanelController@infoPageTableRez')->name('infoPage.infoPageTableRez');
//--------------------------------------------------------------------------------------------------------------------

// Gjenerimi i fakurave 
Route::post('generatePDFReceipt', 'AdminPanelController@generatePDFReceipt')->name('receipt.getReceipt');
Route::post('getTheReceiptQrCodePic', 'AdminPanelController@getTheReceiptQrCodePic')->name('receipt.getTheReceiptQrCodePic');
// ---------------------------------------------------------------------------------------------------------------

// Ndryshimi i passwordit
Route::post('admPassChngDt', 'AdminPanelController@admPassChngDt')->name('admPass.admPassChngDt');
// ---------------------------------------------------------------------------------------------------------------

// add to cart EXPRESS
Route::post('admAddToCartExpresWOT', 'AdminPanelController@addToCartExpresWOT')->name('admExprs.addToCartExpresWOT');
Route::post('admAddToCartExpresWT', 'AdminPanelController@addToCartExpresWT')->name('admExprs.addToCartExpresWT');
// ---------------------------------------------------------------------------------------------------------------

// remove newOrdersAdminAlert
Route::post('admremoveNewOrderAlertFA', 'AdminPanelController@removeNewOrderAlertFA')->name('admin.removeNewOrderAlertFA');
// ---------------------------------------------------------------------------------------------------------------

// client wants to pay Alert the Admin and waiter 
Route::post('admAlertAdmAWaiterClPay', 'AdminPanelController@alertAdmAWaiterClPay')->name('admin.alertAdmAWaiterClPay');
// ---------------------------------------------------------------------------------------------------------------

// group controller 
Route::post('waiSaveGrL1', 'AdmGroupController@waiSaveGrL1F')->name('adminGroup.waiSaveGrL1F');
Route::post('waiSaveGrL2', 'AdmGroupController@waiSaveGrL2F')->name('adminGroup.waiSaveGrL2F');

Route::post('deleteGL1', 'AdmGroupController@deleteGrL1')->name('adminGroup.deleteGrL1');
Route::post('deleteGL2', 'AdmGroupController@deleteGrL2')->name('adminGroup.deleteGrL2');

Route::post('setCatToGroup', 'AdmGroupController@setCateToGroup')->name('adminGroup.setCateToGroup');
Route::post('changeCatToGroup', 'AdmGroupController@changeCateToGroup')->name('adminGroup.changeCateToGroup');

Route::post('copyToWaiters', 'AdmGroupController@copyGrToWaiters')->name('adminGroup.copyGrToWaiters');
// ---------------------------------------------------------------------------------------------------------------

// Barizimi Ditor 
// Route::get('taglicheZiehung', 'AdminPanelController@barazimiDitor')->name('admin.barazimiDitor');
// ---------------------------------------------------------------------------------------------------------------

// Pay All / selected extra function -  
Route::post('payAllFetchOrders', 'AdminPanelController@payAllFetchOrders')->name('admin.payAllFetchOrders');
Route::post('paySelFetchOrders', 'AdminPanelController@paySelFetchOrders')->name('admin.paySelFetchOrders');

// Rechnung
    Route::post('payAllVerifyTelSendNr', 'AdminPanelController@payAllVerifyTelSendNr')->name('admin.payAllVerifyTelSendNr'); 
    Route::post('payAlladdRechnungPay', 'emailBillController@payAlladdRechnungPay')->name('admin.payAlladdRechnungPay'); 
    Route::post('paySeladdRechnungPay', 'emailBillController@paySeladdRechnungPay')->name('admin.paySeladdRechnungPay'); 

    Route::post('sendReminderEmailToCl', 'emailBillController@sendReminderEmailToCl')->name('emailBill.sendReminderEmailToCl'); 
    Route::post('rechnungGetBilMngPage', 'emailBillController@rechnungGetBilMngPage')->name('emailBill.rechnungGetBilMngPage'); 
    Route::post('rechnungConfirmPayment', 'emailBillController@rechnungConfirmPayment')->name('emailBill.rechnungConfirmPayment'); 

    Route::post('getDataClDayToPay', 'emailBillController@getDataClDayToPay')->name('emailBill.getDataClDayToPay'); 
    Route::post('setDaysClDayToPay', 'emailBillController@setDaysClDayToPay')->name('emailBill.setDaysClDayToPay'); 

// ---------------------------------------------------------------------------------------------------------------


// Categorize products onreport
Route::get('categorizeReport', 'AdminPanelController@categorizeReport')->name('admin.categorizeReport');

Route::post('saveTheWHForReportGen', 'AdminPanelController@saveTheWHForReportGen')->name('admin.saveTheWHForReportGen');

Route::post('catRepSaveNewGroup', 'AdminPanelController@saveNewGroup')->name('admin.saveNewGroup');
Route::post('catRepDeleteReportGroup', 'AdminPanelController@deleteReportGroup')->name('admin.deleteReportGroup');
Route::post('catRepSetProdToCat', 'AdminPanelController@catRepSetProdToCat')->name('admin.catRepSetProdToCat');
Route::post('catRepSetProdToCatTA', 'AdminPanelController@catRepSetProdToCatTA')->name('admin.catRepSetProdToCatTA');

Route::post('catRepSetAllCatToCat', 'AdminPanelController@catRepSetAllCatToCat')->name('admin.catRepSetAllCatToCat');
// ---------------------------------------------------------------------------------------------------------------


// Used one time
Route::post('updateProCatSortingNumbers', 'AdminPanelController@updateProCatSortingNumbers')->name('dash.updateProCatSortingNumbers');
Route::post('genEBankingQrCode', 'AdminPanelController@genEBankingQrCode')->name('testAdm.genEBankingQrCode');

Route::get('setGroupToCloseOrers', 'AdminPanelController@setGroupToCloseOrers')->name('testAdm.setGroupToCloseOrers');

Route::get('copyARestoToAResto', 'AdminPanelController@copyARestoToAResto')->name('testAdm.copyARestoToAResto');
//--------------------------------------------------------------------------------------------------------------------

Route::post('checkIfTableHasOrders', 'AdminPanelController@checkIfTableHasOrders')->name('admin.checkIfTableHasOrders');


// waiter daily balance 
Route::get('waitersDailySales', 'AdminPanelController@waDailySalesPage')->name('admin.waDailySalesPage');
Route::post('waitersDailySalesGetData', 'waiterDaySalesController@waDailySalesPageGetData')->name('admin.waDailySalesPageGetData');
Route::post('waitersDailySalesGetDataOrders', 'waiterDaySalesController@waDailySalesPageGetDataOrders')->name('admin.waDailySalesPageGetDataOrders');
//--------------------------------------------------------------------------------------------------------------------

// Activate some notifications
Route::get('NotificationsAct', 'AdminPanelController@notificationsActPage')->name('admin.notificationsActPage');
Route::get('NotificationsActWaiter', 'adminMngWorkersController@notificationsActPageWaiter')->name('waiter.notificationsActPage');
Route::post('NotificationsActChng', 'UserAdminControler@notificationsActChng')->name('admin.notificationsActChng');
Route::post('NotificationsActSetNewGlowTbColor', 'UserAdminControler@notificationsActSetNewGlowTbColor')->name('admin.notificationsActSetNewGlowTbColor');
Route::post('NotificationsActSetNewSound21', 'UserAdminControler@notificationsActSetNewSound21')->name('admin.notificationsActSetNewSound21');
Route::post('NotificationsActSetNewSound31', 'UserAdminControler@notificationsActSetNewSound31')->name('admin.notificationsActSetNewSound31');
//--------------------------------------------------------------------------------------------------------------------

// Table Reservations
Route::post('getWorkingHrsForRes', 'TableRezController@getWorkingHrsForRes')->name('tableRez.getWorkingHrsForRes');
Route::post('getPhoneNrSendVerificationCode', 'TableRezController@getPhoneNrSendVerificationCode')->name('tableRez.getPhoneNrSendVerificationCode');
Route::post('sendVerCodeForPhoneNr', 'TableRezController@sendVerCodeForPhoneNr')->name('tableRez.sendVerCodeForPhoneNr');
Route::post('saveRezRequest77', 'TableRezController@saveRezRequest')->name('tableRez.saveRezRequest');
//--------------------------------------------------------------------------------------------------------------------

// Invalid TAB orders 
Route::post('invTabOrSendCheck', 'AdminPanelController@invTabOrSendCheck')->name('invTabOr.sendCheck');
//--------------------------------------------------------------------------------------------------------------------

// Clean a restaurant AND the orders
Route::get('deleteResCont', 'AdminPanelController@deleteResCont')->name('delRes.deleteResCont');
Route::get('copyOrdersToOrdersPassive', 'AdminPanelController@copyOrdersToOrdersPassive')->name('cleanOrders.copyOrdersToOrdersPassive');
Route::post('checkForCopyOrdersToOrdersPassive', 'AdminPanelController@checkForCopyOrdersToOrdersPassive')->name('cleanOrders.checkForCopyOrdersToOrdersPassive');
//--------------------------------------------------------------------------------------------------------------------

// checkInOut
Route::post('checkInRegister', 'AdminPanelController@checkInRegister')->name('chkInOut.checkInRegister');
Route::post('checkOutRegister', 'AdminPanelController@checkOutRegister')->name('chkInOut.checkOutRegister');

Route::get('openCheckInOutReports', 'AdminPanelController@openCheckInOutReports')->name('admWoMng.openCheckInOutReports');
Route::get('openCheckInOutReportsWa', 'AdminPanelController@openCheckInOutReportsWa')->name('admWoMng.openCheckInOutReportsWa');
Route::post('getWaCheckInOutIns', 'AdminPanelController@getWaCheckInOutIns')->name('chkInOut.getWaCheckInOutIns');
Route::post('getCheckInOutSalesRepo', 'AdminPanelController@getCheckInOutSalesRepo')->name('chkInOut.getCheckInOutSalesRepo');

//--------------------------------------------------------------------------------------------------------------------

// connfirm orders
Route::post('admConfConfirmAll', 'AdminPanelController@admConfConfirmAll')->name('admConf.confirmAll');
//--------------------------------------------------------------------------------------------------------------------

// Abrufen sherbimi 
Route::post('AbrufenCallProd', 'AdminPanelController@AbrufenCallProd')->name('abrufen.callProd');
Route::post('AbrufenCallByPlate', 'AdminPanelController@AbrufenCallByPlate')->name('abrufen.callByPlate');

//--------------------------------------------------------------------------------------------------------------------

// Bill Tablet 
Route::get('BillTablets', 'AdminPanelController@billTabletIndex')->name('billTablet.index');
Route::get('BillTabletsActive', 'AdminPanelController@BillTabletsActive')->name('billTablet.active');
Route::post('BillTabletSaveTablet', 'AdminPanelController@billTabletSaveTablet')->name('billTablet.saveTablet');
Route::post('BillTabletDeleteTablet', 'AdminPanelController@billTabletDeleteTablet')->name('billTablet.deleteTablet');
Route::post('BillTabletEditTablet', 'AdminPanelController@billTabletEditTablet')->name('billTablet.editTablet');
Route::post('BillTabletEditTabletTA', 'AdminPanelController@billTabletEditTabletTA')->name('billTablet.editTabletTA');

Route::post('sendBillToTabletWaiting', 'AdminPanelController@sendBillToTabletWaiting')->name('billTablet.sendBillToTabletWaiting');
Route::post('sendNewTabletStatus', 'AdminPanelController@sendNewTabletStatus')->name('billTablet.sendNewTabletStatus');
Route::post('getAndDisplayOrdersInTablet', 'AdminPanelController@getAndDisplayOrdersInTablet')->name('billTablet.getAndDisplayOrdersInTablet');

Route::post('BillTabletSetNewTipp', 'AdminPanelController@billTabletSetNewTipp')->name('billTablet.billTabletSetNewTipp');
Route::post('BillTabletSetNewRabatt', 'AdminPanelController@billTabletSetNewRabatt')->name('billTablet.billTabletSetNewRabatt');
Route::post('BillTabletSetNewGC', 'AdminPanelController@billTabletSetNewGC')->name('billTablet.billTabletSetNewGC');
//--------------------------------------------------------------------------------------------------------------------

// Order serving page
Route::post('orServingActThePage', 'AdminPanelController@orServingActThePage')->name('orServing.actThePage');

Route::get('orServingPage', 'AdminPanelController@orServingPageOpen')->name('orServing.orServingPageOpen');

Route::get('orderServingDevicesPage', 'AdminPanelController@orderServingDevicesPage')->name('orServing.orderServingDevicesPage');
Route::post('orderServingDevicesSave', 'AdminPanelController@orderServingDevicesSave')->name('orServing.orderServingDevicesSave');
Route::post('orderServingDevicesDelete', 'AdminPanelController@orderServingDevicesDelete')->name('orServing.orderServingDevicesDelete');

Route::post('orderServingDevicesAddKatAccss', 'AdminPanelController@orderServingDevicesAddKatAccss')->name('orServing.orderServingDevicesAddKatAccss');
Route::post('orderServingDevicesRemoveKatAccss', 'AdminPanelController@orderServingDevicesRemoveKatAccss')->name('orServing.orderServingDevicesRemoveKatAccss');

Route::post('orderServingDevicesAddProdAccss', 'AdminPanelController@orderServingDevicesAddProdAccss')->name('orServing.orderServingDevicesAddProdAccss');
Route::post('orderServingDevicesRemoveProdAccss', 'AdminPanelController@orderServingDevicesRemoveProdAccss')->name('orServing.orderServingDevicesRemoveProdAccss');

Route::post('orderServingDevicesChngShowBlocks', 'AdminPanelController@orderServingDevicesChngShowBlocks')->name('orServing.orderServingDevicesChngShowBlocks');
Route::post('orderServingDevicesConfServeProd', 'AdminPanelController@orderServingDevicesConfServeProd')->name('orServing.orderServingDevicesConfServeProd');

Route::post('orderServingDevicesCheckNotify', 'AdminPanelController@orderServingDevicesCheckNotify')->name('orServing.orderServingDevicesCheckNotify');
//--------------------------------------------------------------------------------------------------------------------

// New Table Page
Route::post('tablePageNewOrDetailedFetch', 'AdminPanelController@tablePageNewOrDetailedFetch')->name('resTablePage.tablePageNewOrDetailedFetch');
Route::post('tablePageTableChangeFetchClients', 'AdminPanelController@tablePageTableChangeFetchClients')->name('resTablePage.tablePageTableChangeFetchClients');
Route::post('tablePageTableChangeFetchClientsWaiter', 'AdminPanelController@tablePageTableChangeFetchClientsWaiter')->name('resTablePage.tablePageTableChangeFetchClientsWaiter');
Route::post('tablePageTableChangeFetchTables', 'AdminPanelController@tablePageTableChangeFetchTables')->name('resTablePage.tablePageTableChangeFetchTables');
Route::post('tablePageTableChangeFetchTablesWaiter', 'AdminPanelController@tablePageTableChangeFetchTablesWaiter')->name('resTablePage.tablePageTableChangeFetchTablesWaiter');
//--------------------------------------------------------------------------------------------------------------------


// change payment method for orders >= 24h
Route::post('payMethodChangeByStaff', 'AdminPanelController@payMethodChangeByStaff')->name('payMChng.payMethodChangeByStaff');
//--------------------------------------------------------------------------------------------------------------------

// Print receipt 
Route::post('callDataForPrintReceipt', 'AdminPanelController@callDataForPrintReceipt')->name('print.callDataForPrintReceipt');
Route::post('callDataForPrintReceiptActiveTab', 'AdminPanelController@callDataForPrintReceiptActiveTab')->name('print.callDataForPrintReceiptActiveTab');
//--------------------------------------------------------------------------------------------------------------------

// PAYTEC 
Route::post('payTecPair', 'AdminPanelController@payTecPair')->name('payTec.Pair');
Route::post('payTecConnect', 'AdminPanelController@payTecConnect')->name('payTec.Connect');
Route::post('payTecDisconnect', 'AdminPanelController@payTecDisconnect')->name('payTec.Disconnect');
Route::post('payTecTransact', 'AdminPanelController@payTecTransact')->name('payTec.Transact');

Route::post('payTeccollectErrorLog', 'payTecController@collectErrorLog')->name('payTec.collectErrorLog');
//--------------------------------------------------------------------------------------------------------------------


// Split the bill  
Route::post('splitBillDisplayFirstSplit', 'splitBillController@displayFirstSplit')->name('splitBill.displayFirstSplit');
Route::post('displaysplitBillAfterRechnung', 'splitBillController@displaysplitBillAfterRechnung')->name('splitBill.displaysplitBillAfterRechnung');

Route::post('splitBillPayCashCard', 'splitBillController@splitBillPayCashCard')->name('splitBill.payCashCard');
Route::post('splitBillGiftCardValidateTheIdnCode', 'splitBillController@splitBillGiftCardValidateTheIdnCode')->name('splitBill.splitBillGiftCardValidateTheIdnCode');
Route::post('splitBillGiftCardApplyAmount', 'splitBillController@splitBillGiftCardApplyAmount')->name('splitBill.splitBillGiftCardApplyAmount');
Route::post('splitBillGiftCardApplyAmountMax', 'splitBillController@splitBillGiftCardApplyAmountMax')->name('splitBill.splitBillGiftCardApplyAmountMax');
Route::post('splitBillPayAufRechnung', 'splitBillController@splitBillPayAufRechnung')->name('splitBill.payAufRechnung');

Route::post('splitBillCallUnfinishedBill', 'splitBillController@splitBillCallUnfinishedBill')->name('splitBill.splitBillCallUnfinishedBill');
Route::post('splitBillCancelTheInitiate', 'splitBillController@splitBillCancelTheInitiate')->name('splitBill.splitBillCancelTheInitiate');
//--------------------------------------------------------------------------------------------------------------------

// xxxxxxx
Route::post('prodOnOffCallCatProds', 'AdminPanelController@prodOnOffCallCatProds')->name('prodOnOff.callCatProds');
Route::post('prodOnOffChangeProdStatus', 'AdminPanelController@prodOnOffChangeProdStatus')->name('prodOnOff.changeProdStatus');

Route::post('prodOnOffCallCatProdsTA', 'AdminPanelController@prodOnOffCallCatProdsTA')->name('prodOnOff.callCatProdsTA');
Route::post('prodOnOffChangeProdStatusTA', 'AdminPanelController@prodOnOffChangeProdStatusTA')->name('prodOnOff.changeProdStatusTA');
//--------------------------------------------------------------------------------------------------------------------

// TA POS

Route::post('payTakeawayPosConfirm', 'AdminPanelController@payTakeawayPosConfirm')->name('taDash.payTakeawayPosConfirm');
//--------------------------------------------------------------------------------------------------------------------

// ---- 
Route::post('deleteSelectedTabOrders', 'AdminPanelController@deleteSelectedTabOrders')->name('tablePage.deleteSelectedTabOrders');
Route::post('deleteAllTabOrders', 'AdminPanelController@deleteAllTabOrders')->name('tablePage.deleteAllTabOrders');

Route::post('plateForAbrufenFetchPlatesForAll', 'AdminPanelController@plateForAbrufenFetchPlatesForAll')->name('tablePage.plateForAbrufenFetchPlatesForAll');
Route::post('plateForAbrufenExecuteAbrufen', 'AdminPanelController@plateForAbrufenExecuteAbrufen')->name('tablePage.plateForAbrufenExecuteAbrufen');
Route::post('executeAbrufenOnSelectedTabOr', 'AdminPanelController@executeAbrufenOnSelectedTabOr')->name('tablePage.executeAbrufenOnSelectedTabOr');

Route::post('deleteTabOrderCheckForConfirmed', 'AdminPanelController@deleteTabOrderCheckForConfirmed')->name('tablePage.deleteTabOrderCheckForConfirmed');

Route::post('tabOrderModalCheckTotalPriceShow', 'AdminPanelController@tabOrderModalCheckTotalPriceShow')->name('tablePage.tabOrderModalCheckTotalPriceShow');

Route::post('tabOrderModalCheckActiveOrToReopen', 'AdminPanelController@tabOrderModalCheckActiveOrToReopen')->name('tablePage.tabOrderModalCheckActiveOrToReopen');
//--------------------------------------------------------------------------------------------------------------------


Route::get('repairSomeTables', 'adminMngWorkersController@repairSomeTables')->name('tempScript.repairSomeTables');

Route::get('repairOrdersRefId', 'AdminPanelController@repairOrdersRefId')->name('tempScript.repairOrdersRefId');

Route::post('testDirectPrint', 'AdminPanelController@testDirectPrint')->name('test.testDirectPrint');

Route::get('deleteResSales', 'AdminPanelController@deleteResSales')->name('tempScript.deleteResSales');

