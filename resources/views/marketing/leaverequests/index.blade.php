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

                            <h4>Leave Requests</h4>

                        </div>

                        <div class="col-6">

                            <ol class="breadcrumb">

                                <li class="breadcrumb-item"><a href="index.html">

                                        <svg class="stroke-icon">

                                            <use href="../assets/svg/icon-sprite.svg#stroke-home"></use>

                                        </svg></a></li>

                                <li class="breadcrumb-item">Pages</li>

                                <li class="breadcrumb-item active">Leave Requests</li>

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
                                <div class="list-product-header new">
                                    <div>
                                        <button id="refresh-table" class="btn btn-primary"><i class="fa fa-refresh" aria-hidden="true"></i></button>
                                    </div>

                                </div>
                                <div class="list-product list-category">
                                    <table class="table thead-light" id="leaverequests-table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Employee Name</th>
                                                <th>Employee Type</th>
                                                <th>Leave Type</th>
                                                <th>Leave Reason</th>
                                                <th>From Date</th>
                                                <th>To Date</th>
                                                <th>Reject Reason</th>
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
            <div class="modal fade" id="reason_modal" tabindex="-1" aria-labelledby="reason_modalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="reason_modalLabel">Reason</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body ">
                            <textarea name="reject_reason" id="reject_reason" cols="20" rows="5" class="form-control"></textarea>
                            <input type="hidden" name="leave_id" id="leave_id">
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" onclick="RejectLeaveRequest()">Continue...</button>
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
    @include('marketing.leaverequests.partials.leaverequest_js')

</body>

</html>