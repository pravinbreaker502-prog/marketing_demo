<script>
    
    $(function(){
        
        $('.close').on('click', function(){
            $('.modal').modal('hide')
        })
        
        var table = new DataTable('#leaverequests-table', {
            ajax: {
                url: "{{ route('leave-requests.get') }}", // Use Laravel's route helper to generate the URL
                type: 'POST',
                data: function(d){
                    d._token = '{{ csrf_token() }}'
                }
            },
            responsive: true,
            columns: [
                { data: 'DT_RowIndex', defaultContent: "", className: "text-center" },
                { data: 'emply_name', defaultContent: "", className: "text-center" },
                { data: 'emply_type', defaultContent: "", className: "text-center" },
                { data: 'leavetype', defaultContent: "", className: "text-center" },
                { data: 'leavereason', defaultContent: "", className: "text-center" },
                { data: 'fromdate', defaultContent: "", className: "text-center" },
                { data: 'todate', defaultContent: "", className: "text-center" },
                { data: 'rejectreason', defaultContent: "", className: "text-center" },
                { data: 'actions', orderable: false, searchable: false, defaultContent: "", className: "text-center" }
            ],
            processing: true,
            serverSide: true,
            dom: 
                "<'row'<'col-sm-4'l><'col-sm-6 text-end'f><'col-sm-2'B>>" +
                "<'row'<'col-sm-12't>>" +
                "<'row'<'col-sm-12 text-end'p>>",
            lengthMenu: [10, 25, 50, 100, [ -1, 'All' ]],
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="fa fa-file-excel-o"></i>',
                    titleAttr: 'Download As Excel', // Tooltip text
                    className: 'excel-btn',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7], // Include DT_RowIndex as the first column
                        modifier: {
                            page: 'all'
                        },
                        format: {
                            body: function (data, row, column, node) {
                                if (column === 1) {
                                    return $(node).text();
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
                    titleAttr: 'Download As PDF',
                    className: 'pdf-btn',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6],
                        modifier: {
                            page: 'all'
                        },
                        format: {
                            body: function (data, row, column, node) {
                                if (column === 1) {
                                    return $(node).text();
                                } else {
                                    return $(data).text();
                                }
                            }
                        }
                    }
                },
            ],
            language: {
                // processing: '<div class="custom-spinner"></div>'
            }
        });
        
        $('#refresh-table').on('click', function() {
            table.ajax.reload(null, false);
        });
        
    })
    
    function AcceptLeaveRequest(id){
        var table = new DataTable('#leaverequests-table');
        Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Accept it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('leave-requests.accept') }}",
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: id
                        },
                        success: function(response) {
                            if(response.status == 200){
                                Swal.fire(
                                    'Accepted!',
                                    'Request has been accepted.',
                                    'success'
                                );
                                table.ajax.reload(null, false);
                            }
                            if(response.status == 400){
                                Swal.fire(
                                    'Error!',
                                    'There was an error accept the request.',
                                    'error'
                                );
                            }
                            if(response.status == 500){
                                $.notify(response.error, "error");
                            }
                            if(response.status == 401){
                                Swal.fire(
                                    'Error!',
                                    'There was a validation error is occured.',
                                    'error'
                                );
                            }
                        },
                        error: function(xhr, status, error) {
                            $.notify("Failed to accept: " + error, "error");
                        }
                    });
                }
            });
    }
    
    function OpenReasonModal(id){
        $('#leave_id').val(id)
        $('#reason_modal').modal('show')
    }
    
    function RejectLeaveRequest(){
        if($('#reject_reason').val() == ''){
            $.notify('Kindly enter the valid reason for rejecting request..!', "error");
            return false;
        }
        $('#reason_modal').modal('hide')
        var table = new DataTable('#leaverequests-table');
        Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Reject it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('leave-requests.reject') }}",
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: $('#leave_id').val(),
                            reason: $('#reject_reason').val()
                        },
                        success: function(response) {
                            if(response.status == 200){
                                Swal.fire(
                                    'Rejected!',
                                    'Request has been rejected.',
                                    'success'
                                );
                                table.ajax.reload(null, false);
                            }
                            if(response.status == 400){
                                Swal.fire(
                                    'Error!',
                                    'There was an error reject the request.',
                                    'error'
                                );
                            }
                            if(response.status == 500){
                                $.notify(response.error, "error");
                            }
                            if(response.status == 401){
                                Swal.fire(
                                    'Error!',
                                    'There was a validation error is occured.',
                                    'error'
                                );
                            }
                        },
                        error: function(xhr, status, error) {
                            $.notify("Failed to reject: " + error, "error");
                        }
                    });
                }
            });
    }
    
</script>