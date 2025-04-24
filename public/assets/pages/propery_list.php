<?php if (!empty($properties)): ?>
    <?php foreach ($properties as $housing): ?>
        <div class="property-card" data-housing-id="<?= $housing->id ?>">
            <button class="like-wished <?= in_array($housing->id, $_SESSION['wishlist'] ?? []) ? 'active' : '' ?>"
                    data-housing-id="<?= $housing->id ?>">
                <div class="heart <?= in_array($housing->id, $_SESSION['wishlist'] ?? []) ? 'heart-active' : '' ?>"></div>
            </button>
            <div class="property-slider">
                <!-- Добавь сюда слайдер картинок -->
            </div>
            <div class="property-info">
                <p class="property-title"><?= $housing->title ?></p>
                <p class="property-address"><?= $housing->location ?></p>
                <p class="property-price">Ціна: <?= $housing->price ?></p>
                <p class="property-details">
                    <span>👤 <?= $housing->guests_capacity_max ?></span>
                    <?php if (!empty($housing->amenities)): ?>
                        <br><br>
                        <?php foreach ($housing->amenities as $amenity): ?>
                            <span>
                                <img src="/assets/pages/<?= $amenity->icon ?>" alt="<?= $amenity->name ?>"
                                     style="width: 20px; height: 20px; vertical-align: middle;">
                            </span>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </p>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Нічого не знайдено</p>
<?php endif; ?>
