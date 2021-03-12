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

        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="{{$tabActive == 'hose'?'active':''}}"><a href="#hose" aria-controls="home" role="tab" data-toggle="tab">HOSE</a></li>
            <li role="presentation" class="{{$tabActive == 'hnx'?'active':''}}"><a href="#hnx" aria-controls="profile" role="tab" data-toggle="tab">HNX</a></li>
            <li role="presentation" class="{{$tabActive == 'upcom'?'active':''}}"><a href="#upcom" aria-controls="messages" role="tab" data-toggle="tab">UPCOM</a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane {{$tabActive == 'hose'?'active':''}}" id="hose" >
                <div style="float: right">
                    {{ $hose->links() }}
                </div>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Ma</th>
                        <th>Volume</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach( $hose as $item)
                        <tr>
                            <td>{{$loop->index}}</td>
                            <td>{{substr($item->symbol,0,3)}}</td>
                            <td>{{number_format($item->volume,0)}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div style="float: right">
                    {{ $hose->links() }}
                </div>
            </div>
            <div role="tabpanel" class="tab-pane {{$tabActive == 'hnx'?'active':''}}" id="hnx">
                <div style="float: right">
                    {{ $hnx->links() }}
                </div>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Ma</th>
                        <th>Volume</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach( $hnx as $item_hnx)
                        <tr>
                            <td>{{$loop->index}}</td>
                            <td>{{substr($item_hnx->symbol,0,3)}}</td>
                            <td>{{number_format($item_hnx->volume,0)}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div style="float: right">
                    {{ $hnx->links() }}
                </div>
            </div>
            <div role="tabpanel" class="tab-pane {{$tabActive == 'upcom'?'active':''}}" id="upcom">
                <div style="float: right">
                    {{ $upcom->links() }}
                </div>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Ma</th>
                        <th>Volume</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach( $upcom as $item_upcom)
                        <tr>
                            <td>{{$loop->index}}</td>
                            <td>{{substr($item_upcom->symbol,0,3)}}</td>
                            <td>{{number_format($item_upcom->volume,0)}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div style="float: right">
                    {{ $upcom->links() }}
                </div>
            </div>
        </div>

{{--        --}}


    </div>
@endsection
