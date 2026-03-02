<x-guest-layout>
    <div class="bg-blue-600 p-8 text-center rounded-t-2xl">
        <h2 class="text-2xl font-bold text-white">Welcome Back</h2>
        <p class="text-blue-100 mt-2">Please sign in to your account</p>
    </div>

    <div class="p-8">
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                    required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />

                <div class="mt-1 group" style="position: relative;">
                    <x-text-input id="password" class="block w-full transition-all duration-200"
                        style="padding-right: 3rem;" type="password" name="password" required
                        autocomplete="current-password" />

                    <div
                        style="position: absolute; top: 0; bottom: 0; right: 0; display: flex; align-items: center; padding-right: 0.75rem;">
                        <button type="button" id="toggle-password" onclick="togglePasswordVisibility('password', this)"
                            class="text-gray-400 hover:text-gray-600 focus:outline-none focus:text-blue-500 transition-colors duration-200 p-1 rounded-md hover:bg-gray-100"
                            tabindex="-1" title="Toggle password visibility">
                            {{-- Eye open (shown when password is hidden) --}}
                            <svg class="eye-open w-5 h-5 transition-all duration-200" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            {{-- Eye closed (shown when password is visible) --}}
                            <svg class="eye-closed w-5 h-5 hidden transition-all duration-200" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                <script>
                    function togglePasswordVisibility(inputId, btn) {
                        const input = document.getElementById(inputId);
                        const openEye = btn.querySelector('.eye-open');
                        const closedEye = btn.querySelector('.eye-closed');

                        if (input.type === 'password') {
                            input.type = 'text';
                            openEye.classList.add('hidden');
                            closedEye.classList.remove('hidden');
                        } else {
                            input.type = 'password';
                            openEye.classList.remove('hidden');
                            closedEye.classList.add('hidden');
                        }
                    }
                </script>

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-primary-button class="ms-3">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>