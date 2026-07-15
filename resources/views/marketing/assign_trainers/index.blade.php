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

                  <h4>Assing Trainers</h4>

                </div>

                <div class="col-6">

                  <ol class="breadcrumb">

                    <li class="breadcrumb-item"><a href="index.html">                                       

                        <svg class="stroke-icon">

                          <use href="../assets/svg/icon-sprite.svg#stroke-home"></use>

                        </svg></a></li>

                    <li class="breadcrumb-item">Pages</li>

                    <li class="breadcrumb-item active">Assing Trainers</li>

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
                                <div class="list-product-header1">
                                    <div class="row">
                                        <div class="col-3">
                                            <select id="customer" name="customer" required="" class="form-control">
                                                <option value="">Company Name</option>
                                                @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}">{{ $customer->company_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-3">
                                            <input type="text" name="date_range" placeholder="Order Date" id="date_range" class="form-control"/>
                                            <input type="hidden" name="from_date" id="from_date"/>
                                            <input type="hidden" name="to_date" id="to_date"/>
                                        </div>
                                        <div class="col-3">
                                            <select class="form-control" name="filter_process_status" id="filter_process_status">
                                                <option value="" >Select Status</option>
                                                <option  value="pending" style="text-align: center;">Pending</option>
                                                <option value="on_progress" style="text-align: center;">On Progress</option>
                                                <option value="completed" style="text-align: center;">Completed</option>
                                            </select>
                                        </div>
                                        <div class="col-3">
                                            <select id="employee_filter" name="employee_filter" class="form-control">
                                                <option value="">Employee</option>
                                                @foreach($trainers as $employee)
                                                <option value="{{ $employee->id }}">{{ $employee->employee_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3 one-new">
                                            <button class="btn btn-primary search-submit">Submit</button>
                                            <!--<button id="refresh-form" class="btn btn-primary"><i class="fa fa-refresh" aria-hidden="true"></i></button>-->
                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
              
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
                        <a class="btn btn-primary" href="add-assigned-trainers"><i class="fa fa-plus"></i>Assign Trainers</a>
                      </div>
                     
                    </div>
                    <div class="list-product list-category">
                      <table class="table" id="assign_trainers-table">
                        <thead> 
                          <tr>
                             <th>#</th>
                            <th class="text-center"> <span class="f-light f-w-600">Comapny Name</span></th>
                            <th class="text-center"> <span class="f-light f-w-600">Trainer Name</span></th>
                            <th class="text-center"> <span class="f-light f-w-600">From Date</span></th>
                            <th class="text-center"> <span class="f-light f-w-600">To Date</span></th>
                            <th class="text-center"> <span class="f-light f-w-600">No.Of Teachers</span></th>
                            <th class="text-center"> <span class="f-light f-w-600">Status</span></th>
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
    @include('marketing.assign_trainers.partials.assign_trainers_js')

  </body>

</html>