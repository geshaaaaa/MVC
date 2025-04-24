document.addEventListener('DOMContentLoaded', function () {
    const guestsInput = document.getElementById('guests_capacity_max');
    const guestsButtons = document.querySelectorAll('.guests-counter-button');

    guestsButtons.forEach(button => {
        button.addEventListener('click', function () {
            const action = this.dataset.action;
            let guests = parseInt(guestsInput.value);

            if (action === 'increase') {
                guests++;
            } else if (action === 'decrease' && guests > 0) {
                guests--;
            }

            guestsInput.value = guests;
        });
    });
});
