<?php

namespace App\Services {

    use App\Events\NewChatMessage;
    use App\Models\Chat;
    use App\Models\User;
    use Carbon\Carbon;
    use DB;
    use Illuminate\Pagination\Paginator;
    use Illuminate\Support\Facades\Auth;

    class ChatService
    {
        public function getAllChats(User $user, string $search = null, int $readStatusSearchID = null, string $selectedRole = null)
        {

            if ($search || $readStatusSearchID) {
                Paginator::currentPageResolver(fn () => 1);
            }

            $subQuery = Chat::select(DB::raw('
        CASE
            WHEN sender_id < receiver_id THEN sender_id
            ELSE receiver_id
        END as smaller_id,
        CASE
            WHEN sender_id > receiver_id THEN sender_id
            ELSE receiver_id
        END as larger_id,
        MAX(id) as last_chat_id'
            ))
                ->where(function ($query) use ($user) {
                    $query->where('sender_id', $user->id)
                        ->orWhere('receiver_id', $user->id);
                })
                ->groupBy('smaller_id', 'larger_id');

            $chats = Chat::joinSub($subQuery, 'sub', fn ($join) => $join->on('chats.id', '=', 'sub.last_chat_id'))
                ->leftJoin('users as sender', 'chats.sender_id', '=', 'sender.id')
                ->leftJoin('users as receiver', 'chats.receiver_id', '=', 'receiver.id')
                ->orderByDesc('chats.created_at');

            if ($search) {
                $chats->where(function ($query) use ($search, $user) {
                    $query->where(function ($innerQuery) use ($search) {
                        $innerQuery->where('sender.username', 'like', '%'.$search.'%')
                            ->orWhere('sender.name', 'like', '%'.$search.'%')
                            ->orWhere('sender.email', 'like', '%'.$search.'%');
                    })->where('receiver_id', $user->id)
                        ->orWhere(function ($innerQuery) use ($search) {
                            $innerQuery->where('receiver.username', 'like', '%'.$search.'%')
                                ->orWhere('receiver.name', 'like', '%'.$search.'%')
                                ->orWhere('receiver.email', 'like', '%'.$search.'%');
                        })->where('sender_id', $user->id);
                });
            }

            if ($selectedRole) {
                $chats->where(function ($query) use ($selectedRole, $user) {
                    $query->where(function ($innerQuery) use ($selectedRole, $user) {
                        $innerQuery->where('sender.role_id', $selectedRole)
                            ->where('sender.id', '!=', $user->id);
                    })->orWhere(function ($innerQuery) use ($selectedRole, $user) {
                        $innerQuery->where('receiver.role_id', $selectedRole)
                            ->where('receiver.id', '!=', $user->id);
                    });
                });
            }

            if ($readStatusSearchID == 1) {
                $chats->whereNull('chats.read_at')->where('chats.receiver_id', $user->id);
            } elseif ($readStatusSearchID == 2) {

                $chats->whereNotNull('chats.read_at')->where('chats.receiver_id', $user->id);
            } elseif ($readStatusSearchID == 3) {

                $chats->whereNotNull('chats.read_at')->where('chats.sender_id', $user->id);
            }

            return $chats;
        }

        public function markAsRead($chatMessages)
        {
            $unreadMessages = $this->getUnreadMessages($chatMessages);

            foreach ($unreadMessages as $message) {
                $chat = Chat::find($message->id);
                $chat->markAsRead();
            }
        }

        private function getUnreadMessages($chatMessages)
        {
            $unreadMessageIds = Chat::whereIn('id', $chatMessages->pluck('id'))
                ->whereNull('read_at')
                ->where('receiver_id', Auth::user()->id)
                ->pluck('id');

            return $chatMessages->whereIn('id', $unreadMessageIds);
        }

        public function sendMessage($messageContent, $receiver)
        {
            $message = Chat::create([
                'sender_id' => auth()->user()->id,
                'receiver_id' => $receiver->id,
                'message' => $messageContent,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'read_at' => null,
            ]);

            broadcast(new NewChatMessage($message));

        }

        //        public function hasChatWith(User $user, User $otherUser): bool
        //        {
        //            return $this->chatsWith($user, $otherUser)->exists();
        //        }

        public function getChatMessagesWith(User $otherUser, $page = 1, $limit = 20)
        {
            $offset = ($page - 1) * $limit;

            return $this->chatsWith(auth()->user(), $otherUser)
                ->orderBy('created_at', 'desc') // Um die neuesten Nachrichten zuerst zu erhalten
                ->offset($offset)
                ->limit($limit)
                ->get();
        }

        private function chatsWith(User $user, User $otherUser)
        {
            return Chat::where(function ($query) use ($user, $otherUser) {
                $query->where('sender_id', $user->id)
                    ->where('receiver_id', $otherUser->id);
            })->orWhere(function ($query) use ($user, $otherUser) {
                $query->where('sender_id', $otherUser->id)
                    ->where('receiver_id', $user->id);
            });
        }

        public function unreadChatsCount(?User $user): int
        {
            if (isset($user)) {
                return Chat::where('receiver_id', $user->id)
                    ->whereNull('read_at')
                    ->distinct('sender_id')
                    ->count();
            }

            return 0;
        }

        public function latestMessageForUser($otherUserId)
        {
            return Chat::where(function ($query) use ($otherUserId) {
                $query->where('sender_id', auth()->user()->id)
                    ->where('receiver_id', $otherUserId);
            })
                ->orWhere(function ($query) use ($otherUserId) {
                    $query->where('sender_id', $otherUserId)
                        ->where('receiver_id', auth()->user()->id);
                })
                ->orderBy('created_at', 'desc')
                ->first();
        }

        //        public function isUnreadBy(User $user): bool
        //        {
        //            return $this->receiver_id === $user->id && is_null($this->read_at);
        //        }
    }
}
