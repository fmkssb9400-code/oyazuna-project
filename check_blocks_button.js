// ブラウザのConsoleで実行: TinyMCE blocks button のDOM確認
console.log("=== TinyMCE Blocks Button DOM Check ===");

// TinyMCEエディタの存在確認
const tinymceContainers = document.querySelectorAll('.tox-tinymce');
console.log("TinyMCE containers found:", tinymceContainers.length);

if (tinymceContainers.length > 0) {
    tinymceContainers.forEach((container, index) => {
        console.log(`\n--- Container ${index + 1} ---`);
        
        // Toolbar内のblocks関連要素を探す
        const toolbar = container.querySelector('.tox-toolbar__primary');
        if (toolbar) {
            console.log("Toolbar found:", toolbar);
            
            // blocks selectフィールドを探す
            const selectFields = toolbar.querySelectorAll('.tox-selectfield select, .tox-split-button, .tox-tbtn');
            console.log("Select/button elements in toolbar:", selectFields.length);
            
            selectFields.forEach((field, i) => {
                const label = field.getAttribute('aria-label') || field.getAttribute('title') || field.textContent || 'No label';
                const style = window.getComputedStyle(field);
                console.log(`Element ${i + 1}:`, {
                    element: field,
                    label: label,
                    display: style.display,
                    visibility: style.visibility,
                    opacity: style.opacity,
                    overflow: style.overflow
                });
            });
        } else {
            console.log("Toolbar not found in this container");
        }
    });
} else {
    console.log("No TinyMCE containers found");
}

// Blocks button specific check
const blocksButton = document.querySelector('[aria-label*="Block"], [aria-label*="block"], .tox-selectfield select:first-child');
if (blocksButton) {
    console.log("\n--- Blocks Button Found ---");
    console.log("Element:", blocksButton);
    console.log("Computed styles:", window.getComputedStyle(blocksButton));
    console.log("Parent element:", blocksButton.parentElement);
} else {
    console.log("\n--- Blocks Button NOT Found ---");
    console.log("Searching for any select elements...");
    const allSelects = document.querySelectorAll('.tox-selectfield select, .tox-split-button');
    console.log("All select elements found:", allSelects.length);
    allSelects.forEach((select, i) => {
        console.log(`Select ${i + 1}:`, select, select.getAttribute('aria-label'));
    });
}

console.log("=== End DOM Check ===");