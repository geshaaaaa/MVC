



document.addEventListener('DOMContentLoaded', function () {
    // Обработка переключения табов
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', function () {
            const tab = this.dataset.tab;
            document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            if (tab === 'future') {
                document.getElementById('future-reservations').style.display = 'block';
                document.getElementById('past-reservations').style.display = 'none';
            } else {
                document.getElementById('future-reservations').style.display = 'none';
                document.getElementById('past-reservations').style.display = 'block';
            }
        });
    });

    // Обработка отмены бронирования
    document.querySelectorAll('.cancel-reservation').forEach(button => {
        button.addEventListener('click', function () {
            const reservationId = this.dataset.reservationId;
            if (confirm('Ви впевнені, що хочете скасувати це бронювання?')) {
                console.log('Отправка запроса на удаление бронирования с ID:', reservationId);
            }
        });
    });
});

