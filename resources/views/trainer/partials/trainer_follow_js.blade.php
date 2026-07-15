<script>
    
    $(function(){
        
        var table = new DataTable('#trainers_pending-table', {
            ajax: {
                url: "{{ route('assign-trainers-by-status.get') }}", // Use Laravel's route helper to generate the URL
                type: 'POST',
                data: function(d){
                    d._token = '{{ csrf_token() }}',
                    d.table_type = 'pending'
                }
            },
            columns: [
                { data: 'DT_RowIndex', defaultContent: "", className: "text-center" },
                { data: 'cmpny_name', defaultContent: "", className: "text-center" },
                { data: 'from_date', defaultContent: "", className: "text-center" },
                { data: 'to_date', defaultContent: "", className: "text-center" },
                { data: 'actions', orderable: false, searchable: false, defaultContent: "", className: "text-center" }
            ],
            processing: true,
            serverSide: true,
            lengthMenu: [10, 25, 50, 100, [ -1, 'All' ]],
        });
        
        $('#trainers_pending-refresh-table').on('click', function() {
            table.ajax.reload(null, false); // User paging is not reset on reload
        });
        
        var table1 = new DataTable('#trainers_process-table', {
            ajax: {
                url: "{{ route('assign-trainers-by-status.get') }}", // Use Laravel's route helper to generate the URL
                type: 'POST',
                data: function(d){
                    d._token = '{{ csrf_token() }}',
                    d.table_type = 'on_progress'
                }
            },
            columns: [
                { data: 'DT_RowIndex', defaultContent: "", className: "text-center" },
                { data: 'cmpny_name', defaultContent: "", className: "text-center" },
                { data: 'from_date', defaultContent: "", className: "text-center" },
                { data: 'to_date', defaultContent: "", className: "text-center" },
                { data: 'started_date', defaultContent: "", className: "text-center" },
                { data: 'actions', orderable: false, searchable: false, defaultContent: "", className: "text-center" }
            ],
            processing: true,
            serverSide: true,
            lengthMenu: [10, 25, 50, 100, [ -1, 'All' ]],
        });
        
        $('#trainers_process-refresh-table').on('click', function() {
            table1.ajax.reload(null, false); // User paging is not reset on reload
        });
        
        var table2 = new DataTable('#trainers_completed-table', {
            ajax: {
                url: "{{ route('assign-trainers-by-status.get') }}", // Use Laravel's route helper to generate the URL
                type: 'POST',
                data: function(d){
                    d._token = '{{ csrf_token() }}',
                    d.table_type = 'completed'
                }
            },
            columns: [
                { data: 'DT_RowIndex', defaultContent: "", className: "text-center" },
                { data: 'cmpny_name', defaultContent: "", className: "text-center" },
                { data: 'from_date', defaultContent: "", className: "text-center" },
                { data: 'to_date', defaultContent: "", className: "text-center" },
                { data: 'started_date', defaultContent: "", className: "text-center" },
                { data: 'end_date', defaultContent: "", className: "text-center" }
            ],
            processing: true,
            serverSide: true,
            lengthMenu: [10, 25, 50, 100, [ -1, 'All' ]],
        });
        
        $('#trainers_completed-refresh-table').on('click', function() {
            table2.ajax.reload(null, false); // User paging is not reset on reload
        });
        
        var table3 = new DataTable('#trainers_cancelled-table', {
            ajax: {
                url: "{{ route('assign-trainers-by-status.get') }}", // Use Laravel's route helper to generate the URL
                type: 'POST',
                data: function(d){
                    d._token = '{{ csrf_token() }}',
                    d.table_type = 'cancelled'
                }
            },
            columns: [
                { data: 'DT_RowIndex', defaultContent: "", className: "text-center" },
                { data: 'cmpny_name', defaultContent: "", className: "text-center" },
                { data: 'from_date', defaultContent: "", className: "text-center" },
                { data: 'to_date', defaultContent: "", className: "text-center" },
                { data: 'reject_reason', defaultContent: "", className: "text-center" }
            ],
            processing: true,
            serverSide: true,
            lengthMenu: [10, 25, 50, 100, [ -1, 'All' ]],
        });
        
        $('#trainers_cancelled-refresh-table').on('click', function() {
            table3.ajax.reload(null, false); // User paging is not reset on reload
        });
        
    })
    
    function ChangeStatusOfTraineeWorks(status, id){
        var table = new DataTable('.table');
        if(status == 'completed'){
            var status_go = 'completed';
        }else if(status == 'on_progress'){
            var status_go = 'on_progress';
        }else if(status == 'cancelled'){
            var status_go = 'cancelled';
        }else if(status == 'pending'){
            var status_go = 'pending';
        }else {
            var status_go = 'pending';
        }
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
                    url: "{{ route('status-of-trainee-works.update') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        status: status_go
                    },
                    success: function(response) {
                        if(response.status == 200){
                            Swal.fire(
                                'Success!',
                                'Your Status has been Updated.',
                                'success'
                            );
                            table.ajax.reload(null, false);
                            $('.modal').modal('hide')
                        }
                        if(response.status == 400){
                            Swal.fire(
                                'Error!',
                                'There was an error update the status.',
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
                        $.notify(error, "error");
                    }
                });
            }
        });
    }
    
    function OpenRejectReasonModal(id){
        $('#assign_work_id').val(id)
        $('#reject_reason_modal').modal('show')
    }
    
    function RejectReasonUpdate(){
        var table = new DataTable('.table');
        if($('#reject_reason').val() == '' || $('#reject_reason').val() == null){
            $("#reject_reason").notify('Reason is required to reject a training', {
                className: "error",
                autoHideDelay: 2000
            });
            return false;
        }
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
                    url: "{{ route('status-and-reason.update') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: $('#assign_work_id').val(),
                        reason: $('#reject_reason').val()
                    },
                    success: function(response) {
                        if(response.status == 200){
                            Swal.fire(
                                'Success!',
                                'Work has been rejected.',
                                'success'
                            );
                            $('#reject_reason').val('')
                            $('#reject_reason_modal').modal('hide')
                            table.ajax.reload(null, false);
                        }
                        if(response.status == 400){
                            Swal.fire(
                                'Error!',
                                'There was an error reject.',
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
                        $.notify(error, "error");
                    }
                });
            }
        });
    }
    
    function OpenNoOfTeachersModel(id){
        $('#assign_work_id1').val(id)
        $('#no_of_teachers_modal').modal('show')
    }
    
    function TraineeWorkstoComplete(){
        var table = new DataTable('.table');
        if($('#no_of_teachers').val() == '' || $('#no_of_teachers').val() == null){
            $("#no_of_teachers").notify('No of teachers is required to complete the training', {
                className: "error",
                autoHideDelay: 2000
            });
            return false;
        }
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
                    url: "{{ route('complete-status-and-noteachers.update') }}",
                    type: 'POST',
                    data: {
                       _token: '{{ csrf_token() }}',
                        id: $('#assign_work_id1').val(),
                        no_of_teachers: $('#no_of_teachers').val()
                    },
                    success: function(response) {
                        if(response.status == 200){
                            Swal.fire(
                                'Success!',
                                'Your Status has been Updated.',
                                'success'
                            );
                            table.ajax.reload(null, false);
                            $('.modal').modal('hide')
                        }
                        if(response.status == 400){
                            Swal.fire(
                                'Error!',
                                'There was an error update the status.',
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
                        $.notify(error, "error");
                    }
                });
            }
        });
    }
    
</script>