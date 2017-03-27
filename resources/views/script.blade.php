<script>
    var channelConnection = "public", sls, roomName = $('.roomName');
    var slh, allChannel, activeChannel, slh = new StreamLabHtml(), slu = new StreamLabUser(), OnlineOnRoom;
    var sln = new StreamLabNotification(), people = $('#people'), rooms = $('#rooms'), key, messageData, hideChannelName = $('#channel_name_id');
    var SendData, showMessages, messageClass, message, scroll, divMesssage = $('#messages');
    function Logic(channel){
        sls = new StreamLabSocket({
            appId:"{{ config('stream_lab.app_id') }}",
            channelName:channel,
            event:"*",
            user_id:"{{ $user->id  }}",
            user_secret:"{{ md5( $user->id.$user->email.$user->name) }}"
        });
        if(sls){
            getAllChannels(channel);
            roomName.html(channel);
            slu.getAllUser('{{ url('streamLab/app/user') }}' , function(online){
                OnlineOnRoom = slh.json(online).data;
                var e = '';
                $.each(OnlineOnRoom , function(indexOnline , dataOnline){
                    e += '@include("online")';
                });
                $('#people').html(e);
            } , 30 , 0 , channel);
            sls.socket.onmessage = function(res){
                slh.setData(res);
                slh.updateUserList(function(id){
                    slu.userExist("{{ url('streamLab/app/checkuser') }}" , id , function(dataOnline){
                        if(dataOnline.status){
                            if($('#'+id).length == 0){
                                dataOnline = slh.json(dataOnline).data;
                                var e = '@include("online")';
                                people.append(e);
                                sln.makeNotification("User " + dataOnline.data.name + " Is login");
                            }
                        }
                    });
                } , function(id){
                    slu.userExist("{{ url('streamLab/app/checkuser') }}" , id , function(dataOffline){
                        if(dataOffline.status){
                            dataOffline = slh.json(dataOffline).data;
                            $('#'+id).remove();
                            sln.makeNotification("User " + dataOffline.data.name + " Is Logout");
                        }
                    });
                });
                slh.setOnline('onlineCount');
                if(slh.getSource() === 'messages'){
                    showMessages  = JSON.parse(slh.getMessage());
                    messageClass   =   showMessages.username != "{{ $user->name }}" ? 'me' : "you";
                    message = '@include("message")';
                    divMesssage.append(message);
                    scroll = slh.getById('messages');
                    scroll.scrollTop = scroll.scrollHeight;
                }
            }
            hideChannelName.val(channel);
        }
    }
    function getAllChannels(channel){
        slh.getAllChannel('rooms' , function(result){
            allChannel = slh.json(result).data;
            var e = '';
            $.each(allChannel , function(indexChannel , dataChannel){
                activeChannel = dataChannel.name == channel ? 'active' : '';
                e += '@include('channel')';
            });
            rooms.html(e);
        });
    }
    function goToroom(e){
        if($(e).hasClass('active')){
            return false;
        }else{
            $('.room').removeClass('active');
            sls.socket.close();
            Logic($(e).data('room-name'));
            people.show();
            rooms.hide();
        }
    }
    function back(){
        sls.socket.close();
        people.hide();
        people.html(' ');
        getAllChannels();
        rooms.show();
        slh.html('onlineCount' , 0);
        roomName.html('');
        divMesssage.html(' ');
    }
    slh.getById('messageText').onkeypress  = function(e){
        key  = e.which || e.keyCode;
        if(key === 13){
            if(slh.getVal('messageText') != ""){
                messageData = JSON.stringify({
                            message:slh.getVal('messageText'),
                            username:"{{ $user->name }}",
                            date:new Date()
                });
                SendData = {
                    _token:"{{ csrf_token() }}",
                    message:messageData,
                    channelName:hideChannelName.val(),
                    eventName:"addMessage"
                };
                sls.sendMessage("{{ url('streamLab/post/message') }}" , SendData , function(res){
                    slh.setVal('messageText' , ' ');
                });
            }
        }
    }
    Logic(channelConnection);
</script>