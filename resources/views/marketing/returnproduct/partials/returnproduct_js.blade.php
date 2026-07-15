<script>
    
    $(function(){
        
        $('#customer, #employee, #products').select2()
        $('#customer_edit, #employee_edit, #order_products_edit').select2()
        
        var table = new DataTable('#order_return-table', {
            ajax: {
                url: "{{ route('order-return-products-list.get') }}",
                type: 'POST',
                data: function(d){
                    d._token = '{{ csrf_token() }}',
                    d.customer_id = $('#customer').val(),
                    d.employee_filter = $('#employee').val(),
                    d.product_filter = $('#products').val()
                }
            },
            responsive: true,
            columns: [
                { data: 'DT_RowIndex', defaultContent: "", className: "text-center" },
                { data: 'cmpny_name', defaultContent: "", className: "text-center" },
                { data: 'product', defaultContent: "", className: "text-center" },
                { data: 'emply_name', defaultContent: "", className: "text-center" },
                { data: 'qty', defaultContent: "", className: "text-center" },
                { data: 'ttl_amt', defaultContent: "", className: "text-center" },
                { data: 'compensated_amt', defaultContent: "", className: "text-center" },
                { data: 'returned_amt', defaultContent: "", className: "text-center" },
                { data: 'actions', orderable: false, searchable: false, defaultContent: "", className: "text-center" }
            ],
            processing: true,
            serverSide: true,
            dom: 
                "<'row'<'col-sm-4'l><'col-sm-6 text-end'f><'col-sm-2'B>>" + // Buttons in one column, search in another
                "<'row'<'col-sm-12't>>" + // Table in one column
                "<'row'<'col-sm-12 text-end'p>>", // Length menu in one column, pagination in another
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="fa fa-file-excel-o"></i>',
                    titleAttr: 'Download As Excel', // Tooltip text
                    className: 'excel-btn',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5],
                        modifier: {
                            page: 'all'
                        },
                        format: {
                            body: function (data, row, column, node) {
                                if (column === 0) {
                                    // Special handling for the DT_RowIndex column
                                    return $(node).text(); // Extract plain text from the DT_RowIndex cell
                                } else {
                                    return $(node).text();
                                }
                            }
                        }
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="fa fa-file-pdf-o"></i>',
                    titleAttr: 'Download As PDF', // Tooltip text
                    className: 'pdf-btn',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5],
                        modifier: {
                            page: 'all'
                        },
                        format: {
                            body: function (data, row, column, node) {
                                if (column === 0) {
                                    // Special handling for the DT_RowIndex column
                                    return $(node).text(); // Extract plain text from the DT_RowIndex cell
                                } else {
                                    return $(node).text();
                                }
                            }
                        }
                    }
                }
            ]
        });
        
        $('.search-submit').on('click', function() {
            table.ajax.reload();
        });
        
        $('#refresh-table').on('click', function() {
            table.ajax.reload(null, false); // User paging is not reset on reload
        });

        
        $('#order_products').select2({
            placeholder: 'Select Product',
            ajax: {
                url: "{{ route('order-return-products.get') }}",
                dataType: 'json',
                delay: 250,
                type: 'post',
                data: function (params) {
                    return {
                        _token: "{{ csrf_token() }}",
                        search: params.term, // search term
                        customer_id: $('#customer').val(),
                        employee_id: $('#employee').val()
                    };
                },
                processResults: function (response) {
                    return {
                        results: response.map(function (item) {
                            return {
                                id: item.id,
                                text: item.product
                            };
                        })
                    };
                },
                cache: true
            }
        });
        
        $('#order_return_product_form').on('submit', function(e){
            e.preventDefault();
            $('.loading-overlay').addClass('is-active')
            var formData = new FormData($('#order_return_product_form')[0]);
            $.ajax({
                url: "{{ route('order-return-products-list.create') }}", // Replace with your actual route URL
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if(response.status == 200){
                        $.notify(response.message, "success");
                        $('#order_return_product_form')[0].reset()
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
                    $('.loading-overlay').removeClass('is-active');
                },
                error: function(xhr, status, error) {
                    // If there's an error in creating the product
                    $.notify("Failed to create product: " + error, "error");
                    $('.loading-overlay').removeClass('is-active');
                }
            })
        })
        
        $('#order_return_product_edit_form').on('submit', function(e){
            e.preventDefault();
            $('.loading-overlay').addClass('is-active')
            var formData = new FormData($('#order_return_product_edit_form')[0]);
            $.ajax({
                url: "{{ route('order-return-products-list.update') }}",
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if(response.status == 200){
                        $.notify(response.message, "success");
                        // $('#order_return_product_edit_form')[0].reset()
                        location.reload()
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
                    $('.loading-overlay').removeClass('is-active');
                },
                error: function(xhr, status, error) {
                    // If there's an error in creating the product
                    $.notify("Failed to create product: " + error, "error");
                    $('.loading-overlay').removeClass('is-active');
                }
            })
        })
        
        $('#order_return-table').on('click', '.delete-order_return_products', function(e) {
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
                        url: "{{ route('order-return-products-list.delete') }}",
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
    
    function GetOrdersForProducts(){
        $('#order_products_edit').html('')
        $('#order_products_edit').select2({
            placeholder: 'Select Product',
            ajax: {
                url: "{{ route('order-return-products.get') }}",
                dataType: 'json',
                delay: 250,
                type: 'post',
                data: function (params) {
                    return {
                        _token: "{{ csrf_token() }}",
                        search: params.term, // search term
                        customer_id: $('#customer_edit').val(),
                        employee_id: $('#employee_edit').val()
                    };
                },
                processResults: function (response) {
                    return {
                        results: response.map(function (item) {
                            return {
                                id: item.id,
                                text: item.product
                            };
                        })
                    };
                },
                cache: true
            }
        });
    }
    
</script>