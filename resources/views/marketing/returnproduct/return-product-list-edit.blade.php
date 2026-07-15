<!DOCTYPE html>

<html lang="en">

<head>

    {!! $datas['headerlinks'] !!}


    <style>

        .list-product-header {
            /*display: flex;*/
            justify-content: flex-start;
        }


        .list-product-header.new {
            display: block;
        }

        .list-product-header>div {
            gap: 0px !important;
        }


        select {
            -webkit-appearance: none;
            outline: none;
            font-size: 1rem;
            box-sizing: border-box;
            border-radius: 0;
            background: #ffffff;
            border: 1px solid #d1d1d1;
            padding: 0.5em 3.5em 0.5em 1em;
            background-image: linear-gradient(45deg, transparent 50%, gray 50%),
                linear-gradient(135deg, gray 50%, transparent 50%),
                linear-gradient(to right, #ccc, #ccc);
            background-position: calc(100% - 20px) calc(1em + 2px),
                calc(100% - 15px) calc(1em + 2px),
                calc(100% - 2.5em) 0.5em;
            background-size: 5px 5px, 5px 5px, 1px 1.5em;
            background-repeat: no-repeat;
        }


        .placeholder {
            color: white;
            background: gray;
        }

        .pending {
            color: white;
            background: Blue;
        }

        .packed {
            color: white;
            background: Orange;
        }

        .verified {
            background: #2d9d2d;
            color: white;
        }

        .dispatched {
            background: #952c95;
            color: white;
        }

        .delivered {
            background: green;
            color: white;
        }

        .invoiced {
            background: #c7c70c;
            color: white;
        }

        .cancelled {

            background: red;
            color: white;
        }

        .list-product-header>div {
            gap: 50px !important;
        }


        table.dataTable input,
        table.dataTable select {
            border: 1px solid #efefef;
            /* height: 37px; */
        }

        @media only screen and (max-width: 991.98px) {

            .page-wrapper .card .card-header,
            .page-wrapper .card .card-body,
            .page-wrapper .card .card-footer {
                padding: 20px;
                overflow: auto;
            }
        }

        th.text-center.dt-orderable-asc.dt-orderable-desc {
            color: white;
        }

        thead {
            background: #006666;
        }

        span.f-light.f-w-600 {
            color: white;
        }

        select#dt-length-0 {
            padding-right: 6%;
        }



        @media only screen and (max-width: 991.98px) {

            .page-wrapper .card .card-header,
            .page-wrapper .card .card-body,
            .page-wrapper .card .card-footer {
                padding: 20px;
                overflow: auto;
            }
        }


        @media only screen and (max-width: 600px) {
            span.selection {
                margin-left: -168%;
                overflow: auto;
            }

        }

        @media only screen and (max-width: 600px) {
            .col-4.text-center {
                margin-right: 100px;
                overflow: auto;
            }

            @media only screen and (max-width: 600px) {
                span.select2-dropdown.select2-dropdown--below {
                    text-align: center;
                    width: 59% !important;
                    left: -114px;
                    overflow: auto;
                }
            }


            .list-product-header>div {
                gap: 0PX !important;
            }

            .list-product-header>div {
                gap: 0px !important;
            }






        }

        @media (max-width: 575px) {
            .list-product-header a.btn {
                float: unset;
                width: 180px;
            }
        }


        .select2-container .select2-selection--single {
            border-radius: 0.25rem !important;
            border-color: #efefef;
            height: 38px !important;
            padding: 10px;
            /* margin-top: 20px; */
            margin-bottom: 20px;
        }

        @media only screen and (max-width: 991.98px) {

            .page-wrapper .card .card-header,
            .page-wrapper .card .card-body,
            .page-wrapper .card .card-footer {
                padding: 20px;
                overflow: auto;
            }
        }
    </style>
</head>

<body>

    {!! $datas['headermenu'] !!}

    <!-- Page Body Start-->

    <div class="page-body-wrapper">

        {!! $datas['sidemenubar'] !!}

        <div class="page-body">

            <div class="container-fluid">

                <div class="page-title">

                    <div class="row">

                        <div class="col-6">

                            <h4>Return products Edit</h4>

                        </div>

                        <div class="col-6">

                            <ol class="breadcrumb">

                                <li class="breadcrumb-item"><a href="index.html">

                                        <svg class="stroke-icon">

                                            <use href="../assets/svg/icon-sprite.svg#stroke-home"></use>

                                        </svg></a></li>

                                <li class="breadcrumb-item">Pages</li>

                                <li class="breadcrumb-item active">Return products Edit</li>

                            </ol>

                        </div>

                    </div>

                </div>

            </div>



            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-12">

                        <div class="card">

                        <div class="card-body">
                            <div class="loading-overlay">
                              <span class="fa fa-spinner fa-3x fa-spin"></span>
                            </div>
                            <form class="row g-3 needs-validation custom-input" id="order_return_product_edit_form" method="post">
                             @csrf
                              <div class="col-md-3 position-relative">
                                <label class="form-label" for="customer">Company name</label>
                                <select id="customer_edit" name="customer_edit" onchange="GetOrdersForProducts()" class="form-control">
                                    <option value="">Company Name</option>
                                    @foreach($customers as $customer)
                                    <option {{ $customer->id == $return_products->customer_id ? 'selected' : '' }} value="{{ $customer->id }}">{{ $customer->company_name }}</option>
                                    @endforeach
                                </select>
                              </div>
                              <div class="col-md-3 position-relative">
                                <label class="form-label" for="employee">Employee</label>
                                <select id="employee_edit" name="employee_edit" onchange="GetOrdersForProducts()" class="form-control">
                                    <option value="">Employee</option>
                                    @foreach($employees as $employee)
                                    <option {{ $employee->id == $return_products->employee_id ? 'selected' : '' }} value="{{ $employee->id }}">{{ $employee->employee_name }}</option>
                                    @endforeach
                                </select>
                              </div>
                              <div class="col-md-3 position-relative">
                                    <label class="form-label" for="order_products">Ordered Products</label>
                                    <select class="form-select" name="order_products_edit" id="order_products_edit">
                                      <option value="">Products</option>
                                      @foreach($orders as $order)
                                      <option {{ $order->id == $return_products->order_id ? 'selected' : '' }} value="{{ $order->id }}">{{ $order->product }}</option>
                                      @endforeach
                                    </select>
                                </div>
                            <div class="col-md-3 position-relative">
                              <label class="form-label" for="Quantity">Quantity</label>
                              <input type="number" name="quantity_edit" id="quantity_edit" class="form-control" value="{{ $return_products->quantity }}" placeholder="Quantity">
                            </div>
                            <div class="col-md-3 position-relative">
                              <label class="form-label" for="Quantity">Compensation Amount</label>
                              <input type="number" name="compensated_amount" id="compensated_amount" class="form-control" value="{{ $return_products->compensated_amount }}" placeholder="Compensated Amount">
                            </div>
                            <div class="col-md-3 position-relative">
                              <label class="form-label" for="Quantity">Returned Amount</label>
                              <input type="number" name="returned_amount" id="returned_amount" class="form-control" value="{{ $return_products->returned_amount }}" placeholder="Returned Amount">
                            </div>
                            
                                <div class="col-12 button-class">
                                    <input type="hidden" name="id" id="id" value="{{ $return_products->id }}">
                                  <button class="btn btn-primary" id="order_return_product_edit_btn" type="submit">Submit</button>
                                </div>
                            </form>
                        </div>
                        </div>

                    </div>


                </div>
          
            </div>

   
            

        </div>

    </div>

    {!! $datas['scriptlinks'] !!}
    @include('marketing.returnproduct.partials.returnproduct_js')
    
   
</body>


</html>