/**
 * Frontend script for the ClickUp Pricing Table block.
 *
 * Handles the "See more features" toggle functionality.
 */
document.addEventListener('DOMContentLoaded', () => {
    const pricingTables = document.querySelectorAll('.wp-block-create-block-clickup-pricing-table');

    pricingTables.forEach((table) => {
        const toggleButton = table.querySelector('.features-toggle-button');
        const extendedFeatures = table.querySelector('.features-extended');

        if (!toggleButton || !extendedFeatures) {
            return;
        }

        toggleButton.addEventListener('click', () => {
            const isExpanded = toggleButton.getAttribute('aria-expanded') === 'true';

            toggleButton.setAttribute('aria-expanded', !isExpanded);
            extendedFeatures.hidden = isExpanded;
            table.classList.toggle('is-expanded');

            if (!isExpanded) {
                toggleButton.textContent = 'See less features';
            } else {
                toggleButton.textContent = 'See more features';
            }
        });
    });
});