<template>
    <div class="flex flex-col h-screen p-4 max-w-md mx-auto bg-white">
        <h1 class="text-2xl font-bold mb-4">Chat with {{ otherUser?.name || '' }}</h1>
        <div class="flex-1 overflow-y-auto mb-4 space-y-2">
            <div v-for="msg in messages" :key="msg.id" class="flex" :class="{'justify-end': msg.user_id === myId, 'justify-start': msg.user_id !== myId}">
                <div
                    class="p-2 rounded-lg max-w-xs break-words"
                    :class="msg.user_id === myId ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800'"
                >
                    {{ msg.text }}
                </div>
            </div>
        </div>

        <form @submit.prevent="sendMessage" class="flex space-x-2">
            <input v-model="newMessage" type="text" placeholder="Type a message..." class="flex-1 border rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-400" />
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Send</button>
        </form>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'Chat',
    data() {
        return {
            messages: [],
            newMessage: '',
            myId: Number(localStorage.getItem('userId')) || 0,
            otherUser: null,
            tempMessageId: 0
        };
    },
    mounted() {
        this.fetchMessages();
        this.initializeWebsocket();
        this.scrollBottom();
    },
    methods: {
        initializeWebsocket() {
            const channel = window.Echo.private('chat.' + this.$route.params.id);
            console.log(channel);

            channel.listenForWhisper('message', (e) => {
                console.log('Whisper received:', e);

                this.messages.push({
                    id: e.tempId,
                    chat_id: e.chat_id,
                    user_id: e.user_id,
                    text: e.text,
                    created_at: new Date().toISOString()
                });

                this.$nextTick(() => {
                    this.scrollBottom();
                });
            });


            channel.listen('MessageSent', (e) => {
                console.log('MessageSent event:', e);
            });
        },
        async fetchMessages() {
            const chatId = this.$route.params.id;
            this.api.get(`/chats/` + chatId)
                .then((res) => {
                    this.messages = res.chat.messages;

                    if (res.chat && res.chat.users) {
                        this.otherUser = res.chat.users.find(u => u.id !== this.myId);
                    }
                });
        },
        scrollBottom() {
            const container = this.$el.querySelector('.overflow-y-auto');
            container.scrollTop = container.scrollHeight;
        },
        async sendMessage() {
            if (!this.newMessage.trim()) return;

            const chatId = this.$route.params.id;
            const messageText = this.newMessage;
            const tempId = `temp-${this.tempMessageId++}`;

            this.newMessage = '';

            const channel = window.Echo.private('chat.' + chatId);

            const tempMsg = {
                id: tempId,
                chat_id: chatId,
                user_id: this.myId,
                text: messageText,
                isTemp: true,
            };

            channel.whisper('message', tempMsg);

            this.messages.push(tempMsg);

            this.$nextTick(() => {
                this.scrollBottom();
            });

            try {
                await this.api.post(`/messages`, {
                    chat_id: chatId,
                    text: messageText
                })
            } catch (error) {
                console.error('Failed to save message to database:', error);
            }
        }
    }
};
</script>
