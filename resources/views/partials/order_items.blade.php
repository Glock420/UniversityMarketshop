@if ($orders->count() > 0)
                @foreach ($orders as $order)
                <div class="orderItem">                   
                        <hr>
                        <br>
                        <div class="description">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="order-status" style="display: none;">{{ $order->status }}</div>
                                <div class="d-flex align-items-center">
                                    <h5 style="margin-bottom: 0; margin-right: 2%;">{{ $order->seller->org_name }}</h5>
                                    <a href="{{ $order->seller->chat_link }}" target="_blank" class="btn btn-dark">Chat</a>
                                </div>
                                <div>
                                    <p style="margin-bottom: 0;">Order ID: {{ $order->order_id }}  |  {{ $order->status }}</p>
                                </div>
                            </div>
                            <hr>
                            <a href="{{ url('/dashboard/orderdetails/'.$order->order_id) }}" style="text-decoration: none; color: black;">
                                <div class="order-items">
                                    @foreach ($order->orderItems as $orderItem)
                                    <div class="order-item-row d-flex justify-content-between align-items-center">
                                        <div class="order-item-details">
                                            @if ($orderItem->prod_image !== 'default_pics/default_prod_pic.jpg')
                                                <img src="{{ asset('storage/custom_prod_pics/'.$orderItem->prod_image) }}" alt="Product Image" style="width: 5rem; height: 5rem;">
                                            @else
                                                <img src="{{ asset('default_pics/default_prod_pic.jpg') }}" alt="Product Image" style="width: 5rem; height: 5rem;">
                                            @endif
                                            <div class="product-info" style="color: white;">
                                                {{ $orderItem->prod_name }}<br>
                                                @if ($orderItem->color && $orderItem->size)
                                                    {{ $orderItem->color }} - {{ $orderItem->size }}
                                                @elseif ($orderItem->color)
                                                    {{ $orderItem->color }}
                                                @elseif ($orderItem->size)
                                                    {{ $orderItem->size }}
                                                @endif
                                            </div>
                                        </div>
                                        <div class="product-quantity" style="color: white;">
                                            Quantity: {{ $orderItem->quantity }}
                                        </div>
                                    </div>
                                    <hr>
                                    @endforeach
                                </div>
                                @if ($order->status === 'UNPAID')
                                    @if ($order->ref_no)
                                        <div>
                                            <b>GCash Reference Number: </b>{{ $order->ref_no }}
                                        </div>
                                        <hr>
                                    @else
                                        <div style="color: white;">
                                            <b>Click on me to send a GCash reference number as proof of payment.</b>
                                        </div>
                                        <hr>
                                    @endif
                                @elseif ($order->status === 'ON THE WAY')
                                    @if ($order->track_num)
                                        <div style="color: white;">
                                            <b>Parcel Tracking Number: </b>{{ $order->track_num }}
                                        </div>
                                        <hr>
                                    @else
                                        <div style="color: white;">
                                            <b>Your parcel is being shipped and will be on the way soon.</b>
                                        </div>
                                        <hr>
                                    @endif
                                @elseif ($order->status === 'COMPLETED')
                                    <div style="color: white;">
                                        <b>Parcel has been delivered.</b>
                                    </div>
                                    <hr>
                                @endif
                            </a>
                            <div class="order-total text-right">
                                Total: ₱{{ number_format($order->total, 2) }}
                            </div>
                        </div>
                        <br> 
                </div>
                <br><br>
                @endforeach
                @else
                    <p>No Orders at the moment.</p>
                @endif
            </div>
            <div id="paginationLinks">
                {{ $orders->appends(['status' => request('status')])->links() }}
            </div>