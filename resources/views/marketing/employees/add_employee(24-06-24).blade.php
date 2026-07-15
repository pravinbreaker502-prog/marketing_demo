<!DOCTYPE html>

<html lang="en">

    <head>

    {!! $datas['headerlinks'] !!}

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/min/dropzone.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/min/dropzone.min.js"></script>


    <style>
      .list-product-header {
    display: flex;
    justify-content: flex-start;
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

                  <h4>Add Employee</h4>

                </div>

                <div class="col-6">

                  <ol class="breadcrumb">

                    <li class="breadcrumb-item"><a href="index.html">                                       

                        <svg class="stroke-icon">

                          <use href="../assets/svg/icon-sprite.svg#stroke-home"></use>

                        </svg></a></li>

                    <li class="breadcrumb-item">Pages</li>

                    <li class="breadcrumb-item active">Add Employee</li>

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
                    <form class="row g-3 needs-validation custom-input" novalidate="">
                      <!-- <div class="col-xl-3 col-md-12 box-col-12">
                           
                        <div class="m-0 dz-message needsclick dropzone" id="singleFileUpload" action="/upload.php">
                            <div class="avatar"><img class="img-100 b-r-8" src="../assets/images/avtar/4.jpg" alt="#"></div>
                          <h6 class="mb-0">Drop files here or click to upload.</h6>
                        </div>
                    </div> -->
                 <div class="col-xl-3 col-md-12 box-col-12">
                    <div class="m-0 dz-message needsclick dropzone" id="singleFileUpload" action="/upload.php">
                        <div class="avatar">
                           
                        </div>
                        <h6 class="mb-0">profile pictures click to upload.</h6>
                    </div>
                </div>
                <div class="col-xl-9 col-md-6 box-col-none">
                    <div class="row ">
                    <div class="col-md-4 position-relative">
                        <label class="form-label" for="contactnumber"> Employee Name* </label>
                        <input class="form-control" id="contactnumber" type="text" placeholder="Employee Name"  required="">
                      </div>
                      <div class="col-md-4 position-relative">
                        <label class="form-label" for="validationTooltip02"> Employee ID* </label>
                        <input type="text" class="form-control" placeholder="Employee ID">
                      </div>
                      <div class="col-md-4 position-relative ">
                        <label class="form-label" for="validationTooltip02"> User ID* </label>
                        <input type="text" class="form-control" placeholder="User ID">
                      </div>
                    </div>
                   <br>
                    <div class="row ">
                      <div class="col-md-4  position-relative">
                        <label class="form-label" for="validationTooltip02">Mobile Number*  </label>
                        <input type="text" class="form-control" placeholder="0123456789">
                      </div>
                      <div class="col-md-4  position-relative">
                        <label class="form-label" for="validationTooltip02"> Aadhar card*</label>
                        <input type="file" class="form-control" placeholder="Aadhar card">
                      </div>
                      <div class="col-md-4 position-relative">
                        <label class="form-label" for="validationTooltip02">  D.O.B*  </label>
                        <input class="form-control digits" type="date" value="2024-05-01">
                      </div>
                      
                      <div class="col-md-12 position-relative"><br>
                        <label class="form-label" for="validationTooltip02">Address</label>
                        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                      </div>  
                </div></div>
                      
                      <div class="col-md-4 position-relative">
                        <label class="form-label" for="formFile">Qualification Documents* </label>
                        <input class="form-control" id="formFile" type="file" required="">
                      </div>
                      <div class="col-xxl-4 col-sm-6">
                        <label class="form-label" for="contactnumber"> Work Experience* </label>
                        <input class="form-control" id="contactnumber" type="number"  required="" placeholder="Work Experience">
                      </div>
                      <div class="col-md-4 position-relative">
                        <label class="form-label" for="formFile">Employee Resume*</label>
                        <input class="form-control" id="formFile" type="file" required="">
                      </div>
                      <div class="col-xxl-4 col-sm-6">
                        <label class="form-label" for="contactnumber"> Type of Employee* </label>
                        <select class="form-select" id="validationDefault04" required="">
                          <option selected="" disabled="" value="">Choose...</option>
                          <option>Marketing </option>
                          <option>Product Checker</option>
                        </select>
                      </div>
                      <div class="col-md-4 position-relative">
                        
                          <h6 class="sub-title"> Bike & Car* </h6>
                          <div class="radio-form">
                            <div class="form-check">
                              <input class="form-check-input" id="flexRadioDefault1" type="radio" name="flexRadioDefault" required="">
                              <label class="form-label" for="flexRadioDefault1">Select bike</label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" id="flexRadioDefault2" type="radio" name="flexRadioDefault" checked="" required="">
                              <label class="form-label" for="flexRadioDefault2">Select car</label>
                            
                            
                          </div>
                        </div>
                      </div>
                      <div class="col-md-4 position-relative">
                        <label class="form-label" for="formFile"> license* </label>
                        <input class="form-control" id="formFile" type="file" required="">
                      </div>
                      <div class="col-md-4 position-relative">
                        <label class="form-label" for="formFile">  insurance*  </label>
                        <input class="form-control" id="formFile" type="file" required="">
                      </div>
                      <div class="col-md-4 position-relative">
                        <label class="form-label" for="formFile">   vehicle Name*   </label>
                        <input class="form-control" id="formFile" type="text" required="" placeholder="vehicle Name">
                      </div>
                      <div class="col-md-4 position-relative">
                        <label class="form-label" for="formFile">Vehicle registration Number* </label>
                        <input class="form-control" id="formFile" type="text" required="" placeholder="Vehicle registration Number">
                      </div>
                      <div class="col-md-4 position-relative">
                        <label class="form-label" for="formFile">  Bank Passbook  </label>
                        <input class="form-control" id="formFile" type="file" required="">
                      </div>
                      <div class="col-md-4 position-relative">
                        <label class="form-label" for="formFile">   Working Zone Country*   </label>
                        <input class="form-control" id="formFile" type="file" required="">
                      </div>
                      <div class="col-md-4 position-relative">
                        <label class="form-label" for="formFile">   Working Zone State*   </label>
                        <input class="form-control" id="formFile" type="file" required="">
                      </div>
                      <div class="col-md-4 position-relative">
                        <label class="form-label" for="formFile">    Working Zone city*   </label>
                        <input class="form-control" id="formFile" type="file" required="">
                      </div>
                      <div class="col-md-4 position-relative">
                        <label class="form-label" for="formFile"> Working Zone Pincode*   </label>
                        <input class="form-control" id="formFile" type="file" required="">
                      </div>
                      <div class="col-12">
                        <button class="btn btn-primary" type="submit">Submit</button>
                      </div>
                    </form>
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

    

  </body>

</html>