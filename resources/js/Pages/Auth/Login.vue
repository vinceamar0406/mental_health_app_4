<script setup>
import { ref, computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const isPasswordVisible = ref(false);
const isProcessing = ref(false);

// Form submission with animated loading state
const submit = () => {
    isProcessing.value = true;
    form.post(route('login'), {
        onFinish: () => {
            form.reset('password');
            setTimeout(() => (isProcessing.value = false), 600);
        },
    });
};

const buttonClasses = computed(() => ({
    'opacity-50 cursor-not-allowed': isProcessing.value || form.processing,
}));
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <!-- Full-screen background with modern color scheme -->
        <div class="flex min-h-screen w-full items-center justify-center bg-blue-100 px-6 dark:bg-gray-900">
            <div class="w-full max-w-lg text-center space-y-6">

                <!-- Welcome Title -->
                <h2 class="text-4xl font-extrabold text-blue-500">Welcome Back</h2>
                <p class="text-lg text-gray-700">Log in to your account and continue your journey</p>

                <!-- Status Message -->
                <div v-if="status" class="p-4 mt-2 text-sm text-green-800 bg-green-200 rounded-lg shadow-md">
                    {{ status }}
                </div>

                <!-- Login Form -->
                <form @submit.prevent="submit" class="mt-4 space-y-5 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 transition-all duration-500">
                    <!-- Email Field -->
                    <div>
                        <InputLabel for="email" value="Email" class="text-gray-800 dark:text-gray-300" />
                        <TextInput
                            id="email"
                            type="email"
                            class="w-full px-4 py-3 mt-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition-all duration-300"
                            v-model.lazy="form.email"
                            required
                            autofocus
                            autocomplete="username"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <!-- Password Field -->
                    <div>
                        <InputLabel for="password" value="Password" class="text-gray-800 dark:text-gray-300" />
                        <div class="relative">
                            <TextInput
                                id="password"
                                :type="isPasswordVisible ? 'text' : 'password'"
                                class="w-full px-4 py-3 mt-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition-all duration-300"
                                v-model="form.password"
                                required
                                autocomplete="off"
                            />
                            <!-- Toggle Password Visibility -->
                            <button
                                type="button"
                                @click="isPasswordVisible = !isPasswordVisible"
                                class="absolute inset-y-0 right-4 flex items-center text-gray-500 dark:text-gray-400 hover:text-blue-500 transition-all duration-300"
                                aria-label="Toggle password visibility"
                            >
                                <svg v-if="isPasswordVisible" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10 3a7 7 0 00-7 7 7 7 0 0014 0 7 7 0 00-7-7zm0 12a5 5 0 110-10 5 5 0 010 10z" />
                                </svg>
                                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <Checkbox name="remember" v-model:checked="form.remember" />
                            <span class="ml-2 text-sm text-gray-700 dark:text-gray-400">Remember me</span>
                        </label>
                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-sm text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300"
                        >
                            Forgot password?
                        </Link>
                    </div>

                    <!-- Login Button -->
                    <div>
                        <PrimaryButton class="w-full flex items-center justify-center gap-2 bg-blue-500 hover:bg-blue-600 text-white" :disabled="isProcessing || form.processing">
                            <svg v-if="isProcessing" class="animate-spin h-5 w-5 text-gray-900" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <circle cx="12" cy="12" r="10" stroke-width="4" class="opacity-25"></circle>
                                <path fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M4 12a8 8 0 0116 0"></path>
                            </svg>
                            <span>Log in</span>
                        </PrimaryButton>
                    </div>
                </form>

                <!-- Sign Up Option -->
                <p class="mt-4 text-center text-sm text-gray-700">
                    Don't have an account?
                    <Link
                        :href="route('register')"
                        class="text-blue-500 hover:text-blue-400 transition-all duration-300"
                    >
                        Sign up
                    </Link>
                </p>
            </div>
        </div>
    </GuestLayout>
</template>
