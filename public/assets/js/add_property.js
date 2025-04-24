document.addEventListener('DOMContentLoaded', function () {
    const typeCards = document.querySelectorAll('.property-card');
    const nextButton = document.getElementById('next-step');
    const form = document.querySelector('form'); // Получаем элемент формы
    let selectedType = null;

    typeCards.forEach(card => {
        card.addEventListener('click', function () {
            typeCards.forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            selectedType = this.dataset.type;
            nextButton.disabled = false;
        });
    });

    nextButton.addEventListener('click', function () {
        if (selectedType) {
            // Создаем скрытое поле ввода для передачи данных
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'type'; // Имя поля, которое будет доступно в $_POST
            input.value = selectedType;

            // Добавляем поле в форму
            form.appendChild(input);

            // Отправляем форму
            form.submit();
        }
    });
});