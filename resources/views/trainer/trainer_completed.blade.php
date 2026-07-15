<!DOCTYPE html>

<html lang="en">

<head>

    {!! $datas['headerlinks'] !!}
</head>
<style>
    thead {
    background: #006666;
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

                            <h4> Trainer Completed </h4>

                        </div>

                        <div class="col-6">

                            <ol class="breadcrumb">

                                <li class="breadcrumb-item"><a href="index.html">

                                        <svg class="stroke-icon">

                                            <use href="../assets/svg/icon-sprite.svg#stroke-home"></use>

                                        </svg></a></li>

                                <li class="breadcrumb-item">Pages</li>

                                <li class="breadcrumb-item active"> Trainer Completed </li>

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
                        <button id="trainers_completed-refresh-table" class="btn btn-primary"><i class="fa fa-refresh" aria-hidden="true"></i></button>
                      </div>
                     
                    </div>
                    <div class="list-product list-category">
                      <table class="table" id="trainers_completed-table">
                        <thead> 
                          <tr> 
                            <th>#</th>
                            <th> <span class="f-light f-w-600">School Name</span></th>
                            <th> <span class="f-light f-w-600">Assign From</span></th>
                            <th> <span class="f-light f-w-600">Assign End</span></th>
                            <th> <span class="f-light f-w-600">Start From</span></th>
                            <th> <span class="f-light f-w-600">Completed Date</span></th>
                          </tr>
                        </thead>
                      </table>
                    </div>
                  </div>
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