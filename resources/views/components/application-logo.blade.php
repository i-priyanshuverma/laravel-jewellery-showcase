<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="none" {{ $attributes->merge(['class' => 'w-10 h-10']) }}>
    <defs>
        <linearGradient id="shGoldTop" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#FDE68A"/>
            <stop offset="100%" stop-color="#F59E0B"/>
        </linearGradient>
        <linearGradient id="shGoldBottom" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#F59E0B"/>
            <stop offset="100%" stop-color="#B45309"/>
        </linearGradient>
        <linearGradient id="shBg" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="#1E293B"/>
            <stop offset="100%" stop-color="#0F172A"/>
        </linearGradient>
    </defs>
    <!-- Rounded Base Badge -->
    <rect width="48" height="48" rx="12" fill="url(#shBg)"/>
    <rect x="0.5" y="0.5" width="47" height="47" rx="11.5" stroke="#334155" stroke-width="1"/>
    
    <!-- Outer Gold Facets -->
    <!-- Top Table -->
    <polygon points="17,13 31,13 38,21 10,21" fill="url(#shGoldTop)"/>
    <!-- Top Center Highlight -->
    <polygon points="20,13 28,13 32,21 16,21" fill="#FEF08A" opacity="0.6"/>
    <!-- Bottom Center Pavilion -->
    <polygon points="16,21 32,21 24,37" fill="url(#shGoldTop)"/>
    <!-- Bottom Left Pavilion -->
    <polygon points="10,21 16,21 24,37" fill="url(#shGoldBottom)"/>
    <!-- Bottom Right Pavilion -->
    <polygon points="38,21 32,21 24,37" fill="#92400E"/>
    
    <!-- Facet Edge Accent Glow -->
    <polyline points="10,21 24,37 38,21" stroke="#FDE68A" stroke-width="0.8" stroke-linecap="round" stroke-linejoin="round" opacity="0.8"/>
    <line x1="10" y1="21" x2="38" y2="21" stroke="#FFFBEB" stroke-width="0.8" opacity="0.9"/>
</svg>
