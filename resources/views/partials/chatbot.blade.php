{{-- resources/views/partials/chatbox.blade.php --}}
<div id="chatbox-toggle" style="position:fixed;bottom:24px;right:24px;width:56px;height:56px;
    background:#ff6600;border-radius:50%;display:flex;align-items:center;justify-content:center;
    cursor:pointer;box-shadow:0 4px 12px rgba(0,0,0,.2);z-index:1050;">
    <i class="fa-solid fa-comment-dots text-white fs-4"></i>
</div>

<div id="chatbox-window" style="display:none;position:fixed;bottom:90px;right:24px;width:320px;
    height:420px;background:#fff;border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,.25);
    z-index:1050;overflow:hidden;flex-direction:column;">
    <div style="background:#ff6600;color:#fff;padding:12px 16px;font-weight:600;">
        Hỗ trợ trực tuyến
    </div>
    <div id="chatbox-messages" style="flex:1;overflow-y:auto;padding:12px;font-size:14px;background:#f8f9fa;"></div>
    <div style="display:flex;border-top:1px solid #eee;">
        <input id="chatbox-input" type="text" placeholder="Nhập câu hỏi..."
            style="flex:1;border:none;padding:10px;outline:none;">
        <button id="chatbox-send" style="border:none;background:#ff6600;color:#fff;padding:0 16px;">
            <i class="fa-solid fa-paper-plane"></i>
        </button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('chatbox-toggle');
    const chatWindow = document.getElementById('chatbox-window');
    const messages = document.getElementById('chatbox-messages');
    const input = document.getElementById('chatbox-input');
    const sendBtn = document.getElementById('chatbox-send');

    toggleBtn.addEventListener('click', () => {
        chatWindow.style.display = chatWindow.style.display === 'none' ? 'flex' : 'none';
    });

    function appendMessage(text, from) {
        const bubble = document.createElement('div');
        bubble.style.margin = '6px 0';
        bubble.style.textAlign = from === 'user' ? 'right' : 'left';
        bubble.innerHTML = `<span style="display:inline-block;padding:8px 12px;border-radius:10px;
            background:${from === 'user' ? '#ff6600' : '#e9ecef'};
            color:${from === 'user' ? '#fff' : '#000'};max-width:80%;">${text}</span>`;
        messages.appendChild(bubble);
        messages.scrollTop = messages.scrollHeight;
    }

    function sendMessage() {
        const text = input.value.trim();
        if (!text) return;
        appendMessage(text, 'user');
        input.value = '';
        appendMessage('Đang trả lời...', 'bot');

        fetch("{{ route('chatbot.reply') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: text })
        })
        .then(res => {
            if (!res.ok) throw new Error('HTTP ' + res.status);
            return res.json();
        })
        .then(data => {
            messages.lastChild.remove();
            appendMessage(data.reply, 'bot');
        })
        .catch(err => {
            console.error(err);
            messages.lastChild.remove();
            appendMessage('Có lỗi kết nối, thử lại sau', 'bot');
        });
    }

    // 🔑 PHẦN QUAN TRỌNG BỊ THIẾU — gắn hàm sendMessage vào sự kiện
    sendBtn.addEventListener('click', sendMessage);
    input.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') sendMessage();
    });
});
</script>