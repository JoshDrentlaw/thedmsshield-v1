@extends('layouts.app')

@section('content')
    <div class="alert alert-success fixed-top invisible" id="success-message-alert" style="z-index: 10000;" role="alert">
        <h4 id="success-message"></h4>
    </div>
    <div class="card mt-4">
        <div class="card-body">
            <h1>
                {{$message->title}}
                <button class="btn btn-danger float-right">Delete</button>
            </h1>
            <hr class="my-4">
            <p>{{$message->body}}</p>
            @if($message->message_type === 'invite')
                <div id="invite-btns">
                    <button class="btn btn-success mr-2" id="accept" data-invite-id="{{$message->invite->id}}">Accept</button>
                    <button class="btn btn-danger" id="deny" data-invite-id="{{$message->invite->id}}">Deny</button>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/message.js') . '?' . time() }}"></script>
@endsection