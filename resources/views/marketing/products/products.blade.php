<!DOCTYPE html>

<html lang="en">

    <head>

    {!! $datas['headerlinks'] !!}



    <style>
      .list-group .list-group-item {
    padding: 5px 13px !important;
}

thead {
    background: #006666;
}
span.f-light.f-w-600 {
    color: white;
}
@media only screen and (max-width: 991.98px) {
    .page-wrapper .card .card-header, .page-wrapper .card .card-body, .page-wrapper .card .card-footer {
        padding: 20px;
        overflow: auto;
    }
}

.card .card-body {
    padding: 20px;
    background-color: transparent;
    overflow: auto;
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

                  <h4>Products</h4>

                </div>

                <div class="col-6">

                  <ol class="breadcrumb">

                    <li class="breadcrumb-item"><a href="index.html">                                       

                        <svg class="stroke-icon">

                          <use href="../assets/svg/icon-sprite.svg#stroke-home"></use>

                        </svg></a></li>

                    <li class="breadcrumb-item">Pages</li>

                    <li class="breadcrumb-item active">Sample Page</li>

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
                    <div class="list-product-header">
                      <div> 
                        <div class="light-box">
                            <a data-bs-toggle="collapse" href="#collapseProduct" role="button" aria-expanded="false" aria-controls="collapseProduct"><i class="filter-icon show" data-feather="filter"></i><i class="icon-close filter-close hide"></i></a>
                        </div>
                        <button id="refresh-table" class="btn btn-primary"><i class="fa fa-refresh" aria-hidden="true"></i></button>
                        <a class="btn btn-primary" href="add_products"><i class="fa fa-plus"></i>Add Products</a>
                      </div>
                     
                    </div>
                    <div class="list-product list-category">
                      <table class="table" id="product-table">
                        <thead> 
                          <tr> 
                            <th>#</th>
                            <th class="text-center"> <span class="f-light f-w-600">Product Name</span></th>
                            <th class="text-center"> <span class="f-light f-w-600">Product Category</span></th>
                            <th class="text-center"> <span class="f-light f-w-600">Quantity</span></th>
                            <th class="text-center"> <span class="f-light f-w-600">Actual Price</span></th>
                            <th class="text-center"> <span class="f-light f-w-600">Discount Price</span></th>
                            <th class="text-center"> <span class="f-light f-w-600">Sell Price</span></th>
                            <th class="text-center"> <span class="f-light f-w-600">GST</span></th>
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

        <!-- footer start-->

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
    @include('marketing.products.partials.products_js')
    

  </body>

</html>