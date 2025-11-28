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
            ws: null,
            messages: [],
            newMessage: '',
            myId: Number(localStorage.getItem('userId')) || 0,
            otherUser: null
        };
    },
    mounted() {
        this.fetchMessages();
        this.initializeWebsocket();
        this.$nextTick(() => {
            this.scrollBottom();
        });
    },
    methods: {
        initializeWebsocket() {
            this.ws = new WebSocket('ws://localhost:6002');

            this.ws.onopen = () => {
                console.log('Connected!');

                this.ws.send(JSON.stringify({
                    type: "auth",
                    user_id: this.myId
                }));
            };

            this.ws.onmessage = (e) => {
                const data = JSON.parse(e.data);

                if (data.type === "message") {
                    this.messages.push({
                        id: Date.now(),
                        user_id: data.from,
                        text: data.text
                    });

                    this.$nextTick(() => this.scrollBottom());
                }
            };

            this.ws.onerror = (err) => {
                console.error(err);
            };
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

            const receivers = this.otherUser ? [this.otherUser.id] : [];

            this.ws.send(JSON.stringify({
                type: "message",
                chat_id: chatId,
                from: this.myId,
                text: this.newMessage,
                to: receivers
            }));

            const res = await this.api.post(`/messages`, {
                chat_id: chatId,
                text: this.newMessage
            });

            this.messages.push({
                id: res.id,
                user_id: this.myId,
                text: this.newMessage
            });

            this.newMessage = '';
            await this.$nextTick(() => {
                this.scrollBottom();
            });
        }
    }
};
</script>
