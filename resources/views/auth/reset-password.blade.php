<form method="POST" action="{{ route('password.update') }}">
    @csrf

    <input type="hidden" name="oobCode" value="{{ $oobCode }}">

    <label>New Password</label>
    <input type="password" name="new_password" required>

    <label>Confirm Password</label>
    <input type="password" name="new_password_confirmation" required>

    <button type="submit">Reset Password</button>
</form>
