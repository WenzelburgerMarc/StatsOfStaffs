<?php

namespace App\Livewire\ChatWindow;

use App\services\ChatService;
use Carbon\Carbon;
use Livewire\Attributes\On;
use Livewire\Component;

class MyChatWindow extends Component
{
    public $chatMessages;

    public $otherUser;

    public $message;

    public $page = 1;

    public $limit = 20;

    public $tmpChatMessagesCount = 0;

    protected $chatService;

    protected $listeners = [
        'echo:chat,NewChatMessage' => 'notifyNewMessage',
        'markAsRead' => 'markMessagesAsRead',
    ];

    public function __construct()
    {

        $this->chatService = app(ChatService::class);
    }

    public function mount($otherUser)
    {
        $this->otherUser = $otherUser;
        $this->chatMessages = $this->chatService->getChatMessagesWith($this->otherUser, $this->page, $this->limit);
        $this->tmpChatMessagesCount = $this->chatMessages->count();
        $this->markMessagesAsRead();
    }

    public function markMessagesAsRead()
    {
        $this->chatService->markAsRead($this->chatMessages);

        $this->dispatch('updateUnreadCount');
    }

    #[On('emitLoadMoreMessages')]
    public function loadMoreMessages()
    {

        $this->page++;
        $additionalMessages = $this->chatService->getChatMessagesWith($this->otherUser, $this->page, $this->limit);

        $this->chatMessages = $additionalMessages->concat($this->chatMessages);

        if ($this->tmpChatMessagesCount !== $this->chatMessages->count()) {

            $this->tmpChatMessagesCount = $this->chatMessages->count();
            $this->dispatch('chat-dom-updated');

        }

    }

    public function sendMessage()
    {
        if ($this->message == null || str_replace(' ', '', $this->message) == '' || ! isset($this->message)) {

            return;
        }

        $this->chatService->sendMessage($this->message, $this->otherUser);

        $this->message = '';

        $this->dispatch('scrollToBottom');
    }

    public function render()
    {
        return view('components.livewire.chat-window.my-chat-window');
    }

    public function notifyNewMessage($data)
    {
        $chatMessage = (object) $data['message'];
        $chatMessage->created_at = Carbon::parse($chatMessage->created_at);

        if ($this->otherUser->id == $chatMessage->sender_id || $this->otherUser->id == $chatMessage->receiver_id) {
            $this->chatMessages->push($chatMessage);
        }

        $this->dispatch('newMessageReceived');
    }

    private function validateMessage()
    {
        $this->validate(['message' => 'required|max:255']);
    }
}
