<?php
    use Illuminate\Support\Facades\Route;

    //open page WAITER Desktop / smartphone
    Route::get('admWoMngIndexWaiter', 'adminMngWorkersController@indexAdmMngPageWaiter')->name('admWoMng.indexAdmMngPageWaiter');
    Route::get('admWoMngOrdersListWaiter', 'adminMngWorkersController@ordersListAdmMngPageWaiter')->name('admWoMng.ordersListAdmMngPageWaiter');
    Route::get('admWoMngOrdersFreeTables', 'adminMngWorkersController@ordersFreeTablesAdmMngPageWaiter')->name('admWoMng.ordersFreeTablesAdmMngPageWaiter');

    Route::get('admWoMngStatistics01Waiter', 'adminMngWorkersController@ordersStatisticsWaiter01')->name('admWoMng.ordersStatisticsWaiter01');
    Route::get('admWoMngStatistics02Waiter', 'adminMngWorkersController@ordersStatisticsWaiter02')->name('admWoMng.ordersStatisticsWaiter02');
    Route::get('admWoMngStatistics03Waiter', 'adminMngWorkersController@ordersStatisticsWaiter03')->name('admWoMng.ordersStatisticsWaiter03');
    Route::get('admWoMngStatistics04Waiter', 'adminMngWorkersController@ordersStatisticsWaiter04')->name('admWoMng.ordersStatisticsWaiter04');

    Route::get('admWoMngCanceledOrdersWaiter', 'adminMngWorkersController@ordersCanceledOrdersWaiter')->name('admWoMng.ordersCanceledOrdersWaiter');

    Route::get('WaiterstatsBillsRecs', 'adminMngWorkersController@WaiterPanelStatBillsPage')->name('admWoMng.statBillsPageWa');
    Route::post('WaiterstatsBillsRecsSave', 'adminMngWorkersController@WaiterPanelStatBillsSave')->name('admWoMng.statBillsSaveWa');
    Route::post('WaiterstatsBillsRecsGetDocs', 'adminMngWorkersController@WaiterPanelStatBillsGetDocs')->name('admWoMng.statBillsGetDocsWa');

    //
    Route::get('WaiterstatsDeletedTAProdsPage', 'adminMngWorkersController@waiterstatsDeletedTAProdsPage')->name('admWoMng.statsDeletedTAProdsPage');
    // ---------------------------------------------------------------------------------------------------------------


    // Categorize products onreport
    Route::get('categorizeReportWaiter', 'adminMngWorkersController@categorizeReportWaiter')->name('admin.categorizeReportWaiter');
    Route::post('catRepSetProdToCatWaiter', 'adminMngWorkersController@catRepSetProdToCatWaiter')->name('admin.catRepSetProdToCatWaiter');
    Route::post('catRepSetAllCatToCatWaiter', 'adminMngWorkersController@catRepSetAllCatToCatWaiter')->name('admin.catRepSetAllCatToCatWaiter');
    // ---------------------------------------------------------------------------------------------------------------

    Route::get('admWoMngTakeawayWaiter', 'adminMngWorkersController@ordersTakeawayWaiter')->name('admWoMng.ordersTakeawayWaiter');
    Route::get('admWoMngDeliveryWaiter', 'adminMngWorkersController@ordersDeliveryWaiter')->name('admWoMng.ordersDeliveryWaiter');

    Route::get('adminWoSaMsgWaiter', 'adminMngWorkersController@adminSaMsgWaiter')->name('admWoMng.adminSaMsgWaiter');

    Route::get('adminWoRecomendetProdWaiter', 'adminMngWorkersController@adminWoRecomendetProdWaiter')->name('admWoMng.adminWoRecomendetProdWaiter');
    Route::get('adminWoRechnungPageWaiter', 'adminMngWorkersController@adminWoRechnungPageWaiter')->name('admWoMng.adminWoRechnungPageWaiter');

    Route::get('adminWoWaiterCallsWaiter', 'adminMngWorkersController@adminWoWaiterCallsWaiter')->name('admWoMng.adminWoWaiterCallsWaiter');

    Route::get('adminWoContentMngWaiter', 'adminMngWorkersController@adminWoContentMngWaiter')->name('admWoMng.adminWoContentMngWaiter');
    Route::get('adminWoContentMngWaiter/Order', 'adminMngWorkersController@adminWoContentMngOrderWaiter')->name('admWoMng.adminWoContentMngOrderWaiter');

    Route::get('adminWoTableChngReqWaiter', 'adminMngWorkersController@adminWoTableChngReqWaiter')->name('admWoMng.adminWoTableChngReqWaiter');

    Route::get('adminWoTipsWaiter', 'adminMngWorkersController@adminWoTipsWaiter')->name('admWoMng.adminWoTipsWaiter');
    Route::get('adminWoTipsMonthWaiter', 'adminMngWorkersController@adminWoTipsMonthWaiter')->name('admWoMng.adminWoTipsMonthWaiter');

    Route::get('adminWoFreeProductsWaiter', 'adminMngWorkersController@adminWoFreeProductsWaiter')->name('admWoMng.adminWoFreeProductsWaiter');

    Route::get('adminWoRestrictProductsWaiter', 'adminMngWorkersController@adminWoRestrictProductsWaiter')->name('admWoMng.adminWoRestrictProductsWaiter');

    Route::get('adminWoCouponsWaiter', 'adminMngWorkersController@adminWoCouponsWaiter')->name('admWoMng.adminWoCouponsWaiter');

    Route::get('adminWoTakeawayWaiter', 'adminMngWorkersController@adminWoTakeawayWaiter')->name('admWoMng.adminWoTakeawayWaiter');
    Route::get('adminWoDeliveryWaiter', 'adminMngWorkersController@adminWoDeliveryWaiter')->name('admWoMng.adminWoDeliveryWaiter');

    Route::get('adminWoTakeawaySortingWaiter', 'adminMngWorkersController@adminWoTakeawaySortingWaiter')->name('admWoMng.adminWoTakeawaySortingWaiter');
    Route::get('adminWoDeliverySortingWaiter', 'adminMngWorkersController@adminWoDeliverySortingWaiter')->name('admWoMng.adminWoDeliverySortingWaiter');
    
    Route::get('adminWoTableCapacityWaiter', 'adminMngWorkersController@adminWoTableCapacityWaiter')->name('admWoMng.adminWoTableCapacityWaiter');

    Route::get('adminWoTableReservationIndexWaiter', 'adminMngWorkersController@adminWoTableReservationIndexWaiter')->name('admWoMng.adminWoTableReservationIndexWaiter');
    Route::get('adminWoTableReservationListWaiter', 'adminMngWorkersController@adminWoTableReservationListWaiter')->name('admWoMng.adminWoTableReservationListWaiter');

    Route::get('adminWoServiceRequestWaiter', 'adminMngWorkersController@adminWoServiceRequestWaiter')->name('admWoMng.adminWoServiceRequestWaiter');

    Route::get('adminWoStatusWorkerWaiter', 'adminMngWorkersController@adminWoStatusWorkerWaiter')->name('admWoMng.adminWoStatusWorkerWaiter');

    Route::get('adminWoCovid19Waiter', 'adminMngWorkersController@adminWoCovid19Waiter')->name('admWoMng.adminWoCovid19Waiter');

    //---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


    //open page Cook Desktop

    Route::get('cookPanelIndexCook', 'adminMngWorkersController@cookPanelIndexCook')->name('cookPnl.cookPanelIndexCook');
    Route::get('cookPanelIndexCookNotConf', 'adminMngWorkersController@cookPanelIndexCookNotConf')->name('cookPnl.cookPanelIndexCookNotConf');
    Route::get('cookPanelIndexCookT', 'adminMngWorkersController@cookPanelIndexCookT')->name('cookPnl.cookPanelIndexCookT');
    Route::get('cookPanelIndexCookTNotConf', 'adminMngWorkersController@cookPanelIndexCookTNotConf')->name('cookPnl.cookPanelIndexCookTNotConf');
    Route::get('cookPanelIndexCookD', 'adminMngWorkersController@cookPanelIndexCookD')->name('cookPnl.cookPanelIndexCookD');

    Route::post('cookPanelAddOneDoneOrderProd', 'adminMngWorkersController@cookPanelAddOneDoneOrderProd')->name('cookPnl.cookPanelAddOneDoneOrderProd');
    Route::post('cookPanelAddOneDoneOrderProdT', 'adminMngWorkersController@cookPanelAddOneDoneOrderProdT')->name('cookPnl.cookPanelAddOneDoneOrderProdT');
    Route::post('cookPanelAddOneDoneOrderProdD', 'adminMngWorkersController@cookPanelAddOneDoneOrderProdD')->name('cookPnl.cookPanelAddOneDoneOrderProdD');

    Route::post('cookPanelRemoveOneDoneOrderProd', 'adminMngWorkersController@cookPanelRemoveOneDoneOrderProd')->name('cookPnl.cookPanelRemoveOneDoneOrderProd');
    Route::post('cookPanelRemoveOneDoneOrderProdT', 'adminMngWorkersController@cookPanelRemoveOneDoneOrderProdT')->name('cookPnl.cookPanelRemoveOneDoneOrderProdT');
    Route::post('cookPanelRemoveOneDoneOrderProdD', 'adminMngWorkersController@cookPanelRemoveOneDoneOrderProdD')->name('cookPnl.cookPanelRemoveOneDoneOrderProdD');

    Route::post('cookPanelOrderProdFinished', 'adminMngWorkersController@cookPanelOrderProdFinished')->name('cookPnl.cookPanelOrderProdFinished');
    Route::post('cookPanelOrderProdFinishedAllTable', 'adminMngWorkersController@cookPanelOrderProdFinishedAllTable')->name('cookPnl.cookPanelOrderProdFinishedAllTable');
    Route::post('cookPanelOrderProdFinishedT', 'adminMngWorkersController@cookPanelOrderProdFinishedT')->name('cookPnl.cookPanelOrderProdFinishedT');
    Route::post('cookPanelOrderProdFinishedD', 'adminMngWorkersController@cookPanelOrderProdFinishedD')->name('cookPnl.cookPanelOrderProdFinishedD');


    Route::post('chnGNrBlocksShownCPV232INCH', 'adminMngWorkersController@chnGNrBlocksShownCPV232INCH')->name('cookPnl.chnGNrBlocksShownCPV232INCH');

    Route::post('changePlateColorCook', 'adminMngWorkersController@changePlateColorCook')->name('cookPnl.changePlateColorCook');
    
    //---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    
    //open page 
    Route::get('admWoMngIndex', 'adminMngWorkersController@indexAdmMngPage')->name('admWoMng.indexAdmMngPage');

    // register a worker
    Route::post('admWoMngSaveNewWorker', 'adminMngWorkersController@saveNewWorker')->name('admWoMng.saveNewWorker');

    // set tables WAITER
    Route::post('admWoMngRegisterTableForWaiter', 'adminMngWorkersController@registerTableForWaiter')->name('admWoMng.registerTableForWaiter');
    Route::post('admWoMngRemoveTableForWaiter', 'adminMngWorkersController@removeTableForWaiter')->name('admWoMng.removeTableForWaiter');
    
    // Access controll WAITER
    Route::post('admWoMngRegisterAccessForWaiter', 'adminMngWorkersController@registerAccessForWaiter')->name('admWoMng.registerAccessForWaiter');
    Route::post('admWoMngRemoveAccessForWaiter', 'adminMngWorkersController@removeAccessForWaiter')->name('admWoMng.removeAccessForWaiter');

    // statistics WAITER
    Route::get('admWoMngWaiterStatistics', 'adminMngWorkersController@indexAdmMngOpenWaiterS')->name('admWoMng.indexAdmMngOpenWaiterS');
    Route::get('admWoMngWaiterStatisticsT', 'adminMngWorkersController@indexAdmMngOpenWaiterST')->name('admWoMng.indexAdmMngOpenWaiterST');
    Route::get('admWoMngWaiterStatisticsD', 'adminMngWorkersController@indexAdmMngOpenWaiterSD')->name('admWoMng.indexAdmMngOpenWaiterSD');

    // Extras register COOK
    Route::post('admWoMngRegisterExtraForCook', 'adminMngWorkersController@registerExtraForCook')->name('admWoMng.registerExtraForCook');
    Route::post('admWoMngRemoveExtraForCook', 'adminMngWorkersController@removeExtraForCook')->name('admWoMng.removeExtraForCook');
    
    // Types register COOK
    Route::post('admWoMngRegisterTypeForCook', 'adminMngWorkersController@registerTypeForCook')->name('admWoMng.registerTypeForCook');
    Route::post('admWoMngRemoveTypeForCook', 'adminMngWorkersController@removeTypeForCook')->name('admWoMng.removeTypeForCook');

    // Product register COOK
    Route::post('admWoMngRegisterProductForCook', 'adminMngWorkersController@registerProductForCook')->name('admWoMng.registerProductForCook');
    Route::post('admWoMngRemoveProductForCook', 'adminMngWorkersController@removeProductForCook')->name('admWoMng.removeProductForCook');

    // Category register COOK
    Route::post('admWoMngRegisterCategoryForCook', 'adminMngWorkersController@registerCategoryForCook')->name('admWoMng.registerCategoryForCook');
    Route::post('admWoMngRemoveCategoryForCook', 'adminMngWorkersController@removeCategoryForCook')->name('admWoMng.removeCategoryForCook');
    
    // Takeway / Delivery access COOK
    Route::post('admWoMngTakeawayACSCook', 'adminMngWorkersController@takeawayAccessForCook')->name('admWoMng.takeawayAccessForCook');
    Route::post('admWoMngDeliveryACSCook', 'adminMngWorkersController@deliveryAccessForCook')->name('admWoMng.deliveryAccessForCook');

    // delete worker
    Route::post('admWoMngDeleteWorker', 'adminMngWorkersController@deleteWorker')->name('admWoMng.deleteWorker');

    // change cooks panel version
    Route::post('admWoMngChnfCooksPVer', 'adminMngWorkersController@chngCooksPVer')->name('admWoMng.chngCooksPVer');

    // set plate to category
        Route::post('newPlateForCategory', 'adminMngWorkersController@setPlateForCat')->name('admWoMng.setPlateForCat');
        Route::post('newPlateForRes', 'adminMngWorkersController@saveNewPlate')->name('admWoMng.saveNewPlate');
        Route::post('deletePlateForRes', 'adminMngWorkersController@deleteThisPlate')->name('admWoMng.deleteThisPlate');
        Route::post('saveChangesForPlate', 'adminMngWorkersController@saveChangesFPlate')->name('admWoMng.saveChangesFPlate');
    //----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    
    // KONTABILISTI panel
        Route::get('AccountantStatistics', 'adminMngWorkersController@AccountPanelStatistika')->name('admWoMng.AccountantStatistics');
        Route::get('AccountantStatisticsDash1', 'adminMngWorkersController@AccountPanelStatistikaDash1')->name('admWoMng.AccountantStatisticsDash1');
        Route::get('AccountantStatisticsDash2', 'adminMngWorkersController@AccountPanelStatistikaDash2')->name('admWoMng.AccountantStatisticsDash2');
        Route::get('AccountantStatisticsSales', 'adminMngWorkersController@AccountPanelStatistikaSales')->name('admWoMng.AccountPanelStatistikaSales');
        Route::get('AccountantStatisticsRepCat', 'adminMngWorkersController@AccountPanelStatistikaRepCat')->name('admWoMng.AccountPanelStatistikaRepCat');
        Route::get('AccountantCanceledOrders', 'adminMngWorkersController@AccountPanelCanceledOrders')->name('admWoMng.AccountPanelCanceledOrders');

        Route::get('AccountantstatsBillsRecs', 'adminMngWorkersController@AccountPanelStatBillsPage')->name('admWoMng.statBillsPage');
        Route::post('AccountantstatsBillsRecsSave', 'adminMngWorkersController@AccountPanelStatBillsSave')->name('admWoMng.statBillsSave');
        Route::post('AccountantstatsBillsRecsGetDocs', 'adminMngWorkersController@AccountPanelStatBillsGetDocs')->name('admWoMng.statBillsGetDocs');

        Route::get('AccountantstatsDeletedIns', 'adminMngWorkersController@AccountPanelDeletedIns')->name('admWoMng.deletetdIns');
        Route::get('AccountantstatsWaitersSales', 'adminMngWorkersController@AccountPanelWaitersSales')->name('admWoMng.WaitersSales');
        Route::get('AccountantstatsEmailBillsP', 'adminMngWorkersController@AccountPanelEmailBillsP')->name('admWoMng.EmailBillsP');
        Route::get('AccountantstatsReportCatsPage', 'adminMngWorkersController@AccountPanelReportCatsPage')->name('admWoMng.ReportCatsPage');

        Route::get('AccountantProducts', 'adminMngWorkersController@AccountPanelProduktet')->name('admWoMng.AccountantProducts');
        Route::get('AccountantProducts/Order', 'adminMngWorkersController@AccountPanelProduktetOrder')->name('admWoMng.AccountPanelProduktetOrder');

        Route::post('setAcceToStatPgFA', 'adminMngWorkersController@setAcceToStatPgFA')->name('admWoMng.setAcceToStatPgFA');
        Route::post('setAcceToProdPgFA', 'adminMngWorkersController@setAcceToProdPgFA')->name('admWoMng.setAcceToProdPgFA');
    //----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
    
    // waiters todays sales
        Route::get('waitersSalesToday', 'waiterDaySalesController@waiterSalesTodayWP')->name('waSalesToday.waSalesTodayPage');

        Route::post('waitersSalesTodayRegister', 'waiterDaySalesController@waiterSalesTodayRegister')->name('waSalesToday.waSalesTodayRegister');
        Route::post('waitersSalesTodayRegister2', 'waiterDaySalesController@waiterSalesTodayRegister2')->name('waSalesToday.waSalesTodayRegister2');
        Route::post('waitersSalesTodayGetData', 'waiterDaySalesController@waiterSalesTodayGetData')->name('waSalesToday.waSalesTodayGetData');

        Route::post('waitersSalesPrintPDFRep', 'waiterDaySalesController@waitersSalesPrintPDFRep')->name('waSalesToday.waitersSalesPrintPDFRep');
    //----------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    Route::post('setNewPassToAWorker', 'adminMngWorkersController@setNewPassToAWorker')->name('admWoMng.setNewPassToAWorker');




    // Bill Tablet 
        Route::get('BillTabletsWaiter', 'adminMngWorkersController@billTabletIndex')->name('billTabletWaiter.index');
    //----------------------------------------------------------------------------------------------------------------------------------------------------------------------------

    // Order serving page
        Route::get('orderServingDevicesPageWaiter', 'adminMngWorkersController@orderServingDevicesPageWaiter')->name('orServing.orderServingDevicesPageWaiter');
    //----------------------------------------------------------------------------------------------------------------------------------------------------------------------------
?>