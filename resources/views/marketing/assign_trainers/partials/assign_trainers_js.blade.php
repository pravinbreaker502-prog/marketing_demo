<script>
    
    $(function(){
        
        $('.close').on('click', function(){
            $('#pdfModal').modal('hide')
        })
        
        $('#customer').select2()
        $('#trainers, #employee_filter, #filter_process_status').select2({
            closeOnSelect: false,
            placeHolder: 'Select Trainers'
        })
        
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
        
        var table = new DataTable('#assign_trainers-table', {
            ajax: {
                url: "{{ route('assign-trainers.get') }}", // Use Laravel's route helper to generate the URL
                type: 'POST',
                data: function(d){
                    d._token = '{{ csrf_token() }}',
                    d.customer = $('#customer').val(),
                    d.from_date = $('#from_date').val(),
                    d.to_date = $('#to_date').val(),
                    d.filter_process_status = $('#filter_process_status').val(),
                    d.employee_filter = $('#employee_filter').val()
                }
            },
            columns: [
                { data: 'DT_RowIndex', defaultContent: "", className: "text-center" },
                { data: 'cmpny_name', defaultContent: "", className: "text-center" },
                { data: 'emply_name', defaultContent: "", className: "text-center" },
                { data: 'from_date', defaultContent: "", className: "text-center" },
                { data: 'to_date', defaultContent: "", className: "text-center" },
                { data: 'no_of_teacherssss', defaultContent: "", className: "text-center" },
                { data: 'pros_status', defaultContent: "", className: "text-center" },
                { data: 'actions', orderable: false, searchable: false, defaultContent: "", className: "text-center" }
            ],
            processing: true,
            serverSide: true,
            dom: 
                "<'row'<'col-sm-4'l><'col-sm-6 text-end'f><'col-sm-2'B>>" + // Buttons in one column, search in another
                "<'row'<'col-sm-12't>>" + // Table in one column
                "<'row'<'col-sm-12 text-end'p>>", // Length menu in one column, pagination in another
            lengthMenu: [10, 25, 50, 100, [ -1, 'All' ]],
            buttons: [
                // {
                //     extend: 'copy',
                //     exportOptions: {
                //         columns: [1, 2, 3, 4, 5, 6, 7], // Include DT_RowIndex as the first column
                //         modifier: {
                //             page: 'all'
                //         }
                //     }
                // },
                // {
                //     extend: 'csv',
                //     exportOptions: {
                //         columns: [1, 2, 3, 4, 5, 6, 7], // Include DT_RowIndex as the first column
                //         modifier: {
                //             page: 'all'
                //         }
                //     }
                // },
                {
                    extend: 'excel',
                    text: '<i class="fa fa-file-excel-o"></i>',
                    titleAttr: 'Download As Excel', // Tooltip text
                    className: 'excel-btn',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6], // Include DT_RowIndex as the first column
                        modifier: {
                            page: 'all'
                        },
                        format: {
                            body: function (data, row, column, node) {
                                if (column === 0) {
                                    // Special handling for the DT_RowIndex column
                                    return $(node).text(); // Extract plain text from the DT_RowIndex cell
                                } else {
                                    return $(data).text();
                                }
                            }
                        }
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="fa fa-file-pdf-o"></i>',
                    titleAttr: 'Download As PDF', // Tooltip text,
                    className: 'pdf-btn',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6], // Include DT_RowIndex as the first column
                        modifier: {
                            page: 'all'
                        },
                        format: {
                            body: function (data, row, column, node) {
                                if (column === 0) {
                                    // Special handling for the DT_RowIndex column
                                    return $(node).text(); // Extract plain text from the DT_RowIndex cell
                                } else {
                                    return $(data).text();
                                }
                            }
                        }
                    }
                },
                // {
                //     extend: 'print',
                //     exportOptions: {
                //         columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12], // Include DT_RowIndex as the first column
                //         modifier: {
                //             page: 'all'
                //         },
                //         format: {
                //             body: function (data, row, column, node) {
                //                 if (column === 1) {
                //                     // Special handling for the DT_RowIndex column
                //                     return $(node).text(); // Extract plain text from the DT_RowIndex cell
                //                 } else if (column === 12) {
                //                     // Handle order_status column if needed
                //                     return $(data).find('option:selected').text();
                //                 } else {
                //                     return $(data).text();
                //                 }
                //             }
                //         }
                //     }
                // }
            ],
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
        
        $('#create_assign_trainer_form').on('submit', function(e){
            e.preventDefault();
            $('.loading-overlay').addClass('is-active')
            var formData = new FormData($('#create_assign_trainer_form')[0]);
            $.ajax({
                url: "{{ route('assign-trainers.create') }}", // Replace with your actual route URL
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if(response.status == 200){
                        $.notify(response.message, "success");
                        $('#create_assign_trainer_form')[0].reset()
                        location.reload()
                        $('.loading-overlay').removeClass('is-active');
                    }
                    if(response.status == 400){
                        $.notify(response.message, "error");
                        $('.loading-overlay').removeClass('is-active');
                    }
                    if (response.status == 401) {
                        var error_json = response.errors;
                        for (var field in error_json) {
                            if (error_json.hasOwnProperty(field)) {
                                var errors = error_json[field];
                                for (var i = 0; i < errors.length; i++) {
                                    var error = errors[i];
                                    console.log('Field: ' + field + ', Error: ' + error);
                                    if(field === 'from_date' || field === 'to_date'){
                                        $("#date_range").notify(error, {
                                            className: "error",
                                            autoHideDelay: 2000
                                        });
                                    } else if(field === 'trainers'){
                                        $("#trainers").notify(error, {
                                            className: "error",
                                            autoHideDelay: 2000
                                        });
                                    } else {
                                        $("#"+field).notify(error, {
                                            className: "error",
                                            autoHideDelay: 2000
                                        });
                                    }
                                    
                                    // Break out of the loop after displaying the first error
                                    $('.loading-overlay').removeClass('is-active');
                                    $('#'+field).focus()
                                    return false;
                                }
                            }
                        }
                        $('.loading-overlay').removeClass('is-active');
                    }
                    if(response.status == 500){
                        $.notify(response.error, "error");
                        $('.loading-overlay').removeClass('is-active');
                    }
                },
                error: function(xhr, status, error) {
                    // If there's an error in creating the product
                    $.notify("Failed to create product: " + error, "error");
                    $('.loading-overlay').removeClass('is-active');
                }
            })
        })
        
        $('#update_assign_trainer_form').on('submit', function(e){
            e.preventDefault();
            $('.loading-overlay').addClass('is-active')
            var formData = new FormData($('#update_assign_trainer_form')[0]);
            $.ajax({
                url: "{{ route('assign-trainers.update') }}", // Replace with your actual route URL
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if(response.status == 200){
                        $.notify(response.message, "success");
                        $('#update_assign_trainer_form')[0].reset()
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
                                    if(field === 'from_date' || field === 'to_date'){
                                        $("#date_range").notify(error, {
                                            className: "error",
                                            autoHideDelay: 2000
                                        });
                                    } else if(field === 'trainers'){
                                        $("#trainers").notify(error, {
                                            className: "error",
                                            autoHideDelay: 2000
                                        });
                                    } else {
                                        $("#"+field).notify(error, {
                                            className: "error",
                                            autoHideDelay: 2000
                                        });
                                    }
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
                    $('#update_assign_trainer_form')[0].reset()
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
        
        $('#assign_trainers-table').on('click', '.delete-assigned_trainer', function(e) {
            e.preventDefault();
            var productId = $(this).data('id');
            $('.loading-overlay').addClass('is-active')
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('assign-trainers.delete') }}",
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
                            $.notify(error, "error");
                            $('.loading-overlay').removeClass('is-active');
                        }
                    });
                }
            });
        });
        
    })
    
</script>