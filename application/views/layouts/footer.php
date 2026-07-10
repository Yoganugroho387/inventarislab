<?php
/**
 * Shared Footer Layout Component
 * © Yoga Nugroho - All Rights Reserved
 */
?>
    <!-- Initialize Lucide Icons -->
    <script>
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    </script>

    <!-- Anti-Theft Protection -->
    <script>
        // Disable right-click context menu
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            return false;
        });

        // Disable keyboard shortcuts for DevTools & View Source
        document.addEventListener('keydown', function(e) {
            // F12 - DevTools
            if (e.key === 'F12') {
                e.preventDefault();
                return false;
            }
            // Ctrl+Shift+I - DevTools
            if (e.ctrlKey && e.shiftKey && (e.key === 'I' || e.key === 'i')) {
                e.preventDefault();
                return false;
            }
            // Ctrl+Shift+J - Console
            if (e.ctrlKey && e.shiftKey && (e.key === 'J' || e.key === 'j')) {
                e.preventDefault();
                return false;
            }
            // Ctrl+Shift+C - Inspect Element
            if (e.ctrlKey && e.shiftKey && (e.key === 'C' || e.key === 'c')) {
                e.preventDefault();
                return false;
            }
            // Ctrl+U - View Source
            if (e.ctrlKey && (e.key === 'U' || e.key === 'u')) {
                e.preventDefault();
                return false;
            }
            // Ctrl+S - Save Page
            if (e.ctrlKey && (e.key === 'S' || e.key === 's')) {
                e.preventDefault();
                return false;
            }
        });

        // Disable drag on all elements
        document.addEventListener('dragstart', function(e) {
            e.preventDefault();
            return false;
        });

        // Console warning
        console.log('%c⚠️ STOP!', 'color: red; font-size: 40px; font-weight: bold;');
        console.log('%cWebsite ini dilindungi hak cipta. Dilarang menyalin atau menduplikasi tanpa izin.', 'color: #333; font-size: 14px;');
        console.log('%c© Yoga Nugroho | 089685027530', 'color: #0d9488; font-size: 12px;');
    </script>
</body>
</html>
