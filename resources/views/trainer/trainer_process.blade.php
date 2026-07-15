<!DOCTYPE html>

<html lang="en">

<head>

    {!! $datas['headerlinks'] !!}
</head>

<style>
.form-label {
    margin-bottom: 23px;
    filter: drop-shadow(2px 4px 6px #006666);
    font-size: 21px;
    /* display: block; */
    display: flex;
    justify-content: space-around;
}
.modal-content.one {
    box-shadow: rgba(3, 102, 214, 0.3) 0px 0px 0px 3px;
    border-radius: 15px;
}
    thead {
    background: #006666;
}
input#Teachers {
    box-shadow: rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset;
    border-radius: 12px;
}
.modal-header {
   
    border-bottom: 0px solid #dee2e6 !important;

}
.modal-footer {


    border-top: 0px solid #dee2e6 !important;
}
div .action {
 
    padding-bottom: 12px;
    display: flex;
    justify-content: flex-end;
}
span.f-light.f-w-600 {
    color: white;
}
a:hover {
    color: #0056b3;
    text-decoration: none;
}

</style>

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

                            <h4>Trainer process</h4>

                        </div>

                        <div class="col-6">

                            <ol class="breadcrumb">

                                <li class="breadcrumb-item"><a href="index.html">

                                        <svg class="stroke-icon">

                                            <use href="../assets/svg/icon-sprite.svg#stroke-home"></use>

                                        </svg></a></li>

                                <li class="breadcrumb-item">Pages</li>

                                <li class="breadcrumb-item active"> Trainer process </li>

                            </ol>

                        </div>

                    </div>

                </div>

            </div>

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
                                    <button id="trainers_process-refresh-table" class="btn btn-primary"><i class="fa fa-refresh" aria-hidden="true"></i></button>
                                  </div>
                                 
                                </div>
                                <div class="list-product list-category">
                                    <table class="table" id="trainers_process-table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th> <span class="f-light f-w-600">School Name</span></th>
                                                <th> <span class="f-light f-w-600">Assign From</span></th>
                                                <th> <span class="f-light f-w-600">Assign End</span></th>
                                                <th> <span class="f-light f-w-600">Started From</span></th>
                                                <th> <span class="f-light f-w-600">Action</span></th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Container-fluid Ends-->
        </div>


        <div class="modal fade" id="reject_reason_modal" tabindex="-1" role="dialog" aria-labelledby="reject_reason_modal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Reason to Cancel</h5>
                        <button class="btn-close py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="row g-3 needs-validation" action="javascript:void(0);">
                            <div class="col-md-12">
                                <textarea class="form-control" id="reject_reason" name="reject_reason" rows="3"></textarea>
                            </div>


                            <div class="modal-footer">
                                <input type="hidden" name="assign_work_id" id="assign_work_id">
                                <button class="btn btn-primary" onclick="RejectReasonUpdate()" type="button">Save</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        
        
        <div class="modal fade" id="no_of_teachers_modal" tabindex="-1" role="dialog" aria-labelledby="no_of_teachers_modal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content one" >
            <div class="modal-header">
                        <button class="btn-close py-0" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
     
     <div class="modal-body">
         <form class="row g-3 needs-validation" novalidate="">
             <div class="col-md-12">

             <label class="form-label" for="Teachers">Number Of Teachers </label>
             <input type="number" name="no_of_teachers"  id="no_of_teachers" class="form-control" placeholder="Number Of Teachers">


             </div>


             <div class="modal-footer">
                 <input type="hidden" name="assign_work_id1" id="assign_work_id1">
                 <button class="btn btn-primary" onclick="TraineeWorkstoComplete()" type="button">Submit</button>

             </div>
         </form>
     </div>

 </div>

   
            </div>
        </div>
    </div>


    </div>

    </div>

    {!! $datas['scriptlinks'] !!}
    @include('trainer.partials.trainer_follow_js')

</body>

</html>