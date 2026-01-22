<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            // Student validation
            'student_id' => ['required', 'string', 'unique:' . \App\Models\Student::class],
            'phone' => ['required', 'string'],
            'department' => ['required', 'string'],
            'year' => ['required', 'integer', 'min:1', 'max:6'],
            'gender' => ['required', 'in:male,female'],
            'study_level' => ['required', 'in:undergraduate,postgraduate,diploma'],
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Create Student Profile
            $student = \App\Models\Student::create([
                'user_id' => $user->id,
                'student_id' => $request->student_id,
                'full_name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'department' => $request->department,
                'year' => $request->year,
                'gender' => $request->gender,
                'study_level' => $request->study_level,
            ]);

            event(new Registered($user));

            \Illuminate\Support\Facades\Mail::to($user)->send(new \App\Mail\StudentWelcomeMail($student));

            Auth::login($user);
        });

        return redirect(route('dashboard', absolute: false));
    }
}
