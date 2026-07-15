<script>
    $(function(){
        
        var table = new DataTable('#orders-table', {
            ajax: {
                url: "{{ route('verifier-verified-orders.get') }}", // Use Laravel's route helper to generate the URL
                type: 'POST',
                data: function(d){
                    d._token = '{{ csrf_token() }}'
                }
            },
            columns: [
                { data: 'DT_RowIndex', defaultContent: "", className: "text-center" },
                { data: 'cmpny_name', defaultContent: "", className: "text-center" },
                { data: 'product', defaultContent: "", className: "text-center" },
                { data: 'qty', defaultContent: "", className: "text-center" },
                { data: 'del_date', defaultContent: "", className: "text-center" },
                { data: 'order_status', defaultContent: "", className: "text-center" },
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
        
    });
    
    function ChangeTheOrderStatus(id){
        var table = new DataTable('#orders-table');
        Swal.fire({
                title: 'Are you sure?',
                text: "Is order is dispatched?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Dispatched!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('verifier-verified-orders.update') }}",
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: id
                        },
                        success: function(response) {
                            if(response.status == 200){
                                Swal.fire(
                                    'Dispatched!',
                                    'Your status has been updated.',
                                    'success'
                                );
                                table.ajax.reload(null, false);
                            }
                            if(response.status == 400){
                                Swal.fire(
                                    'Error!',
                                    'There was an error update the product.',
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
    }
    
</script>