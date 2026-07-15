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

                  <h4>Client</h4>

                </div>

                <div class="col-6">

                  <ol class="breadcrumb">

                    <li class="breadcrumb-item"><a href="index.html">                                       

                        <svg class="stroke-icon">

                          <use href="../assets/svg/icon-sprite.svg#stroke-home"></use>

                        </svg></a></li>

                    <li class="breadcrumb-item">Pages</li>

                    <li class="breadcrumb-item active">Client</li>

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
                        <a class="btn btn-primary" href="add_client"><i class="fa fa-plus"></i>Add Client</a>
                      </div>
                     
                    </div>
                    <div class="list-product list-category">
                      <table class="table" id="customer-table">
                        <thead> 
                          <tr>
                             <th>#</th>
                            <th class="text-center"> <span class="f-light f-w-600">Comapny Name</span></th>
                            <th class="text-center"> <span class="f-light f-w-600">Client Name</span></th>
                            <th class="text-center"> <span class="f-light f-w-600">Email</span></th>
                            <th class="text-center"> <span class="f-light f-w-600">Phone No</span></th>
                            <th class="text-center"> <span class="f-light f-w-600">Address</span></th>
                            <th class="text-center"> <span class="f-light f-w-600">GST No</span></th>
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

        <!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 style="text-align: center;" class="modal-title" id="staticBackdropLabel">Client Edit Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <form role="form" action="action_page.php" method="post">
            <fieldset>
              <!-- <legend> :</legend> -->
              <div class="form-group col-xs-6">
                <label for="name"> Company name*</label>
                <input type="text" class="form-control" id="Companyname">
              </div>

              <div class="form-group col-xs-6">
                <label for="email"> Client Name*</label>
                <input type="text" class="form-control" id="ClientName">
              </div>

              <div class="form-group col-xs-6">
                <label for="tel"> Email*</label>
                <input type="email" class="form-control" id="Email">
              </div>

          
              <div class="form-group col-xs-6">
                <label for="text"> Phone No*</label>
                <input type="number" class="form-control" id="Phone">
              </div>
              <div class="form-group col-xs-12">
                <label for="comment"> Address*</label>
                <textarea class="form-control" rows="5" id="Address"></textarea>
              </div>
              <div class="form-group col-xs-6">
                <label for="text"> GST No*</label>
                <input type="text" class="form-control" id="GST">
              </div>


            </fieldset>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Submit</button>
      </div>
    </div>
  </div>
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
    @include('marketing.client.partials.client_js')

  </body>

</html>