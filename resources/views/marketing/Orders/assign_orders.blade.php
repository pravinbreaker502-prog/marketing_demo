<!DOCTYPE html>

<html lang="en">

    <head>

    {!! $datas['headerlinks'] !!}

    


    <style>

.container.mt-5 {
    padding-top: 60px !important;
}

.arrow {
    position: absolute;
    content: "";
    margin-top: 10px;
    margin-left: 10px;
    border-left: 10px solid #fdfdfd;
    border-right: 10px solid #fff6f6;
    border-bottom: 10px solid #85515100;
    margin-top: 5px;
    background-color: #a39696;
}

.drop {
    position: absolute;
    content: "";
    margin-top: 9px;
    transform: skewX(10deg);
    margin-left: -21px;
    width: 360px;
    padding: 7px 5px 14px 15px;
    background: #bdbaba;
    padding-bottom: 11px;
}


.pic {
  background:grey;
  width:0px;
  height:0px;
  margin-top:60px;
  margin-left:60px;
}

.drop .line {
    position: relative;
    background: #9f8c8c00;
    height: 9px;
    padding-right: 145px;
    margin-top: 8px;
    margin-left: 67px;
    border-radius: 0px;
    padding-bottom: 12px;
    font-size: 14px;
}
/*End Notification Info-Box outer frame*/






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

                  <h4> Assign orders</h4>

                </div>

                <div class="col-6">

                  <ol class="breadcrumb">

                    <li class="breadcrumb-item"><a href="index.html">                                       

                        <svg class="stroke-icon">

                          <use href="../assets/svg/icon-sprite.svg#stroke-home"></use>

                        </svg></a></li>

                    <li class="breadcrumb-item">Pages</li>

                    <li class="breadcrumb-item active"> Assign orders</li>

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
                    <form class="row g-3 needs-validation custom-input" method="post" id="create_orders_form">
                        @csrf
                    <div class="col-md-3 position-relative " >
                    <label class="form-label" for="Products">Company name</label>
                    <select id="customer" name="customer" class="form-control" onchange="GetTheCreditOrDebitBalance(this.value)">
                        <option value="">Company Name</option>
                        @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->company_name }}</option>
                        @endforeach
                    </select>
                    <div class="arrow" style="display:none;">
                        <div class="drop">
                            <div class="line showamt_message" style="display:contents;">Return balance amount of need to settle to customer</div><br>
                            <div class="line showamt_amount" style="display:contents;">Balance Amount : ₹ 1000  </div><br>
                        </div>
                    </div>
                      </div>
                      <div class="col-md-3 position-relative">
                    <label class="form-label" for="Products">Employee</label>
                    <select id="employee" name="employee" class="form-control">
                        <option value="">Employee</option>
                        @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->employee_name }}</option>
                        @endforeach
                    </select>
                      </div>
                    <!--  <div class="col-md-3 position-relative">-->
                    <!--<label class="form-label" for="product_category">Product Category</label>-->
                    <!--    <select class="form-control" name="product_category" id="product_category" onchange="GenerateTheOrderProductsList(this.value)">-->
                    <!--        <option value="">Select Category</option>-->
                    <!--        @foreach ($pro_cats as $pro_cat)-->
                    <!--            <option value="{{ $pro_cat->id }}">{{ $pro_cat->category_name }}</option>-->
                    <!--        @endforeach-->
                    <!--    </select>-->
                    <!--  </div>-->
                    
                     

                


 <div class="container mt-5">
    <div class="generated_products">
    </div>
  <div class="jumbotron mt-2" data-x-wrapper="employees">
        <div class="employee-row d-flex my-2" data-x-group>
            <div class="col-md-3 position-relative ">
            <label class="form-label show-alert show-alert__success" for="Products">Products</label>
                <select class="form-control product-select productsss" name="product[]" id="product_0">
                </select>
        
            </div>
            

            <div class="col-md-3 position-relative">
            <label class="form-label" for="Quantity">Quantity</label>
                <input type="number" name="quantity[]"  id="quantity_0" class="form-control" placeholder="Quantity">
            </div>

            <div class="col-xxl-2 col-sm-6">
                <label class="form-label" for="delivary_date">Delivery Date</label>
                <input id="delivery_date_0" name="delivery_date[]" class="delivery-date form-control" type="date">
            </div>




                      <div class="col-xxl-2 col-sm-6" style="margin-left: 1%;">
                        <label class="form-label" for="product_discount">Discount %</label>
                        <input type="number" class="form-control" name="product_discount[]" id="product_discount_0" autocomplete="off"placeholder="Discount %">
                      </div>

            <div class="col-md-2 position-relative one">
                <button type="button" class="btn btn-danger" data-remove-btn>-</button>
                <button type="button" class="btn btn-success" data-add-btn>+</button>
            </div>
        </div>
    </div>
                      
                      <div class="col-12 button-class">
                        <button class="btn btn-primary" id="create_orders_btn" type="submit">Submit</button>
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

        <!-- <footer class="footer">

          <div class="container-fluid">

            <div class="row">

              <div class="col-md-12 footer-copyright text-center">

                <p class="mb-0">Copyright {{ date('Y') }} © Developed by D2C </p>

              </div>

            </div>

          </div>

        </footer> -->

      </div>

    </div>

    {!! $datas['scriptlinks'] !!}
    @include('marketing.Orders.partials.orders_js')
  
</body>

</html>