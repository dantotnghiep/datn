<h2>Xác minh email</h2>
<p>Xin chào {{ $data['name'] ?? 'bạn' }},</p>
<p>Nhấn vào liên kết sau để xác minh tài khoản:</p>
<a href="{{ $data['veirfyLink'] }}">Xác minh tài khoản</a>
