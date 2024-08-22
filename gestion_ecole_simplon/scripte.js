function validateDate() {
        const today = new Date();
        const minAge = 17;
        const maxYear = 2008;
        const dateInput = document.getElementById('date_naissance').value;
        const birthDate = new Date(dateInput);

        // Vérifier si la date de naissance est après l'année 2008
        if (birthDate.getFullYear() > maxYear || (birthDate.getFullYear() === maxYear && (birthDate.getMonth() > today.getMonth() || (birthDate.getMonth() === today.getMonth() && birthDate.getDate() > today.getDate())))) {
            alert('La date de naissance ne peut pas être après l\'année 2008.');
            return false;
        }

        // Calculer l'âge correct
        const age = today.getFullYear() - birthDate.getFullYear();
        const monthDifference = today.getMonth() - birthDate.getMonth();
        
        if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }

        // Vérifier si l'âge est suffisant
        if (age < minAge) {
            alert('Vous devez avoir au moins 17 ans.');
            return false;
        }

        return true;
    }




document.addEventListener('DOMContentLoaded', function () {
    const studentList = document.getElementById('student-list');
    const urlParams = new URLSearchParams(window.location.search);
    const type = urlParams.get('type');

    fetch(`list-students.php?type=${type}`)
        .then(response => response.json())
        .then(data => {
            if (data.length === 0) {
                studentList.innerHTML = '<p>Aucun étudiant trouvé.</p>';
            } else {
                const table = document.createElement('table');
                table.innerHTML = `
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.map((student, index) => `
                            <tr>
                                <td>${index + 1}</td>
                                <td>${student.nom}</td>
                                <td>${student.prenom}</td>
                                <td>${student.email}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                `;
                studentList.appendChild(table);
            }
        });
});

// temps d'inactivité avant la déconnexion (en millisecondes)
const INACTIVITY_TIME = 300000; // 5 minutes

let inactivityTimer;
let countdownTimer;
let timeLeft = INACTIVITY_TIME;

function startCountdown() {
    const countdownDisplay = document.getElementById('countdown');
    countdownTimer = setInterval(() => {
        if (timeLeft <= 0) {
            clearInterval(countdownTimer);
            logoutUser();
            return;
        }
        const minutes = Math.floor(timeLeft / 60000);
        const seconds = Math.floor((timeLeft % 60000) / 1000);
        countdownDisplay.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
        timeLeft -= 1000;
    }, 1000);
}

function resetTimer() {
    clearTimeout(inactivityTimer);
    clearInterval(countdownTimer);
    timeLeft = INACTIVITY_TIME;
    startCountdown();
    inactivityTimer = setTimeout(logoutUser, INACTIVITY_TIME);
}

function logoutUser() {
    window.location.href = './login.php'; // Ajustez cette URL en fonction de votre logique de déconnexion
}

function createTimerDisplay() {
    const timerDiv = document.createElement('div');
    timerDiv.id = 'timer-container';
    timerDiv.style.position = 'fixed';
    timerDiv.style.top = '0';
    timerDiv.style.right = '0';
    timerDiv.style.backgroundColor = '#f0f0f0';
    timerDiv.style.border = '1px solid #ccc';
    timerDiv.style.padding = '5px 10px';
    timerDiv.style.zIndex = '1000';
    timerDiv.style.fontFamily = 'Arial, sans-serif';
    timerDiv.style.fontSize = '14px';
    timerDiv.style.color = '#333';

    const icon = document.createElement('img');
    icon.src = 'time.png'; // Remplacez par le chemin vers votre icône d'horloge
    icon.style.width = '16px';
    icon.style.height = '16px';
    icon.style.verticalAlign = 'middle';
    icon.alt = 'Clock Icon';

    const countdownDisplay = document.createElement('span');
    countdownDisplay.id = 'countdown';
    countdownDisplay.textContent = '5:00'; // Initialisation à 5 minutes

    timerDiv.appendChild(icon);
    timerDiv.appendChild(document.createTextNode(' '));
    timerDiv.appendChild(countdownDisplay);
    document.body.appendChild(timerDiv);
}

window.onload = function() {
    createTimerDisplay();
    resetTimer(); // Initialiser le timer lorsque la page est chargée

    // Événements qui réinitialisent le timer
    document.onmousemove = resetTimer;
    document.onkeypress = resetTimer;
    document.ontouchstart = resetTimer; // Pour les appareils tactiles
    document.onchange = resetTimer;

    // Assurez-vous de réinitialiser le timer lorsque l'utilisateur revient sur la page après une absence
    window.onfocus = resetTimer;
    window.onblur = function() {
        clearTimeout(inactivityTimer); // Efface le timer lorsque la fenêtre n'est pas active
        clearInterval(countdownTimer);
    };
};
document.addEventListener('DOMContentLoaded', () => {
    const passwordInput = document.getElementById('mot-de-passe');
    const togglePasswordButton = document.getElementById('togglePassword');

    togglePasswordButton.addEventListener('click', () => {
        const isPassword = passwordInput.type === 'mot-de-passe';
        passwordInput.type = isPassword ? 'text' : 'mot-de-passe';
        togglePasswordButton.classList.toggle('fa-eye', isPassword);
        togglePasswordButton.classList.toggle('fa-eye-slash', !isPassword);
    });
});
