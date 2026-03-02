<x-guest-layout>
    <div class="bg-blue-600 p-8 text-center rounded-t-2xl">
        <h2 class="text-2xl font-bold text-white">Create Account</h2>
        <p class="text-blue-100 mt-2">Join our community today</p>
    </div>

    <div class="p-8">
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required
                    autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                    required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />

                <div class="mt-1 group" style="position: relative;">
                    <x-text-input id="password" class="block w-full transition-all duration-200"
                        style="padding-right: 3rem;" type="password" name="password" required
                        autocomplete="new-password" />

                    <div
                        style="position: absolute; top: 0; bottom: 0; right: 0; display: flex; align-items: center; padding-right: 0.75rem;">
                        <button type="button" onclick="togglePasswordVisibility('password', this)"
                            class="text-gray-400 hover:text-gray-600 focus:outline-none focus:text-blue-500 transition-colors duration-200 p-1 rounded-md hover:bg-gray-100"
                            tabindex="-1" title="Toggle password visibility">
                            <svg class="eye-open w-5 h-5 transition-all duration-200" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg class="eye-closed w-5 h-5 hidden transition-all duration-200" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                <div class="mt-1 group" style="position: relative;">
                    <x-text-input id="password_confirmation" class="block w-full transition-all duration-200"
                        style="padding-right: 3rem;" type="password" name="password_confirmation" required
                        autocomplete="new-password" />

                    <div
                        style="position: absolute; top: 0; bottom: 0; right: 0; display: flex; align-items: center; padding-right: 0.75rem;">
                        <button type="button" onclick="togglePasswordVisibility('password_confirmation', this)"
                            class="text-gray-400 hover:text-gray-600 focus:outline-none focus:text-blue-500 transition-colors duration-200 p-1 rounded-md hover:bg-gray-100"
                            tabindex="-1" title="Toggle password visibility">
                            <svg class="eye-open w-5 h-5 transition-all duration-200" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg class="eye-closed w-5 h-5 hidden transition-all duration-200" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                </div>

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
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

            <div class="mt-6 border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Student Details</h3>

                <!-- Student ID -->
                <div>
                    <x-input-label for="student_id" :value="__('Student ID / Reg Number')" />
                    <x-text-input id="student_id" class="block mt-1 w-full" type="text" name="student_id"
                        :value="old('student_id')" required />
                    <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                </div>

                <!-- Phone -->
                <div class="mt-4">
                    <x-input-label for="phone" :value="__('Phone Number')" />
                    <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')"
                        required />
                    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                </div>

                <!-- Department -->
                <div class="mt-4">
                    <x-input-label for="department" :value="__('Department')" />
                    <x-text-input id="department" class="block mt-1 w-full" type="text" name="department"
                        :value="old('department')" required />
                    <x-input-error :messages="$errors->get('department')" class="mt-2" />
                </div>

                <!-- Year of Study -->
                <div class="mt-4">
                    <x-input-label for="year" :value="__('Year of Study')" />
                    <x-text-input id="year" class="block mt-1 w-full" type="number" name="year" :value="old('year')"
                        min="1" max="6" required />
                    <x-input-error :messages="$errors->get('year')" class="mt-2" />
                </div>

                <!-- Gender -->
                <div class="mt-4">
                    <x-input-label for="gender" :value="__('Gender')" />
                    <select id="gender" name="gender"
                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        required>
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                    </select>
                    <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                </div>

                <!-- Study Level -->
                <div class="mt-4">
                    <x-input-label for="study_level" :value="__('Study Level')" />
                    <select id="study_level" name="study_level"
                        class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        required>
                        <option value="">Select Level</option>
                        <option value="undergraduate" {{ old('study_level') == 'undergraduate' ? 'selected' : '' }}>
                            Undergraduate</option>
                        <option value="postgraduate" {{ old('study_level') == 'postgraduate' ? 'selected' : '' }}>
                            Postgraduate</option>
                        <option value="diploma" {{ old('study_level') == 'diploma' ? 'selected' : '' }}>Diploma</option>
                    </select>
                    <x-input-error :messages="$errors->get('study_level')" class="mt-2" />
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-primary-button class="ms-4">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>