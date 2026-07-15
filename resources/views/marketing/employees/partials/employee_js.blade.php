<script>
    
    $(function(){
        
        // $("#employee_profile").change(function() {
        //     readURL(this);
        // });
        
        var table = new DataTable('#employee-table', {
            ajax: {
                url: "{{ route('employees.get') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                }
            },
            columns: [
                { data: 'DT_RowIndex', defaultContent: "", className: "text-center" },
                { data: 'emply_name', defaultContent: "", className: "text-center" },
                { data: 'emply_id', defaultContent: "", className: "text-center" },
                { data: 'user_name', defaultContent: "", className: "text-center" },
                { data: 'mobile', defaultContent: "", className: "text-center" },
                { data: 'email', defaultContent: "", className: "text-center" },
                { data: 'dateofbirth', defaultContent: "", className: "text-center" },
                { data: 'typeof_emply', defaultContent: "", className: "text-center" },
                { data: 'actions', orderable: false, searchable: false, defaultContent: "", className: "text-center" }
            ],
            processing: true,
            serverSide: true,
            language: {
                // processing: '<div class="custom-spinner"></div>'
            }
        });
        
        $('#refresh-table').on('click', function() {
            table.ajax.reload(null, false); 
        });
        
        $('#employee_zone_country').select2({
            placeholder: 'Select a country',
            ajax: {
                url: "{{ route('countries.get') }}",
                dataType: 'json',
                delay: 250,
                type: 'post',
                data: function (params) {
                    return {
                        _token: "{{ csrf_token() }}",
                        search: params.term // search term
                    };
                },
                processResults: function (response) {
                    return {
                        results: response.data.map(function (item) {
                            return {
                                id: item.id,
                                text: item.name
                            };
                        })
                    };
                },
                cache: true
            }
        });
        
        $('#employee_zone_state').select2({
            placeholder: 'Select a state',
            ajax: {
                url: "{{ route('states.get') }}",
                dataType: 'json',
                delay: 250,
                type: 'post',
                data: function (params) {
                    return {
                        _token: "{{ csrf_token() }}",
                        search: params.term, // search term
                        country_id: $('#employee_zone_country').val()
                    };
                },
                processResults: function (response) {
                    return {
                        results: response.data.map(function (item) {
                            return {
                                id: item.id,
                                text: item.name
                            };
                        })
                    };
                },
                cache: true
            }
        });
        
        $('#employee_zone_city').select2({
            placeholder: 'Select a city',
            ajax: {
                url: "{{ route('cities.get') }}",
                dataType: 'json',
                delay: 250,
                type: 'post',
                data: function (params) {
                    return {
                        _token: "{{ csrf_token() }}",
                        search: params.term,
                        state_id: $('#employee_zone_state').val()
                    };
                },
                processResults: function (response) {
                    return {
                        results: response.data.map(function (item) {
                            return {
                                id: item.id,
                                text: item.name
                            };
                        })
                    };
                },
                cache: true
            }
        });
        
        $('#create_employee_form').on('submit', function(e){
            e.preventDefault();
            $('.loading-overlay').addClass('is-active')
            var formData = new FormData($('#create_employee_form')[0]);
            $.ajax({
                url: "{{ route('employees.create') }}",
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
                    $('#create_employee_form')[0].reset()
                    $('.loading-overlay').removeClass('is-active');
                },
                error: function(xhr, status, error) {
                    // If there's an error in creating the product
                    $.notify("Failed to create product: " + error, "error");
                    $('.loading-overlay').removeClass('is-active');
                }
            })
        })
        
        $('#edit_employee_form').on('submit', function(e){
            e.preventDefault();
            $('.loading-overlay').addClass('is-active')
            var formData = new FormData($('#edit_employee_form')[0]);
            $.ajax({
                url: "{{ route('employees.update') }}",
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if(response.status == 200){
                        $.notify(response.message, "success");
                        $('#edit_employee_form')[0].reset()
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
                    $('#edit_employee_form')[0].reset()
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
        
        $('#employee-table').on('click', '.delete-employee', function(e) {
            e.preventDefault();
            var employeeId = $(this).data('id');
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
                        url: "{{ route('employees.delete') }}",
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: employeeId
                        },
                        success: function(response) {
                            if(response.status == 200){
                                Swal.fire(
                                    'Deleted!',
                                    'Your data has been deleted.',
                                    'success'
                                );
                                table.ajax.reload(null, false);
                            }
                            if(response.status == 400){
                                Swal.fire(
                                    'Error!',
                                    'There was an error deleting the employee.',
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
                            $.notify("Failed to delete employee: " + error, "error");
                            $('.loading-overlay').removeClass('is-active');
                        }
                    });
                }
            });
        });
        
    })
    
    function toggleFormSection() {
        var dropdown = document.getElementById("employee_type");
        var selectedOption = dropdown.options[dropdown.selectedIndex].value;

        // Hide all form sections
        var formSections = document.querySelectorAll('.form-section');
        formSections.forEach(function(section) {
            section.classList.remove('show');
        });

        // Show the selected form section
        var selectedSection = document.getElementById(selectedOption + 'Section');
        if (selectedSection) {
            selectedSection.classList.add('show');
        }
    }
    
    // function readURL(input) {
    //     if (input.files && input.files[0]) {
    //         var reader = new FileReader();
    //         reader.onload = function(e) {
    //             $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
    //             $('#imagePreview').hide();
    //             $('#imagePreview').fadeIn(650);
    //         }
    //         reader.readAsDataURL(input.files[0]);
    //     }
    // }
    
</script>