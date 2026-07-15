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

                    <li class="breadcrumb-item active">Edit product</li>

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
                    <form class="row g-3 needs-validation custom-input" id="edit_product_form" action="javascript:void(0);" method="post">
                        @csrf
                      <div class="col-md-4 position-relative">
                        <label class="form-label" for="product_category">Product Category</label>
                        <select class="form-control" multiple name="product_category[]" id="product_category" onchange="ShowOrHideQuantity()">
                            <option value="">Select Category</option>
                            @foreach ($pro_cats as $pro_cat)
                                <option 
                                    value="{{ $pro_cat->id }}" 
                                    {{ 
                                        $product->category_id != NULL && $product->category_id != '' &&
                                        in_array($pro_cat->id, json_decode($product->category_id, true)) 
                                            ? 'selected' 
                                            : '' 
                                    }}
                                >
                                    {{ $pro_cat->category_name }}
                                </option>
                            @endforeach
                        </select>
                      </div>
                      <div class="col-md-4 position-relative">
                        <label class="form-label" for="product_name">Product name</label>
                        <input class="form-control" name="product_name" id="product_name" type="text" value="{{ $product->product }}">
                      </div>
                      <div class="col-md-4 position-relative">
                        <label class="form-label" for="product_standard">Standard</label>
                        <select class="form-control" name="product_standard" id="product_standard">
                            <option value="">Select Standard</option>
                            <option {{ $product->standard == 'PreKg' ? 'selected' : '' }} value="PreKg">PreKg</option>
                            <option {{ $product->standard == 'LKG' ? 'selected' : '' }} value="LKG">LKG</option>
                            <option {{ $product->standard == 'UKG' ? 'selected' : '' }} value="UKG">UKG</option>
                            <option {{ $product->standard == '1' ? 'selected' : '' }} value="1">1</option>
                            <option {{ $product->standard == '2' ? 'selected' : '' }} value="2">2</option>
                            <option {{ $product->standard == '3' ? 'selected' : '' }} value="3">3</option>
                            <option {{ $product->standard == '4' ? 'selected' : '' }} value="4">4</option>
                            <option {{ $product->standard == '5' ? 'selected' : '' }} value="5">5</option>
                            <option {{ $product->standard == '6' ? 'selected' : '' }} value="6">6</option>
                            <option {{ $product->standard == '7' ? 'selected' : '' }} value="7">7</option>
                            <option {{ $product->standard == '8' ? 'selected' : '' }} value="8">8</option>
                            <option {{ $product->standard == '9' ? 'selected' : '' }} value="9">9</option>
                            <option {{ $product->standard == '10' ? 'selected' : '' }} value="10">10</option>
                            <option {{ $product->standard == '11' ? 'selected' : '' }} value="11">11</option>
                            <option {{ $product->standard == '12' ? 'selected' : '' }} value="12">12</option>
                        </select>
                      </div>
                      
                      @if (!in_array('1', json_decode($product->category_id)))
                          <div class="col-xxl-4 col-sm-6 qty_div" style="display: none;">
                            <label class="form-label" for="product_qty">Quantity</label>
                            <input class="form-control" name="product_qty" id="product_qty" type="hidden" value="{{ $product->quantity }}">
                          </div>
                      @else
                          <div class="col-xxl-4 col-sm-6 qty_div">
                        <label class="form-label" for="product_qty">Quantity</label>
                        <input class="form-control" name="product_qty" id="product_qty" type="number" value="{{ $product->quantity }}">
                      </div>
                      @endif
                      
                      <div class="col-md-4 position-relative">
                        <label class="form-label" for="product_accprice">Purchase Price</label>
                        <input type="number" class="form-control" name="product_purprice" id="product_purprice"  value="{{ $product->product_purprice }}">
                      </div>
                      <div class="col-md-4 position-relative">
                        <label class="form-label" for="product_accprice">Actual Price</label>
                        <input type="number" class="form-control" name="product_accprice" id="product_accprice" value="{{ $product->actual_price }}">
                      </div>
                      <div class="col-md-4 position-relative">
                        <label class="form-label" for="product_discount">Discount Price</label>
                        <input type="number" class="form-control" name="product_discount" id="product_discount" value="{{ $product->discount }}">
                      </div>
                      <div class="col-md-4 position-relative">
                        <label class="form-label" for="product_sellprice">Sell Price</label>
                        <input type="number" readonly class="form-control" name="product_sellprice" id="product_sellprice" value="{{ $product->sell_price }}">
                        <div class="icon-container d-none">
                          <i class="loader123"></i>
                        </div>
                      </div>
                      <div class="col-md-4 position-relative">
                        <label class="form-label" for="product_gst">GST</label>
                        <input class="form-control" type="number" name="product_gst" id="product_gst" value="{{ $product->gst }}">
                      </div>
                      <div class="col-md-4 position-relative">
                        <label class="form-label" for="product_accprice">Sort Order</label>
                        <input type="number" class="form-control" name="sort_order" id="sort_order"  value="{{ $product->sort_order }}">
                      </div>
                      <div class="col-12">
                          <input type="hidden" name="id" id="id" value="{{ $product->id }}">
                        <button class="btn btn-primary" id="edit_product_btn" type="submit">Submit</button>
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
    @include('marketing.products.partials.products_js')
    

  </body>

</html>