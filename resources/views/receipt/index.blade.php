@extends('layouts.app')
@section('content')

<div class="container">

    <div class="row ">
        <div class="col-lg-6 mx-auto">
            <div class="card mt-2 mx-auto p-4 bg-light">
                <div class="card-body bg-light">
                    <div class="container">
                        <div class=" text-center mt-3 ">
                            <h5>Чеки загружать здесь</h5>
                        </div>
                        <form method="POST" action="{{ route('receipt.store') }}" id="mainForm" enctype="multipart/form-data">
                            @csrf
                            <div class="controls">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group"> <label for="InputName">Имя *</label>
                                            <input type="text" name="user_name" id="#user_name" class="form-control" placeholder="Please enter your firstname *" required="required" data-error="Firstname is required.">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group"> <label for="image_path">Загрузите чек</label>
                                            <input type="file" name="image_path" id="#image_path" required="required" data-error="Valid email is required.">
                                        </div>
                                    </div>
                                    <div class="col-md-12"> <input type="submit" id="savebtn" class="btn btn-success btn-send pt-2 btn-block" value="Отправить"> </div>
                                </div>
                            </div>
                        </form>
                        @if($errors->any())
                        <div class="row justify-content-center">
                            <div class="col-md-11">
                                <div class="alert alert-danger" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="close">
                                        <span aria-hidden="true">x</span>
                                    </button>
                                    {{ $errors->first() }}
                                </div>
                            </div>
                        </div>
                        @endif
                        @if (Session::has('success'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle">{{ Session::get('success') }}</i>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card-body bg-light">
                <div class="container">
                    <div class="row">
                        <div class="form-group">Выигрышные коды на этой неделе:</div>
                        <ul class="list-group">
                            @foreach($items as $item)
                            <li class="list-group-item">
                                {{ $item->user_name }} -
                                <img class="mr-2 rounded" style="width: 40px; height: 40px;" src="/image/{{ $item->image_path }}">
                                - {{ $item->type }}
                                - {{ $item->created_at->format('m/d/Y') }}
                                @if($item->code)
                                - ваш код: {{ $item->code }}
                                @endif
                                - {{$item->status}}
                            </li>
                            @endforeach
                            {{$items->links()}}
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
<script src=https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.js></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#mainForm').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: "{{ route('receipt.store') }}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function(data) {
                    console.log(data);
                },
                success: function(data) {
                    $('#mainForm')[0].reset();
                }
            });
        });
    });
</script>