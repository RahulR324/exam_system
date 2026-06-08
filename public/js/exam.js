// public/js/exam.js
let currentQuestion = 0;
let answers = {};
let questions = [];
let timeLeft = 0;
let timerInterval;

function showQuestion(index) {

    currentQuestion = index;

    document.getElementById('question-number').textContent = index + 1;

    const q = questions[index];

    document.getElementById('question-text').innerHTML =
        `<strong>Q${index + 1}.</strong> ${q.question}`;

    const optionsContainer =
        document.getElementById('options-container');

    optionsContainer.innerHTML = '';

    const options = [
        q.option1,
        q.option2,
        q.option3,
        q.option4
    ];

    options.forEach((opt, i) => {

        const checked =
            answers[q.id] === opt ? 'checked' : '';

        optionsContainer.innerHTML += `
            <label class="option">
                <input
                    type="radio"
                    name="${q.id}"
                    value="${opt}"
                    ${checked}
                >
                <span>${opt}</span>
            </label>
        `;
    });

    const nextBtn = document.getElementById('btn-next');

    if (index === questions.length - 1) {
        nextBtn.style.display = 'none';
    } else {
        nextBtn.style.display = 'inline-block';
    }
}

function saveCurrentAnswer() {

    const q = questions[currentQuestion];

    const selected = document.querySelector(
        `input[name="${q.id}"]:checked`
    );

    if (selected) {
        answers[q.id] = selected.value;
    }
}

function nextQuestion() {
    saveCurrentAnswer();
    if (currentQuestion < questions.length - 1) {
        showQuestion(currentQuestion + 1);
    }
}

function prevQuestion() {
    saveCurrentAnswer();
    if (currentQuestion > 0) {
        showQuestion(currentQuestion - 1);
    }
}

// Submit Exam
function submitExam() {

    saveCurrentAnswer();

    const form = document.getElementById('exam-form');

    document.querySelectorAll('.dynamic-answer')
        .forEach(el => el.remove());

    // Add all answers
    Object.keys(answers).forEach(qid => {

        const input = document.createElement('input');

        input.type = 'hidden';

        input.name = qid;

        input.value = answers[qid];

        input.classList.add('dynamic-answer');

        form.appendChild(input);
    });

    // IMPORTANT
    const submitFlag = document.createElement('input');

    submitFlag.type = 'hidden';

    submitFlag.name = 'submit_exam';

    submitFlag.value = '1';

    submitFlag.classList.add('dynamic-answer');

    form.appendChild(submitFlag);

    form.method = 'POST';

    form.action = '/exam';

    form.submit();
}

// Timer
function startTimer() {
    const timerElement = document.getElementById('timer');
    timerInterval = setInterval(() => {
        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            submitExam();
            return;
        }
        let min = Math.floor(timeLeft / 60);
        let sec = timeLeft % 60;
        timerElement.textContent = `${min}:${sec < 10 ? '0' : ''}${sec}`;
        timeLeft--;
    }, 1000);
}

// Custom Popup
function showCustomPopup(message) {
    const popup = document.getElementById('examPopup');
    document.getElementById('popup-message').innerHTML = message;
    popup.style.display = 'flex';

    document.getElementById('popup-confirm').onclick = () => {
        popup.style.display = 'none';
        saveCurrentAnswer();
        submitExam();           // Auto submit on confirm
    };

    document.getElementById('popup-cancel').onclick = () => {
        popup.style.display = 'none';
    };
}

// Protection
function enableProtection() {
    history.pushState(null, null, location.href);
    history.pushState(null, null, location.href);

    window.addEventListener('popstate', () => {
        showCustomPopup("You pressed Back button.<br><br>Exam will be submitted and result will be shown.");
        history.pushState(null, null, location.href);
    });
}

// Initialize
function initExam(questionsData, duration) {
    questions = questionsData;
    timeLeft = duration;

    document.addEventListener('DOMContentLoaded', () => {
        showQuestion(0);
        startTimer();
        enableProtection();
    });
}

