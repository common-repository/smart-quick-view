<?php 
    use SmartQuickView\App\Data\Preferences\Preferences;
    use SmartQuickView\Original\Collections\Collection;
?>

<button class="<?php echo esc_attr(Collection::create([
    'sqv-quick-view-button',
    Preferences::get()->preferences->button_colors_isEnabled? 'bg-button-background text-button-text' : ''
])->filter()->implode(' ')) ?>" data-sqv-product-id="<?php echo esc_attr(the_ID()) ?>">
    <?php echo esc_html(Preferences::get()->preferences->button_text) ?>
</button>