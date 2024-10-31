@foreach($getChat as $value)
    @if($value->sender_id == Auth::user()->id)
        <li class="clearfix">
            <div class="message-data text-right">
                <span class="message-data-time">{{ Carbon\Carbon::parse($value->created_date)->diffForHumans() }}</span>
                <img style="height: 40px;" src="{{ $value->getSender->getProfileDirect() }}" alt="avatar">
            </div>
            <div class="message other-message float-right" style="text-align: left;">
                {!! nl2br($value->message) !!}

                @if(!empty($value->getFile()))
                <div>
                    <a href="{{ $value->getFile() }}" download="" target="_blank">Attachment</a>
                </div>
                @endif

            </div>

        </li>
    @else
        <li class="clearfix">
            <div class="message-data">
                <img style="height: 40px;" src="{{ $value->getSender->getProfileDirect() }}" alt="avatar">
                <span class="message-data-time">{{ Carbon\Carbon::parse($value->created_date)->diffForHumans() }}</span>
            </div>
            <div class="message my-message">
                {!! nl2br($value->message) !!}
                
                @if(!empty($value->getFile()))
                <div>
                    <a href="{{ $value->getFile() }}" download="" target="_blank">Attachment</a>
                </div>
                @endif
            </div>
            
        </li>
    @endif
@endforeach