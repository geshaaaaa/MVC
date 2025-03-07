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
        window.location.href = "/auth"; // Измените на ваш роут авторизации
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


git filter-branch --force --index-filter \
"git rm --cached --ignore-unmatch public/assets/pages/images/
--prune-empty --tag-name-filter cat -- --all

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".like-wished").forEach(button => {
        button.addEventListener("click", function () {
            let housingId = this.getAttribute("data-housing-id");
            let heart = this.querySelector(".heart");

            fetch("/toggle-wishlist", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                credentials: "include",
                body: JSON.stringify({ housing_id: housingId })
            })
                .then(response => response.json())
                .then(data => {
                    console.log("Ответ от сервера:", data);
                    this.classList.toggle("heart-active");
                    heart.classList.toggle("heart-active");
                })
                .catch(error => console.error("Ошибка запроса:", error));
        });
    });
});

