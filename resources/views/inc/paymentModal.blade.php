<?php
        $orderSend = "";
    ?>


<div class="container demo">
    <div class="text-center">
        <button type="button" class="btn btn-demo" data-toggle="modal" data-target="#exampleModal">
            {{__('inc.leftSidebarModel')}}
        </button>
    </div>
    <div class="modal left fade" id="exampleModal" tabindex="" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12 p-2 mb-3 bg-white rounded shadow-sm ">

                            <!-- Shopping cart table -->
                                <div class="table-responsive">


                                        <div class="col-sm-12 col-md-12 col-lg-12">
                                            <h3 class="color-qrorpa"> {{Cart::count()}} {{__('inc.itemsOnTheOrder')}}</h3>
                                        </div>


                                        <table class="table table-borderless">
                                            <thead>
                                                <tr>
                                                <th scope="col" class="border-0 bg-light">
                                                    <div class="p-2 px-3 text-uppercase">{{__('inc.product')}}</div>
                                                </th>
                                                <th scope="col" class="border-0 bg-light">
                                                    <div class="p-2 px-3 text-uppercase">{{__('inc.extras')}}</div>
                                                </th>
                                                <th scope="col" class="border-0 bg-light">
                                                    <div class="py-2 text-uppercase">{{__('inc.priceTotal')}}</div>
                                                </th>
                                                <th scope="col" class="border-0 bg-light">
                                                    <div class="py-2 text-uppercase">{{__('inc.quantity')}}</div>
                                                </th>
                                                <th scope="col" class="border-0 bg-light">
                                                    <div class="py-2 text-uppercase"></div>
                                                </th>
                                                </tr>
                                            </thead>    

                                            <tbody>

                                                <?php
                                                    $porosiaSend = "";
                                                    $step = 1;
                                                ?>
                                                @foreach(Cart::content() as $item)
                                                <?php
                                                    if($step++ == 1){
                                                        $porosiaSend .= $item->name."-8-".$item->options->persh."-8-".$item->options->ekstras.'-8-'.$item->qty.'-8-'.$item->price.'-8-'.explode('||',$item->options->type)[0];
                                                    }else{
                                                        $porosiaSend .= '---8---'.$item->name."-8-".$item->options->persh."-8-".$item->options->ekstras.'-8-'.$item->qty.'-8-'.$item->price.'-8-'.explode('||',$item->options->type)[0];
                                                    }
                                                
                                                ?>
                                                    
                                                    <tr>
                                                        <th scope="row" class="border-0">
                                                            <div class="p-2">
                                                            
                                                        
                                                            <div class="ml-3 d-inline-block align-middle">
                                                                <h5 class="mb-0"> <a href="#" class="text-dark d-inline-block align-middle">{{ $item->name }}</a></h5>
                                                                <?php

                                                                    $pershkrimi = substr($item->options->persh, 0, 18);
                                                                    if(strlen($item->options->persh) >= 19){
                                                                        echo '<span class="text-muted font-weight-normal font-italic d-block">'.$pershkrimi.'...</span>';
                                                                    }else{
                                                                        echo '<span class="text-muted font-weight-normal font-italic d-block">'.$pershkrimi.'</span>';
                                                                    }
                                                                    


                                                                ?>
                                                                {{explode('||',$item->options->type)[0] }}
                                                                
                                                                
                                                            </div>
                                                            </div>
                                                        </th>


                                                        <td class="border-0 align-middle">
                                                                <?php
                                                                    if($item->options->ekstras ==""){
                                                                        echo __("inc.noExtraIndgredients");
                                                                    }else{
                                                                        $extProD1 =explode('--0--',$item->options->ekstras);
                                                                        foreach($extProD1 as $extProOne){
                                                                            if(!empty($extProOne)){
                                                                                $extProD2 =explode('||',$extProOne);
                                                                                if(!empty($extProD2)){
                                                                                ?>
                                                                                {{Form::open(['action' => 'ProduktController@removeExtFromCart', 'method' => 'post', 'class' => 'mt-2']) }}

                                                                                    {{ Form::submit('X', ['class' => ' btn btn-outline-danger btn-sm']) }}
                                                                                    {{$extProD2[0].' {'.$extProD2[1].'}'}}

                                                                                    {{ Form::hidden('extPro',$extProOne, ['class' => 'form-control']) }}
                                                                                    {{ Form::hidden('elementId',$item->rowId, ['class' => 'form-control']) }}
                                                                                    {{ Form::hidden('allExtra', $item->options->ekstras, ['class' => 'form-control']) }}

                                                                                
                                                                                    
                                                                                {{Form::close() }}
                                                                                
                                                                                
                                                                                <?php
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                
                                                                ?>
                                                        </td>


                                                        <td class="border-0 align-middle"><strong>€ {{ sprintf('%01.2f', $item->price)  }} /
                                                            {{ sprintf('%01.2f', $item->price * $item->qty)}} </strong></td>
                                                        <td class="border-0 align-middle text-center">
                                                            {{$item->qty}}
                                                        </td>
                                                        <td class="border-0 align-middle">
                                                            <form action="{{ route('cart.destroy', $item->rowId) }}" method="POST">
                                                                {{ csrf_field() }}
                                                                {{ method_field('DELETE')}}

                                                                <button type="submit" class="btn btn-default">
                                                                    <img src="https://img.icons8.com/material-rounded/24/000000/filled-trash.png"/> 
                                                                </button>

                                                            </form> 
                                                        </td>
                                                        </tr>


                                                        <?php
                                                            if(empty($orderSend)){
                                                                $orderSend .= $item->name.'||'.$item->options->persh.'||'.$item->qty.'||'.$item->price.'||'.$item->options->type;
                                                            }else{
                                                                $orderSend .= '--+--'.$item->name.'||'.$item->options->persh.'||'.$item->qty.'||'.$item->price.'||'.$item->options->type;
                                                            }
                                                        
                                                        ?>

                                                
                                                        
                                
                                                @endforeach

                                                <tr>
                                                    <td colspan="5"> 
                                                        <a href="/" class="btn btn-outline-primary btn-block text-center"> 
                                                            <img src="https://img.icons8.com/nolan/64/add.png" width="20"/>
                                                            {{__('inc.addMoreOrders')}}
                                                        </a>
                                                    </td> 
                                                </tr>
                            
                                
                                    
                                            </tbody>
                                            
                                        </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('inc.close')}}</button>
                </div>
            </div>
        </div>
    </div>
</div>