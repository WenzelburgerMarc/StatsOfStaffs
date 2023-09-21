<x-form.panel class="flex h-screen w-full flex-col min-h-[600px] md:h-[50vh]">
    <div id="chat-window" class="flex-grow overflow-y-scroll px-5 pt-5">

        @foreach($chatMessages as $chatMessage)
            @include('components.livewire.chat-window.includes.chat-message', ['chatMessage' => $chatMessage])
        @endforeach
    </div>

    @include('components.livewire.chat-window.includes.chat-input')
</x-form.panel>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        setTimeout(function () {
            const chatWindow = document.getElementById('chat-window');
            let distanceFromBottom = 0;

            chatWindow.addEventListener('scroll', function (e) {
                if (chatWindow.scrollTop <= 10 && !chatWindow.getAttribute('data-loading')) {
                    distanceFromBottom = chatWindow.scrollHeight - (chatWindow.scrollTop + chatWindow.clientHeight);

                    chatWindow.setAttribute('data-loading', 'true');

                    Livewire.dispatch('emitLoadMoreMessages');
                }
            });

            Livewire.on('chat-dom-updated', () => {
                chatWindow.scrollTop = chatWindow.scrollHeight - chatWindow.clientHeight - distanceFromBottom;

                chatWindow.removeAttribute('data-loading');
            });

            const observer = new MutationObserver((mutations) => {
                for (let mutation of mutations) {
                    if (mutation.type === 'childList') {
                        chatWindow.scrollTop = chatWindow.scrollHeight - chatWindow.clientHeight - distanceFromBottom;
                    }
                }
            });

            observer.observe(chatWindow, {
                childList: true,
                subtree: true
            });

        }, 1000);
    });
</script>
