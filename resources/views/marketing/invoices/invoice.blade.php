<!DOCTYPE html>

<html lang="en">

    <head>

    {!! $datas['headerlinks'] !!}



    <style>
      .list-product-header {
    display: flex;
    justify-content: flex-start;
}


thead {
    background: #006666;
}
span.f-light.f-w-600 {
    color: white;
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

                  <h4>Invoice</h4>

                </div>

                <div class="col-6">

                  <ol class="breadcrumb">

                    <li class="breadcrumb-item"><a href="index.html">                                       

                        <svg class="stroke-icon">

                          <use href="../assets/svg/icon-sprite.svg#stroke-home"></use>

                        </svg></a></li>

                    <li class="breadcrumb-item">Pages</li>

                    <li class="breadcrumb-item active">Invoice list</li>

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
                                <div class="">
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
                                            <input type="text" name="date_range" placeholder="Invoiced Date" id="date_range" class="form-control"/>
                                            <input type="hidden" name="from_date" id="from_date"/>
                                            <input type="hidden" name="to_date" id="to_date"/>
                                        </div>

                                        <div class="col-md-3 ">
                                            <button class="btn btn-primary search-submit">Submit</button>
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
                    <div class="list-product-header new">
                        <div>
                            <button id="refresh-table" class="btn btn-primary"><i class="fa fa-refresh"
                                    aria-hidden="true"></i></button>
                        </div>
                    </div>
                    <div class="list-product list-category">
                      <table class="table" id="Invoices-table">
                        <thead> 
                          <tr>
                              <th>#</th>
                            <th> <span class="f-light f-w-600">Comapny Name</span></th>
                            <th> <span class="f-light f-w-600">Invoice No</span></th>
                            <th> <span class="f-light f-w-600">Invoiced Date</span></th>
                            <th> <span class="f-light f-w-600">Invoice</span></th>
                            <th> <span class="f-light f-w-600">Download</span></th>
                            <th> <span class="f-light f-w-600">Payment Status</span></th>
                            <th> <span class="f-light f-w-600">Payment</span></th>
                            <th> <span class="f-light f-w-600">Delete</span></th>
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
    @include('marketing.invoices.partials.invoice_js')
    @include('marketing.invoices.partials.payment_modal')

  </body>

</html>