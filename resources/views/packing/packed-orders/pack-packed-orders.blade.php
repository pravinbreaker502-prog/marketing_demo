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



    /* select {
  color: black;
} */

    .placeholder {
        color: white;
        background: gray;
    }

    .pending {
        color: white;
        background: Blue;
    }

    .Packed {
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

    .cancelled{

      background: red;
      color: white;
    }

    .list-product-header>div {
    gap: 50px !important;
}


table.dataTable input, table.dataTable select {
    border: 1px solid #efefef;
    /* height: 37px; */
}
@media only screen and (max-width: 991.98px) {
    .page-wrapper .card .card-header, .page-wrapper .card .card-body, .page-wrapper .card .card-footer {
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
    .page-wrapper .card .card-header, .page-wrapper .card .card-body, .page-wrapper .card .card-footer {
        padding: 20px;
        overflow: auto;
    }
}


@media only screen and (max-width: 600px ) {
    span.selection {
    margin-left: -168%;
}
  
}
@media only screen and (max-width: 600px ) {
    .col-4.text-center {
    margin-right: 100px;
}
@media only screen and (max-width: 600px ) {
    span.select2-dropdown.select2-dropdown--below {
    text-align: center;
    width: 59% !important;
    left: -114px;
}
}
  









}
@media (max-width: 575px) {
    .list-product-header a.btn {
        float: unset;
        width: 180px;
    }
}



.dt-layout-row {
    display: flex;
    justify-content: space-between;
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

                            <h4>Packed Orders</h4>

                        </div>

                        <div class="col-6">

                            <ol class="breadcrumb">

                                <li class="breadcrumb-item"><a href="dashboard">

                                        <svg class="stroke-icon">

                                            <use href="../assets/svg/icon-sprite.svg#stroke-home"></use>

                                        </svg></a></li>

                                <li class="breadcrumb-item">Pages</li>

                                <li class="breadcrumb-item active">Packed Orders</li>

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
                      <div class="list-product-header new">
                          <div>
                              <button id="refresh-table" class="btn btn-primary"><i class="fa fa-refresh"
                                      aria-hidden="true"></i></button>
                          </div>
                      </div>
                    <div class="list-product list-category">
                      <table class="table thead-light" id="orders-table">
                          <thead>
                              <tr>
                                  <th>#</th>
                                  <th class="text-center"><span class="f-light f-w-600">Comapny
                                          Name</span></th>
                                  <th class="text-center"><span class="f-light f-w-600">Product</span>
                                  </th>
                                  <th class="text-center"><span class="f-light f-w-600">Quantity</span>
                                  </th>
                                  <th class="text-center"><span class="f-light f-w-600">Delivery
                                          Date</span></th>
                                  </th>
                                  <th class="text-center"><span class="f-light f-w-600">Status</span>
                                  </th>
                              </tr>
                          </thead>
                          <tbody>
                          </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
        </div>
        
        <footer class="footer">

            <div class="container-fluid">

                <div class="row">

                    <div class="col-md-12 footer-copyright text-center">

                        <p class="mb-0">Copyright {{ date('Y') }} © Developed by D2C </p>

                    </div>

                </div>

            </div>

        </footer>

    </div>

    </div>

    {!! $datas['scriptlinks'] !!}
    @include('packing.packed-orders.partials.pack_packed_orders_js')

</body>



</html>