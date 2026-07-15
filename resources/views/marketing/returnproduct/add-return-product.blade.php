<!DOCTYPE html>

<html lang="en">

<head>

  {!! $datas['headerlinks'] !!}

  <style>


.col-md-3.position-relative {
    margin-right: 0% !important;
}


    .col-12.button-class {
      display: flex;
      justify-content: space-around;
      margin-top: 4%;
      margin-bottom: 2%;
    }

    .col-md-2.position-relative.one {
      margin-top: 25px;
      margin-left: 30px;

    }

    .footer {
      background-color: #fff;
      -webkit-box-shadow: 0 0 20px rgba(89, 102, 122, 0.1);
      box-shadow: 0 0 20px rgba(89, 102, 122, 0.1);
      padding: 15px;
      bottom: 0;
      left: 0;
      margin-left: 0px !important;
      -webkit-transition: 0.5s;
      transition: 0.5s;
    }

    .col-md-4.position-relative {
      margin-right: 1%;

    }

    .col-md-3.position-relative {
      margin-right: 1%;

    }



    .calendar-box {
      text-align: center;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 10px;
      background-color: white;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      width: 300px;
    }

    .calendar-title {
      font-size: 18px;
      margin-bottom: 10px;
      color: #333;
    }

    .calendar {
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      width: 300px;
      position: absolute;
      z-index: 1;
      display: none;
    }



    #prevBtn,
    #nextBtn {
      background: none;
      border: none;
      color: white;
      cursor: pointer;
      font-size: 16px;
    }

    #monthYear {
      font-size: 18px;
      font-weight: bold;
    }

    .days {
      display: grid;
      grid-template-columns: repeat(7, 1fr);
      gap: 5px;
      padding: 10px;
    }

    .day {
      padding: 10px;
      text-align: center;
      border-radius: 5px;
      cursor: pointer;
    }

    .day.current {
      background-color: #3498db;
      color: white;
    }

    .day.selected {
      background-color: #2ecc71;
      color: white;
    }

    #dateInput {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 14px;
      outline: none;
      cursor: pointer;
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

              <h4>Add Return products</h4>

            </div>

            <div class="col-6">

              <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="index.html">

                    <svg class="stroke-icon">

                      <use href="../assets/svg/icon-sprite.svg#stroke-home"></use>

                    </svg></a></li>

                <li class="breadcrumb-item">Pages</li>

                <li class="breadcrumb-item active">Add Return products</li>

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
                <form class="row g-3 needs-validation custom-input" id="order_return_product_form" method="post">
                    @csrf
                  <div class="col-md-3 position-relative">
                    <label class="form-label" for="customer">Company name</label>
                    <select id="customer" name="customer" class="form-control">
                        <option value="">Company Name</option>
                        @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->company_name }}</option>
                        @endforeach
                    </select>
                  </div>
                  <div class="col-md-3 position-relative">
                    <label class="form-label" for="employee">Employee</label>
                    <select id="employee" name="employee" class="form-control">
                        <option value="">Employee</option>
                        @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->employee_name }}</option>
                        @endforeach
                    </select>
                  </div>
                  <div class="col-md-3 position-relative">
                        <label class="form-label" for="order_products">Ordered Products</label>
                        <select class="form-select" name="order_products" id="order_products">
                          <option value="">Products</option>
                        </select>
                    </div>
                <div class="col-md-3 position-relative">
                  <label class="form-label" for="Quantity">Quantity</label>
                  <input type="number" name="quantity" id="quantity" class="form-control" placeholder="Quantity">
                </div>
                
                    <div class="col-12 button-class">
                      <button class="btn btn-primary" id="order_return_product_btn" type="submit">Submit</button>
                    </div>
                </form>
              </div>
            </div>

          </div>


        </div>
      </div>

    </div>



  </div>

  </div>

  {!! $datas['scriptlinks'] !!}
  @include('marketing.returnproduct.partials.returnproduct_js')

</body>

</html>