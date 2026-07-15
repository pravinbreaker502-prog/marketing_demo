<!DOCTYPE html>

<html lang="en">

    <head>

    {!! $datas['headerlinks'] !!}


    <style>
      .col-12.button-class {
    display: flex;
    justify-content: space-around;
    margin-top: 4%;
    margin-bottom: 2%;
}
.col-md-2.position-relative.one {
    margin-top: 25px;
    margin-left: 30px;

}
.footer {
    background-color: #fff;
    -webkit-box-shadow: 0 0 20px rgba(89, 102, 122, 0.1);
    box-shadow: 0 0 20px rgba(89, 102, 122, 0.1);
    padding: 15px;
    bottom: 0;
    left: 0;
    margin-left: 0px !important;
    -webkit-transition: 0.5s;
    transition: 0.5s;
}
.col-md-4.position-relative {
    margin-right: 1%;

}

.col-md-3.position-relative {
    margin-right: 1%;

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

                  <h4> Edit orders</h4>

                </div>

                <div class="col-6">

                  <ol class="breadcrumb">

                    <li class="breadcrumb-item"><a href="index.html">                                       

                        <svg class="stroke-icon">

                          <use href="../assets/svg/icon-sprite.svg#stroke-home"></use>

                        </svg></a></li>

                    <li class="breadcrumb-item">Pages</li>

                    <li class="breadcrumb-item active"> Edit orders</li>

                  </ol>

                </div>

              </div>

            </div>

          </div>

          <!-- Container-fluid starts-->
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-12">
                
                <div class="card">
                  
                  <div class="card-body">
                    <div class="loading-overlay">
                      <span class="fa fa-spinner fa-3x fa-spin"></span>
                    </div>
                    <form class="row g-3 needs-validation custom-input" method="post" id="edit_orders_form">
                        @csrf
                    <div class="col-md-2 position-relative">
                    <label class="form-label" for="Products">Company name</label>
                    <select id="customer" name="customer" class="form-control">
                        <option value="">Company Name</option>
                        @foreach($customers as $customer)
                        <option {{ $customer->id == $order->customer_id ? 'selected' : '' }} value="{{ $customer->id }}">{{ $customer->company_name }}</option>
                        @endforeach
                    </select>
                      </div>
                      <div class="col-md-3 position-relative">
                    <label class="form-label" for="Products">Employee</label>
                    <select id="employee" name="employee" class="form-control">
                        <option value="">Employee</option>
                        @foreach($employees as $employee)
                        <option {{ $employee->id == $order->employee_id ? 'selected' : '' }} value="{{ $employee->id }}">{{ $employee->employee_name }}</option>
                        @endforeach
                    </select>
                      </div>
                    
                     




 <div class="container mt-5">
  <div class="jumbotron mt-2" data-x-wrapper="employees">
        <div class="employee-row d-flex my-2" data-x-group>
            
            <div class="col-md-3 position-relative">
            <label class="form-label" for="Products">Products</label>
                <select class="form-control product-select product_001" name="product" id="product">
                    <option value="">Product Name</option>
                        @foreach($products as $product)
                        <option {{ $product->id == $order->product_id ? 'selected' : '' }} value="{{ $product->id }}">{{ $product->product }}</option>
                        @endforeach
                </select>
            </div>

            <div class="col-md-3 position-relative">
            <label class="form-label" for="Quantity">Quantity</label>
                <input type="number" name="quantity"  id="quantity" class="form-control" value="{{ $order->quantity }}" placeholder="Quantity">
            </div>

            <div class="col-xxl-2 col-sm-6">
                <label class="form-label" for="delivary_date">Delivery Date</label>
                <input id="delivery_date" name="delivery_date" class="delivery-date form-control" value="{{ date("Y-m-d", strtotime($order->delivery_date)) }}" type="date">
            </div>
            
            <div class="col-xxl-2 col-sm-6" style="margin-left: 1%;">
              <label class="form-label" for="product_discount">Discount %</label>
              <input type="number" class="form-control" name="product_discount" id="product_discount" value="{{ $order->discount }}" autocomplete="off"placeholder="Discount %">
            </div>

            <!--<div class="col-md-2 position-relative one">-->
            <!--    <button type="button" class="btn btn-danger" data-remove-btn>-</button>-->
            <!--    <button type="button" class="btn btn-success" data-add-btn>+</button>-->
            <!--</div>-->
        </div>
    </div>
                      
                      <div class="col-12 button-class">
                          <input type="hidden" name="id" id="id" value="{{ $order->id }}">
                        <button class="btn btn-primary" id="edit_orders_btn" type="submit">Submit</button>
                      </div>
                    </form>
                  </div>
                </div>
                
              </div>
              
              
            </div>
          </div>





          <!-- Container-fluid Ends-->

        </div>

        <!-- Button trigger modal -->


<!-- Modal -->


        <!-- footer start-->

        <!-- <footer class="footer">

          <div class="container-fluid">

            <div class="row">

              <div class="col-md-12 footer-copyright text-center">

                <p class="mb-0">Copyright {{ date('Y') }} © Developed by D2C </p>

              </div>

            </div>

          </div>

        </footer> -->

      </div>

    </div>

    {!! $datas['scriptlinks'] !!}
    @include('marketing.Orders.partials.orders_js')

  </body>

</html>