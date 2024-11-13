<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-sm-4">
                <p>© {{ date('Y') }} Стройка.com</p>
            </div>
            <div class="col-sm-8">
                <ul>
                    <li><a href="{{ route('terms.and.conditions') }}">Политика конфиденциальности</a></li>    
                    <li style="margin-right: 7%;"><a href="{{ route('blog.index') }}">Блог</a></li>
                    <li><a href="{{ route('terms.and.conditions') }}">Условия использования</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="feedback-circle" onclick="toggleFeedbackForm()">
        <i class="fas fa-comments"></i>
    </div>
    <div class="feedback-form" id="feedbackForm">
    <form method="POST" action="{{ route('send.feedback') }}" onsubmit="return confirmSubmission()">
    @csrf
    <input type="text" name="name" placeholder="Ваше имя" required>
    <input type="email" name="email" placeholder="Ваш email" required>
    <textarea name="message" placeholder="Ваше сообщение"></textarea>
        <button type="submit">Отправить</button>
    </form>
    </div>
</footer>

<style>
.feedback-circle {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 50px;
    height: 50px;
    background-color: #007bff;
    border-radius: 50%;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.feedback-form {
    display: none;
    position: fixed;
    bottom: 80px;
    right: 20px;
    background-color: white;
    border-radius: 8px;
    border: 1px solid #ccc;
    padding: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    width: 300px;
    z-index: 10;
}

.feedback-form input,
.feedback-form textarea {
    width: calc(100% - 20px);
    margin-bottom: 10px;
    padding: 8px;
    border: 1px solid #ddd;
}

.feedback-form button {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 15px;
    cursor: pointer;
    border-radius: 4px;
    font-size: 14px;
    width: 100%;
}

.feedback-form button:hover {
    background-color: #0056b3;
}
</style>

<script>
function toggleFeedbackForm() {
    var form = document.getElementById('feedbackForm');
    if (form.style.display === 'none' || form.style.display === '') {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';
    }
}

// Add click event listener to the document
document.addEventListener('click', function(event) {
    var form = document.getElementById('feedbackForm');
    var circle = document.querySelector('.feedback-circle');
    
    // If click is outside form and circle, close the form
    if (!form.contains(event.target) && !circle.contains(event.target)) {
        form.style.display = 'none';
    }
});

function confirmSubmission() {
    return confirm('Отправить запрос?');
}
</script>
