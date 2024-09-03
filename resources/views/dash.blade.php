<meta name="csrf-token" content="{{ csrf_token() }}" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
        <div>
    <button id="refilter" type="button">
        Recalculate
    </button>
    <input id="search" name="search" />
    <button type="button" id="filter">Filter</button>
    <select id="filter_by">
        <option value="">Select Filter</option>
        <option value="day">Day</option>
        <option value="month">Month</option>
        <option value="year">Year</option>
    </select>
</div>
<br>
<div id="top_3_users">
    <table class="table">
        <thead>
            <tr>
                <th>Rank 2</th>
                <th>Rank 1</th>
                <th>Rank 3</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td id="rank_2"></td>
                <td id="rank_1"></td>
                <td id="rank_3"></td>
            </tr>
        </tbody>
    </table>
</div>
<br>
<table class="table" id="users">
    <thead>
        <tr>
            <th style="min-width: 100px">Id</th>
            <th style="min-width: 100px">Name</th>
            <th style="min-width: 100px">Points</th>
            <th style="min-width: 100px">Rank</th>
        </tr>
    </thead>
</table>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous">
</script>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script>
    let table = null;
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
            table = $('#users').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/get-all-data",
                    type: "POST",
                    data:{
                        filter_by:$("#filter_by").val(),
                        search_by:$("#search").val(),
                    }
                },
                pageLength: 10,
                columns: [
                    {
                        data: 'id',
                        orderable:false
                    },
                    {
                        data: 'name',
                        orderable:false
                    },
                    {
                        data: 'total_points',
                        orderable:false
                    },
                    {
                        data: 'rank',
                        orderable:false
                    },
                ],
                createdRow: function(row, data, index) {
                    if(data.id == $('#search').val()){
                        $(row).find('td:eq(0)').attr("style","border:1px solid");
                        $(row).find('td:eq(1)').attr("style","border:1px solid");
                        $(row).find('td:eq(2)').attr("style","border:1px solid");
                        $(row).find('td:eq(3)').attr("style","border:1px solid");
                    }
                }
            });
            setTimeout(() => {
                    getTop3Users();
            }, 1000);
            $('#refilter').on("click",function(){
                $.ajax({
                    url:"/re-filter",
                    method:"GET",
                    success:function(data){
                        table.ajax.reload();
                        getTop3Users();
                    },
                    error:function(err){
                        console.log(err);
                    }
                });
            });

            $("#filter").on("click",function(){
                applyFilter();
            });
            $("#filter_by").on("change",function(){
                applyFilter();
            });

    });

    function applyFilter(){
        table.destroy();
        table = $('#users').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/get-filter-data",
                    type: "POST",
                    data:{
                        filter_by:$("#filter_by").val(),
                        search_by:$("#search").val(),
                    }
                },
                pageLength: 10,
                columns: [
                    {
                        data: 'id',
                        orderable:false
                    },
                    {
                        data: 'name',
                        orderable:false
                    },
                    {
                        data: 'total_points',
                        orderable:false
                    },
                    {
                        data: 'rank',
                        orderable:false
                    },
                ],createdRow: function(row, data, index) {
                    console.log("data",data.id);
                    if(data.id == $('#search').val()){
                        $(row).find('td:eq(0)').attr("style","border:1px solid");
                        $(row).find('td:eq(1)').attr("style","border:1px solid");
                        $(row).find('td:eq(2)').attr("style","border:1px solid");
                        $(row).find('td:eq(3)').attr("style","border:1px solid");
                    }
                }
            });
    }

    function getTop3Users(){
        $('#rank_1').html("");
        $('#rank_2').html("");
        $('#rank_3').html("");
        $.ajax({
            url:"/top-3-users",
            method:"GET",
            success:function(data){
                if(data.success){
                    var res = data.data;
                    $('#rank_1').html(res[0]['name']+"</br>"+res[0]['total_points'])
                    $('#rank_2').html(res[1]['name']+"</br>"+res[1]['total_points'])
                    $('#rank_3').html(res[2]['name']+"</br>"+res[2]['total_points'])
                }
            },
            error:function(err){
                console.log(err);
            }
        });
    }
</script>
