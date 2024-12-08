@extends('layouts.alternative')

@section('canonical', '')

@section('additional_head')
<title>Мои переписки</title>
<meta name="description" content="Мои переписки на портале Стройка.com. Здесь вы можете просматривать и управлять своими переписками с заказчиками и строителями.">
@endsection

@section('content')
<script>
let currentUserId = null;
let existingConversations = new Set();

$(document).ready(function() {
    const urlParams = new URLSearchParams(window.location.search);
    const supplierUserId = urlParams.get('supplier_id');
    
    if (supplierUserId) {
        try {
            ym(97430601,'reachGoal','legal_contant');
            console.log('Yandex Metrika reached');
        } catch (error) {
            console.error('Yandex Metrika error:', error);
        }
        
        // Wait for the modal to be fully initialized
        $('#composeMessageModal').on('shown.bs.modal', function() {
            // Get supplier details and open compose modal
            $.get(`/api/users/${supplierUserId}`, function(data) {
                console.log('Modal shown, data received:', data);
                if (data.user) {
                    console.log('data.user', data.user);
                    // Add the supplier to the recipient dropdown if not already present
                    if (!existingConversations.has(parseInt(supplierUserId))) {
                        console.log('Adding supplier to recipient dropdown');
                        // Create a new option element properly
                        const option = document.createElement('option');
                        option.value = supplierUserId;
                        option.textContent = data.user.name;
                        
                        // Get the recipient select element
                        const recipientSelect = document.getElementById('recipient');
                        if (recipientSelect) {
                            // Remove any existing option with the same value
                            const existingOption = recipientSelect.querySelector(`option[value="${supplierUserId}"]`);
                            if (existingOption) {
                                existingOption.remove();
                            }
                            
                            recipientSelect.appendChild(option);
                            recipientSelect.value = supplierUserId; // Set the selection
                            console.log('Added and selected supplier:', supplierUserId);
                        } else {
                            console.error('Recipient select element not found');
                        }
                        $('#composeMessageModal').modal('show');
                    }
                }
            });
        });
        
        // Show the modal
        
    }

    let currentUserId = {{ Auth::id() }};
    // Populate existing conversations in the recipient select
    @foreach($messages as $userId => $conversation)
        userId = {{ $userId }};
        existingConversations.add(userId);
        if (userId != 7) {
            $('#recipient').append(`<option value="${userId}">${{!! json_encode($conversation['user']->name) !!}}</option>`);
        }
    @endforeach

    $(document).on('click', '.message-row', function(e) {
        e.preventDefault();
        let userId = $(this).data('user-id');
        if (!userId) {
            console.error('Invalid user ID');
            return;
        }
        console.log('Message row clicked. User ID:', userId);
        loadConversation(userId);
        
        // Enable the message input and send button
        $('#message-input, #message-form button[type="submit"], #attach-file').prop('disabled', false);
        
        // Mark as read if there's an unread dot
        const unreadDot = $(this).find('.unread-dot');
        if (unreadDot.length) {
            const messageId = unreadDot.data('message-id');
            if (messageId) {
                markAsRead(messageId);
                unreadDot.remove();
            }
        }
    });

    function loadConversation(userId) {
        if (!userId) {
            console.error('Attempted to load conversation with undefined or null userId');
            return;
        }

        currentUserId = userId;
        $.get(`/messages/${userId}`, function(data) {
            if (!data || !data.otherUser) {
                console.error('Invalid data received from server');
                return;
            }

            let lastSeen = data.otherUser.last_seen || 'Unknown';
            const isOnline = lastSeen === 'Online';
            if (userId == 7) {
                lastSeen = 'Файлы и длинные сообщения присылайте на <a href="mailto:info@стройка.com">info@стройка.com</a>';
            }
            $('#chat-header').html(`
                <div class="d-flex align-items-center">
                    <img src="${data.otherUser.avatar || '/default-avatar.png'}" alt="" class="rounded-circle" width="40">
                    <div class="ms-3">
                        <h5 class="mb-0 font-w600 d-flex align-items-center">
                            ${data.otherUser.name || 'Unknown User'}
                            ${userId == 7 ? '<i class="fas fa-headset ms-1 text-primary" title="Тех поддержка"></i>' : ''}
                            ${isOnline ? '<span class="online-indicator ms-2"></span>' : ''}
                        </h5>
                        <small class="text-muted">${lastSeen}</small>
                    </div>
                </div>
            `);

            let messagesHtml = '';
            if (!data.messages || data.messages.length === 0) {
                messagesHtml = '<div class="text-center py-5"><p class="text-muted">У вас пока нет сообщений</p></div>';
            } else {
                data.messages.forEach(function(message) {
                    const messageClass = message.sender_id == {{ Auth::id() }} ? 'sent' : 'received';
                    const supportClass = message.is_support ? 'support' : '';
                    const supportIcon = message.is_support ? '<i class="fas fa-headset ms-1 text-primary" title="Тех поддержка"></i>' : '';
                    
                    // Provide a fallback for sender_name
                    const senderName = message.sender_name || 'Неизвестный отправитель';
                    
                    let subjectHtml = '';
                    if (message.subject) {
                        subjectHtml = `<div class="message-subject"><strong>${message.subject}</strong></div>`;
                    }

                    let projectInfo = '';
                    if (message.project) {
                        projectInfo = `
                            <div class="project-info">
                                <strong>${senderName} хотел направить вам сообщение о смете проекта <a href="${message.project.link}" target="_blank">${message.project.title}</a> </strong>
                                <br>
                                
                                <strong>Скачать смету: <a href="${message.project.filepath}" download></strong>
                                    <i class="fas fa-file-excel fa-lg" style="color: #217346;"></i>
                                </a>
                                <br>
                                <br>
                                <strong>Сообщение от ${senderName}: </strong>
                                <br>
                            </div>
                        `;
                    }

                    messagesHtml += `
                        <div class="message ${messageClass} ${supportClass}" data-message-id="${message.id}" data-timestamp="${message.created_at}">
                            ${subjectHtml}
                            ${projectInfo}
                            <p>${message.content}</p>
                            <small class="message-timestamp">${message.created_at} ${supportIcon}</small>
                        </div>
                    `;

                    if (message.attachments && message.attachments.length > 0) {
                        message.attachments.forEach(function(attachment) {
                            const icon = attachment.mime_type.includes('image') ? 'fa-file-image' : attachment.mime_type.includes('excel') ? 'fa-file-excel' : 'fa-file-pdf';
                            if (icon == 'fa-file-image') {
                                messagesHtml += `<img style="max-width: 50%;" src="${attachment.url}" alt="${attachment.filename}"/>`;
                            }
                            messagesHtml += `
                                <div class="attachment">
                                    <a href="${attachment.url}" target="_blank">
                                        <i class="fas ${icon}"></i> ${attachment.filename}
                                    </a>
                                    (${(attachment.size / 1024).toFixed(2)} KB)
                                </div>
                            `;
                        });
                    }
                });
            }
            $('#chat-messages').html(messagesHtml);
            
            // Mark the last message as read if it's not from the current user
            const lastMessage = data.messages && data.messages.length > 0 ? data.messages[data.messages.length - 1] : null;
            if (lastMessage && lastMessage.sender_id != {{ Auth::id() }} && !lastMessage.is_read) {
                markAsRead(lastMessage.id);
            }

            scrollChatToBottom();
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Error loading conversation:", textStatus, errorThrown);
        });

        // Enable the message input and send button
        $('#message-input, #message-form button[type="submit"], #attach-file').prop('disabled', false);
    }

    // Add this new function to scroll the chat to the bottom
    function scrollChatToBottom() {
        const chatContainer = document.getElementById('chat-messages');
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    function markAsRead(messageId) {
        if (!messageId) {
            console.error('Attempted to mark message as read with undefined ID');
            return;
        }
        $.ajax({
            url: `/messages/${messageId}/read`,
            method: 'PUT',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                console.log('Message marked as read:', messageId);
                updateUnreadCount(); // Update the unread count
            },
            error: function(error) {
                console.error('Error marking message as read:', error);
            }
        });
    }

    // Remove all existing event handlers from the form
    $('#message-form').off();

    // Attach a single submit handler
    $('#message-form').on('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log('Form submitted. Current User ID:', currentUserId);
        if (!currentUserId) {
            console.error('No recipient selected');
            alert('Пожалуйста, выберите получателя');
            return;
        }

        const formData = new FormData(this);
        formData.append('receiver_id', currentUserId);
        formData.append('content', $('#message-input').val());

        // Disable the submit button to prevent double submission
        $('#message-form button[type="submit"]').prop('disabled', true);

        $.ajax({
            url: '/messages',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#message-input').val('');
                $('#attachment').val('');
                $('#attach-file').html('<i class="fas fa-paperclip"></i>');
                loadConversation(currentUserId);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error sending message:", textStatus, errorThrown);
            },
            complete: function() {
                // Re-enable the submit button
                $('#message-form button[type="submit"]').prop('disabled', false);
            }
        });
    });

    // Prevent default form submission on enter key
    $('#message-input').on('keypress', function(e) {
        if (e.which === 13 && !e.shiftKey) {
            e.preventDefault();
            $('#message-form').submit();
        }
    });

    $('#sendMessageBtn').on('click', function() {
        const receiverId = $('#recipient').val();
        const subject = $('#subject').val();
        const content = $('#messageBody').val();

        if (!receiverId || !content) {
            alert('Пожалуйста, заполните все обязательные поля');
            return;
        }

        const formData = new FormData();
        formData.append('receiver_id', receiverId);
        formData.append('content', content);
        formData.append('subject', subject);
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: '/messages',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#composeMessageModal').modal('hide');
                $('#recipient').val('');
                $('#subject').val('');
                $('#messageBody').val('');
                loadConversation(receiverId);
            },
            error: function(error) {
                console.error('Error sending message:', error);
                alert('Произошла ошибка при отправке сообщения');
            }
        });
    });

    function sendMessageForm() {
        if (!currentUserId) {
            console.error('No recipient selected');
            alert('Пожалуйста, выберите получателя');
            return;
        }

        const content = $('#message-input').val();
        sendMessage(currentUserId, content);
    }

    // Initialize with empty conversation and disabled input
    $('#chat-messages').html('<div class="text-center py-5"><p class="text-muted">Выберите получателя для начала переписки</p></div>');
    $('#message-input, #message-form button[type="submit"], #attach-file').prop('disabled', true);

    // Add this new function for checking new messages
    function checkNewMessages() {
        if (!currentUserId) return;

        const lastMessageTimestamp = $('.message:last').data('timestamp') || 0;
        console.log('Checking for new messages since:', lastMessageTimestamp);
        
        $.get(`/messages/${currentUserId}/new`, { last_timestamp: lastMessageTimestamp }, function(data) {
            console.log('Received messages:', data.messages);
            if (data.messages && data.messages.length > 0) {
                appendNewMessages(data.messages);
            }
        });
    }

    function appendNewMessages(messages) {
        let messagesHtml = '';
        messages.forEach(function(message) {
            // Only append messages that are newer than the last displayed message
            if (new Date(message.created_at) > new Date($('.message:last').data('timestamp'))) {
                const messageClass = message.sender_id == {{ Auth::id() }} ? 'sent' : 'received';
                const supportClass = message.is_support ? 'support' : '';
                const supportIcon = message.is_support ? '<i class="fas fa-headset ms-1 text-primary" title="Тех поддержка"></i>' : '';
                
                // Provide a fallback for sender_name
                const senderName = message.sender_name || 'Неизвестный отправитель';
                
                let projectInfo = '';
                if (message.project) {
                    projectInfo = `
                        <div class="project-info">
                            <strong>${senderName} хотел направить вам сообщение о смете поекта <a href="${message.project.link}" target="_blank">${message.project.title}</a> </strong>
                            <br>
                            
                            <strong>Скачать смету: <a href="${message.project.filepath}" download></strong>
                                <i class="fas fa-file-excel fa-lg" style="color: #217346;"></i>
                            </a>
                            <br>
                            <br>
                            <strong>Сообщение от ${senderName}: </strong>
                            <br>
                        </div>
                    `;
                }

                messagesHtml += `
                    <div class="message ${messageClass} ${supportClass}" data-message-id="${message.id}" data-timestamp="${message.created_at}">
                        ${projectInfo}
                        <p>${message.content}</p>
                        <small>${new Date(message.created_at).toLocaleTimeString()} ${supportIcon}</small>
                    </div>
                `;

                if (message.attachments && message.attachments.length > 0) {
                    message.attachments.forEach(function(attachment) {
                        const icon = attachment.mime_type.includes('pdf') ? 'fa-file-pdf' : 'fa-file-excel';
                        messagesHtml += `
                            <div class="attachment">
                                <a href="${attachment.url}" target="_blank">
                                    <i class="fas ${icon}"></i> ${attachment.filename}
                                </a>
                                (${(attachment.size / 1024).toFixed(2)} KB)
                            </div>
                        `;
                    });
                }
            }
        });
        
        if (messagesHtml) {
            $('#chat-messages').append(messagesHtml);
            scrollChatToBottom();
        }
    }

    // Check for new messages every 5 seconds
    setInterval(checkNewMessages, 20000);

    // Add this new function for message searching
    function searchMessages() {
        const searchTerm = $('#message-search').val().toLowerCase();
        $('.message-row').each(function() {
            const userName = $(this).find('h6').text().toLowerCase();
            const messageContent = $(this).find('p.mb-1').text().toLowerCase();
            if (userName.includes(searchTerm) || messageContent.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });

        // Update the "Непрочитанные" tab
        updateUnreadTab();
    }

    // Function to update the "Непрочитанные" tab
    function updateUnreadTab() {
        const visibleUnreadMessages = $('#AllMessage .unread-dot').closest('.message-row:visible');
        $('#Unread .list-group').html(
            visibleUnreadMessages.length > 0 
                ? visibleUnreadMessages.clone() 
                : '<div class="text-center py-5"><p class="text-muted">У вас нет непрочитанных сообщений</p></div>'
        );
    }

    // Add event listener for the search input
    $('#message-search').on('input', searchMessages);

    // Modify the updateMessageList function to call searchMessages after updating
    function updateMessageList(newMessages) {
        newMessages.forEach(function(message) {
            const existingRow = $(`.message-row[data-user-id="${message.sender_id}"]`);
            if (existingRow.length) {
                // Update existing conversation
                existingRow.find('p.mb-1').text(message.content);
                existingRow.find('small.text-muted:last').text(message.created_at);
                if (!existingRow.find('.unread-dot').length) {
                    existingRow.find('.d-flex.align-items-center').prepend('<span class="unread-dot me-2"></span>');
                }
                existingRow.prependTo('#AllMessage .list-group');
            } else {
                // Add new conversation
                const newRow = `
                    <div class="list-group-item list-group-item-action message-row" data-user-id="${message.sender_id}">
                        <div class="d-flex w-100 justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <span class="unread-dot me-2"></span>
                                <h6 class="mb-1">${message.sender_name}</h6>
                            </div>
                            <small class="text-muted">${message.created_at}</small>
                        </div>
                        <p class="mb-1">${message.content}</p>
                    </div>
                `;
                $('#AllMessage .list-group').prepend(newRow);
            }
        });

        // Update unread messages tab
        const unreadMessages = $('#AllMessage .unread-dot').length;
        $('#Unread .list-group').html(unreadMessages > 0 ? $('#AllMessage .unread-dot').closest('.list-group-item').clone() : '<div class="text-center py-5"><p class="text-muted">У вас нет непрочитанных сообщений</p></div>');

        // Call searchMessages to apply any active filters
        searchMessages();

        // Scroll to bottom if new messages were added
        if (newMessages.length > 0) {
            scrollChatToBottom();
        }
    }

    function updateUnreadCount() {
        $.get('/messages/unread-count', function(data) {
            const unreadCount = $('#AllMessage .message-row').filter(function() {
                // Only count messages where the user is the recipient and they're unread
                return $(this).find('.unread-dot').length > 0 && 
                       $(this).data('sender-id') != 11; // 11 is the current user ID
            }).length;
            
            $('#unread-count').text(unreadCount);
            if (unreadCount > 0) {
                $('#unread-count').show();
            } else {
                $('#unread-count').hide();
            }
        });
    }

    // Call this function periodically or after certain actions
    //setInterval(updateUnreadCount, 30000); // Update every 30 seconds

    $('#attach-file').on('click', function(e) {
        e.preventDefault();
        if (!currentUserId) {
            console.error('No recipient selected');
            alert('Пожалуйста, выберите получателя');
            return;
        }
        $('#attachment').click();
    });

    $('#attachment').on('change', function() {
        const file = this.files[0];
        if (file) {
            if (file.size > 500 * 1024) { // 500 KB
                alert('Файл слишком большой. Максимальный размер 500 КБ.');
                this.value = '';
            } else {
                $('#attach-file').html('<i class="fas fa-check"></i>');
            }
        }
    });

    // Automatically open the newest conversation
    
    const newestConversation = document.querySelector('.message-row');
    let relevantConversation = newestConversation;
    // If there's a supplier_id in the URL, open that conversation instead
    if (supplierUserId) {
        relevantConversation = document.querySelector(`.message-row[data-user-id="${supplierUserId}"]`);
        relevantConversation.click();
    } else {
        newestConversation.click();
    }

    // Add this function to populate the recipient dropdown
    function populateRecipientDropdown() {
        const recipientSelect = $('#recipient');
        
        // Keep the support option
        const supportOption = recipientSelect.find('option[value="7"]');
        recipientSelect.empty().append(supportOption);

        // Add other users from the message list
        $('.message-row').each(function() {
            const userId = $(this).data('user-id');
            const userName = $(this).find('h6').text().trim();
            
            // Skip if option already exists or if it's the current user
            if (userId != {{ Auth::id() }} && !recipientSelect.find(`option[value="${userId}"]`).length) {
                recipientSelect.append(new Option(userName, userId));
            }
        });
    }

    // Call this function after loading conversations and when opening the compose modal
    $('#composeMessageModal').on('show.bs.modal', function() {
        populateRecipientDropdown();
    });
});
</script>
<div class="container-fluid">
    @include('partials.tab-navigator', ['items' => [
        ['url' => '/my-orders', 'label' => 'Заказы'],
        ['url' => '/suppliers', 'label' => 'Строители'],
        ['url' => '/messages', 'label' => 'Мои переписки'],
        ['url' => '/profile', 'label' => 'Мои данные'],
    ]])
    <div class="row">
        <div class="col-xl-5">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Сообщения</h4>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#composeMessageModal">
                            <i class="las la-plus"></i> Новое сообщение
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <!-- Add this search box -->
                    <div class="p-3">
                        <input type="text" id="message-search" class="form-control" placeholder="Поиск сообщений...">
                    </div>
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#AllMessage" role="tab">Все сообщения</a>
                        </li>
                        <li class="nav-item" hidden>
                            <a class="nav-link" data-bs-toggle="tab" href="#Unread" role="tab">
                                Непрочитанные <span id="unread-count" class="badge bg-danger">{{ $unreadThreads }}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#Archived" role="tab">Архив</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="AllMessage" role="tabpanel">
                            <div class="list-group list-group-flush">
                                @forelse($messages as $userId => $conversation)
                                    <div class="list-group-item list-group-item-action message-row" data-user-id="{{ $userId }}">
                                        <div class="d-flex w-100 justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                @if(!$conversation['is_read'])
                                                    <span  data-message-id="{{ $conversation['last_message_id'] }}">
                                                        <i class="fas fa-envelope" style="color: #ff0000; margin-right: 5px;"></i>
                                                    </span>
                                                @endif
                                                <h6 class="mb-1 d-flex align-items-center">
                                                    {{ $conversation['sender_name'] }}
                                                    @if($userId == 7)
                                                        <i class="fas fa-headset ms-1 text-primary" title="Тех поддержка"></i>
                                                    @endif
                                                </h6>
                                            </div>
                                            <small class="text-muted">{{ $conversation['created_at']->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-1">{{ Str::limit($conversation['last_message'], 50) }}</p>
                                        @if($conversation['user']->last_seen === 'Online' && $userId != 7)
                                            <span class="online-indicator ms-2"></span>
                                            <small class="text-muted">{{ $conversation['user']->last_seen }}</small>
                                        @endif
                                    </div>
                                @empty
                                    <div class="text-center py-5">
                                        <p class="text-muted">У вас пока нет сообщений</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Unread" role="tabpanel">
                            <div class="list-group list-group-flush">
                                @forelse($messages->filter(function($conversation) { return !$conversation['is_read']; }) as $userId => $conversation)
                                    <div class="list-group-item list-group-item-action message-row" data-user-id="{{ $userId }}">
                                        <div class="d-flex w-100 justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <span class="unread-dot me-2"></span>
                                                <h6 class="mb-1">
                                                    {{ $conversation['user']->name }}
                                                    @if($userId == 7)
                                                        <i class="fas fa-headset ms-1 text-primary" title="Тех поддержка"></i>
                                                    @endif
                                                </h6>
                                            </div>
                                            <small>{{ $conversation['created_at']->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-1">{{ Str::limit($conversation['last_message'], 50) }}</p>
                                        <div class="mt-2">
                                            <button class="btn btn-sm btn-secondary mark-read" data-message-id="{{ $conversation['last_message_id'] }}">Отметить как прочитанное</button>
                                            <button class="btn btn-sm btn-danger delete-message" data-message-id="{{ $conversation['last_message_id'] }}">Удалить</button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-5">
                                        <p class="text-muted">У вас нет непрочитанных сообщений</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Archived" role="tabpanel">
                            <!-- Archived messages content -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-7">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center" id="chat-header">
                    <!-- This will be populated by JavaScript -->
                </div>
                <div class="card-body" style="height: 400px; overflow-y: auto;" id="chat-messages">
                    <!-- This will be populated by JavaScript -->
                </div>
                <div class="card-footer">
                    <form id="message-form" enctype="multipart/form-data">
                        @csrf
                        <div class="input-group">
                            <textarea class="form-control" id="message-input" placeholder="Напишите сообщение..." disabled></textarea>
                            <input type="file" id="attachment" name="attachment" accept=".xls,.xlsx,.pdf,.jpg,.jpeg,.png" style="display: none;">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-secondary" id="attach-file" disabled>
                                    <i class="fas fa-paperclip"></i>
                                </button>
                                <button type="submit" class="btn btn-primary" disabled>Отправить</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Compose Message Modal -->
<div class="modal fade" id="composeMessageModal" tabindex="-1" aria-labelledby="composeMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="composeMessageModalLabel">Новое сообщение</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="composeMessageForm">
                    <div class="mb-3">
                        <label for="recipient" class="form-label">Кому:</label>
                        <select class="form-select" id="recipient" required>
                            <option value="">Выберите получателя...</option>
                            <option value="7">Поддержка портала</option>
                            <!-- Other recipients will be populated by JavaScript -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Тема:</label>
                        <input type="text" class="form-control no-text-transform" id="subject">
                    </div>
                    <div class="mb-3">
                        <label for="messageBody" class="form-label">Сообщение:</label>
                        <textarea class="form-control no-text-transform" id="messageBody" rows="5" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary" id="sendMessageBtn">Отправить</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.delete-message').on('click', function(e) {
        e.stopPropagation();
        const messageId = $(this).data('message-id');
        if (confirm('Вы уверены, что хотите удалить это сообщение?')) {
            $.ajax({
                url: `/messages/${messageId}`,
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function() {
                    location.reload();
                },
                error: function() {
                    alert('Произошла ошибка при удалении сообщения.');
                }
            });
        }
    });

    $('.mark-read, .mark-unread').on('click', function(e) {
        e.stopPropagation();
        const messageId = $(this).data('message-id');
        const action = $(this).hasClass('mark-read') ? 'read' : 'unread';
        $.ajax({
            url: `/messages/${messageId}/${action}`,
            method: 'PUT',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function() {
                location.reload();
            },
            error: function() {
                alert(`Произошла ошибка при отметке сообщения как ${action === 'read' ? 'прочитанное' : 'непрочитанное'}.`);
            }
        });
    });
});
</script>
<style>
    .attachment-image {
        max-width: 50%;
        max-height: 50%;
    }

    .message-subject {
        margin-bottom: 8px;
        padding-bottom: 4px;
        border-bottom: 1px solid rgba(0,0,0,0.1);
    }

    .message.received .message-subject {
        border-bottom-color: rgba(0,0,0,0.1);
    }

    .message.sent .message-subject {
        border-bottom-color: rgba(255,255,255,0.2);
    }

    .message-timestamp {
        display: inline-block;
        color: rgba(0, 0, 0, 0.5);
        font-size: 0.8em;
        margin-top: 4px;
    }

    .message.sent .message-timestamp {
        color: rgba(255, 255, 255, 0.7);
    }
</style>
@endpush
