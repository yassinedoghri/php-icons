<?php

declare(strict_types=1);

use PHPIcons\PHPIcons;

if (! function_exists('icon')) {
    /**
     * @param  array<string, string>  $attributes
     */
    function icon(string $iconKey, array $attributes = []): string
    {
        return (string) (new PHPIcons())
            ->icon($iconKey)
            ->attributes($attributes);
    }
}

?>

<div class="relative text-xs group">
    <h1>This is an exemple file</h1>
    <p>Let's echo some icons here:</p>
    <ul>
        <li><?= icon('lucide:copy', ['class' => 'text-sm']) ?></li>
        <li><?= icon('lucide:check', ['class' => 'text-rose-400']) ?></li>
        <li><?= icon('lucide:airplay') ?></li>
    </ul>
</div>


