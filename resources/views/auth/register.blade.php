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

                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                    autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                    name="password_confirmation" required autocomplete="new-password" />

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

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