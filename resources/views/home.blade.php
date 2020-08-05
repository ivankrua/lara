@extends('layouts.app')

@section('title', 'Home')

@section('content')
    @parent
<script>
    validateUser();
    $.getJSON( "api/pricelist", function( data ) {
        if ('data' in data)
        {
            let serverList = data['data'];
            let tableData = '';
            serverList.forEach((item)=>{
                tableData+='<tr><td>'+item.provider
                    +'</td><td>'+item.brand
                    +'</td><td>'+item.location
                    +'</td><td>'+item.cpu
                    +'</td><td>'+item.drive
                    +'</td><td>$'+Math.round(item.price * 100) / 100+'</td></tr>'
            })
            $('#pricelist').html(tableData);
        }
        else
        {
            $('#pricelist').html('<tr><td colspan="6">Oops! Something happend.</td></tr>');
        }
    });
</script>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col">Provider</th>
            <th scope="col">Brand</th>
            <th scope="col">Location</th>
            <th scope="col">CPU</th>
            <th scope="col">Drive</th>
            <th scope="col">Price</th>
        </tr>
        </thead>
        <tbody id="pricelist">

        </tbody>
    </table>
@endsection
