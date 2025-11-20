<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-6 text-center">
                {{ isLogin ? 'Login' : 'Register' }}
            </h2>

            <form @submit.prevent="isLogin ? login() : register()">
                <div v-if="!isLogin" class="mb-4">
                    <label class="block text-gray-700 mb-2">Name</label>
                    <input v-model="name" type="text" class="w-full p-2 border rounded" required />
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Email</label>
                    <input v-model="email" type="email" class="w-full p-2 border rounded" required />
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Password</label>
                    <input v-model="password" type="password" class="w-full p-2 border rounded" required />
                </div>

                <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">
                    {{ isLogin ? 'Login' : 'Register' }}
                </button>
            </form>

            <p class="mt-4 text-center text-gray-600">
                <span v-if="isLogin">Don't have an account?</span>
                <span v-else>Already have an account?</span>
                <button @click="toggleForm" class="text-blue-500 hover:underline ml-1">
                    {{ isLogin ? 'Register' : 'Login' }}
                </button>
            </p>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isLogin: true,
            name: "",
            email: "",
            password: ""
        };
    },
    methods: {
        toggleForm() {
            this.isLogin = !this.isLogin;
        },
        async login() {
            try {
                this.api.post("/login", {
                    email: this.email,
                    password: this.password
                })
                    .then((res) => {
                        if (res.success) {
                            localStorage.setItem("token", res.token);
                            localStorage.setItem("userId", res.user.id);
                            this.$router.push('/');
                        } else {
                            alert("Login failed: " + (res.data.message || "Unknown error"));
                        }
                    });
            }catch (err) {
                alert(err.response.data.message || 'Registration failed');
            }
        },
        async register() {
            try {
                this.api.post("/register", {
                    name: this.name,
                    email: this.email,
                    password: this.password
                })
                    .then((res) => {
                        if (res.data && res.data.success) {
                            localStorage.setItem("token", res.data.token);
                            localStorage.setItem("userId", res.data.user.id);
                            this.$router.push('/');
                        }else {
                            alert("Register failed: " + (res.data.message || "Unknown error"));
                        }
                    })
                    .catch((err) => {
                        alert(err.response.data.message || 'Registration failed');
                    });
            } catch (err) {
                console.log(err);
                alert("Registration failed");
            }
        }
    }
};
</script>

<style scoped>
</style>
