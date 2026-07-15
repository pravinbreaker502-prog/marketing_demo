<!DOCTYPE html>

<html lang="en">

<head>

    {!! $datas['headerlinks'] !!}

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/min/dropzone.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/min/dropzone.min.js"></script>


    <style>
    .form-control {
        display: block;
        width: 96% !important;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: var(--bs-body-color);
        background-color: var(--bs-body-bg);
        background-clip: padding-box;
        border: var(--bs-border-width) solid var(--bs-border-color);
        appearance: none;
        border-radius: var(--bs-border-radius);
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .list-product-header {
        display: flex;
        justify-content: flex-start;
    }

    h1.Sales__Executive {
    font-size: 27px;
    font-weight: bold;

    color: #006666;
    background: whitesmoke;
    padding: 10px;
}

    .buttonload {
        background-color: #04AA6D;
        /* Green background */
        border: none;
        /* Remove borders */
        color: white;
        /* White text */
        padding: 12px 16px;
        /* Some padding */
        font-size: 16px
            /* Set a font size */
    }

    .form-select {
        width: 100%;
        margin-bottom: 15px;
    }

    .form-section {
        display: none;
        /* Hide all form sections initially */
    }

    .show {
        display: block;
        /* Display the selected form section */
    }

    .list-product-header.new {
        display: block;
    }

    @media only screen and (max-width: 991.98px) {

        .page-wrapper .card .card-header,
        .page-wrapper .card .card-body,
        .page-wrapper .card .card-footer {
            padding: 20px;
            overflow: auto;
        }
    }

    .show {
        display: block;
        display: flex;
        justify-content: space-evenly;
        flex-wrap: wrap;
    }


    .avatar-upload {
        position: relative;
        max-width: 205px;
        margin: 12px auto;
    }

    .avatar-upload .avatar-edit {
        position: absolute;
        right: 12px;
        z-index: 1;
        top: 10px;
    }

    .avatar-upload .avatar-edit input {
        display: none;
    }

    .avatar-upload .avatar-edit input+label {
        display: inline-block;
        width: 34px;
        height: 34px;
        margin-bottom: 0;
        border-radius: 100%;
        background: #FFFFFF;
        border: 1px solid transparent;
        box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
        cursor: pointer;
        font-weight: normal;
        transition: all 0.2s ease-in-out;
    }

    .avatar-upload .avatar-edit input+label:hover {
        background: #f1f1f1;
        border-color: #d6d6d6;
    }

    .avatar-upload .avatar-edit input+label:after {
        content: "\f040";
        font-family: 'FontAwesome';
        color: #757575;
        position: absolute;
        top: 10px;
        left: 0;
        right: 0;
        text-align: center;
        margin: auto;
    }

    .avatar-upload .avatar-preview {
        width: 192px;
        height: 192px;
        position: relative;
        border-radius: 100%;
        border: 6px solid #F8F8F8;
        box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
    }

    .avatar-upload .avatar-preview>div {
        width: 100%;
        height: 100%;
        border-radius: 100%;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
    }

    h1 {
        font-size: 20px;
        text-align: center;
        margin: 20px 0 20px;
    }

    select.form-select.one {
    width: 96% !important;
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
                                <div class="loading-overlay">
                                    <span class="fa fa-spinner fa-3x fa-spin"></span>
                                </div>
                                <form class="row g-3 needs-validation custom-input" id="create_employee_form" novalidate="" method="post">
                                    @csrf
                                    <!-- <div class="col-xl-3 col-md-12 box-col-12">
                           
                        <div class="m-0 dz-message needsclick dropzone" id="singleFileUpload" action="/upload.php">
                            <div class="avatar"><img class="img-100 b-r-8" src="../assets/images/avtar/4.jpg" alt="#"></div>
                          <h6 class="mb-0">Drop files here or click to upload.</h6>
                        </div>
                    </div> -->
                                    <!--<div class="col-xl-3 col-md-12 box-col-12">-->

                                        <!--<div class="avatar-upload">-->

                                        <!--    <div class="avatar-edit">-->
                                        <!--        <input type='file' name="employee_profile" id="employee_profile" accept=".png, .jpg, .jpeg" />-->
                                        <!--        <label for="employee_profile"></label>-->
                                        <!--    </div>-->
                                            <!--<div class="avatar-preview">-->
                                            <!--    <div id="imagePreview"-->
                                            <!--        style="background-image: url('http://i.pravatar.cc/500?img=7');">-->
                                            <!--    </div>-->
                                            <!--</div>-->

                                        <!--</div>-->
                                        
                                        <!--<h1>profile Image Upload </h1>-->
                                    <!--</div>-->
                                    <div class="col-xl-12 col-md-6 box-col-none">
                                        <div class="row ">
                                            <div class="col-md-4 position-relative">
                                                <label class="form-label" for="employee_profile"> Profile Image Upload* </label>
                                                <input class="form-control" name="employee_profile" id="employee_profile" type="file"
                                                    required="" accept=".png, .jpg, .jpeg">
                                            </div>
                                            <div class="col-md-4 position-relative">
                                                <label class="form-label" for="employee_name"> Employee Name* </label>
                                                <input class="form-control" name="employee_name" id="employee_name" type="text"
                                                    placeholder="Employee Name" required="">
                                            </div>
                                            <div class="col-md-4 position-relative">
                                                <label class="form-label" for="employee_id"> Employee ID*
                                                </label>
                                                <input type="text" name="employee_id" id="employee_id" class="form-control" placeholder="Employee ID">
                                            </div>
                                            <div class="col-md-4 position-relative ">
                                                <label class="form-label" for="username">User Name* </label>
                                                <input type="text" name="username" id="username" class="form-control" placeholder="User Name">
                                            </div>
                                            <div class="col-md-4 position-relative ">
                                                <label class="form-label" for="password">password* </label>
                                                <input type="password" name="password" id="password" class="form-control" placeholder="password">
                                            </div>
                                            <div class="col-md-4 position-relative">
                                                <label class="form-label" for="employee_email">Email Address*</label>
                                                <input class="form-control digits" name="employee_email" id="employee_email" type="email">
                                            </div>
                                            <div class="col-md-4  position-relative">
                                                <label class="form-label" for="employee_mobile">Mobile Number*
                                                </label>
                                                <input type="text" class="form-control" name="employee_mobile" id="employee_mobile"placeholder="0123456789">
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row ">
                                         

                                            <div class="col-md-4  position-relative">
                                                <label class="form-label" for="employee_adhaar_doc"> Aadhar
                                                    card*</label>
                                                <input type="file" name="employee_adhaar_doc" id="employee_adhaar_doc" class="form-control" placeholder="Aadhar card">
                                            </div>
                                            <div class="col-md-4 position-relative">
                                                <label class="form-label" for="employee_dob"> D.O.B* </label>
                                                <input class="form-control digits" name="employee_dob" id="employee_dob" type="date">
                                            </div>

                                            <div class="col-md-12 position-relative"><br>
                                                <label class="form-label" for="employee_address">Address</label>
                                                <textarea class="form-control" name="employee_address" id="employee_address"
                                                    rows="3"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4 position-relative">
                                        <label class="form-label" for="employee_qualification_doc">Qualification Documents* </label>
                                        <input class="form-control" name="employee_qualification_doc"  id="employee_qualification_doc" type="file" required="">
                                    </div>
                                    <div class="col-xxl-4 col-sm-6">
                                        <label class="form-label" for="employee_experience"> Work Experience* </label>
                                        <input class="form-control" name="employee_experience"  id="employee_experience" type="text" required=""
                                            placeholder="Work Experience">
                                    </div>
                                    <div class="col-md-4 position-relative">
                                        <label class="form-label" for="employee_resume">Employee Resume*</label>
                                        <input class="form-control" name="employee_resume"  id="employee_resume" type="file" required="">
                                    </div>
                                    <div class="col-md-4 position-relative">
                                        <label class="form-label" for="employee_passbook_doc"> Bank Passbook </label>
                                        <input class="form-control" name="employee_passbook_doc" id="employee_passbook_doc" type="file" required="">
                                    </div>
                                    <div class="col-md-4 position-relative">
                                        <label class="form-label" for="employee_pan_doc"> pan card</label>
                                        <input class="form-control" name="employee_pan_doc" id="employee_pan_doc" type="file" required="">
                                    </div>
                                    <div class="col-xxl-4 col-sm-6" id="employeeForm">
                                        <label class="form-label" for="employee_type"> Type of Employee* </label>
                                        <select class="form-select" name="employee_type" onchange="toggleFormSection()" id="employee_type"
                                            required="">
                                            <option selected="" disabled="" value="">Choose...</option>
                                            <!-- <option>Marketing</option> -->
                                            <option value="SalesExecutive">Sales Executive</option>
                                            <option value="Packing">Packing</option>
                                            <option value="SalesManager">Sales Manager</option>
                                            <option value="Verifier">Verifier</option>
                                            <option value="Trainer">Trainer</option>
                                            <option value="Dealer">Dealer</option>
                                        </select>
                                    </div>
                                    <div class="container">

                                        <div class="row">
                                       
                                            <div class="col-md-12">
                                           
                                                <div class=" form-section" id="SalesExecutiveSection" >

                                                <div class="container-fluid">
                                                <h1 class="Sales__Executive">Sales Executive Details</h1><br>
                                                </div>
                                            
                                                    <div class="col-md-4 position-relative padding">

                                                        <label class="form-label" for="Vehicle-Type">Vehicle Type *</label>
                                                        <select class="form-select one" required="" name="vehichle_type" id="vehichle_type">
                                                            <option selected="" disabled="" value="">Choose...</option>

                                                            <option value="Bike">Bike</option>
                                                            <option value="Car">Car</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-4 position-relative">
                                                        <label class="form-label" for="vehichle_license"> license* </label>
                                                        <input class="form-control" name="vehichle_license" id="vehichle_license" type="file"
                                                            required="">
                                                    </div>
                                                    <div class="col-md-4 position-relative">
                                                        <label class="form-label" for="vehichle_insurance"> insurance* </label>
                                                        <input class="form-control" name="vehichle_insurance" id="vehichle_insurance" type="file"
                                                            required="">
                                                    </div>
                                                    <div class="col-md-4 position-relative">
                                                        <label class="form-label" for="vehichle_name"> vehicle Name* </label>
                                                        <input class="form-control" name="vehichle_name" id="vehichle_name" type="text"
                                                            required="" placeholder="vehicle Name">
                                                    </div>
                                                    <div class="col-md-4 position-relative">
                                                        <label class="form-label" for="vehichle_regno"> Vehicle registration
                                                            Number* </label>
                                                        <input class="form-control" name="vehichle_regno" id="vehichle_regno" type="text"
                                                            required="" placeholder="Vehicle registration Number">
                                                    </div>

                                                    <div class="col-md-4 position-relative">
                                                        <label class="form-label" for="employee_zone_country"> Working Zone Country*
                                                        </label>
                                                        <select name="employee_zone_country" id="employee_zone_country" class="form-control" required>
                                                            <option value="" disabled selected>Select a country</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4 position-relative">
                                                        <label class="form-label" for="employee_zone_state"> Working Zone State*
                                                        </label>
                                                        <select name="employee_zone_state" id="employee_zone_state" class="form-control" required>
                                                            <option value="" disabled selected>Select a state</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4 position-relative">
                                                        <label class="form-label" for="employee_zone_city"> Working Zone city*
                                                        </label>
                                                        <select name="employee_zone_city" id="employee_zone_city" class="form-control" required>
                                                            <option value="" disabled selected>Select a city</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4 position-relative">
                                                        <label class="form-label" for="employee_zone_pincode"> Working Zone Pincode*
                                                        </label>
                                                        <input class="form-control" name="employee_zone_pincode" id="employee_zone_pincode" type="text"
                                                            required="">
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>




                                    <div class="col-12">
                                        <button class="btn btn-primary" id="create_employee_btn" type="submit">Submit</button>
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
    @include('marketing.employees.partials.employee_js')

</body>

</html>