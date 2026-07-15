<script>
    $(function(){
        
        var table = new DataTable('#orders-table', {
            ajax: {
                url: "{{ route('dispatched-orders.get') }}", // Use Laravel's route helper to generate the URL
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
                // { data: 'actions', orderable: false, searchable: false, defaultContent: "", className: "text-center" }
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
    
</script>