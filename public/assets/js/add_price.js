const customFile = document.getElementById('images');
const customFileButton = document.getElementById('customFileButton');
const fileName = document.getElementById('fileName');
const form = document.getElementById('housingForm');
const fileError = document.getElementById('fileError');

customFileButton.addEventListener('click', () => {
    customFile.click();
});

customFile.addEventListener('change', () => {
    if (customFile.files.length > 0) {
        let names = Array.from(customFile.files).map(file => file.name).join(', ');
        fileName.textContent = names;
    } else {
        fileName.textContent = 'Файл не вибрано';
    }

    // Сброс ошибки, если пользователь выбрал файлы
    if (customFile.files.length >= 5) {
        fileError.textContent = '';
    }
});

form.addEventListener('submit', (e) => {
    if (customFile.files.length < 5) {
        e.preventDefault(); // Останавливаем отправку формы
        fileError.textContent = 'Будь ласка, завантажте щонайменше 5 фотографій.';
    }
});
