// Enhanced table display and editing for Trix editor
document.addEventListener('DOMContentLoaded', function() {
    console.log('Enhanced table script loaded');
    
    // Initialize after a short delay to ensure Trix is ready
    setTimeout(function() {
        initializeTableEnhancements();
    }, 1000);
    
    // Also initialize on interval to catch late-loaded editors
    setInterval(initializeTableEnhancements, 2000);
});

function initializeTableEnhancements() {
    console.log('Initializing table enhancements...');
    
    // Find all Trix editors
    const editors = document.querySelectorAll('trix-editor');
    console.log('Found', editors.length, 'Trix editors');
    
    editors.forEach(function(editor, index) {
        console.log('Processing editor', index + 1);
        
        // Style existing tables
        styleExistingTables(editor);
        
        // Add observer for content changes
        if (!editor.hasAttribute('data-table-observer')) {
            addContentObserver(editor);
            editor.setAttribute('data-table-observer', 'true');
        }
    });
}

function styleExistingTables(editor) {
    const tables = editor.querySelectorAll('table');
    console.log('Found', tables.length, 'tables in editor');
    
    // Also check for tables with Tailwind classes (newly inserted tables)
    const tailwindTables = editor.querySelectorAll('.w-full.min-w-\\[640px\\].border.border-gray-200.text-sm');
    console.log('Found', tailwindTables.length, 'Tailwind-styled tables');
    
    tables.forEach(function(table, tableIndex) {
        console.log('Styling table', tableIndex + 1);
        
        // Main table styling
        table.style.cssText = `
            width: 100% !important;
            border-collapse: collapse !important;
            margin: 20px auto !important;
            border: 2px solid #d1d5db !important;
            font-size: 14px !important;
            background: white !important;
            display: table !important;
            visibility: visible !important;
            opacity: 1 !important;
            table-layout: auto !important;
        `;
        
        // Ensure table structure elements are properly displayed
        const thead = table.querySelector('thead');
        const tbody = table.querySelector('tbody');
        const tfoot = table.querySelector('tfoot');
        
        if (thead) {
            thead.style.display = 'table-header-group';
        }
        if (tbody) {
            tbody.style.display = 'table-row-group';
        }
        if (tfoot) {
            tbody.style.display = 'table-footer-group';
        }
        
        // Style all rows
        const rows = table.querySelectorAll('tr');
        rows.forEach(function(row) {
            row.style.display = 'table-row';
        });
        
        // Style header cells
        const headerCells = table.querySelectorAll('thead th, thead td');
        headerCells.forEach(function(cell) {
            cell.style.cssText = `
                display: table-cell !important;
                border: 1px solid #9ca3af !important;
                padding: 12px !important;
                background-color: #f3f4f6 !important;
                font-weight: bold !important;
                text-align: left !important;
                vertical-align: top !important;
                min-width: 120px !important;
                cursor: text !important;
            `;
        });
        
        // Style body header cells (first column in tbody)
        const bodyRows = table.querySelectorAll('tbody tr');
        bodyRows.forEach(function(row) {
            const firstCell = row.querySelector('th, td:first-child');
            if (firstCell && (firstCell.tagName === 'TH' || row.cells[0] === firstCell)) {
                firstCell.style.cssText = `
                    display: table-cell !important;
                    border: 1px solid #9ca3af !important;
                    padding: 12px !important;
                    background-color: #f9fafb !important;
                    font-weight: bold !important;
                    text-align: left !important;
                    vertical-align: top !important;
                    min-width: 120px !important;
                    cursor: text !important;
                `;
            }
        });
        
        // Style regular data cells
        const dataCells = table.querySelectorAll('tbody td:not(:first-child)');
        dataCells.forEach(function(cell) {
            cell.style.cssText = `
                display: table-cell !important;
                border: 1px solid #9ca3af !important;
                padding: 12px !important;
                background-color: white !important;
                text-align: left !important;
                vertical-align: top !important;
                min-width: 120px !important;
                cursor: text !important;
            `;
        });
        
        // Add interaction enhancements
        const allCells = table.querySelectorAll('th, td');
        allCells.forEach(function(cell) {
            // Remove any existing event listeners to avoid duplicates
            if (!cell.hasAttribute('data-enhanced')) {
                addCellInteractivity(cell);
                cell.setAttribute('data-enhanced', 'true');
            }
        });
        
        console.log('Table', tableIndex + 1, 'styled successfully');
    });
}

function addCellInteractivity(cell) {
    // Focus enhancement
    cell.addEventListener('focus', function() {
        this.style.outline = '2px solid #3b82f6';
        this.style.boxShadow = '0 0 0 1px #3b82f6';
        console.log('Cell focused:', this.textContent);
    });
    
    cell.addEventListener('blur', function() {
        this.style.outline = 'none';
        this.style.boxShadow = 'none';
        console.log('Cell updated:', this.textContent);
    });
    
    // Hover effects
    cell.addEventListener('mouseenter', function() {
        if (this !== document.activeElement) {
            this.style.backgroundColor = '#eff6ff';
        }
    });
    
    cell.addEventListener('mouseleave', function() {
        if (this !== document.activeElement) {
            // Restore original background color
            if (this.tagName === 'TH') {
                if (this.closest('thead')) {
                    this.style.backgroundColor = '#f3f4f6';
                } else {
                    this.style.backgroundColor = '#f9fafb';
                }
            } else {
                this.style.backgroundColor = 'white';
            }
        }
    });
    
    // Keyboard navigation
    cell.addEventListener('keydown', function(e) {
        if (e.key === 'Tab' && !e.shiftKey) {
            e.preventDefault();
            const nextCell = findNextCell(this);
            if (nextCell) {
                nextCell.focus();
            }
        } else if (e.key === 'Tab' && e.shiftKey) {
            e.preventDefault();
            const prevCell = findPrevCell(this);
            if (prevCell) {
                prevCell.focus();
            }
        } else if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            const nextRowCell = findNextRowCell(this);
            if (nextRowCell) {
                nextRowCell.focus();
            }
        }
    });
}

function findNextCell(currentCell) {
    const table = currentCell.closest('table');
    if (!table) return null;
    
    const cells = Array.from(table.querySelectorAll('th, td'));
    const currentIndex = cells.indexOf(currentCell);
    
    return cells[currentIndex + 1] || cells[0];
}

function findPrevCell(currentCell) {
    const table = currentCell.closest('table');
    if (!table) return null;
    
    const cells = Array.from(table.querySelectorAll('th, td'));
    const currentIndex = cells.indexOf(currentCell);
    
    return cells[currentIndex - 1] || cells[cells.length - 1];
}

function findNextRowCell(currentCell) {
    const currentRow = currentCell.closest('tr');
    if (!currentRow) return null;
    
    const nextRow = currentRow.nextElementSibling;
    if (!nextRow) {
        // Try to find first row in tbody if we're in thead
        const table = currentCell.closest('table');
        const tbody = table.querySelector('tbody');
        if (tbody && currentRow.closest('thead')) {
            const firstBodyRow = tbody.querySelector('tr');
            if (firstBodyRow) {
                return firstBodyRow.querySelector('th, td');
            }
        }
        return null;
    }
    
    const cellIndex = Array.from(currentRow.children).indexOf(currentCell);
    return nextRow.children[cellIndex] || nextRow.querySelector('th, td');
}

function addContentObserver(editor) {
    // Listen for content changes
    editor.addEventListener('trix-change', function() {
        console.log('Content changed, re-styling tables...');
        setTimeout(function() {
            styleExistingTables(editor);
        }, 100);
    });
    
    // Also use MutationObserver for more reliable detection
    const observer = new MutationObserver(function(mutations) {
        let shouldRestyle = false;
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                const addedTables = Array.from(mutation.addedNodes).filter(node => 
                    node.nodeType === 1 && (node.tagName === 'TABLE' || node.querySelector('table'))
                );
                if (addedTables.length > 0) {
                    shouldRestyle = true;
                }
            }
        });
        
        if (shouldRestyle) {
            console.log('New table detected, applying styles...');
            setTimeout(function() {
                styleExistingTables(editor);
            }, 100);
        }
    });
    
    observer.observe(editor, {
        childList: true,
        subtree: true
    });
}