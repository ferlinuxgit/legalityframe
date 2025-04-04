<?php
use App\Config\Lang;

// Obtener el idioma actual
$currentLocale = Lang::getInstance()->getLocale();

// Obtener idiomas disponibles
$languages = Lang::getInstance()->getAvailableLanguages();
?>

<div class="language-selector">
    <div class="dropdown">
        <button class="btn btn-sm dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <?= $languages[$currentLocale] ?>
        </button>
        <ul class="dropdown-menu" aria-labelledby="languageDropdown">
            <?php foreach ($languages as $code => $name): ?>
                <?php if ($code !== $currentLocale): ?>
                    <li>
                        <a class="dropdown-item" href="<?= url('/language/' . $code) ?>">
                            <?= $name ?>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </div>
</div> 