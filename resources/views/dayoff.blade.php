@extends("welcome")
@section('body')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(Session::has("error"))
        <div class="alert alert-danger">{{Session::get("error")}}</div>
    @endif

    @if(Session::has("message"))
        <div class="alert alert-info">{{Session::get("message")}}</div>
    @endif
    <div class="container" style="margin-top: 10px;">
        <form method="post" action="/day-offs">
            @csrf
            <div class="form-group">
                <label for="exampleInputEmail1">Day Off</label>
                <input type="text" name="day_off" class="form-control" style="width: 200px" id="datepicker" aria-describedby="emailHelp"
                       placeholder="Enter Day off">
                <small id="emailHelp" class="form-text text-muted">Job will be ignore in this day</small>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <br>
        <hr>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>#</th>
                <th>Day</th>
            </tr>
            </thead>
            <tbody>
            @if(sizeof($data))
                @foreach( $data as $item)
                    <tr>
                        <td>{{$loop->index}}</td>
                        <td>{{$item->day }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="2" style="text-align: center">No Result</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
@endsection

@section('script')
    <script>
        $( function() {
            $( "#datepicker" ).datepicker({
                dateFormat:"dd/mm/yy",
                beforeShow: function (textbox, instance) {
                    instance.dpDiv.css({
                        marginLeft: textbox.offsetWidth + 'px'
                    });
                }
            });
        } );
    </script>
@endsection
