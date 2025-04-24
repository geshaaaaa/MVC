document.addEventListener('DOMContentLoaded', function () {
    const amenityCards = document.querySelectorAll('.amenity-card');

    amenityCards.forEach(card => {
        card.addEventListener('click', function () {
            this.classList.toggle('selected');

            // Удаляем все существующие скрытые поля amenities[]
            document.querySelectorAll('input[name="amenities[]"]').forEach(el => el.remove());

            // Собираем выбранные ID
            const selected = Array.from(document.querySelectorAll('.amenity-card.selected'))
                .map(card => card.dataset.id);

            // Добавляем скрытые input-ы заново
            const form = document.querySelector('form');
            selected.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'amenities[]';
                input.value = id;
                form.appendChild(input);
            });
        });
    });
});