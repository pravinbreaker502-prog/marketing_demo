<!DOCTYPE html>

<html lang="en">

    <head>

    {!! $datas['headerlinks'] !!}

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

                  <h4>Edit product</h4>

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
                    <div class="loading-overlay">
                      <span class="fa fa-spinner fa-3x fa-spin"></span>
                    </div>
                    <form class="row g-3 needs-validation custom-input" id="create_product_form" action="javascript:void(0);" method="post">
                        @csrf
                      <div class="col-md-4 position-relative">
                        <label class="form-label" for="product_Name">Product Name </label>
                        <select class="form-control" name="product_Name" id="product Name" onchange="ShowOrHideQuantity()">
                            <option value="">Select Category</option>
                           
                        </select>
                      </div>
                      <div class="col-md-4 position-relative">
                        <label class="form-label" for="product_qty">Product Qty</label>
                        <input class="form-control" name="product_qty" id="product_qty" type="text">
                      </div>
                      
                      <div class="col-12">
                        <button class="btn btn-primary" id="create_product_btn" type="submit">Submit</button>
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
    

  </body>

</html>