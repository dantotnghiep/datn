<!doctype html>
<html>

<head>
    <title>BotMan Widget</title>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Đảm bảo Bootstrap CSS hiện diện -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Thêm Stripe.js -->
    <script src="https://js.stripe.com/v3/"></script>

    <style>
        @charset "UTF-8";
        @import "https://fonts.googleapis.com/css2?family=Cabin:wght@400;500;600;700&amp;family=Dosis:wght@300;400;500;600;700;800&amp;display=swap";

        body,
        html {
            background-color: #f9f9f9;
            background-size: cover;
            font-family: 'Dosis', sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
            color: #000 !important;
            height:100%!important;
        }

        p {
            color: #000 !important
        }

        #messageArea {
            overflow-y: scroll
        }

        .chat {
            list-style: none;
            background: 0 0;
            padding: 0;
            margin: 0
        }

        .chat li {
            padding: 8px;
            padding: .5rem;
            font-size: 1rem;
            overflow: hidden;
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            color: #000
        }

        .visitor {
            -webkit-box-pack: end;
            -webkit-justify-content: flex-end;
            -ms-flex-pack: end;
            justify-content: flex-end;
            -webkit-box-align: end;
            -webkit-align-items: flex-end;
            -ms-flex-align: end;
            -ms-grid-row-align: flex-end;
            align-items: flex-end
        }

        .visitor .msg {
            -webkit-box-ordinal-group: 2;
            -webkit-order: 1;
            -ms-flex-order: 1;
            order: 1;
            border-top-right-radius: 2px
        }

        .chatbot .msg {
            -webkit-box-ordinal-group: 2;
            -webkit-order: 1;
            -ms-flex-order: 1;
            order: 1;
            border-top-left-radius: 2px
        }

        .msg {
            word-wrap: break-word;
            min-width: 50px;
            max-width: 90%;
            padding: 10px;
            -webkit-border-radius: 10px;
            -moz-border-radius: 10px;
            border-radius: 10px;
            background: #ffffff
        }

        .msg p {
            margin: 0 0 .2rem 0
        }

        .msg .time {
            font-size: .7rem;
            color: #7d7b7b;
            margin-top: 3px;
            float: right;
            cursor: default;
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none
        }

        .textarea {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            height: 55px;
            z-index: 99;
            background-color: #fff;
            border: none;
            outline: 0;
            padding-left: 15px;
            padding-right: 15px;
            color: #000;
            font-weight: 300;
            font-size: 1rem;
            line-height: 1.5;
            background: rgba(250, 250, 250, .8)
        }

        .textarea:focus,
        .form-control:focus {
            background: #fff !important;
            box-shadow: 0 -6px 12px 0 rgba(235, 235, 235, .95) !important;
            transition: .4s !important;
        }

        a.banner {
            position: fixed;
            bottom: 5px;
            right: 10px;
            height: 12px;
            z-index: 99;
            outline: 0;
            color: #777;
            font-size: 10px;
            text-align: right;
            font-weight: 200;
            text-decoration: none
        }

        div.loading-dots {
            position: relative
        }

        div.loading-dots .dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            margin-right: 2px;
            border-radius: 50%;
            background: #196eb4;
            animation: blink 1.4s ease-out infinite;
            animation-fill-mode: both
        }

        div.loading-dots .dot:nth-child(2) {
            animation-delay: -1.1s
        }

        div.loading-dots .dot:nth-child(3) {
            animation-delay: -.9s
        }

        div.loading-dots .dot-grey {
            background: #787878
        }

        div.loading-dots .dot-sm {
            width: 6px;
            height: 6px;
            margin-right: 2px
        }

        div.loading-dots .dot-md {
            width: 12px;
            height: 12px;
            margin-right: 2px
        }

        div.loading-dots .dot-lg {
            width: 16px;
            height: 16px;
            margin-right: 3px
        }

        @keyframes blink {

            0%,
            100% {
                opacity: .2
            }

            20% {
                opacity: 1
            }
        }

        .btn {
            display: block;
            padding: 5px;
            border-radius: 5px;
            margin: 5px;
            min-width: 100px;
            background-color: #101010;
            cursor: pointer;
            color: #fff;
            text-align: center
        }

        .btn:hover {
            color: white !important;
            background: #101010;
        }
    </style>
</head>

<body>
    {{-- <script id="botmanWidget" src='https://cdn.jsdelivr.net/npm/botman-web-widget@0/build/js/chat.js'></script> --}}

     <!-- jQuery (đảm bảo sẵn có trước khi các script khác chạy) -->
     <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

     <!-- Đảm bảo Bootstrap JS hiện diện -->
     <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="{{ asset('botman/chat.js') }}"></script>
    <script>
        window.laravel_echo_port = '{{ env('LARAVEL_ECHO_PORT') }}';
    </script>
    <script src="//{{ Request::getHost() }}:{{ env('LARAVEL_ECHO_PORT') }}/socket.io/socket.io.js"></script>
    <script src="{{ url('js/echo.js') }}" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/jwt-decode@3.1.2/build/jwt-decode.min.js"></script>

    <script>
        window.addEventListener('load', function() {
            const botmanWidgetData = window.parent.sharedBotmanWidgetData;
            const sendBot = document.getElementById('sendBot');
            const userInput = document.getElementById('userText');
            const startAction = document.getElementById('startAction');
            if (sendBot && botmanWidgetData && userInput || startAction) {

                sendBot.addEventListener('click', function() {
                    if (userInput.value.length > 0) {
                        botmanWidgetData.say(userInput.value);
                        userInput.value = '';
                    }
                });
                startAction.addEventListener('click', function() {
                    botmanWidgetData.whisper('/batdau');
                });

                function checkChatMessages() {
                    fetch('/get-chat-messages')
                        .then(response => response.json())
                        .then(data => {

                            if (data.expired || !data.messages || data.messages.length == 0) {
                                botmanWidgetData.whisper('/batdau');
                            }
                        })
                        .catch(error => console.error('Error fetching chat messages:', error));
                }
                checkChatMessages();

                // Hàm kiểm tra thông tin người dùng và gửi tin nhắn khi có userId
                function checkUserMessage() {
                    fetch("{{ route('botman.checkId') }}")
                        .then(response => response.json())
                        .then(data => {
                            const userId = data.userId;
                            if (data.success && userId) {
                                window.user_id = userId;
                                // Nếu đã có userId, lắng nghe sự kiện .message trên channel của user đó
                                if (!window.isListenerAttached) {
                                window.Echo.channel('laravel_database_user.' + userId)
                                    .listen('.message', (data) => {
                                        const decodedPayload = jwt_decode(data.message);
                                        if (decodedPayload && decodedPayload.userId == userId) {
                                            botmanWidgetData.sayAsBot(decodedPayload.message);
                                        } else {
                                            console.error("Chữ ký không hợp lệ");
                                        }
                                    })
                                    .error((error) => {
                                        console.error('Error listening to notifications channel: ', error);
                                    });
                                    window.isListenerAttached = true;
                                }
                                window.Echo.channel('laravel_database_GetMessageRoom.' + userId)
                                    .listen('.stop-chat', (event) => {
                                        fetch("{{ route('StopConversation') }}", {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': document.querySelector(
                                                        'meta[name="csrf-token"]').content
                                                }
                                            })
                                            .then(response => response.json())
                                            .then(data => {
                                                if (data.success) {
                                                    window.location.reload();
                                                }
                                            });
                                    });
                            } else {
                                window.Echo.channel('laravel_database_startSale')
                                    .listen('.start', (data) => {
                                        checkUserMessage();
                                    })
                                    .error((error) => {
                                        console.error('Error listening to notifications channel: ', error);
                                    });
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching userId: ', error);
                        });
                }

                // Gọi checkUserMessage để bắt đầu lắng nghe sự kiện ngay khi trang tải
                checkUserMessage();


            }
        });
    </script>

</body>

</html>
