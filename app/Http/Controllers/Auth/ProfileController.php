<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $user = Auth::user();

        // Calculate days active
        $createdAt = new Carbon($user->created_at);
        $daysActive = $createdAt->diffInDays(Carbon::now()) + 1; // +1 to include today

        return view('pages.profile', compact('daysActive'));
    }

    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'bank_name' => ['nullable', 'string', 'max:255'],
            'bank_account_number' => ['nullable', 'string', 'max:50'],
        ]);

        $user->update($validated);

        return redirect()->route('profile.show')->with('success', 'Thông tin cá nhân đã được cập nhật thành công.');
    }

    /**
     * Update the user's avatar.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'max:2048'], // 2MB max
        ]);

        $user = Auth::user();

        // Delete old avatar if exists
        if ($user->avatar && !str_contains($user->avatar, 'ui-avatars.com')) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Store new avatar
        $path = $request->file('avatar')->store('avatars', 'public');

        // Update user avatar
        $user->avatar = $path;
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Ảnh đại diện đã được cập nhật thành công.');
    }

    /**
     * Update the user's QR code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateQRCode(Request $request)
    {
        $request->validate([
            'qr_code_image' => ['required', 'image', 'max:2048'], // 2MB max
        ]);

        $user = Auth::user();

        // Delete old QR code if exists
        if ($user->qr_code_image) {
            Storage::disk('public')->delete($user->qr_code_image);
        }

        // Store new QR code
        $path = $request->file('qr_code_image')->store('qr_codes', 'public');

        // Update user QR code
        $user->qr_code_image = $path;
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Mã QR đã được cập nhật thành công.');
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        // Check current password
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác.']);
        }

        // Update password
        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->route('profile.show')->with('success', 'Mật khẩu đã được cập nhật thành công.');
    }
}
