document.getElementById('chat-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const msg = document.getElementById('message').value;
    const chatWith = document.getElementById('chat_with').value;

    fetch('send_message.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `message=${encodeURIComponent(msg)}&chat_with=${encodeURIComponent(chatWith)}`
    })
    .then(res => res.text())
    .then(data => {
        if (data === 'success') {
            document.getElementById('message').value = '';
            loadMessages(); // reload lại tin nhắn
        } else {
            alert(data);
        }
    });
});

// Load lại tin nhắn
function loadMessages() {
    const chatWith = document.getElementById('chat_with').value;
    fetch(`load_messages.php?chat_with=${chatWith}`)
        .then(res => res.text())
        .then(html => {
            document.getElementById('chat-box').innerHTML = html;
        });
}

// Auto reload mỗi 5s
setInterval(loadMessages, 5000);
loadMessages();
