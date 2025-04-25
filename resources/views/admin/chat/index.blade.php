@extends('admin.layouts.master')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/node-waves/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/typeahead-js/typeahead.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/libs/bootstrap-maxlength/bootstrap-maxlength.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/css/pages/app-chat.css') }}" />
    <style>
        .chat-search-input {
            border: var(--bs-border-width) solid #d1d0d4;
            border-left: none;
            border-radius: var(--bs-border-radius);
        }

        .chat-search-input:hover {
            border: 1px solid #d1d0d4;
        }

        .chat-message.chat-message-right .chat-message-wrapper {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .chat-message .chat-message-wrapper {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .text-white {
            color: white !important;
        }

        .command-list {
            bottom: 20%;
            position: absolute;
            background-color: white;
            max-height: 200px;
            overflow-y: auto;
            width: 93%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 10;
            margin-top: 5px;
            border-radius: 5px;
        }

        .command-item {
            padding: 8px;
            cursor: pointer;
        }

        .command-item:hover {
            background-color: #f0f0f0;
        }

        .avatar.avatar-0::after {
            background-color: #0d6efd;
        }

        .avatar.avatar-1::after {
            background-color: #ffc107;
        }

        .avatar.avatar-2::after {
            background-color: #17a2b8;
        }

        .avatar.avatar-3::after {
            background-color: #6c757d;
        }

        .avatar.avatar-4::after {
            background-color: #28a745;
        }

        .avatar.avatar-5::after {
            background-color: #dc3545;
        }

        .avatar.avatar-6::after {
            background-color: #343a40;
        }

        .avatar.avatar-7::after {
            background-color: #f8f9fa;
        }

        .avatar.avatar-0::after,
        .avatar.avatar-1::after,
        .avatar.avatar-2::after,
        .avatar.avatar-3::after,
        .avatar.avatar-4::after,
        .avatar.avatar-5::after,
        .avatar.avatar-6::after,
        .avatar.avatar-7::after {
            content: "";
            position: absolute;
            bottom: 0;
            right: 3px;
            width: 8px;
            height: 8px;
            border-radius: 100%;
            box-shadow: 0 0 0 2px #fff;
        }
    </style>
@endsection
@section('content')
    <div class="cr-main-content">
        <div class="container-fluid">
            <div class="cr-card mb-2">
                <div class="cr-card-header d-flex align-items-center">
                    <h5 class="cr-card-title">Hỗ trợ trực tuyến</h5>
                    <div class="ms-2 btn btn-text-secondary btn-icon rounded-pill me-1">
                        <i class="fa-solid fa-volume-xmark ti-md email-list-read cursor-pointer fs-5"
                            id="soundNotification"></i>
                    </div>
                </div>
            </div>
            <div class="app-chat cr-card overflow-hidden">
                <div class="row g-0">
                    <!-- Sidebar Left -->
                    <div class="col app-chat-sidebar-left app-sidebar overflow-hidden" id="app-chat-sidebar-left">
                        <div
                            class="chat-sidebar-left-user sidebar-header d-flex flex-column justify-content-center align-items-center flex-wrap px-6 pt-12">
                            <div class="avatar avatar-xl avatar-online chat-sidebar-avatar">
                                <img src="{{ asset('be/assets/img/user/1.jpg') }}" alt="Avatar"
                                    class="rounded-circle" />
                            </div>
                            <h5 class="mt-4 mb-0">{{ auth()->user()->name ?? 'Tên' }}</h5>
                            <span></span>
                            <i class="ti ti-x ti-lg cursor-pointer close-sidebar" data-bs-toggle="sidebar" data-overlay
                                data-target="#app-chat-sidebar-left"></i>
                        </div>
                    </div>
                    <!-- /Sidebar Left-->

                    <!-- Chat & Contacts -->
                    <div class="col app-chat-contacts app-sidebar flex-grow-0 overflow-hidden border-end"
                        id="app-chat-contacts">
                        <div class="sidebar-header h-px-75 px-5 border-bottom d-flex align-items-center">
                            <div class="d-flex align-items-center me-6 me-lg-0">
                                <div class="flex-shrink-0 avatar avatar-online me-4" data-bs-toggle="sidebar"
                                    data-overlay="app-overlay-ex" data-target="#app-chat-sidebar-left">
                                    <img class="user-avatar rounded-circle cursor-pointer"
                                        src="{{ asset('be/assets/img/user/1.jpg') }}" alt="Avatar" />
                                </div>
                                <div class="flex-grow-1 input-group input-group-merge">
                                    <span class="input-group-text" id="basic-addon-search31"><i
                                            class="ti ti-search"></i></span>
                                    <input type="text" class="form-control chat-search-input" placeholder="Tìm kiếm..."
                                        aria-label="Tìm kiếm..." aria-describedby="basic-addon-search31" />
                                </div>
                            </div>
                            <i class="ti ti-x ti-lg cursor-pointer position-absolute top-50 end-0 translate-middle d-lg-none d-block"
                                data-overlay data-bs-toggle="sidebar" data-target="#app-chat-contacts"></i>
                        </div>
                        <div class="sidebar-body">
                            <ul class="list-unstyled chat-contact-list py-2 mb-0" id="chat-list">

                            </ul>
                        </div>
                    </div>

                    <!-- Lịch sử nhắn -->
                    <div class="col app-chat-history">
                        <div class="chat-history-wrapper d-none">
                            <div class="chat-history-container">
                                <div class="chat-history-header border-bottom">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex overflow-hidden align-items-center">
                                            <i class="ti ti-menu-2 ti-lg cursor-pointer d-lg-none d-block me-4"
                                                data-bs-toggle="sidebar" data-overlay data-target="#app-chat-contacts"></i>
                                            <div class="flex-shrink-0 avatar avatar-online">
                                                <img src="{{ asset('be/assets/img/user/1.jpg') }}" alt="Avatar"
                                                    class="rounded-circle" data-bs-toggle="sidebar" data-overlay
                                                    data-target="#app-chat-sidebar-right" />
                                            </div>
                                            <div class="chat-contact-info flex-grow-1 ms-4">
                                                <h6 class="m-0 fw-normal">Người dùng </h6>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <i
                                                class="ti ti-trash ti-md cursor-pointer d-sm-inline-flex d-none me-1 btn btn-sm btn-text-secondary text-secondary btn-icon rounded-pill"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="chat-history-body">
                                    <ul class="list-unstyled chat-history h-100" id="chat-history">
                                    </ul>
                                </div>
                            </div>


                            <div class="chat-history-footer shadow-xs">
                                <form class="form-send-message d-flex justify-content-between align-items-center">
                                    <input class="form-control message-input border-0 me-4 shadow-none"
                                        placeholder="Gửi tin nhắn..." />
                                    <div class="message-actions d-flex align-items-center">
                                        <label for="short-code"
                                            class="form-label mb-0 d-flex justify-content-center align-items-center"
                                            style="height: 50px; line-height: 50px;">
                                            <svg class="ti-md cursor-pointer me-2" viewBox="0 0 30 22" width="30"
                                                height="30" xmlns="http://www.w3.org/2000/svg" fill="#000000">
                                                <path d="M6 14H4V6h2V4H2v12h4M7.1 17h2.1l3.7-14h-2.1M14 4v2h2v8h-2v2h4V4">
                                                </path>
                                            </svg>
                                        </label>

                                        <button class="btn btn-info d-flex send-msg-btn">
                                            <span class="align-middle d-md-inline-block d-none">Gửi</span>
                                            <i class="ti ti-send ti-16px ms-md-2 ms-0"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="chat-none bg-white h-100 ">
                            <p class="d-flex h-100  align-items-center justify-content-center">
                                Vui lòng chọn người dùng để hỗ trợ
                            </p>
                        </div>
                    </div>
                    <div class="app-overlay"></div>
                </div>
            </div>
            <!-- Quickview -->
            <div class="modal fade" id="quickview_modal">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content min-h-400 h-100">
                        <div class="modal-header bg-white">
                            <h5>Cập nhật thông tin</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body bg-white h-100">
                            <div class="data-preloader-wrapper d-flex align-items-center justify-content-center min-h-400">
                                <div class="" role="status">
                                    <span class="sr-only"></span>
                                </div>
                            </div>

                            <div class="user_info">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Quickview -->
        </div>
    </div>
@endsection
@section('js')
    <!-- Vendors JS -->
    <script src="{{ asset('assets/admin/vendor/libs/bootstrap-maxlength/bootstrap-maxlength.js') }}"></script>
    <!-- Page JS -->
    <script src="{{ asset('assets/admin/vendor/libs/block-ui/block-ui.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment-timezone@0.5.34/builds/moment-timezone-with-data.min.js"></script>
    <script src="{{ asset('assets/admin/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="https://malsup.github.io/jquery.blockUI.js"></script>
    <script>
        'use strict';

        document.addEventListener('DOMContentLoaded', function() {
            (function() {
                const chatContactsBody = document.querySelector('.app-chat-contacts .sidebar-body'),
                    chatContactListItems = [].slice.call(
                        document.querySelectorAll('.chat-contact-list-item:not(.chat-contact-list-item-title)')
                    ),
                    chatHistoryBody = document.querySelector('.chat-history-body'),
                    chatSidebarLeftBody = document.querySelector('.app-chat-sidebar-left .sidebar-body'),
                    chatSidebarRightBody = document.querySelector('.app-chat-sidebar-right .sidebar-body'),
                    chatUserStatus = [].slice.call(document.querySelectorAll(
                        ".form-check-input[name='chat-user-status']")),
                    chatSidebarLeftUserAbout = $('.chat-sidebar-left-user-about'),
                    formSendMessage = document.querySelector('.form-send-message'),
                    messageInput = document.querySelector('.message-input'),
                    searchInput = document.querySelector('.chat-search-input'),
                    userStatusObj = {
                        active: 'avatar-online',
                        offline: 'avatar-offline',
                        away: 'avatar-away',
                        busy: 'avatar-busy'
                    };
                let currentPage = 1;
                let chatPerPage = 10;
                let totalDataChat = 0;
                let currentPageChatRoom = 1;
                let totalAvailablePages = 1;
                const chatRoomPerPage = 10;
                const audioContext = new(window.AudioContext || window.webkitAudioContext)();
                let isMuted = true;
                let loading = false,
                    loadingMessage = false;
                let currentSearch = '';
                let currentFilter = 'all';
                let searchTimeout;
                let chatId;
                let userInfoAdded = false;
                let chatHistoryScrollbar;
                let isScrolling = false;
                let currentChatId = null;
                let loadedUsers = [];

                function timeAgo(datetime) {
                    const now = new Date();
                    const past = new Date(datetime);
                    let timeDiff = Math.floor((now - past) / 1000); // Tính thời gian chênh lệch tính bằng giây

                    // Đảm bảo không có giá trị âm
                    if (timeDiff < 0) {
                        timeDiff = 0;
                    }

                    const seconds = timeDiff % 60;
                    const minutes = Math.floor(timeDiff / 60) % 60;
                    const hours = Math.floor(timeDiff / 3600) % 24;
                    const days = Math.floor(timeDiff / 86400); // 86400 = số giây trong 1 ngày

                    if (days > 0) {
                        return `${days} ngày${hours > 0 ? ` ${hours} tiếng` : ''} trước`;
                    } else if (hours > 0) {
                        return `${hours} tiếng${minutes > 0 ? ` ${minutes} phút` : ''} trước`;
                    } else if (minutes > 0) {
                        return `${minutes} phút${seconds > 0 ? ` ${seconds} giây` : ''} trước`;
                    } else {
                        return `${seconds} giây trước`;
                    }
                }



                // Hàm để lấy tham số filter từ URL
                function getCurrentChatId() {
                    const urlParams = new URLSearchParams(window.location.search);
                    const chatId = urlParams.get('chatId');
                    if (chatId) {
                        currentChatId = chatId;
                        loadHeaderMessageDetail(chatId);
                    }
                    return currentChatId;
                }


                // Chọn người
                window.selectChatContact = function(element) {
                    document.querySelectorAll('.chat-contact-list-item').forEach(item => {
                        item.classList.remove('active');
                    });
                    element.classList.add('active');
                    chatId = element.getAttribute('data-id-chat');
                    const url = new URL(window.location);
                    url.searchParams.set('chatId', chatId);
                    window.history.pushState({}, '', url);
                    loadingMessage = false;
                    // eventGetMessageRoom();
                    currentChatId = chatId;
                    loadHeaderMessageDetail(chatId);
                    loadMessages(chatId);
                    if (chatHistoryScrollbar) {
                        chatHistoryScrollbar.update();
                    }
                    eventGetMessageRoom();
                }

                function initializeInfiniteScroll() {
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting && !loading) {

                                // Kiểm tra nếu không có tìm kiếm hoặc nếu tìm kiếm có đủ dữ liệu để cuộn
                                if (currentSearch.length > 0 && totalDataChat > currentPage *
                                    chatPerPage) {
                                    loadListPerson(currentPage + 1, chatPerPage, currentFilter,
                                        currentSearch);
                                } else if (currentSearch.length === 0 && totalDataChat >
                                    currentPage *
                                    chatPerPage) {
                                    // Không có tìm kiếm, tiếp tục tải thêm dữ liệu theo trang
                                    loadListPerson(currentPage + 1, chatPerPage, currentFilter,
                                        currentSearch);
                                }
                            }
                        });
                    }, {
                        threshold: 1.0
                    });

                    const chatListContainer = document.getElementById('chat-list');
                    const lastItem = chatListContainer.lastElementChild;
                    if (lastItem) {
                        observer.observe(lastItem);
                    }
                }

                function updateActiveFilter(listPersons, currentFilter) {
                    listPersons.forEach(listPerson => {
                        let currentTargetData = listPerson.getAttribute('data-target');
                        if (currentTargetData === currentFilter) {
                            listPerson.classList.add('active');

                            loadNotifications(1, chatPerPage, currentTargetData);
                        } else {
                            listPerson.classList.remove('active');
                        }
                    });


                    if (currentChatId) {
                        const selectedPerson = document.querySelector(
                            `.chat-contact-list-item[data-id-chat="${currentChatId}"]`);
                        if (selectedPerson) {
                            selectedPerson.classList.add('active');
                        }
                        eventGetMessageRoom();
                        loadMessages(currentChatId)
                    }
                }

                function loadMessagesDetail(chatId, page = 1, perPage = 10, loadType = 'append') {
                    if (loadingMessage) return;
                    loadingMessage = true;
                    const chatHistoryWrapper = document.querySelector('.chat-history-wrapper');
                    const chatNone = document.querySelector('.chat-none');
                    const chatHistoryContainer = document.getElementById('chat-history');
                    $('#chat-history').block({
                        message: '<div class="spinner-border text-primary" role="status"></div>',
                        timeout: 0,
                        css: {
                            backgroundColor: 'transparent',
                            border: '0'
                        },
                        overlayCSS: {
                            backgroundColor: 'transparent',
                            opacity: 0.7
                        }
                    });

                    fetch(
                            `{{ route('admin.chat.getMessages') }}?chatId=${chatId}&page=${page}&per_page=${perPage}`
                        )
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.data) {
                                const userNameElement = document.getElementById('user_name');
                                const noteUserElement = document.getElementById('note_user');
                                const avatarUserElement = document.getElementById('avatar_user');
                                if (userNameElement) {
                                    userNameElement.textContent = data.room[0].name || 'Người dùng';
                                }
                                if (noteUserElement) {
                                    if (data.room[0].note) {
                                        noteUserElement.style.display = 'block'; // Hiển thị phần tử
                                        noteUserElement.style.overflow = 'hidden'; // Ẩn nội dung tràn
                                        noteUserElement.style.textOverflow = 'ellipsis'; // Thêm dấu "..."
                                        noteUserElement.style.whiteSpace = 'nowrap'; // Giới hạn 1 dòng
                                        noteUserElement.textContent = data.room[0].note;
                                    } else {
                                        noteUserElement.style.display = 'none'; // Ẩn phần tử
                                    }
                                }
                                if (avatarUserElement) {
                                    const avatarHTML = `
                                        <div class="flex-shrink-0 avatar avatar-${data.room[0].status}" data-bs-toggle="tooltip" data-bs-placement="top" title="${getStatusDetails(data.room[0].status).statusText}">
                                            <img src="{{ asset('be/assets/img/user/1.jpg') }}" alt="Avatar" class="rounded-circle" />
                                        </div>
                                    `;
                                    // Ghi đè nội dung
                                    avatarUserElement.innerHTML = avatarHTML;
                                }
                                chatHistoryWrapper.style.setProperty('display', 'block', 'important');
                                chatNone.style.setProperty('display', 'none', 'important');

                                totalAvailablePages = data.total_pages;
                                const messages = data.data.messages;
                                if (messages.length === 0) {
                                    stopLoadingOlderMessages();
                                    if (page == 1) {
                                        chatHistoryContainer.innerHTML =
                                            '<p class="none-message text-muted text-center mt-3 d-flex h-100 align-items-center justify-content-center">Không có tin nhắn nào</p>';
                                    }
                                    return; // Ngừng tải tin nhắn nếu không còn tin nhắn mới
                                } else {
                                    const noMessageElement = chatHistoryContainer.querySelector(
                                        '.none-message');
                                    if (noMessageElement) {
                                        noMessageElement.remove(); // Xóa phần tử `p.none-message`
                                    }
                                }

                                if (page === 1 && loadType === 'append') {
                                    chatHistoryContainer.innerHTML = ''; // Xóa nội dung cũ
                                }

                                let previousMessage = null;
                                let currentMessageGroup = null;

                                messages.forEach(message => {
                                    const formattedTime = timeAgo(formatTime(message.created_at));
                                    // Kiểm tra xem tin nhắn trước có cùng user_id và có thể nhóm lại
                                    if (previousMessage && previousMessage.user_id === message
                                        .user_id) {
                                        // Nếu tin nhắn là của người gửi trước đó, thêm vào nhóm tin nhắn hiện tại
                                        const additionalMessage = document.createElement('div');
                                        additionalMessage.className = 'chat-message-text mt-2';
                                        additionalMessage.setAttribute('data-user-id', message
                                            .user_id);
                                        additionalMessage.setAttribute('data-created-at-message',
                                            formatTime(message.created_at));
                                        additionalMessage.innerHTML = `
                                            <p class="mb-0">${message.message}</p>
                                            <div class="mt-1 ${message.type === 'reply' ? 'text-end text-muted' : 'text-muted'}">
                                                <small>${formattedTime}</small>
                                            </div>
                                        `;
                                        currentMessageGroup.querySelector('.chat-message-wrapper')
                                            .appendChild(additionalMessage);
                                    } else {
                                        // Nếu không phải người gửi trước đó, tạo một nhóm tin nhắn mới
                                        const messageItem = document.createElement('li');
                                        messageItem.className =
                                            `chat-message ${message.type === "reply" ? 'chat-message-right' : ''}`;
                                        messageItem.setAttribute('data-user-id', message.user_id);
                                        messageItem.innerHTML = `
                                            <div class="d-flex overflow-hidden">
                                                <div class="chat-message-wrapper flex-grow-1">
                                                    <div class="chat-message-text" data-created-at-message="${formatTime(message.created_at)}">
                                                        <p class="mb-0">${message.message}</p>
                                                        <div class="mt-1 ${message.type === 'reply' ? 'text-end text-muted' : 'text-muted'}">
                                                            <small>${formattedTime}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        `;

                                        if (loadType === 'prepend') {
                                            chatHistoryContainer.prepend(messageItem);
                                        } else {
                                            chatHistoryContainer.appendChild(messageItem);
                                        }

                                        currentMessageGroup =
                                            messageItem; // Cập nhật nhóm tin nhắn hiện tại
                                    }

                                    // Cập nhật previousMessage sau mỗi lần lặp
                                    previousMessage = message;
                                });



                                // Nếu là trang đầu tiên và kiểu tải là "append", cuộn xuống cuối sau khi tải tin nhắn mới
                                if (page === 1 && loadType === 'append') {
                                    scrollToBottom();
                                }

                                initializeInfiniteScrollMessage();
                            } else {
                                chatHistoryWrapper.style.setProperty('display', 'none', 'important');
                                chatNone.style.setProperty('display', 'block', 'important');
                                chatHistoryContainer.innerHTML =
                                    '<p class="none-message text-muted text-center mt-3 d-flex h-100 align-items-center justify-content-center">Đoạn chat không tồn tại hoặc đã bị xoá.</p>';
                            }
                        })
                        .catch(error => {
                            console.error('Lỗi khi tải tin nhắn:', error);
                        })
                        .finally(() => {
                            $('#chat-history').unblock();
                            loadingMessage = false;
                        });
                }

                function loadHeaderMessageDetail(chatId) {
                    const headerMessageDetail = document.querySelector('.chat-history-header');

                    headerMessageDetail.innerHTML =
                        `<div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex overflow-hidden align-items-center">
                                        <i class="ti ti-menu-2 ti-lg cursor-pointer d-lg-none d-block me-4"
                                            data-bs-toggle="sidebar" data-overlay data-target="#app-chat-contacts"></i>
                                            <div id="avatar_user">
                                                <div class="flex-shrink-0 avatar avatar-online" >
                                            <img src="{{ asset('be/assets/img/user/1.jpg') }}" alt="Avatar"
                                                class="rounded-circle" data-bs-toggle="sidebar" data-overlay
                                                data-target="#app-chat-sidebar-right" />
                                        </div>
                                                </div>


                                        <div class="chat-contact-info flex-grow-1 ms-4">
                                            <h6 class="m-0 fw-normal" id="user_name">Người dùng </h6>
                                            <h6 class="m-0 fw-normal" id="note_user" style="display:none;"></h6>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-icon btn-text-secondary text-secondary rounded-pill dropdown-toggle hide-arrow waves-effect waves-light" data-bs-toggle="dropdown" aria-expanded="true" id="chat-header-actions">
                                                <i class="ti ti-dots-vertical ti-md"></i>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="chat-header-actions">
                                                <a class="dropdown-item waves-effect" onclick="showUpdateModal(${chatId})">Cập nhật thông tin</a>
                                                <a class="dropdown-item waves-effect" onclick="deleteRoom(${chatId})">Xoá cuộc trò chuyện</a>
                                            </div>
                                        </div>
                                    </div>
                        </div>`;
                }




                function initializeInfiniteScrollMessage() {
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting && !loadingMessage &&
                                shouldLoadOlderMessages() && currentPageChatRoom <
                                totalAvailablePages) {
                                if (!stopLoadingOlderMessages()) {
                                    return;
                                }
                                currentPageChatRoom++;

                                loadMessagesDetail(currentChatId, currentPageChatRoom,
                                    chatRoomPerPage, 'prepend');
                                // Đẩy thanh cuộn xuống một chút sau khi tải tin nhắn mới
                                chatHistoryBody.scrollTop = 100;
                            }
                        });
                    }, {
                        root: document.getElementById(
                            'chat-history'),
                        threshold: 0.1
                    });

                    const chatListContainer = document.getElementById('chat-history');
                    const firstItem = chatListContainer.firstElementChild;

                    if (firstItem) {
                        observer.observe(firstItem);
                    }
                }

                function stopLoadingOlderMessages() {
                    // Kiểm tra lại điều kiện dừng tải tin nhắn cũ
                    return currentPageChatRoom >= totalAvailablePages;
                }

                function shouldLoadOlderMessages() {
                    const scrollTop = chatHistoryBody.scrollTop; // Vị trí thanh cuộn
                    const threshold = 0; // Ngưỡng cuộn để bắt đầu tải thêm tin nhắn
                    return scrollTop <= threshold;
                }
                chatHistoryBody.addEventListener('scroll', () => {
                    if (isScrolling) return;
                    isScrolling = true; // Đánh dấu là đang cuộn
                    // Sử dụng setTimeout để trì hoãn việc tải tin nhắn trong 500ms
                    setTimeout(() => {
                        const scrollTop = chatHistoryBody.scrollTop;
                        const scrollHeight = chatHistoryBody.scrollHeight;
                        const clientHeight = chatHistoryBody.clientHeight;
                        if (shouldLoadOlderMessages() && currentPageChatRoom <
                            totalAvailablePages) {
                            currentPageChatRoom++;
                            loadMessagesDetail(currentChatId, currentPageChatRoom,
                                chatRoomPerPage, 'prepend');
                        }
                        isScrolling = false;
                    }, 500);
                });




                function loadMessages(chatId) {
                    userInfoAdded = false;
                    loadingMessage = false;
                    const chatHistoryContainer = document.getElementById('chat-history');
                    chatHistoryContainer.innerHTML = '';
                    loadMessagesDetail(chatId);
                }

                function scrollToBottom() {
                    chatHistoryBody.scrollTo(0, chatHistoryBody.scrollHeight);
                }


                // Hàm kiểm tra sự kiện và xử lý thêm dữ liệu vào chat history hoặc chat list
                function checkUserMessage(event) {
                    console.log(event)
                    if (event.channel === 'GetMessageRoom') {
                        // Nếu kênh là GetMessageUser thì xử lý thêm tin nhắn mới vào chat history
                        const message = event.data.message;
                        const roomId = event.data.roomId;
                        // console.log(roomId, parseInt(currentChatId)) // đúng
                        // if (roomId === parseInt(currentChatId)) {
                        //     displayNewMessage({
                        //         room_id: event.data.roomId,
                        //         message: event.data.message,
                        //         type: "default",
                        //         created_at: new Date().toISOString(),
                        //         user_id: event.data.user_id
                        //     });
                        // }

                    }

                    if (event.channel === 'GetPerson') {
                        // Nếu kênh là GetPerson thì xử lý thêm người dùng vào danh sách
                        const user = event.data.message;
                        const formattedTime = timeAgo(formatTime(user.created_at));
                        const chatListContainerMain = document.getElementById('chat-list');
                        const noDataItem = chatListContainerMain.querySelector(
                            '.chat-contact-list-item-none');
                        if (noDataItem) {
                            noDataItem.style.display = 'none';
                        }
                        // Tạo phần tử mới cho người dùng
                        const listItem = document.createElement('li');
                        listItem.className = 'chat-contact-list-item mb-1';
                        listItem.setAttribute('data-id-chat', user.id);
                        listItem.setAttribute('data-target', user.id);
                        listItem.setAttribute('data-created-at-message-person', formatTime(user.created_at));
                        listItem.setAttribute('onclick', 'selectChatContact(this)');

                        listItem.innerHTML = `
                            <a class="d-flex align-items-center">
                                <div class="flex-shrink-0 avatar avatar-${user.status}" data-bs-toggle="tooltip" data-bs-placement="top" title="${getStatusDetails(user.status).statusText}">
                                    <img src="{{ asset('be/assets/img/user/1.jpg') }}" alt="Avatar" class="rounded-circle" />
                                </div>
                                <div class="chat-contact-info flex-grow-1 ms-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="chat-contact-name text-truncate fw-normal m-0">${user.name}</h6>
                                        <small class="text-muted">${formattedTime}</small>
                                    </div>
                                    <small class="">ID: ${user.user_id}</small>
                                    <small class="chat-contact-status text-truncate">${user.lastMessage || 'Không có tin nhắn'}</small>
                                </div>
                            </a>
                        `;

                        // Thêm người dùng vào danh sách
                        const chatListContainer = document.getElementById('chat-list');
                        chatListContainer.insertBefore(listItem, chatListContainer.firstChild);
                    }
                    if (event.channel === 'RefreshPerson') {
                        const chatId = event.data.message.chat_id;
                        console.log(event)
                        console.log(chatId)
                        editRefreshPerson(event, chatId)
                        if (chatId === parseInt(currentChatId)) {
                            const messageData = event.data?.message || event.message;
                            if (messageData) {
                                displayNewMessage({
                                    room_id: messageData.chat_id,
                                    message: messageData.message,
                                    type: "default",
                                    created_at: new Date().toISOString(),
                                    user_id: messageData.user_id
                                });
                            }
                        }
                    }
                    if (!isMuted) {
                        playSound("{{ asset('client/sound/notification.mp3') }}");
                    }
                }

                function editRefreshPerson(event, chatId) {
                    const chatList = document.getElementById("chat-list");
                    const existingChatItem = document.querySelector(
                        `.chat-contact-list-item[data-id-chat="${chatId}"]`);
                    console.log(event)
                    // console.log(event && event.message && event.lastMessage)
                    // console.log(event, event.message, event.lastMessage)

                    if (event && existingChatItem && event.type) {
                        const formattedTime = timeAgo(formatTime(event.created_at));
                        existingChatItem.setAttribute('data-created-at-message-person', formatTime(event
                            .created_at));
                        existingChatItem.querySelector('.chat-contact-name').textContent = event.name ??
                            'Người dùng';
                        existingChatItem.querySelector('.chat-contact-status').textContent = event.type ===
                            'default' ?
                            'Khách: ' + (event.message || 'Không có tin nhắn') :
                            'Nhân viên: ' + (event.message || 'Không có tin nhắn');
                        existingChatItem.querySelector('small.text-muted').textContent = formattedTime;
                        chatList.removeChild(existingChatItem);
                        chatList.insertBefore(existingChatItem, chatList.firstChild);
                    } else if (event && event.data.message && event.data.lastMessage) {

                        const formattedTime = timeAgo(formatTime(event.data.lastMessage.time));
                        existingChatItem.setAttribute('data-created-at-message-person', formatTime(event.data
                            .lastMessage.time));
                        // existingChatItem.querySelector('.chat-contact-name').textContent = event.data.message.name ??
                        //     'Người dùng';
                        existingChatItem.querySelector('.chat-contact-status').textContent = event.data.message
                            .type ==
                            'default' ?
                            'Khách: ' + (event.data.message.message || 'Không có tin nhắn') :
                            'Nhân viên: ' + (event.data.message.message || 'Không có tin nhắn');
                        existingChatItem.querySelector('small.text-muted').textContent = formattedTime;
                        chatList.removeChild(existingChatItem);
                        chatList.insertBefore(existingChatItem, chatList.firstChild);
                    } else {
                        loadListPerson(currentPage, chatPerPage, currentFilter, currentSearch);
                    }
                }

                function eventGetMessageRoom() {
                    if (currentChatId) {
                        if (!window.isListenerAttached) {
                            window.Echo.channel('laravel_database_GetMessageRoom.' + parseInt(currentChatId))
                                .listen('.message', (event) => {
                                    checkUserMessage({
                                        channel: 'GetMessageRoom',
                                        data: event
                                    });
                                });
                            window.isListenerAttached = true;
                        }
                    }
                }



                window.Echo.channel('laravel_database_GetPerson')
                    .listen('.get', (event) => {
                        checkUserMessage({
                            channel: 'GetPerson',
                            data: event
                        });
                    });
                window.Echo.channel('laravel_database_RefreshPerson')
                    .listen('.refresh', (event) => {
                        checkUserMessage({
                            channel: 'RefreshPerson',
                            data: event
                        });
                    });

                function formatTime(utcTime) {
                    moment.tz.setDefault("Asia/Ho_Chi_Minh");
                    return moment(utcTime).tz("Asia/Ho_Chi_Minh").format("YYYY-MM-DD HH:mm:ss");
                }

                function loadListPerson(page = 1, perPage = chatPerPage, filterType = currentFilter, search =
                    currentSearch) {
                    if (loading) return;
                    loading = true;

                    $('#chat-list').block({
                        message: '<div class="spinner-border text-primary" role="status"></div>',
                        timeout: 0,
                        css: {
                            backgroundColor: '#FFFFFF',
                            border: '0'
                        },
                        overlayCSS: {
                            backgroundColor: '#FFFFFF',
                            opacity: 1
                        }
                    });
                    fetch(
                            `{{ route('admin.chat.getListPerson') }}?page=${page}&per_page=${perPage}&type=${filterType}&search=${search}`
                        )
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.data) {
                                totalDataChat = data.data.total;
                                const users = data.data;
                                // loadedUsers = users;

                                const chatListContainer = document.getElementById('chat-list');

                                if (users.length == 0) {
                                    const noDataItem = document.createElement('li');
                                    noDataItem.className =
                                        'chat-contact-list-item chat-contact-list-item-none chat-list-item-0';
                                    noDataItem.innerHTML =
                                        '<h6 class="text-muted mb-0">Không tìm thấy người dùng nào</h6>';
                                    chatListContainer.appendChild(noDataItem);
                                } else {
                                    const noDataItem = chatListContainer.querySelector(
                                        '.chat-contact-list-item-none');
                                    if (noDataItem) {
                                        noDataItem.style.display = 'none';
                                    }
                                    users.forEach(user => {
                                        const formattedTime = timeAgo(formatTime(user.created_at));
                                        const listItem = document.createElement('li');
                                        listItem.className = 'chat-contact-list-item mb-1';
                                        listItem.setAttribute('data-id-chat', user.id);
                                        listItem.setAttribute('data-target', user.id);
                                        const messageTime = user.lastMessage_created_at ?
                                            formatTime(user.lastMessage_created_at) : formatTime(
                                                user.created_at);
                                        listItem.setAttribute('data-created-at-message-person',
                                            messageTime);
                                        listItem.setAttribute('onclick', 'selectChatContact(this)');

                                        listItem.innerHTML = `
                                            <a class="d-flex align-items-center">
                                                <div class="flex-shrink-0 avatar avatar-${user.status}" data-bs-toggle="tooltip" data-bs-placement="top" title="${getStatusDetails(user.status).statusText}">
                                                    <img src="{{ asset('be/assets/img/user/1.jpg') }}" alt="Avatar" class="rounded-circle" />
                                                </div>
                                                <div class="chat-contact-info flex-grow-1 ms-4">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <h6 class="chat-contact-name text-truncate fw-normal m-0">${user.name}</h6>
                                                        <small class="text-muted">${formattedTime}</small>
                                                    </div>
                                                    <small class="">ID: ${user.user_id}</small>
                                                    <small class="chat-contact-status text-truncate">${user.lastMessage || 'Không có tin nhắn'}</small>
                                                </div>
                                            </a>
                                        `;
                                        chatListContainer.appendChild(listItem);
                                    });

                                    // Only initialize the observer when new items are added
                                    const lastItem = chatListContainer.lastElementChild;
                                    if (lastItem) {
                                        initializeInfiniteScroll();
                                    }
                                    const chatContactList = document.querySelectorAll(
                                        '.chat-contact-list li');
                                    updateActiveFilter(chatContactList, currentFilter);
                                }
                            }
                        })
                        .catch(error => console.error('Lỗi khi tải thông báo:', error))
                        .finally(() => {
                            $('#chat-list').unblock();
                            loading = false;
                        });
                }



                // Lọc người
                if (searchInput) {
                    searchInput.addEventListener('keyup', e => {
                        clearTimeout(searchTimeout);
                        currentSearch = e.currentTarget.value;
                        searchTimeout = setTimeout(() => {
                            const chatListContainer = document.getElementById('chat-list');
                            chatListContainer.innerHTML = '';
                            loadListPerson(1, chatPerPage, currentFilter, currentSearch);
                        }, 500); // Debounce
                    });
                }

                window.getStatusDetails = function(status) {
                    let statusText = '';
                    let badgeClass = '';

                    switch (status) {
                        case 0:
                            statusText = 'Mới';
                            badgeClass = 'bg-primary';
                            break;
                        case 1:
                            statusText = 'Đang xử lý';
                            badgeClass = 'bg-warning';
                            break;
                        case 2:
                            statusText = 'Đợi phản hồi';
                            badgeClass = 'bg-info';
                            break;
                        case 3:
                            statusText = 'Đang xử lý thêm';
                            badgeClass = 'bg-secondary';
                            break;
                        case 4:
                            statusText = 'Đã giải quyết';
                            badgeClass = 'bg-success';
                            break;
                        case 5:
                            statusText = 'Tạm dừng';
                            badgeClass = 'bg-danger';
                            break;
                        case 6:
                            statusText = 'Chưa giải quyết';
                            badgeClass = 'bg-dark';
                            break;
                        case 7:
                            statusText = 'Đã đóng';
                            badgeClass = 'bg-muted';
                            break;
                        default:
                            statusText = 'Không xác định';
                            badgeClass = 'bg-light';
                            break;
                    }

                    return {
                        statusText,
                        badgeClass
                    };
                }

                // Dữ liệu các lệnh
                const commands = [{
                        command: '/xinchao',
                        description: 'Lời chào nhanh'
                    },
                    {
                        command: '/tambiet',
                        description: 'Lời tạm biệt nhanh'
                    },
                    {
                        command: '/dung',
                        description: 'Dừng cuộc trò chuyện'
                    },
                    {
                        command: '/xoa',
                        description: 'Xóa cuộc trò chuyện'
                    }
                ];

                // Hàm tạo hoặc lấy phần tử commandListContainer
                function getCommandListContainer() {
                    let commandListContainer = document.getElementById('commandListContainer');
                    if (!commandListContainer) {
                        commandListContainer = document.createElement('div');
                        commandListContainer.id = 'commandListContainer';
                        commandListContainer.classList.add('command-list');
                        formSendMessage.appendChild(commandListContainer);
                    }
                    return commandListContainer;
                }

                // Hàm hiển thị danh sách các lệnh phù hợp
                function displayCommands(inputText) {
                    const commandListContainer = getCommandListContainer();
                    if (!inputText.startsWith('/')) {
                        commandListContainer.style.display = 'none';
                        return;
                    }

                    const filteredCommands = commands.filter(item => item.command.startsWith(inputText));
                    if (filteredCommands.length > 0) {
                        commandListContainer.style.display = 'block';
                        commandListContainer.innerHTML = filteredCommands.map(item => `
                            <div class="command-item" data-command="${item.command}">
                                <strong>${item.command}</strong> - ${item.description}
                            </div>
                        `).join('');
                    } else {
                        commandListContainer.style.display = 'none';
                    }
                }

                // Hàm xử lý sự kiện click vào lệnh
                function handleCommandItemClick(e) {
                    if (e.target.classList.contains('command-item')) {
                        messageInput.value = e.target.getAttribute('data-command');
                        getCommandListContainer().style.display = 'none';
                    }
                }

                // Hàm xử lý khi người dùng nhấn phím ESC hoặc Enter
                function handleKeyDown(e) {
                    if (e.key === 'Escape' || e.key === 'Enter') {
                        getCommandListContainer().style.display = 'none';
                    }
                    if (e.key === 'Enter' && !e.shiftKey) {
                        // Nhấn Enter mà không giữ Shift để gửi tin nhắn
                        e.preventDefault();
                        sendMessage();
                    }
                }

                // Hàm xử lý sự kiện click vào label với for="short-code"
                function handleLabelClick() {
                    const commandListContainer = getCommandListContainer();
                    const isCommandListVisible = commandListContainer.style.display === 'block';

                    if (isCommandListVisible) {
                        commandListContainer.style.display = 'none';
                    } else {
                        messageInput.value = '';
                        displayCommands('/');
                    }
                }

                // Hàm gắn các sự kiện
                function setupEventListeners() {
                    messageInput.addEventListener('input', (e) => displayCommands(e.target.value.trim()));
                    document.addEventListener('click', handleCommandItemClick);
                    messageInput.addEventListener('keydown', handleKeyDown);
                    formSendMessage.addEventListener('submit', handleSendMessage);
                    const label = document.querySelector('label[for="short-code"]');
                    if (label) {
                        label.addEventListener('click', handleLabelClick);
                    }
                }

                function handleSendMessage(e) {
                    e.preventDefault();
                    sendMessage();
                }

                function sendMessage() {
                    const messageText = messageInput.value.trim();
                    if (messageText.length === 0) {
                        return notifyMe('error', 'Vui lòng nhập nội dung');
                    }

                    fetch('{{ route('admin.chat.sendMessageToUser') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                message: messageText,
                                roomId: currentChatId,
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log(data)
                            if (!data.success) {
                                return notifyMe('error', data.message);
                            }
                            if (data.success && data.type == "delete") {
                                window.location.reload();
                                return;
                            }

                            // Kiểm tra xem data.data có tồn tại và chứa các thuộc tính cần thiết
                            if (data.data && data.data.message) {
                                displayNewMessage({
                                    message: data.data.message,
                                    type: data.data.type,
                                    created_at: data.data.created_at,
                                    user_id: data.data.user_id,
                                    room_id: data.data.chat_id,
                                    name: data.data.name,
                                });
                                messageInput.value = '';
                                scrollToBottom();
                            } else {
                                console.error('Dữ liệu trả về không đúng định dạng:', data);
                                notifyMe('error', 'Dữ liệu trả về không hợp lệ.');
                            }

                        })
                        .catch(error => console.error('Lỗi khi gửi tin nhắn:', error));
                }

                function displayNewMessage(message) {
                    const chatHistoryContainer = document.getElementById('chat-history');
                    const formattedTime = timeAgo(formatTime(message.created_at));
                    const lastMessageGroup = chatHistoryContainer.lastElementChild;
                    const noMessageElement = chatHistoryContainer.querySelector(
                        '.none-message');
                    if (noMessageElement) {
                        noMessageElement.remove();
                    }
                    // Kiểm tra xem tin nhắn cuối cùng trong lịch sử trò chuyện có phải từ cùng một người dùng
                    if (lastMessageGroup && lastMessageGroup.classList.contains('chat-message') &&
                        lastMessageGroup.getAttribute('data-user-id') == message.user_id) {
                        const additionalMessage = document.createElement('div');
                        additionalMessage.className = 'chat-message-text mt-2';
                        additionalMessage.setAttribute('data-created-at-message', formatTime(message
                            .created_at));
                        additionalMessage.innerHTML = `
                            <p class="mb-0">${message.message}</p>
                            <div class="mt-1 ${message.type === 'reply' ? 'text-end text-muted' : 'text-muted'}">
                                <small>${formattedTime}</small>
                            </div>
                        `;
                        lastMessageGroup.querySelector('.chat-message-wrapper').appendChild(additionalMessage);
                    } else {
                        // Tạo nhóm tin nhắn mới cho người gửi mới
                        const messageItem = document.createElement('li');
                        messageItem.className =
                            `chat-message ${message.type === "reply" ? 'chat-message-right' : ''}`;
                        messageItem.setAttribute('data-user-id', message.user_id);

                        messageItem.innerHTML = `
                            <div class="d-flex overflow-hidden">
                                <div class="chat-message-wrapper flex-grow-1">
                                    <div class="chat-message-text" data-created-at-message="${formatTime(message.created_at)}">
                                        <p class="mb-0">${message.message}</p>
                                        <div class="mt-1 ${message.type === 'reply' ? 'text-end text-muted' : 'text-muted'}">
                                            <small>${formattedTime}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        chatHistoryContainer.appendChild(messageItem);
                    }
                    // if (message && message.room_id) {
                    //     console.log(message)
                    //     editRefreshPerson(message, message.room_id);
                    // }
                    scrollToBottom();
                }

                function playSound(filePath) {
                    if (isMuted) return;
                    fetch(filePath)
                        .then(response => response.arrayBuffer())
                        .then(arrayBuffer => audioContext.decodeAudioData(arrayBuffer))
                        .then(audioBuffer => {
                            const soundSource = audioContext.createBufferSource();
                            soundSource.buffer = audioBuffer;
                            soundSource.connect(audioContext.destination);
                            soundSource.start();
                        })
                        .catch(error => console.error('Error playing sound:', error));
                }

                $('#soundNotification').on('click', function() {
                    if (audioContext.state === 'suspended') {
                        audioContext.resume();
                    }
                    if ($(this).hasClass('fa-volume-high')) {
                        $(this).removeClass('fa-volume-high').addClass('fa-volume-xmark');
                        isMuted = true;
                    } else {
                        $(this).removeClass('fa-volume-xmark').addClass('fa-volume-high');
                        isMuted = false;
                    }
                });

                function updateMessageTimesA(selector) {
                    $(selector).each(function() {
                        // Sử dụng .attr() để lấy giá trị cập nhật mới nhất từ thuộc tính HTML
                        const createdAt = $(this).attr('data-created-at-message');
                        const createdAtPerson = $(this).attr('data-created-at-message-person');
                        const timestamp = createdAt || createdAtPerson;

                        if (timestamp) {
                            const formattedTime = timeAgo(timestamp);
                            $(this).find('.text-muted').text(formattedTime);
                        }
                    });

                    // Gọi lại hàm theo thời gian thực để cập nhật liên tục
                    requestAnimationFrame(() => updateMessageTimesA(selector));
                }



                function initializeChat() {
                    getCurrentChatId();
                    loadListPerson();
                    initializeInfiniteScroll();
                    scrollToBottom();
                    setupEventListeners();
                    updateMessageTimesA(
                        '.chat-contact-list .chat-contact-list-item,.chat-history .chat-message-text');

                }
                initializeChat();

                if (chatContactsBody) {
                    new PerfectScrollbar(chatContactsBody, {
                        wheelPropagation: false,
                        suppressScrollX: true
                    });
                }

                if (chatHistoryBody) {
                    chatHistoryScrollbar = new PerfectScrollbar(chatHistoryBody, {
                        wheelPropagation: false,
                        suppressScrollX: true
                    });
                }

                if (chatSidebarLeftBody) {
                    new PerfectScrollbar(chatSidebarLeftBody, {
                        wheelPropagation: false,
                        suppressScrollX: true
                    });
                }

                if (chatSidebarRightBody) {
                    new PerfectScrollbar(chatSidebarRightBody, {
                        wheelPropagation: false,
                        suppressScrollX: true
                    });
                }



                if (chatSidebarLeftUserAbout.length) {
                    chatSidebarLeftUserAbout.maxlength({
                        alwaysShow: true,
                        warningClass: 'label label-success bg-success text-white',
                        limitReachedClass: 'label label-danger',
                        separator: '/',
                        validate: true,
                        threshold: 120
                    });
                }
                $('#quickview_modal').on('hide.bs.modal', function(e) {
                    $('#quickview_modal .user_info').html(null);
                });

                window.showUpdateModal = function(roomId) {
                    $('.modal').modal('hide');
                    $('#quickview_modal .user_info').html(null);
                    $('.data-preloader-wrapper>div').addClass('spinner-border');
                    $('.data-preloader-wrapper').addClass('min-h-400');
                    $('#quickview_modal').modal('show');

                    $.post('{{ route('admin.chat.showInfoUser') }}', {
                        _token: '{{ csrf_token() }}',
                        id: roomId
                    }, function(data) {
                        setTimeout(() => {
                            $('.data-preloader-wrapper>div').removeClass('spinner-border');
                            $('.data-preloader-wrapper').removeClass('min-h-400');
                            $('#quickview_modal .user_info').html(data);
                        }, 200);
                    });
                }
                window.deleteRoom = function(roomId) {
                    $.post('{{ route('admin.chat.deleteChatUser', ['id' => '__ROOM_ID__']) }}'.replace(
                        '__ROOM_ID__', roomId), {
                        _token: '{{ csrf_token() }}'
                    }, function(data) {
                        if (data.success) {
                            window.location.reload();
                        }
                    });
                }
                // }
            })();
        });
    </script>

    <script></script>
@endsection
