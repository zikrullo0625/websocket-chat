<template>
    <div class="p-4 max-w-md mx-auto h-screen bg-white">
        <h1 class="text-2xl font-bold mb-4">Chats</h1>

        <div class="w-full my-3 relative">
            <input
                v-model="searchQuery"
                @input="handleInput"
                class="w-full h-max bg-white outline-none px-3 py-2 pr-12 rounded-lg border border-gray-300"
                placeholder="Search..."
            >

            <button
                @click="searchChats"
                class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700"
            >
                <i class="fa-solid fa-magnifying-glass"></i>
            </button>
        </div>

        <div v-if="chats.length > 0">
            <div
                v-for="chat in chats"
                :key="chat.id"
                class="mb-3"
            >
                <div
                    @click="handleChatClick(chat)"
                    class="block p-4 rounded-lg hover:bg-gray-200 bg-gray-100 flex justify-between items-center cursor-pointer"
                >
                    <div>
                        <p class="font-semibold">
                            {{ getChatName(chat) }}
                        </p>
                        <p class="text-gray-500 text-sm truncate max-w-xs">
                            {{ chat.last_message?.text || 'No messages yet' }}
                        </p>
                    </div>
                    <p class="text-xs text-gray-400">
                        {{ chat.last_message ? formatDate(chat.last_message.created_at) : '' }}
                    </p>
                </div>
            </div>
        </div>

        <div v-if="users.length > 0">
            <div
                v-for="user in users"
                :key="user.id"
                class="mb-3"
            >
                <div
                    @click="handleChatClick(user)"
                    class="block p-4 rounded-lg hover:bg-gray-200 bg-gray-100 flex justify-between items-center cursor-pointer"
                >
                    <div>
                        <p class="font-semibold">
                            {{ user.name }}
                        </p>
                </div>
            </div>
        </div>
        </div>
        <div v-if="users.length === 0 && chats.length === 0" class="flex items-center justify-center py-10 text-gray-500">
            No chats
        </div>
    </div>
</template>

<script>
export default {
    name: 'Home',
    data() {
        return {
            chats: [],
            users: [],
            myId: Number(localStorage.getItem('userId')) || 0,
            searchQuery: null,
            isSearchMode: false,
        };
    },

    mounted() {
        this.fetchChats();
    },

    methods: {
        fetchChats() {
            this.api.get('/my-chats')
                .then((res) => {
                    this.chats = res.chats;
                    this.isSearchMode = false;
                });
        },

        getChatName(chat) {
            const otherUser = chat.users.find(u => u.id !== this.myId);
            return otherUser ? otherUser.name : 'Me';
        },

        otherUserId(chat) {
            const otherUser = chat.users.find(u => u.id !== this.myId);
            return otherUser.id;
        },

        formatDate(date) {
            return new Date(date).toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });
        },

        searchChats() {
            const query = this.searchQuery?.trim();

            if (!query) {
                this.fetchChats();
                return;
            }

            this.api.post('/search', { query })
                .then((res) => {
                    const result = res.chats ?? [];
                    this.chats = [];
                    this.users = result;
                    this.isSearchMode = true;

                    if (result.length === 0) {
                        this.fetchChats();
                    }
                });
        },

        handleInput() {
            if (!this.searchQuery) {
                this.fetchChats();
            }
        },

        handleChatClick(chat) {
            if (this.isSearchMode) {
                this.api.post('/create-chat', { user_id: chat.id })
                    .then((res) => {
                        this.$router.push(`/chat/${res.chat.id}`);
                    });
                return;
            }

            this.$router.push(`/chat/${chat.id}`);
        }
    }
};
</script>

<style scoped>
</style>
