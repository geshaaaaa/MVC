document.addEventListener("DOMContentLoaded", function () {
    const userIcon = document.getElementById('userIcon');
    const userIconLogged = document.getElementById('userIconLogged');
    const dropdownContent = document.getElementById('dropdownContent');

    function toggleDropdown(event) {
        event.stopPropagation(); // Чтобы клик не закрывал меню из-за window.addEventListener
        dropdownContent.style.display = (dropdownContent.style.display === 'block') ? 'none' : 'block';
    }

    function redirectToAuth(event) {
        event.preventDefault();
        window.location.href = "/auth";
    }

    if (userIcon) {
        userIcon.addEventListener("click", function (event) {
            if (document.cookie.indexOf('token=') > -1) {
                toggleDropdown(event);
            } else {
                redirectToAuth(event);
            }
        });
    }

    if (userIconLogged) {
        userIconLogged.addEventListener("click", toggleDropdown);
    }

    window.addEventListener("click", function (event) {
        if (dropdownContent && dropdownContent.style.display === 'block') {
            if (!userIconLogged.contains(event.target) && !dropdownContent.contains(event.target)) {
                dropdownContent.style.display = 'none';
            }
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".like-wished").forEach(button => {
        button.addEventListener("click", function () {
            let housingId = this.getAttribute("data-housing-id");
            let heart = this.querySelector(".heart");

            fetch("wishlist/toggle", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                credentials: "include",
                body: JSON.stringify({ housing_id: housingId })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Ошибка HTTP: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(this, heart);
                    this.classList.toggle("heart-active");
                    heart.classList.toggle("heart-active");
                })
                .catch(error => console.error("Ошибка при обновлении лайка:", error.message));
        });
    });
});


$(document).ready(function() {
    $('.property-card').each(function() {
        var housingId = $(this).data('housing-id');
        var slider = $(this).find('.property-slider');

        $.get('/api/showImages?housing_id=' + housingId, function(response) {
            var images = response.data.data;

            if (Array.isArray(images) && images.length) {
                images.forEach(function(image) {
                    slider.append('<div><img src="' + image + '" alt="Image" class="slider-image"></div>');
                });

                // Инициализация слайдера
                slider.slick({
                    infinite: true,
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: true,
                    dots: true,
                    autoplay: true,
                    autoplaySpeed: 2000,
                });
            } else {
                slider.append('<p>No images available</p>');
            }
        }).fail(function() {
            slider.append('<p>Failed to load images</p>');
        });
    });
});


document.addEventListener('DOMContentLoaded', function() {
    const filterButton = document.querySelector('.filter-button');
    const filterModal = document.querySelector('.filter-modal');
    const closeFilter = document.querySelector('.close-filter');

    filterButton.addEventListener('click', function() {
        filterModal.style.display = 'flex';
    });

    closeFilter.addEventListener('click', function() {
        filterModal.style.display = 'none';
    });

    // Додаємо обробник події для форми, щоб закрити модальне вікно
    const filterForm = document.querySelector('.filter-modal form');
    filterForm.addEventListener('submit', function() {
        filterModal.style.display = 'none';
    });
});



const ratingItemList = document.querySelectorAll('.rating_item');
const ratingItemsArray = Array.prototype.slice.call(ratingItemList);

ratingItemsArray.forEach(item =>
    item.addEventListener('click', () => {
        const { itemValue } = item.dataset;
        const housingId = item.parentNode.dataset.housingId;

        // Отправляем AJAX-запрос на сервер
        fetch('/submitRating', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                housingId: housingId,
                rating: itemValue
            })
        })
            .then(response => {
                if (response.ok) {
                    console.log('Рейтинг успешно отправлен');
                    item.parentNode.dataset.totalValue = itemValue;
                    item.parentNode.classList.add('rated');
                } else {
                    console.error('Ошибка при отправке рейтинга');
                }
            })
            .catch(error => {
                console.error('Ошибка:', error);
            });
    })
);


document.addEventListener('DOMContentLoaded', function() {
    const ratingContainer = document.querySelector('.rating');
    if (ratingContainer) {
        const housingId = ratingContainer.dataset.housingId;
        const avgRatingSpan = document.getElementById(`avgRating-${housingId}`);

        if (housingId && avgRatingSpan) {
            fetch('/api/avg-rating', { // Замените на ваш фактический API-эндпоинт
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ housingId: housingId })
            })
                .then(response => response.json())
                .then(data => {
                    console.log('Received data:', data);

                    // Проверяем правильную структуру ответа
                    if (data.data && data.data.data && typeof data.data.data.avg_rating !== 'undefined') {
                        avgRatingSpan.textContent = `${data.data.data.avg_rating}`;
                        console.log('Avg rating set:', avgRatingSpan.textContent);
                    } else {
                        avgRatingSpan.textContent = '(нет данных)';
                        console.warn('Unexpected data format:', data);
                    }
                })
                .catch(error => {
                    console.error('Error fetching average rating:', error);
                    avgRatingSpan.textContent = '(ошибка)'; // Отображаем сообщение об ошибке
                });
        }
    }
});





