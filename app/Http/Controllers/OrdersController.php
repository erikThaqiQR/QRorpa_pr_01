<?php

namespace App\Http\Controllers;

use App\User;
use App\Orders;
use App\WorkActAP;
use App\notifyClient;
use App\taDeForCookOr;
use App\Events\updateAP;
use SpryngApiHttpPhp\Client;
use App\waiterActivityLog;
use Illuminate\Http\Request;
use App\cooksProductSelection;
use App\giftCard;
use App\OrdersPassive;
use App\Restorant;
use App\tablesAccessToWaiters;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    public function Saindex()
    {
        return view('sa/superAdminIndex');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function show(Orders $orders)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function edit(Orders $orders)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Orders $orders)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function destroy(Orders $orders)
    {
        //
    }
















    public function ChangeStatus(Request $request){

        $order = Orders::find($request->orderId);
        
        $wAct =new  WorkActAP;
        $wAct->worker = $request->chBy;
        $wAct->toRes = $order->Restaurant;
        $wAct->orderId = $order->id ;
        $wAct->statBefore = $order->statusi ;
        $wAct->statAfter = $request->orderStat;
        $wAct->save();

        $order->statusi = $request->orderStat;
        $order->StatusBy = Auth::user()->id;
        $order->servedBy = Auth::user()->id;
        if($request->orderStat == 3 && Auth::User()->role == 55){
            $order->orForWaiter = Auth::User()->id;
        }
        $order->save();

        event(new updateAP($order->Restaurant));

        if($order->nrTable == 500 ){
            $clNoti = new notifyClient();

            $clNoti->for = 'taOrdStatusChange';
            $clNoti->toRes = $order->Restaurant;
            $clNoti->tableNr = 500;
            $clNoti->clPhoneNr = $order->shifra;
            $clNoti->data = json_encode([
                'trackMO' => $order->shifra,
                'newStat' =>$order->statusi
            ]);
            $clNoti->readInd = 0;
            $clNoti->save();
        }


        // Send Notifications for the Admin
        foreach(\App\User::where([['sFor',$order->Restaurant],['role','5']])->get() as $user){
            if($user->id != auth()->user()->id){
                if($order->nrTable != 500 && $order->nrTable != 9000){
                    $details = [
                        'id' => $order->id,
                        'type' => 'AdminUpdateOrdersP',
                        'tableNr' => $order->nrTable
                    ];
                }else if($order->nrTable == 500){
                    $details = [
                        'id' => $order->id,
                        'type' => 'AdminUpdateOrdersPT',
                        'tableNr' => $order->nrTable
                    ];
                }else if($order->nrTable == 9000){
                    $details = [
                        'id' => $order->id,
                        'type' => 'AdminUpdateOrdersPD',
                        'tableNr' => $order->nrTable
                    ];
                }
                $user->notify(new \App\Notifications\NewOrderNotification($details));
            }
        }
        foreach(User::where([['sFor',$order->Restaurant],['role','55']])->get() as $oneWaiter){
            if($oneWaiter->id != auth()->user()->id){
                $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $order->nrTable]])->first();
                if(($aToTable != NULL && $aToTable->statusAct == 1) || $order->nrTable == 500){
                    // register the notification ...
                    if($order->nrTable != 500 && $order->nrTable != 9000){
                        $details = [
                            'id' => $order->id,
                            'type' => 'AdminUpdateOrdersP',
                            'tableNr' => $order->nrTable
                        ];
                    }else if($order->nrTable == 500){
                        $details = [
                            'id' => $order->id,
                            'type' => 'AdminUpdateOrdersPT',
                            'tableNr' => $order->nrTable
                        ];
                    }else if($order->nrTable == 9000){
                        $details = [
                            'id' => $order->id,
                            'type' => 'AdminUpdateOrdersPD',
                            'tableNr' => $order->nrTable
                        ];
                    }
                    $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                }
            }
        }

        if($request->orderStat == 3){
            // Register a new notifyClient
            $newNotifyClient = new notifyClient();
            $newNotifyClient->for = "takeawayOrderFinished";
            $newNotifyClient->toRes = $order->Restaurant;
            $newNotifyClient->tableNr = $order->nrTable;
            $newNotifyClient->data = json_encode([
                'tableNr' => $order->nrTable,
                'shifra' => $order->shifra,
                'newStat' => $request->orderStat,
            ]);
            $newNotifyClient->readInd = 0;
            $newNotifyClient->save();
        }

        // menaxhimi i produktit per kuzhinier per Takeaway
        if($order->nrTable == 500){
            if($request->orderStat == 2 || $request->orderStat == 3){
                foreach(taDeForCookOr::where('orderId',$order->id)->get() as $oOrForC){
                    $oOrForC->delete();
                }
                foreach(User::where([['sFor',$order->Restaurant],['role','54']])->get() as $oneCook){
                    if (cooksProductSelection::where([['workerId',$oneCook->id],['contentType','Takeaway']])->first() != NULL){
                        $details = [
                            'id' => $order->id,
                            'type' => 'cookPanelUpdateTaToPay',
                            'prodId' => '0'
                        ];
                        $oneCook->notify(new \App\Notifications\cookPanelUpdate($details));
                    }
                }
            }
            if(Auth::User()->role == 55){
                $wLog = new waiterActivityLog();
                $wLog->waiterId = Auth::User()->id;
                $wLog->actType = 'takeawayOrStatChng'.$request->orderStat;
                $wLog->actId =  $order->id;

                $ctOr = (int)0;
                foreach(explode('---8---',$order->porosia) as $op){
                    $ctOr = $ctOr + (int)explode('-8-',$op)[3];
                }
                $wLog->sasia = $ctOr;

                $wLog->save();
            }
        }

        // menaxhimi i produktit per kuzhinier per Delivery
        if($order->nrTable == 9000 ){
            if($request->orderStat == 2 || $request->orderStat == 3){
                foreach(taDeForCookOr::where('orderId',$order->id)->get() as $oOrForC){
                    $oOrForC->delete();
                }
                foreach(User::where([['sFor',$order->Restaurant],['role','54']])->get() as $oneCook){
                    if (cooksProductSelection::where([['workerId',$oneCook->id],['contentType','Delivery']])->first() != NULL){
                        $details = [
                            'id' => $order->id,
                            'type' => 'cookPanelUpdateDe',
                            'prodId' => '0'
                        ];
                        $oneCook->notify(new \App\Notifications\cookPanelUpdate($details));
                    }
                }
            }
            if(Auth::User()->role == 55){
                $wLog = new waiterActivityLog();
                $wLog->waiterId = Auth::User()->id;
                $wLog->actType = 'deliveryOrStatChng'.$request->orderStat;
                $wLog->actId =  $order->id;
                
                $ctOr = (int)0;
                foreach(explode('---8---',$order->porosia) as $op){
                    $ctOr = $ctOr + (int)explode('-8-',$op)[3];
                }
                $wLog->sasia = $ctOr;
                
                $wLog->save();
            }
        }

        if(isset($request->backToMap)){
            if($request->backToMap == 2){
                return redirect()->route ('dash.index2');
            }else if($request->backToMap == 3){
                return redirect()->route ('dash.index3');
            }else if($request->backToMap == 4){
                return redirect()->route ('dash.takeaway');
            }else if($request->backToMap == 5){
                return redirect()->route ('dash.delivery');
            }else if($request->backToMap == 11){
                return redirect()->route ('dash.index');
            }else if($request->backToMap == 551){
                return redirect()->route ('admWoMng.ordersTakeawayWaiter');
            }else if($request->backToMap == 552){
                return redirect()->route ('admWoMng.ordersDeliveryWaiter');
            }
        }else{
            return redirect()->route ('dash.index',['tabs']);
        }
        
    }














    public function ChangeStatusAjax(Request $request){
        $order = Orders::find($request->orderId);
        
        $wAct =new  WorkActAP;
        $wAct->worker = $request->chBy;
        $wAct->toRes = $order->Restaurant;
        $wAct->orderId = $order->id ;
        $wAct->statBefore = $order->statusi ;
        $wAct->statAfter = $request->orderStat;

        $order->statusi = $request->orderStat;
        $order->StatusBy = Auth::user()->id;
        $order->servedBy = Auth::user()->id;

        $orderPassive = OrdersPassive::find($request->orderId);
        if($orderPassive != Null){
            $orderPassive->statusi = $request->orderStat;
            $orderPassive->StatusBy = Auth::user()->id;
            $orderPassive->servedBy = Auth::user()->id;
        }


        if($request->orderStat == 3 && Auth::User()->role == 55){
            $order->orForWaiter = Auth::User()->id;
        }

        $wAct->save();
        $order->save();

        event(new updateAP($order->Restaurant));

        // Send Notifications for the Admin
        foreach(\App\User::where([['sFor',$order->Restaurant],['role','5']])->get() as $user){
            if($user->id != auth()->user()->id){
                if($order->nrTable != 500 && $order->nrTable != 9000){
                    $details = [
                        'id' => $order->id,
                        'type' => 'AdminUpdateOrdersP',
                        'tableNr' => $order->nrTable
                    ];
                }else if($order->nrTable == 500){
                    $details = [
                        'id' => $order->id,
                        'type' => 'AdminUpdateOrdersPT',
                        'tableNr' => $order->nrTable
                    ];
                }else if($order->nrTable == 9000){
                    $details = [
                        'id' => $order->id,
                        'type' => 'AdminUpdateOrdersPD',
                        'tableNr' => $order->nrTable
                    ];
                }
                $user->notify(new \App\Notifications\NewOrderNotification($details));
            }
        }
        foreach(User::where([['sFor',$order->Restaurant],['role','55']])->get() as $oneWaiter){
            if($oneWaiter->id != auth()->user()->id){
                $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $order->nrTable]])->first();
                if(($aToTable != NULL && $aToTable->statusAct == 1) || $order->nrTable == 500){
                    // register the notification ...
                    if($order->nrTable != 500 && $order->nrTable != 9000){
                        $details = [
                            'id' => $order->id,
                            'type' => 'AdminUpdateOrdersP',
                            'tableNr' => $order->nrTable
                        ];
                    }else if($order->nrTable == 500){
                        $details = [
                            'id' => $order->id,
                            'type' => 'AdminUpdateOrdersPT',
                            'tableNr' => $order->nrTable
                        ];
                    }else if($order->nrTable == 9000){
                        $details = [
                            'id' => $order->id,
                            'type' => 'AdminUpdateOrdersPD',
                            'tableNr' => $order->nrTable
                        ];
                    }
                    $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                }
            }
        }


        if($request->orderStat == 3){
            // Register a new notifyClient
            $newNotifyClient = new notifyClient();
            $newNotifyClient->for = "takeawayOrderFinished";
            $newNotifyClient->toRes = $order->Restaurant;
            $newNotifyClient->tableNr = $order->nrTable;
            $newNotifyClient->data = json_encode([
                'tableNr' => $order->nrTable,
                'shifra' => $order->shifra,
                'newStat' => $request->orderStat,
            ]);
            $newNotifyClient->readInd = 0;
            $newNotifyClient->save();
        }
    }






    public function ChangeStatusAjaxCancelOr(Request $request){
        $order = Orders::find($request->orderId);
        
        $wAct =new  WorkActAP;
        $wAct->worker = $request->chBy;
        $wAct->toRes = $order->Restaurant;
        $wAct->orderId = $order->id ;
        $wAct->statBefore = $order->statusi ;
        $wAct->statAfter = $request->orderStat;

        $order->statusi = $request->orderStat;
        $order->StatusBy = Auth::user()->id;
        $order->servedBy = Auth::user()->id;
        $order->cancelComm = strval($request->theComm);

        $orderPassive = OrdersPassive::find($request->orderId);
        if($orderPassive != Null){
            $orderPassive->statusi = $request->orderStat;
            $orderPassive->StatusBy = Auth::user()->id;
            $orderPassive->servedBy = Auth::user()->id;
            $orderPassive->cancelComm = strval($request->theComm);
        }

        if($request->orderStat == 3 && Auth::User()->role == 55){
            $order->orForWaiter = Auth::User()->id;
        }

        $wAct->save();
        $order->save();

        event(new updateAP($order->Restaurant));

        // Send Notifications for the Admin
        foreach(\App\User::where([['sFor',$order->Restaurant],['role','5']])->get() as $user){
            if($user->id != auth()->user()->id){
                if($order->nrTable != 500 && $order->nrTable != 9000){
                    $details = [
                        'id' => $order->id,
                        'type' => 'AdminUpdateOrdersP',
                        'tableNr' => $order->nrTable
                    ];
                }else if($order->nrTable == 500){
                    $details = [
                        'id' => $order->id,
                        'type' => 'AdminUpdateOrdersPT',
                        'tableNr' => $order->nrTable
                    ];
                }else if($order->nrTable == 9000){
                    $details = [
                        'id' => $order->id,
                        'type' => 'AdminUpdateOrdersPD',
                        'tableNr' => $order->nrTable
                    ];
                }
                $user->notify(new \App\Notifications\NewOrderNotification($details));
            }
        }
        foreach(User::where([['sFor',$order->Restaurant],['role','55']])->get() as $oneWaiter){
            $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $order->nrTable]])->first();
            if(($aToTable != NULL && $aToTable->statusAct == 1) || $order->nrTable == 500){
                // register the notification ...
                if($order->nrTable != 500 && $order->nrTable != 9000){
                    $details = [
                        'id' => $order->id,
                        'type' => 'AdminUpdateOrdersP',
                        'tableNr' => $order->nrTable
                    ];
                }else if($order->nrTable == 500){
                    $details = [
                        'id' => $order->id,
                        'type' => 'AdminUpdateOrdersPT',
                        'tableNr' => $order->nrTable
                    ];
                }else if($order->nrTable == 9000){
                    $details = [
                        'id' => $order->id,
                        'type' => 'AdminUpdateOrdersPD',
                        'tableNr' => $order->nrTable
                    ];
                }
                $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
            }
        }


        if($request->orderStat == 3){
            // Register a new notifyClient
            $newNotifyClient = new notifyClient();
            $newNotifyClient->for = "takeawayOrderFinished";
            $newNotifyClient->toRes = $order->Restaurant;
            $newNotifyClient->tableNr = $order->nrTable;
            $newNotifyClient->data = json_encode([
                'tableNr' => $order->nrTable,
                'shifra' => $order->shifra,
                'newStat' => $request->orderStat,
            ]);
            $newNotifyClient->readInd = 0;
            $newNotifyClient->save();
        }
    }











 	public function ChangeStatus02(Request $request){

    	$order = Orders::find($request->orderId);
    	$order->statusi = $request->orderStat;
        $order->save();
        event(new updateAP($order->toRes));

	    return redirect()->route('dash.dash02');
    }








    public function cancelAOrder(Request $req){
        // theComm  oId
        $order = Orders::find($req->oId);
        $order->cancelComm = strval($req->theComm);
        $order->statusi = 2;
        $order->StatusBy = Auth::user()->id;

        foreach(giftCard::where([['toRes',$order->Restaurant],['usedInOrdersId','!=','empty']])->get() as $chkGC){
            $newUsedOrIdArray = '';
            foreach(explode('|||',$chkGC->usedInOrdersId) as $inOrderUsedId){
                


                if($order->id == $inOrderUsedId){
                    $chkGC->gcSumInChfUsed = number_format($chkGC->gcSumInChfUsed - $order->dicsountGcAmnt , 2, '.', '');
                    
                    $order->dicsountGcAmnt = number_format(0, 2, '.', '');

                }else{
                    if($newUsedOrIdArray == ''){ $newUsedOrIdArray = $inOrderUsedId;
                    }else{ $newUsedOrIdArray .= '|||'.$inOrderUsedId; }
                }

                $chkGC->usedInOrdersId = $newUsedOrIdArray;
                $chkGC->save(); 
            }
        }

        $order->save();

        event(new updateAP($order->Restaurant));

        // Send Notifications for the Admin
        foreach(\App\User::where([['sFor',$order->Restaurant],['role','5']])->get() as $user){
            if($user->id != auth()->user()->id){
                if($order->nrTable != 500 && $order->nrTable != 9000){
                    $details = [
                        'id' => $order->id,
                        'type' => 'AdminUpdateOrdersP',
                        'tableNr' => $order->nrTable
                    ];
                }else if($order->nrTable == 500){
                    $details = [
                        'id' => $order->id,
                        'type' => 'AdminUpdateOrdersPT',
                        'tableNr' => $order->nrTable
                    ];
                }else if($order->nrTable == 9000){
                    $details = [
                        'id' => $order->id,
                        'type' => 'AdminUpdateOrdersPD',
                        'tableNr' => $order->nrTable
                    ];
                }
                $user->notify(new \App\Notifications\NewOrderNotification($details));
            }
        }
        foreach(User::where([['sFor',$order->Restaurant],['role','55']])->get() as $oneWaiter){
            $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $order->nrTable]])->first();
            if($oneWaiter->id != Auth::user()->id && (($aToTable != NULL && $aToTable->statusAct == 1) || $order->nrTable == 500)){
                // register the notification ...
                if($order->nrTable != 500 && $order->nrTable != 9000){
                    $details = [
                        'id' => $order->id,
                        'type' => 'AdminUpdateOrdersP',
                        'tableNr' => $order->nrTable
                    ];
                }else if($order->nrTable == 500){
                    $details = [
                        'id' => $order->id,
                        'type' => 'AdminUpdateOrdersPT',
                        'tableNr' => $order->nrTable
                    ];
                }else if($order->nrTable == 9000){
                    $details = [
                        'id' => $order->id,
                        'type' => 'AdminUpdateOrdersPD',
                        'tableNr' => $order->nrTable
                    ];
                }
                $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
            }
        }
        foreach(User::where([['sFor',$order->Restaurant],['role','54']])->get() as $oneCook){
            // register the notification ...
            if($order->nrTable != 500 && $order->nrTable != 9000){
                $details = [
                    'id' => $order->id,
                    'type' => 'cookPanelUpdate',
                    'tableNr' => $order->nrTable,
                ];
            }else if($order->nrTable == 500){
                $details = [
                    'id' => $order->id,
                    'type' => 'cookPanelUpdateTaToPay',
                    'tableNr' => $order->nrTable,
                ];
            }else if($order->nrTable == 9000){
                $details = [
                    'id' => $order->id,
                    'type' => 'cookPanelUpdateDe',
                    'tableNr' => $order->nrTable,
                ];
            }
            $oneCook->notify(new \App\Notifications\NewOrderNotification($details));
        }

        if($order->nrTable == 500){
            $clNoti = new notifyClient();

            $clNoti->for = 'taOrdStatusChange';
            $clNoti->toRes = $order->Restaurant;
            $clNoti->tableNr = 500;
            $clNoti->clPhoneNr = $order->shifra;
            $clNoti->data = json_encode([
                'trackMO' => $order->shifra,
                'newStat' =>$order->statusi
            ]);
            $clNoti->readInd = 0;
            $clNoti->save();
        }
    }



    public function taOrderChangeStatus(Request $req){
        $order = Orders::find((int)$req->oId);
        if($order != NULL){
            $order->statusi = (int)$req->newStat;
            $order->orForWaiter = (int)Auth::user()->id;
            $order->servedBy = (int)Auth::user()->id;
            $order->StatusBy = (int)Auth::user()->id;
            $order->save();

            event(new updateAP($order->Restaurant));

            // Send Notifications for the Admin
            foreach(\App\User::where([['sFor',$order->Restaurant],['role','5']])->get() as $user){
                if($user->id != auth()->user()->id){
                    if($order->nrTable != 500 && $order->nrTable != 9000){
                        $details = [
                            'id' => $order->id,
                            'type' => 'AdminUpdateOrdersP',
                            'tableNr' => $order->nrTable
                        ];
                    }else if($order->nrTable == 500){
                        $details = [
                            'id' => $order->id,
                            'type' => 'AdminUpdateOrdersPT',
                            'tableNr' => $order->nrTable
                        ];
                    }else if($order->nrTable == 9000){
                        $details = [
                            'id' => $order->id,
                            'type' => 'AdminUpdateOrdersPD',
                            'tableNr' => $order->nrTable
                        ];
                    }
                    $user->notify(new \App\Notifications\NewOrderNotification($details));
                }
            }
            if(Auth::user()->role != 55){
                foreach(User::where([['sFor',$order->Restaurant],['role','55']])->get() as $oneWaiter){
                    $aToTable = tablesAccessToWaiters::where([['waiterId',$oneWaiter->id],['tableNr', $order->nrTable]])->first();
                    if($oneWaiter->id != Auth::user()->id && (($aToTable != NULL && $aToTable->statusAct == 1) || $order->nrTable == 500)){
                        // register the notification ...
                        if($order->nrTable != 500 && $order->nrTable != 9000){
                            $details = [
                                'id' => $order->id,
                                'type' => 'AdminUpdateOrdersP',
                                'tableNr' => $order->nrTable
                            ];
                        }else if($order->nrTable == 500){
                            $details = [
                                'id' => $order->id,
                                'type' => 'AdminUpdateOrdersPT',
                                'tableNr' => $order->nrTable
                            ];
                        }else if($order->nrTable == 9000){
                            $details = [
                                'id' => $order->id,
                                'type' => 'AdminUpdateOrdersPD',
                                'tableNr' => $order->nrTable
                            ];
                        }
                        $oneWaiter->notify(new \App\Notifications\NewOrderNotification($details));
                    }
                }
            }
            if($order->statusi == 3){
                foreach(User::where([['sFor',$order->Restaurant],['role','54']])->get() as $oneCook){
                    // register the notification ...
                    if($order->nrTable != 500 && $order->nrTable != 9000){
                        $details = [
                            'id' => $order->id,
                            'type' => 'cookPanelUpdate',
                            'tableNr' => $order->nrTable,
                        ];
                    }else if($order->nrTable == 500){
                        $details = [
                            'id' => $order->id,
                            'type' => 'cookPanelUpdateTaToPay',
                            'tableNr' => $order->nrTable,
                        ];
                    }else if($order->nrTable == 9000){
                        $details = [
                            'id' => $order->id,
                            'type' => 'cookPanelUpdateDe',
                            'tableNr' => $order->nrTable,
                        ];
                    }
                    $oneCook->notify(new \App\Notifications\NewOrderNotification($details));
                }
            }
    
            // Send the SMS notification to the client if the order is ready 
            if($order->statusi == 1 && $order->userPhoneNr != '0770000000'){
                if(substr($order->userPhoneNr, 0, 1) == 0 && strlen($order->userPhoneNr) == 10){
                    $sendTo = '41'.substr($order->userPhoneNr, 1, 9);
                }else if(strlen($order->userPhoneNr) == 9){
                    $sendTo = '41'.$order->userPhoneNr;
                }
                $theRr = Restorant::find($order->Restaurant);
                if($theRr != Null){$theRrName = $theRr->emri;
                }else{ $theRrName = 'Undefined'; }

                $spryng = new Client('QRorpasms', 'Spryng@qrorpa.ch', 'qrorpa.ch');
                if($spryng->sms->checkBalance() > 0){
                    try {
                        $spryng->sms->send($sendTo,'Ihre Takeaway-Bestellung bei '.$theRrName.' ist fertig. Bitte verwenden Sie den Code "'.explode('|',$order->shifra)[1].'", um Ihre Bestellung abzuholen.', array(
                            'route'     => 'business',
                            'allowlong' => true,
                            )
                        );
                    }catch (InvalidRequestException $e){
                        dd ($e->getMessage());
                    }
                }
            }
    
            if($order->statusi == 3){
                // Register a new notifyClient
                $newNotifyClient = new notifyClient();
                $newNotifyClient->for = "takeawayOrderFinished";
                $newNotifyClient->toRes = $order->Restaurant;
                $newNotifyClient->tableNr = $order->nrTable;
                $newNotifyClient->data = json_encode([
                    'tableNr' => $order->nrTable,
                    'shifra' => $order->shifra,
                    'newStat' => $order->statusi,
                ]);
                $newNotifyClient->readInd = 0;
                $newNotifyClient->save();
            }
            if($order->nrTable == 500){
                $clNoti = new notifyClient();
    
                $clNoti->for = 'taOrdStatusChange';
                $clNoti->toRes = $order->Restaurant;
                $clNoti->tableNr = 500;
                $clNoti->clPhoneNr = $order->shifra;
                $clNoti->data = json_encode([
                    'trackMO' => $order->shifra,
                    'newStat' =>$order->statusi
                ]);
                $clNoti->readInd = 0;
                $clNoti->save();
            }
        }
    }
}
