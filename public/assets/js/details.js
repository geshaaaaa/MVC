document.addEventListener('DOMContentLoaded', function () {
    const morePhotos = document.querySelector('.more-photos');
    const galleryModal = document.getElementById('galleryModal');
    const closeGalleryBtn = document.querySelector('.close-gallery');
    const gridImages = document.querySelectorAll('.grid-image');
    const mainImage = document.querySelector('.main-image img');
    const sideImages = document.querySelectorAll('.side-image img');

    const imageModal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const closeModal = document.querySelector('.close');

    let imagesArray = [];
    let currentImageIndex = 0;

    function updateImagesArray() {
        imagesArray = [];
        document.querySelectorAll('.grid-image img').forEach((img, index) => {
            imagesArray.push(img.src);
            img.dataset.index = index; // Добавляем индекс
        });
    }

    updateImagesArray();

    function openGalleryWithImage(imageSrc) {
        updateImagesArray(); // Обновляем список фото

        currentImageIndex = imagesArray.indexOf(imageSrc);

        if (currentImageIndex !== -1) {
            galleryModal.style.display = 'block';

            document.querySelectorAll('.grid-image').forEach(img => img.classList.remove('highlight'));

            const selectedImage = document.querySelector(`.grid-image img[src="${imageSrc}"]`);
            if (selectedImage) {
                selectedImage.parentElement.classList.add('highlight');
                selectedImage.scrollIntoView({behavior: 'smooth', block: 'center'});
            }
        }
    }

    function openModalWithImage(imageSrc) {
        currentImageIndex = imagesArray.indexOf(imageSrc);
        if (currentImageIndex !== -1) {
            modalImage.src = imageSrc;
            imageModal.style.display = 'block';
            galleryModal.style.display = 'none'; // **Закрываем галерею при открытии модального окна**
        }
    }

    if (mainImage) {
        mainImage.addEventListener('click', function () {
            openGalleryWithImage(mainImage.src);
        });
    }

    sideImages.forEach(img => {
        img.addEventListener('click', function () {
            openGalleryWithImage(img.src);
        });
    });

    if (morePhotos) {
        morePhotos.addEventListener('click', function () {
            galleryModal.style.display = 'block';
        });
    }

    gridImages.forEach(image => {
        image.addEventListener('click', function () {
            const imgSrc = this.querySelector('img').src;
            openModalWithImage(imgSrc);
        });
    });

    if (closeGalleryBtn) {
        closeGalleryBtn.addEventListener('click', function () {
            galleryModal.style.display = 'none';
        });
    }

    window.addEventListener('click', function (event) {
        if (event.target === galleryModal) {
            galleryModal.style.display = 'none';
        }
    });

    closeModal.addEventListener('click', function () {
        imageModal.style.display = 'none';
    });

    window.addEventListener('click', function (event) {
        if (event.target === imageModal) {
            imageModal.style.display = 'none';
        }
    });

    window.changeImage = function (n) {
        if (!imageModal.style.display || imageModal.style.display === 'none') {
            return;
        }
        currentImageIndex += n;
        if (currentImageIndex >= imagesArray.length) {
            currentImageIndex = 0;
        }
        if (currentImageIndex < 0) {
            currentImageIndex = imagesArray.length - 1;
        }
        modalImage.src = imagesArray[currentImageIndex];
    };
});

document.addEventListener("DOMContentLoaded", function () {
    const reserveButtonMain = document.querySelector('.reserve-button');
    const modal = document.getElementById('reservationModal');
    const closeModal = document.querySelector('.close-modal');
    const reserveButtonModal = document.getElementById('reserveButton');
    const checkinDateInput = document.getElementById('checkinDate');
    const checkoutDateInput = document.getElementById('checkoutDate');
    const totalDaysElement = document.getElementById('totalDays');
    const totalPriceElement = document.getElementById('totalPrice');
    const totalPriceInput = document.getElementById('totalPriceInput');
    const dailyPriceElement = document.getElementById('dailyPrice');
    const reservationForm = document.getElementById('reservationForm');
    const housingId = document.querySelector('.like-wished')?.dataset.housingId;

    if (reserveButtonMain) {
        reserveButtonMain.addEventListener('click', function () {
            modal.style.display = 'block';
        });
    }

    if (closeModal) {
        closeModal.addEventListener('click', function () {
            modal.style.display = 'none';
        });
    }

    window.addEventListener('click', function (event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });

    if (reserveButtonModal && housingId) {
        reserveButtonModal.addEventListener('click', function () {
            const checkinDate = checkinDateInput.value;
            const checkoutDate = checkoutDateInput.value;

            if (checkinDate && checkoutDate) {
                window.location.href = `/reservation?housing_id=${housingId}&checkin=${checkinDate}&checkout=${checkoutDate}`;
            } else {
                alert('Будь ласка, оберіть дати заїзду та виїзду.');
            }
        });
    }

    function calculateTotalPrice() {
        const checkinDate = new Date(checkinDateInput.value);
        const checkoutDate = new Date(checkoutDateInput.value);
        const dailyPrice = parseFloat(dailyPriceElement.textContent.replace(/\s/g, ''));

        if (!isNaN(checkinDate.getTime()) && !isNaN(checkoutDate.getTime()) && checkoutDate > checkinDate) {
            const totalDays = Math.ceil((checkoutDate - checkinDate) / (1000 * 60 * 60 * 24));
            const totalPrice = totalDays * dailyPrice;

            totalDaysElement.textContent = `Кількість днів: ${totalDays}`;
            totalPriceElement.textContent = totalPrice.toFixed(2) + " грн";
            totalPriceInput.value = totalPrice.toFixed(2); // Обновляем hidden input
        } else {
            totalDaysElement.textContent = "";
            totalPriceElement.textContent = "0.00 грн";
            totalPriceInput.value = "0.00"; // Обнуляем, если даты некорректны
        }
    }

    checkinDateInput.addEventListener('change', calculateTotalPrice);
    checkoutDateInput.addEventListener('change', calculateTotalPrice);

    reservationForm.addEventListener('submit', function (event) {
        calculateTotalPrice(); // Гарантируем обновление total_price перед отправкой

        if (!checkinDateInput.value || !checkoutDateInput.value) {
            event.preventDefault();
            alert('Будь ласка, оберіть дати заїзду та виїзду.');
        }
    });
});






