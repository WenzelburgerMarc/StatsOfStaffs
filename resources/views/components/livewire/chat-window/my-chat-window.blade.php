<div class="flex w-full py-5">

    <div class="flex w-full flex-col justify-between bg-transparent space-y-5">


        @include('components.livewire.chat-window.includes.chat-header', ['otherUser' => $otherUser])

        @include('components.livewire.chat-window.includes.chat-window', ['chatMessages' => $chatMessages])


    </div>
</div>

<script type="text/javascript">
    function getThreshold(chatWindow) {
        const messages = chatWindow.querySelectorAll('.chat-message');
        const lastIndex = messages.length - 1;
        let height = 0;

        for (let i = 0; i < 3 && i <= lastIndex; i++) {
            height += messages[lastIndex - i].offsetHeight;
        }

        return height;
    }

    function shouldScrollToBottom(chatWindow) {
        const distanceFromBottom = chatWindow.scrollHeight - chatWindow.scrollTop - chatWindow.clientHeight;
        const threshold = getThreshold(chatWindow);

        return distanceFromBottom < threshold;
    }

    function scrollToBottom(checkShouldScroll = false) {
        const chatWindow = document.getElementById("chat-window");
        if (checkShouldScroll && !shouldScrollToBottom(chatWindow)) {
            return;
        }

        chatWindow.scrollTop = chatWindow.scrollHeight;
    }

    document.addEventListener("DOMContentLoaded", function () {
        window.onload = scrollToBottom();


        Livewire.on('scrollToBottom', () => {

            requestAnimationFrame(() => {
                scrollToBottom();
            });

        });

        Livewire.on('newMessageReceived', () => {
            requestAnimationFrame(() => {
                scrollToBottom(true);

                Livewire.dispatch('markAsRead');
            });
        });
    });
</script>



