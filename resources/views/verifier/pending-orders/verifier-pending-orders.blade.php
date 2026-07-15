<!DOCTYPE html>

<html lang="en">

<head>

    {!! $datas['headerlinks'] !!}

<style>
    thead {
    background: #006666;
}
span.f-light.f-w-600 {
    color: white;
}

.dataTables_wrapper .dataTables_paginate {

    border: 0px solid #E6E9EB !important;

}
div .action .edit {
    margin-top: -22px;
    margin-bottom: -6px;
}
@media only screen and (max-width: 991.98px) {
    .page-wrapper .card .card-header, .page-wrapper .card .card-body, .page-wrapper .card .card-footer {
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

                            <h4>verified orders</h4>

                        </div>

                        <div class="col-6">

                            <ol class="breadcrumb">

                                <li class="breadcrumb-item"><a href="dashboard">

                                        <svg class="stroke-icon">

                                            <use href="../assets/svg/icon-sprite.svg#stroke-home"></use>

                                        </svg></a></li>

                                <li class="breadcrumb-item">Pages</li>

                                <li class="breadcrumb-item active">Verified orders</li>

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
                                  <th class="text-center"> <span class="f-light f-w-600">action</span></th>
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

          <!-- Container-fluid Ends-->

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
    @include('verifier.pending-orders.partials.verifier_pending_orders_js')


<script>
</script>

</body>



</html>