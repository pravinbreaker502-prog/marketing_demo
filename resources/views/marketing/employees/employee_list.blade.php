<!DOCTYPE html>

<html lang="en">

    <head>

    {!! $datas['headerlinks'] !!}



    <style>
.list-product-header {
    display: flex;
    justify-content: flex-end;
}

.buttonload {
  background-color: #04AA6D; /* Green background */
  border: none; /* Remove borders */
  color: white; /* White text */
  padding: 12px 16px; /* Some padding */
  font-size: 16px /* Set a font size */ 
}



.list-product-header.new {
    display: block;
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

                  <h4>Employee list</h4>

                </div>

                <div class="col-6">

                  <ol class="breadcrumb">

                    <li class="breadcrumb-item"><a href="index.html">                                       

                        <svg class="stroke-icon">

                          <use href="../assets/svg/icon-sprite.svg#stroke-home"></use>

                        </svg></a></li>

                    <li class="breadcrumb-item">Pages</li>

                    <li class="breadcrumb-item active">Employee list</li>

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
                        <div class="light-box"><a data-bs-toggle="collapse" href="#collapseProduct" role="button" aria-expanded="false" aria-controls="collapseProduct"><i class="filter-icon show" data-feather="filter"></i><i class="icon-close filter-close hide"></i></a></div>
                        <button id="refresh-table" class="btn btn-primary"><i class="fa fa-refresh" aria-hidden="true"></i></button>
                        <a class="btn btn-primary" href="add_employee"><i class="fa fa-plus"></i>Add Employees</a>
                      </div>
                     
                    </div>
                    <div class="list-product list-category">
                      <table class="table" id="employee-table">
                        <thead> 
                          <tr>
                            <th> <span class="f-light f-w-600">S.No	</span></th>
                            <th> <span class="f-light f-w-600">Employees Name</span></th>
                            <th> <span class="f-light f-w-600">Employee ID</span></th>
                            <th> <span class="f-light f-w-600">User ID</span></th>
                            <th> <span class="f-light f-w-600">Mobile Number</span></th>
                            <th> <span class="f-light f-w-600">Email</span></th>
                            <th> <span class="f-light f-w-600">D.O.B</span></th>
                            <th> <span class="f-light f-w-600">Type</span></th>
                            <th> <span class="f-light f-w-600">action</span></th>
                          
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
    @include('marketing.employees.partials.employee_js')

  </body>

</html>