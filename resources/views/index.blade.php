@extends("welcome")
@section('body')

    @if(Session::has("message"))
    <div class="alert alert-info">{{Session::get("message")}}</div>
    @endif
    <div>
        @if($week==3 && $weekday <=3)
            <h1 style="color: red;font-weight: bold">Dao han phai sinh thu 5 tuan nay</h1>
        @endif
    </div>
    <div class="container">
        <a class="btn btn-info" href="/download">Download file</a>
    </div>
    <br/>
    <div class="container">
        <div style="float: right">
            {{ $data->links() }}
        </div>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>#</th>
                <th>Ma</th>
                {{--            <th>Price</th>--}}
                <th>Volume</th>
            </tr>
            </thead>
            <tbody>
            @foreach( $data as $item)
                <tr>
                    <td>{{$loop->index}}</td>
                    <td>{{substr($item->symbol,0,3)}}</td>
                    <td>{{number_format($item->volume,0)}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div style="float: right">
            {{ $data->links() }}
        </div>
    </div>
@endsection
