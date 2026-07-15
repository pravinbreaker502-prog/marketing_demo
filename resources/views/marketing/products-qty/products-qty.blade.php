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

                  <h4>Add product</h4>

                </div>

                <div class="col-6">

                  <ol class="breadcrumb">

                    <li class="breadcrumb-item"><a href="index.html">                                       

                        <svg class="stroke-icon">

                          <use href="../assets/svg/icon-sprite.svg#stroke-home"></use>

                        </svg></a></li>

                    <li class="breadcrumb-item">Pages</li>

                    <li class="breadcrumb-item active">Add product</li>

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
                        <a class="btn btn-primary" href="add-products-qty"><i class="fa fa-plus"></i>Add Products</a>
                      </div>
                     
                    </div>
                    <div class="list-product list-category">
                      <table class="table" id="product-table">
                        <thead> 
                          <tr> 
                            <th>#</th>
                            <th class="text-center"> <span class="f-light f-w-600">Product </span></th>
                            <th class="text-center"> <span class="f-light f-w-600">Quantity</span></th>
                            <th class="text-center"> <span class="f-light f-w-600">action</span></th>
                          </tr>
                        </thead>
                        <tbody> 
                        <tr>
                        <td>1 </td>
                        <td class="text-center"> Harish </td>
                        <td class="text-center"> 20</td>
                        <td class="text-center"> 
                        <p class="f-light">
                          </p>
                            <ul id="t-1" class="action simple-list flex-row list-group">
                            <li class="edit list-group-item"> <a href="edit-products-qty"><i class="fa fa-pencil"></i></a></li>
                            <li class="delete list-group-item"> <a href="#" class="delete-product" data-id="136"><i class="fa fa-trash"></i></a></li>
                          </ul>
                         </td>
                        
                        </tr>
                        
                        </tbody>
                      </table>
                    </div>
                  </div>
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
  
    

  </body>

</html>