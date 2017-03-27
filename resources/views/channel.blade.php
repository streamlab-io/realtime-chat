<li class="room '+activeChannel+'" onclick="goToroom(this)" data-room-id="'+dataChannel.id+'" data-room-name="'+dataChannel.name+'">'+
    '<img src="{{url('/')}}/img/room.png" alt="" />'+
    '<span class="name">'+dataChannel.name+'</span>'+
    '<span class="time">'+dataChannel.online+'</span>'+
    '<span class="preview"></span>'+
'</li>