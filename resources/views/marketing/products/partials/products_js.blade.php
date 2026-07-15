<script>
    $(function(){
        
        $('#product_category').select2({
            closeOnSelect: false
        })
        
        var table = new DataTable('#product-table', {
            ajax: {
                url: "{{ route('products.get') }}", // Use Laravel's route helper to generate the URL
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}' // Ensure CSRF token is included if needed
                }
            },
            columns: [
                { data: 'DT_RowIndex', defaultContent: "", className: "text-center" },
                { data: 'product_name', defaultContent: "", className: "text-center" },
                { data: 'product_caregory', defaultContent: "", className: "text-center" },
                { data: 'qty', defaultContent: "", className: "text-center" },
                { data: 'acc_price', defaultContent: "", className: "text-center" },
                { data: 'dis_per', defaultContent: "", className: "text-center" },
                { data: 'sell', defaultContent: "", className: "text-center" },
                { data: 'gst_per', defaultContent: "", className: "text-center" },
                { data: 'actions', orderable: false, searchable: false, defaultContent: "", className: "text-center" }
            ],
            processing: true,
            serverSide: true,
            language: {
                // processing: '<div class="custom-spinner"></div>'
            }
        });
        
        $('#refresh-table').on('click', function() {
            table.ajax.reload(null, false); // User paging is not reset on reload
        });
        
        $('#product_accprice, #product_discount').on('keyup change', function(){
            $('.icon-container').removeClass('d-none')
            if($('#product_accprice').val() == "" || $('#product_accprice').val() == null){
                $('#product_sellprice').val('')
                $('#product_discount').val('')
                $('#product_accprice').focus()
                $("#product_accprice").notify("Need to enter the accutal price first", {
                    className: "warn",
                    autoHideDelay: 2000
                });
                return false;
            }
            if($('#product_discount').val() == "" || $('#product_discount').val() == null || $('#product_discount').val() > 100){
                $('#product_discount').val('')
                var sell_price = $('#product_accprice').val();
            }else{
                if($('#product_discount').val() == 0){
                    var sell_price = $('#product_accprice').val();
                }else{
                    var sell_price = $('#product_accprice').val()-(($('#product_discount').val()/100)*$('#product_accprice').val());
                }
            }
            // alert(sell_price);
            $('#product_sellprice').val(sell_price)
            $('.icon-container').addClass('d-none')
        })
            
        $('#create_product_form').on('submit', function(e){
            e.preventDefault();
            $('.loading-overlay').addClass('is-active')
            var formData = new FormData($('#create_product_form')[0]);
            $.ajax({
                url: "{{ route('products.create') }}", // Replace with your actual route URL
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
                    $('#create_product_form')[0].reset()
                    $('.loading-overlay').removeClass('is-active');
                },
                error: function(xhr, status, error) {
                    // If there's an error in creating the product
                    $.notify("Failed to create product: " + error, "error");
                    $('.loading-overlay').removeClass('is-active');
                }
            })
        })
        
        $('#edit_product_form').on('submit', function(e){
            e.preventDefault();
            $('.loading-overlay').addClass('is-active')
            var formData = new FormData($('#edit_product_form')[0]);
            $.ajax({
                url: "{{ route('products.update') }}", // Replace with your actual route URL
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if(response.status == 200){
                        $.notify(response.message, "success");
                        $('#edit_product_form')[0].reset()
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
                    $('#edit_product_form')[0].reset()
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
        
        $('#product-table').on('click', '.delete-product', function(e) {
            e.preventDefault();
            var productId = $(this).data('id');
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
                        url: "{{ route('products.delete') }}", // Replace with your delete route
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: productId
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
                            $.notify("Failed to create product: " + error, "error");
                            $('.loading-overlay').removeClass('is-active');
                        }
                    });
                }
            });
        });
        
    })
    
    function ShowOrHideQuantity(){
        $.ajax({
            url: "{{ route('show-or-hide-quantity.get') }}", // Replace with your actual route URL
            method: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                ids: $('#product_category').val()
            },
            success: function(response) {
                if(response.status == 200){
                    if(response.type == 'hide'){
                        $('#product_qty').val(response.qty)
                        $('#product_qty').attr('type', 'hidden')
                        $('.qty_div').hide()
                    }else if(response.type == 'show'){
                        $('#product_qty').val('')
                        $('#product_qty').attr('type', 'number')
                        $('.qty_div').show()
                    }else{
                        return false;
                    }
                }
                if(response.status == 500){
                    $.notify(response.error, "error");
                }
            },
            error: function(xhr, status, error) {
                // If there's an error in creating the product
                $.notify("Error: " + error, "error");
            }
        })
    }
</script>