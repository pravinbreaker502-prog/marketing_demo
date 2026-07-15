<script>
    $(function(){
        
        $('#customer').select2({
            placeholder: "Company Name"
        })
        
        $('.close').on('click', function(){
            $('.modal').modal('hide')
        })
        
        var table = new DataTable('#Invoices-table', {
            ajax: {
                url: "{{ route('invoices.get') }}", // Use Laravel's route helper to generate the URL
                type: 'POST',
                data: function(d){
                    d._token = '{{ csrf_token() }}',
                    d.customer_id = $('#customer').val(),
                    d.from_date = $('#from_date').val(),
                    d.to_date = $('#to_date').val()
                }
            },
            columns: [
                { data: 'DT_RowIndex', defaultContent: "", className: "text-center" },
                { data: 'cmpny_name', defaultContent: "", className: "text-center" },
                { data: 'invoice_number', defaultContent: "", className: "text-center" },
                { data: 'invoice_date', defaultContent: "", className: "text-center" },
                { data: 'invoice_pre', defaultContent: "", className: "text-center" },
                { data: 'dwnld_invoice', defaultContent: "", className: "text-center" },
                { data: 'payment_status_show', defaultContent: "", className: "text-center" },
                { data: 'pay_btn', defaultContent: "", className: "text-center" },
                { data: 'actions', defaultContent: "", className: "text-center" }
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
            table.ajax.reload();
        });
        
        $('input[id="date_range"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });
        
        $('input[id="date_range"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            $('#from_date').val(picker.startDate.format('MM/DD/YYYY'))
            $('#to_date').val(picker.endDate.format('MM/DD/YYYY'))
        });
        
        $('#Invoices-table').on('click', '.delete-invoice', function(e) {
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
                        url: "{{ route('invoices.delete') }}",
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
                                    'There was an error deleting the invoice.',
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
                            $.notify("Failed to delete invoice: " + error, "error");
                            $('.loading-overlay').removeClass('is-active');
                        }
                    });
                }
            });
        });
        
    })
    
    function PaymentForInvoice(id){
        $('#invoice_id').val(id)
        $.ajax({
            url: "{{ route('invoice-amount.pay') }}", // Add the URL here
            type: "post",
            data: {
                _token: "{{ csrf_token() }}",
                history: "get_invoice_data",
                invoice_id: id,
            },
            success: function(res) {
                // console.log(res);
                
                if(res.status == 200){
                    $('#pending_amt_show').text(res.data.pending_amount != null ? res.data.pending_amount : 'NIL')
                    $('#invoice_amount').val('')
                    $('.loading-overlay').removeClass('is-active');
                    $('#payment_modal').modal('show')
                }
                if(res.status == 400){
                    $.notify(response.message, "error");
                    $('.loading-overlay').removeClass('is-active');
                }
                if(res.status == 402){
                    Swal.fire(
                        'Warning!',
                        res.message,
                        'warning'
                    );
                    $('.loading-overlay').removeClass('is-active');
                }
                if(res.status == 500){
                    $.notify(response.error, "error");
                    $('.loading-overlay').removeClass('is-active');
                }
                
            },
            error: function(xhr, status, error) {
                // Notify the user of the error
                $.notify("Failed to create product: " + error, "error");
                // Remove the loading overlay on error
                $('.loading-overlay').removeClass('is-active');
            }
        });
    }
    
    function InvoiceFullPayment() {
        // Add the loading overlay
        $('.loading-overlay').addClass('is-active');
    
        // Check if the invoice amount input is empty
        if ($('#invoice_amount').val() == '') {
            $("#invoice_amount").notify('Please enter amount', {
                className: "error",
                autoHideDelay: 2000
            });
            $('.loading-overlay').removeClass('is-active');
            return false;
        }
    
        // Make the AJAX request
        $.ajax({
            url: "{{ route('invoice-amount.pay') }}", // Add the URL here
            type: "post",
            data: {
                _token: "{{ csrf_token() }}",
                invoice_id: $('#invoice_id').val(),
                invoice_amount: $('#invoice_amount').val()
            },
            success: function(res) {
                console.log(res);
                if(res.status == 200){
                   Swal.fire(
                        'Success!',
                        res.message,
                        'success'
                    );
                    $('.loading-overlay').removeClass('is-active');
                    $('#payment_modal').modal('hide')
                }
                if(res.status == 400){
                    $.notify(response.message, "error");
                    $('.loading-overlay').removeClass('is-active');
                }
                if(res.status == 402){
                    Swal.fire(
                        'Warning!',
                        res.message,
                        'warning'
                    );
                    $('.loading-overlay').removeClass('is-active');
                }
                if(res.status == 500){
                    $.notify(response.error, "error");
                    $('.loading-overlay').removeClass('is-active');
                }
            },
            error: function(xhr, status, error) {
                // Notify the user of the error
                $.notify("Failed to create product: " + error, "error");
                // Remove the loading overlay on error
                $('.loading-overlay').removeClass('is-active');
            }
        });
    }
    
    function InvoiceSplitPayment() {
        // Add the loading overlay
        $('.loading-overlay').addClass('is-active');
    
        // Check if the invoice amount input is empty
        if ($('#invoice_amount').val() == '') {
            $("#invoice_amount").notify('Please enter amount', {
                className: "error",
                autoHideDelay: 2000
            });
            $('.loading-overlay').removeClass('is-active');
            return false;
        }
    
        // Make the AJAX request
        $.ajax({
            url: "{{ route('split-invoice-amount.pay') }}", // Add the URL here
            type: "post",
            data: {
                _token: "{{ csrf_token() }}",
                invoice_id: $('#invoice_id').val(),
                invoice_amount: $('#invoice_amount').val()
            },
            success: function(res) {
                console.log(res);
                if(res.status == 200){
                   Swal.fire(
                        'Success!',
                        res.message,
                        'success'
                    );
                    $('.loading-overlay').removeClass('is-active');
                    $('#payment_modal').modal('hide')
                }
                if(res.status == 400){
                    $.notify(response.message, "error");
                    $('.loading-overlay').removeClass('is-active');
                }
                if(res.status == 402){
                    Swal.fire(
                        'Warning!',
                        res.message,
                        'warning'
                    );
                    $('.loading-overlay').removeClass('is-active');
                }
                if(res.status == 500){
                    $.notify(response.error, "error");
                    $('.loading-overlay').removeClass('is-active');
                }
            },
            error: function(xhr, status, error) {
                // Notify the user of the error
                $.notify("Failed to create product: " + error, "error");
                // Remove the loading overlay on error
                $('.loading-overlay').removeClass('is-active');
            }
        });
    }
    
</script>