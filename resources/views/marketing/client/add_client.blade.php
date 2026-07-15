<!DOCTYPE html>

<html lang="en">

    <head>

    {!! $datas['headerlinks'] !!}


    <style>
        
.lds-dual-ring.hidden { 
display: none;
}
.lds-dual-ring {
  display: inline-block;
  width: 80px;
  height: 80px;
}
.lds-dual-ring:after {
  content: " ";
  display: block;
  width: 64px;
  height: 64px;
  margin: 5% auto;
  border-radius: 50%;
  border: 6px solid #fff;
  border-color: red transparent green transparent;
  animation: lds-dual-ring 1.2s linear infinite;
}
@keyframes lds-dual-ring {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}


.overlay {
    position: fixed;
    top: 10%;
    left: 0;
    width: 100%;
    height: 100vh;
    /* background: rgba(0,0,0,.8); */
    z-index: 999;
    opacity: 1;
    transition: all 0.5s;
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

                  <h4>Add Client</h4>

                </div>

                <div class="col-6">

                  <ol class="breadcrumb">

                    <li class="breadcrumb-item"><a href="index.html">                                       

                        <svg class="stroke-icon">

                          <use href="../assets/svg/icon-sprite.svg#stroke-home"></use>

                        </svg></a></li>

                    <li class="breadcrumb-item">ECommerce</li>

                    <li class="breadcrumb-item active">Add Client</li>

                  </ol>

                </div>

              </div>

            </div>

          </div>

          <!-- Container-fluid starts-->
          <div class="container-fluid">
          <div id="richList"></div>
          <div id="loader" class="lds-dual-ring hidden overlay"></div>
            <div class="row">
              <div class="col-sm-12">
                
                <div class="card">
                  
                  <div class="card-body">
                    <div class="loading-overlay">
                      <span class="fa fa-spinner fa-3x fa-spin"></span>
                    </div>
                    <form class="row g-3 needs-validation custom-input" id="create_customer_form" method="post">
                        @csrf
                      <div class="col-md-4 position-relative">
                        <label class="form-label" for="company_name">Comapny name</label>
                        <input class="form-control" name="company_name" id="company_name" type="text">
                      </div>
                      <div class="col-xxl-4 col-sm-6">
                        <label class="form-label" for="client_name">Client Name</label>
                        <input class="form-control" name="client_name" id="client_name" type="text">
                      </div>
                      <div class="col-md-4 position-relative">
                        <label class="form-label" for="client_email">Email</label>
                        <input type="text" class="form-control" name="client_email" id="client_email">
                      </div>
                      <div class="col-md-4 position-relative">
                        <label class="form-label" for="client_mobile">Phone No</label>
                        <input type="text" class="form-control" name="client_mobile" id="client_mobile">
                      </div>
                      <div class="col-md-4 position-relative">
                        <label class="form-label" for="client_address">Address</label>
                        <textarea class="form-control" rows="1" cols="1" name="client_address" id="client_address"></textarea>
                      </div>
                      <div class="col-md-4 position-relative">
                        <label class="form-label" for="gst_no">GST No</label>
                        <input class="form-control" name="gst_no" id="gst_no" type="text">
                      </div>
                      <div class="col-12">
                        <button class="btn btn-primary" id='create_customer_btn' type="submit">Submit</button>
                      </div>
                    </form>
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
    @include('marketing.client.partials.client_js')
    
  </body>

</html>