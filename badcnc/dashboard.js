document.getElementById('menu-toggle').addEventListener('click', function () {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('hidden');
    sidebar.classList.toggle('visible');
});

// Close sidebar when clicking the close button
document.getElementById('close-sidebar').addEventListener('click', function () {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.add('hidden');
    sidebar.classList.remove('visible');
});

// Close sidebar when clicking outside of it
document.addEventListener('click', function (event) {
    const sidebar = document.getElementById('sidebar');
    const menuToggle = document.getElementById('menu-toggle');
    if (!sidebar.contains(event.target) && !menuToggle.contains(event.target)) {
        sidebar.classList.add('hidden');
        sidebar.classList.remove('visible');
    }
});

document.querySelectorAll('.category-header').forEach(header => {
    header.addEventListener('click', () => {
        console.log('Header clicked:', header); // Log the clicked header
        const subcategory = header.nextElementSibling; // Get the next sibling element
        if (subcategory && subcategory.classList.contains('subcategory')) {
            console.log('Toggling subcategory:', subcategory); // Log the subcategory being toggled
            subcategory.classList.toggle('hidden'); // Toggle the 'hidden' class
            header.classList.toggle('expanded'); // Optional: Add a class for styling the expanded state
        } else {
            console.log('No subcategory found or incorrect structure.');
        }
    });
});


// Make inventory quantities editable
document.querySelectorAll('.inventory-table td:nth-child(2)').forEach(cell => {
    cell.style.cursor = 'pointer';
    cell.addEventListener('click', function handleClick() {
        if (cell.querySelector('input')) return;

        const match = cell.textContent.trim().match(/^(\d+)\s*(kg|packs?|kilogram|pack)$/i);
        let value = match ? match[1] : '';
        let unit = match ? match[2] : 'kg';

        if (unit === 'kilogram') unit = 'kg';
        if (unit === 'pack') unit = 'packs';

        const input = document.createElement('input');
        input.type = 'number';
        input.value = value;
        input.style.width = '50px';

        const select = document.createElement('select');
        ['kg', 'packs'].forEach(opt => {
            const option = document.createElement('option');
            option.value = opt;
            option.textContent = opt;
            if (opt === unit) option.selected = true;
            select.appendChild(option);
        });

        // Track focus
        let focusCount = 0;
        function onFocus() { focusCount++; }
        function onBlur() {
            focusCount--;
            setTimeout(() => {
                if (focusCount <= 0) save();
            }, 0);
        }

        input.addEventListener('focus', onFocus);
        select.addEventListener('focus', onFocus);
        input.addEventListener('blur', onBlur);
        select.addEventListener('blur', onBlur);

        function save() {
            const newValue = input.value;
            const newUnit = select.value;
            if (newValue) {
                cell.textContent = `${newValue} ${newUnit}`;
                if ((newUnit === 'kg' && Number(newValue) <= 1) || (newUnit === 'packs' && Number(newValue) <= 1)) {
                    cell.classList.add('low-stock');
                } else {
                    cell.classList.remove('low-stock');
                }
            } else {
                cell.textContent = `${value} ${unit}`;
            }
        }

        cell.textContent = '';
        cell.appendChild(input);
        cell.appendChild(select);
        input.focus();

        input.addEventListener('keydown', e => {
            if (e.key === 'Enter') {
                save();
                input.blur();
                select.blur();
            }
        });
    });
});