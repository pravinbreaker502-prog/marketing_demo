<!DOCTYPE html>

<html lang="en">

<head>

    {!! $datas['headerlinks'] !!}


    <style>
    .list-product-header {
        /*display: flex;*/
        justify-content: flex-start;
    }


    .list-product-header.new {
        display: block;
    }

    .list-product-header>div {
        gap: 0px !important;
    }


    select {
        -webkit-appearance: none;
        outline: none;
        font-size: 1rem;
        box-sizing: border-box;
        border-radius: 0;
        background: #ffffff;
        border: 1px solid #d1d1d1;
        padding: 0.5em 3.5em 0.5em 1em;
        background-image: linear-gradient(45deg, transparent 50%, gray 50%),
            linear-gradient(135deg, gray 50%, transparent 50%),
            linear-gradient(to right, #ccc, #ccc);
        background-position: calc(100% - 20px) calc(1em + 2px),
            calc(100% - 15px) calc(1em + 2px),
            calc(100% - 2.5em) 0.5em;
        background-size: 5px 5px, 5px 5px, 1px 1.5em;
        background-repeat: no-repeat;
    }

    /* select {
  color: black;
} */

    .placeholder {
        color: white;
        background: gray;
    }

    .pending {
        color: white;
        background: Blue;
    }

    .packed {
        color: white;
        background: Orange;
    }

    .verified {
        background: #2d9d2d;
        color: white;
    }

    .dispatched {
        background: #952c95;
        color: white;
    }

    .delivered {
        background: green;
        color: white;
    }

    .invoiced {
        background: #c7c70c;
        color: white;
    }

    .cancelled{

      background: red;
      color: white;
    }

    .list-product-header>div {
    gap: 50px !important;
}


table.dataTable input, table.dataTable select {
    border: 1px solid #efefef;
    /* height: 37px; */
}
@media only screen and (max-width: 991.98px) {
    .page-wrapper .card .card-header, .page-wrapper .card .card-body, .page-wrapper .card .card-footer {
        padding: 20px;
        overflow: auto;
    }
}

th.text-center.dt-orderable-asc.dt-orderable-desc {
    color: white;
}
thead {
    background: #006666;
}

span.f-light.f-w-600 {
    color: white;
}
select#dt-length-0 {
    padding-right: 6%;
}



@media only screen and (max-width: 991.98px) {
    .page-wrapper .card .card-header, .page-wrapper .card .card-body, .page-wrapper .card .card-footer {
        padding: 20px;
        overflow: auto;
    }
}


@media only screen and (max-width: 600px ) {
    span.selection {
    margin-left: -168%;
}
  
}
@media only screen and (max-width: 600px ) {
    .col-4.text-center {
    margin-right: 100px;
}
@media only screen and (max-width: 600px ) {
    span.select2-dropdown.select2-dropdown--below {
    text-align: center;
    width: 59% !important;
    left: -114px;
}
}
  

.list-product-header>div {
    gap: 0PX !important;
}
.list-product-header>div {
    gap: 0px !important;
}






}
@media (max-width: 575px) {
    .list-product-header a.btn {
        float: unset;
        width: 180px;
    }
}


.select2-container .select2-selection--single {
    border-radius: 0.25rem !important;
    border-color: #efefef;
    height: 38px !important;
    padding: 10px;
    /* margin-top: 20px; */
    margin-bottom: 20px;
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

                            <h4>Orders list</h4>

                        </div>

                        <div class="col-6">

                            <ol class="breadcrumb">

                                <li class="breadcrumb-item"><a href="index.html">

                                        <svg class="stroke-icon">

                                            <use href="../assets/svg/icon-sprite.svg#stroke-home"></use>

                                        </svg></a></li>

                                <li class="breadcrumb-item">Pages</li>

                                <li class="breadcrumb-item active">Orders list</li>

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
                                            <select class="form-control" name="filter_order_status" id="filter_order_status">
                                                <option value="" >Select Status</option>
                                                <option  value="pending" style="text-align: center;">Pending</option>
                                                <option value="packed" style="text-align: center;">Packed</option>
                                                <option value="verified" style="text-align: center;">Verified</option>
                                                <option value="dispatched" style="text-align: center;">Dispatched</option>
                                                <option value="delivered" style="text-align: center;">Delivered</option>
                                                <option value="invoiced" style="text-align: center;">Invoiced</option>
                                                <option value="cancelled" style="text-align: center;">Cancelled</option>
                                            </select>
                                        </div>
                                        <div class="col-3">
                                            <input type="text" name="product_name_filter" placeholder="Product Name" id="product_name_filter" class="form-control"/>
                                        </div>
                                        <div class="col-3">
                                            <select id="employee_filter" name="employee_filter" class="form-control">
                                                <option value="">Employee</option>
                                                @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}">{{ $employee->employee_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select name="employee_zone_country_filter" id="employee_zone_country_filter" class="form-control" required>
                                                <option value="" disabled selected>Select a zone country</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select name="employee_zone_state_filter" id="employee_zone_state_filter" class="form-control" required>
                                                <option value="" disabled selected>Select a zone state</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select name="employee_zone_city_filter" id="employee_zone_city_filter" class="form-control" required>
                                                <option value="" disabled selected>Select a zone city</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3 one-new">
                                            <button class="btn btn-primary search-submit">Submit</button>
                                            <!--<button id="refresh-form" class="btn btn-primary"><i class="fa fa-refresh" aria-hidden="true"></i></button>-->
                                        </div>

                                        <div class="col-4 text-center">
                                            <div class="light-box"><a data-bs-toggle="collapse" href="#collapseProduct"
                                                    role="button" aria-expanded="false"
                                                    aria-controls="collapseProduct"><i class="filter-icon show"
                                                        data-feather="filter"></i><i
                                                        class="icon-close filter-close hide"></i></a></div><a
                                                class="btn btn-primary" href="assign_orders"><i
                                                    class="fa fa-plus"></i>Create Orders</a>
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
                                        <button class="btn btn-primary invoice" onclick="updatePreview()" id="generatePdfBtn">Invoice</button>
                                        <!-- <div>
                          <select id="exampleSelect" name="select" required="" aria-placeholder="Company Name" class="form-select"><option disabled>Company Name...</option>
                          <option>U.K </option><option>Thailand</option><option>India </option><option>U.S</option></select></div> -->

                                        <button id="refresh-table" class="btn btn-primary"><i class="fa fa-refresh"
                                                aria-hidden="true"></i></button>
                                    </div>

                                </div>
                                <div class="list-product list-category">
                                    <table class="table thead-light" id="orders-table">

                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>#</th>
                                                <th>Comapny Name</th>
                                                <th>Product</th>
                                                <th>Quantity</th>
                                                <th>Delivery Date</th>
                                                <th>Discount %</th>
                                                <th>GST %</th>
                                                <th>Actual Amt</th>
                                                <th>Discount Amt</th>
                                                <th>GST Amt</th>
                                                <th>Total</th>
                                                <th>Status</th>
                                                <th>action</th>
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
            
            <!--Invoice Preview Modal Starts-->
            <div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="pdfModalLabel">PDF Preview</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body ">
                            <div id="preview-container"></div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" onclick="generatePdf()">Generate</button>
                            <button type="button" class="btn btn-primary" onclick="printDiv('preview-container')">Print</button>
                        </div>
                    </div>
                </div>
            </div>
            <!--Invoice Preview Modal Ends-->




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
    @include('marketing.Orders.partials.orders_js')

</body>

<script>
    
    function printDiv(className) {
            // Get the content of the div with the specified class name
            var divContents = $('#'+className).html();
            if (divContents === undefined) {
                alert('Content not found. Please check the selector.');
                return;
            }

            // Create a new window
            var printWindow = window.open('', '', 'height=400,width=800');

            // Add the content to the new window
            printWindow.document.write('<html><head><title>Print DIV Content</title>');
            printWindow.document.write('<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">');
            printWindow.document.write('</head><body >');
            printWindow.document.write(divContents);
            printWindow.document.write('</body></html>');

            // Print the content
            printWindow.document.close();
            printWindow.print();
        }
    
</script>

</html>