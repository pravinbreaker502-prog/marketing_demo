<script>
    $(function(){
        
        $('.close').on('click', function(){
            $('#pdfModal').modal('hide')
        })
        
        $('#customer').select2({
            placeholder: "Company Name"
        })
        
        var table = new DataTable('#orders-table', {
            ajax: {
                url: "{{ route('orders.get') }}", // Use Laravel's route helper to generate the URL
                type: 'POST',
                data: function(d){
                    d._token = '{{ csrf_token() }}',
                    d.customer_id = $('#customer').val()
                }
            },
            columns: [
                { data: 'order_checkbox', defaultContent: "", className: "text-center" },
                { data: 'DT_RowIndex', defaultContent: "", className: "text-center" },
                { data: 'cmpny_name', defaultContent: "", className: "text-center" },
                { data: 'product', defaultContent: "", className: "text-center" },
                { data: 'qty', defaultContent: "", className: "text-center" },
                { data: 'del_date', defaultContent: "", className: "text-center" },
                { data: 'disc_per', defaultContent: "", className: "text-center" },
                { data: 'order_status', defaultContent: "", className: "text-center" },
                { data: 'actions', orderable: false, searchable: false, defaultContent: "", className: "text-center" }
            ],
            processing: true,
            serverSide: true,
            language: {
                // processing: '<div class="custom-spinner"></div>'
            }
        });
        
        $('.search-submit').on('click', function() {
            table.ajax.reload();
        });
        
        $('#refresh-table').on('click', function() {
            table.ajax.reload(null, false); // User paging is not reset on reload
        });
        
        const wrapper = '[data-x-wrapper="employees"]';
        const group = '[data-x-group]';
        const addBtn = '[data-add-btn]';
        const removeBtn = '[data-remove-btn]';

        ProductSelect2(0)
        
        $(document).on('click', addBtn, function() {
            const newRow = $(group).first().clone();
            var ttl_leng = $('.employee-row').length;
            $(wrapper).append(`<div class="employee-row d-flex my-2" data-x-group>
            <div class="col-md-3 position-relative">
            <label class="form-label" for="Products">Products</label>
                <select class="form-control product-select productsss" name="product[]" id="product_${ttl_leng}">
                </select>
            </div>

            <div class="col-md-3 position-relative">
            <label class="form-label" for="Quantity">Quantity</label>
                <input type="number" name="quantity[]"  id="quantity_${ttl_leng}" class="form-control" placeholder="Quantity">
            </div>

            <div class="col-xxl-2 col-sm-6">
                <label class="form-label" for="delivary_date">Delivery Date</label>
                <input id="delivery_date_${ttl_leng}" name="delivery_date[]" class="delivery-date form-control" type="date">
            </div>




                      <div class="col-xxl-2 col-sm-6" style="margin-left: 1%;">
                        <label class="form-label" for="product_discount">Discount %</label>
                        <input type="number" class="form-control" name="product_discount[]" id="product_discount_${ttl_leng}" autocomplete="off" placeholder="Discount %">
                      </div>

            <div class="col-md-2 position-relative one">
                <button type="button" class="btn btn-danger" data-remove-btn>-</button>
                <button type="button" class="btn btn-success" data-add-btn>+</button>
            </div>
        </div>`);
            ProductSelect2(ttl_leng)
        });

        $(document).on('click', removeBtn, function() {
            if ($(group).length > 1) { 
                $(this).closest(group).remove();
                // ProductSelect2()
            }
        });
        
        
        function ProductSelect2(id){
            // Add an empty option to the select element
            $('#product_' + id).html('<option value="">Select a product...</option>');
        
            $('#product_' + id).select2({
                placeholder: 'Search for a product...',
                ajax: {
                    url: "{{ route('products-for-input.get') }}",
                    dataType: 'json',
                    delay: 250,
                    type: "post",
                    data: function (params) {
                        return {
                            search: params.term,
                            _token: "{{ csrf_token() }}"
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    id: item.id,
                                    text: item.product
                                };
                            })
                        };
                    },
                    cache: true
                },
                // Set the empty option as the default selected option
                allowClear: true,
                placeholder: 'Select a product...'
            }).val('').trigger('change');
        }
        
        $('#create_orders_form').on('submit', function(e){
            e.preventDefault();
            $('.loading-overlay').addClass('is-active')
            var formData = new FormData($('#create_orders_form')[0]);
            $.ajax({
                url: "{{ route('orders.create') }}", // Replace with your actual route URL
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if(response.status == 200){
                        $.notify(response.message, "success");
                    }
                    if(response.status == 400){
                        $.notify(response.message, "error");
                    }
                    if (response.status == 401) {
                        var error_json = response.errors;
                        for (var field in error_json) {
                            if (error_json.hasOwnProperty(field)) {
                                var errors = error_json[field];
                                for (var i = 0; i < errors.length; i++) {
                                    var error = errors[i];
                                    console.log('Field: ' + field + ', Error: ' + error);
                                    
                                    // Adjust field name for array inputs
                                    var fieldId = field.replace('.', '_');
                                    console.log(fieldId)
                    
                                    $("#" + fieldId).notify(error, {
                                        className: "error",
                                        autoHideDelay: 2000
                                    });
                    
                                    // Break out of the loop after displaying the first error
                                    $('.loading-overlay').removeClass('is-active');
                                    $('#' + fieldId).focus();
                                    return false;
                                }
                            }
                        }
                    }
                    if(response.status == 500){
                        $.notify(response.error, "error");
                        $('.loading-overlay').removeClass('is-active');
                    }
                    $('#create_orders_form')[0].reset()
                    $('.loading-overlay').removeClass('is-active');
                },
                error: function(xhr, status, error) {
                    // If there's an error in creating the product
                    $.notify("Failed to create product: " + error, "error");
                    $('.loading-overlay').removeClass('is-active');
                }
            })
        })
        
        $('#edit_orders_form').on('submit', function(e){
            e.preventDefault();
            $('.loading-overlay').addClass('is-active')
            var formData = new FormData($('#edit_orders_form')[0]);
            $.ajax({
                url: "{{ route('orders.update') }}", // Replace with your actual route URL
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if(response.status == 200){
                        $.notify(response.message, "success");
                        $('#edit_orders_form')[0].reset()
                        $('.loading-overlay').removeClass('is-active');
                    }
                    if(response.status == 400){
                        $.notify(response.message, "error");
                    }
                    if (response.status == 401) {
                        var error_json = response.errors;
                        for (var field in error_json) {
                            if (error_json.hasOwnProperty(field)) {
                                var errors = error_json[field];
                                for (var i = 0; i < errors.length; i++) {
                                    var error = errors[i];
                                    console.log('Field: ' + field + ', Error: ' + error);
                                    $("#" + field).notify(error, {
                                        className: "error",
                                        autoHideDelay: 2000
                                    });
                                    // Break out of the loop after displaying the first error
                                    $('.loading-overlay').removeClass('is-active');
                                    $('#'+field).focus()
                                    return false;
                                }
                            }
                        }
                    }
                    if(response.status == 500){
                        $.notify(response.error, "error");
                        $('.loading-overlay').removeClass('is-active');
                    }
                    $('#edit_orders_form')[0].reset()
                    $('.loading-overlay').removeClass('is-active');
                    location.reload()
                },
                error: function(xhr, status, error) {
                    // If there's an error in creating the product
                    $.notify("Failed to create product: " + error, "error");
                    $('.loading-overlay').removeClass('is-active');
                }
            })
        })
        
        $('.product_001').select2()
        
        $('#orders-table').on('click', '.delete-order', function(e) {
            e.preventDefault();
            var orderId = $(this).data('id');
            $('.loading-overlay').addClass('is-active')
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('orders.delete') }}",
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: orderId
                        },
                        success: function(response) {
                            if(response.status == 200){
                                Swal.fire(
                                    'Deleted!',
                                    'Your file has been deleted.',
                                    'success'
                                );
                                table.ajax.reload(null, false);
                            }
                            if(response.status == 400){
                                Swal.fire(
                                    'Error!',
                                    'There was an error deleting the product.',
                                    'error'
                                );
                            }
                            if(response.status == 500){
                                $.notify(response.error, "error");
                                $('.loading-overlay').removeClass('is-active');
                            }
                            if(response.status == 401){
                                Swal.fire(
                                    'Error!',
                                    'There was a validation error is occured.',
                                    'error'
                                );
                            }
                            $('.loading-overlay').removeClass('is-active');
                        },
                        error: function(xhr, status, error) {
                            $.notify("Failed to delete order: " + error, "error");
                            $('.loading-overlay').removeClass('is-active');
                        }
                    });
                }
            });
        });
        
    })
    
    function ChangeOrderStatus(val,id){
        // alert(val)
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to chage it!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, change!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('order-status.change') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        status: val
                    },
                    success: function(response) {
                        if(response.status == 200){
                            Swal.fire(
                                'Updated!',
                                'Your status has been changed.',
                                'success'
                            );
                            // table.ajax.reload(null, false);
                            $('#order_status_drop_0'+id).removeClass()
                            switch(val){
                                case 'pending':
                                    $('#order_status_drop_0'+id).addClass('pending')
                                    break;
                                case 'packed':
                                    $('#order_status_drop_0'+id).addClass('packed');
                                    break;
                                case 'verified':
                                    $('#order_status_drop_0'+id).addClass('verified');
                                    break;
                                case 'dispatched':
                                    $('#order_status_drop_0'+id).addClass('dispatched');
                                    break;
                                case 'delivered':
                                    $('#order_status_drop_0'+id).addClass('delivered');
                                    break;
                                case 'invoiced':
                                    $('#order_status_drop_0'+id).addClass('invoiced');
                                    break;
                                case 'cancelled':
                                    $('#order_status_drop_0'+id).addClass('cancelled');
                                    break;
                                default:
                                    $('#order_status_drop_0'+id).addClass('placeholder');
                                    break;
                            }
                        }
                        if(response.status == 400){
                            Swal.fire(
                                'Error!',
                                'There was an error change the status.',
                                'error'
                            );
                        }
                        if(response.status == 500){
                            $.notify(response.error, "error");
                            $('.loading-overlay').removeClass('is-active');
                        }
                        if(response.status == 401){
                            Swal.fire(
                                'Error!',
                                'There was a validation error is occured.',
                                'error'
                            );
                        }
                        $('.loading-overlay').removeClass('is-active');
                    },
                    error: function(xhr, status, error) {
                        $.notify("Failed to change status: " + error, "error");
                        $('.loading-overlay').removeClass('is-active');
                    }
                });
            }
        });
    }
    
    function updatePreview() {
        var selectedOrderIds = $("input[name='order_ids_for_generate_invoice[]']:checked").map(function() {
            return $(this).val();
        }).get();
        if(selectedOrderIds.length == 0){
            $.notify('Please select orders to generate invoice', "error");
            return false;
        }
        $.ajax({
            url: "{{ route('invoice.preview') }}",
            method: 'POST',
            data: {
                customer_id: $('#customer').val(),
                order_ids: selectedOrderIds,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if(response.status == 400){
                    $.notify(response.message, "error");
                    return false;
                }
                $('#preview-container').html(response.preview_html);
                $('#pdfModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }
    
    function generatePdf(){
        var selectedOrderIds = $("input[name='order_ids_for_generate_invoice[]']:checked").map(function() {
            return $(this).val();
        }).get();
        if(selectedOrderIds.length == 0){
            $.notify('Please select orders to generate invoice', "error");
            return false;
        }
        $.ajax({
            url: "{{ route('invoice.generate') }}",
            method: 'POST',
            data: {
                customer_id: $('#customer').val(),
                order_ids: selectedOrderIds,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                // $('#preview-container').html(response.preview_html);
                // $('#pdfModal').modal('show');
                if(response.status == 200){
                    Swal.fire({
                title: 'Are you want to download?',
                // text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: `<a href="${response.url}" style="color: #fcfcfc;" download>Yes, Download!</>`,
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire(
                        'Downloaded!',
                        'Invoice has been downloaded.',
                        'success'
                    );
                }
            });
                }
                if(response.status == 400){
                    $.notify(response.message, "error");
                    return false;
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }
    
</script>