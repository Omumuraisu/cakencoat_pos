$(document).ready(function() {




    $("#signOut").click(function() {
        if (confirm("Are you sure you want to sign out?")) {
            $.ajax({
                url: 'actions/logout.php',
                type: 'POST',
                success: function(response) {
                    // Optionally, you can check response === 'logged out'
                    window.location.href = "login.php";
                }
            });
        }
    });

    $(".order-history").click(function() {
        window.location.href = "orderhistory.php";
    });

    $(".order-card").click(function() {
        var oid = $(this).data("oid");
        window.location.href = "order.php?oid=" + oid;
    });

    $(".order-card.paid").click(function() {
        var oid = $(this).data("oid");
        window.location.href = "order.php?oid=" + oid;
    });
    

    $(".return-home").click(function() {
        window.location.href = "dashboard.php";
    });

    $(".button.pos-button").click(function() {
        window.location.href = "pos.php";
    });

    $(".button.menu-button").click(function() {
        window.location.href = "menu.php";
    });

    $(".button.inventory-button").click(function() {
        window.location.href = "inventory.php";
    });

    const urlCheck = new URLSearchParams(window.location.search); //check url for errors
            if (urlCheck.has('error')) {
                setTimeout(function() {
                    alert("Login Invalid. Please try again.");
                }, 300);
            }












    // Toggle sidebar visibility
    $('#menu-toggle').on('click', function () {
        $('#sidebar').toggleClass('hidden visible');
    });

    // Close sidebar on close button click
    $('#close-sidebar').on('click', function () {
        $('#sidebar').addClass('hidden').removeClass('visible');
    });

    // Close sidebar if clicking outside of it
    $(document).on('click', function (event) {
        const $sidebar = $('#sidebar');
        const $menuToggle = $('#menu-toggle');

        if (!$sidebar.is(event.target) && $sidebar.has(event.target).length === 0 &&
            !$menuToggle.is(event.target) && $menuToggle.has(event.target).length === 0) {
            $sidebar.addClass('hidden').removeClass('visible');
        }
    });

    // Toggle subcategories on header click
    $('.category-header').on('click', function () {
        const $header = $(this);
        const $subcategory = $header.next('.subcategory');

        console.log('Header clicked:', $header);
        if ($subcategory.length > 0) {
            console.log('Toggling subcategory:', $subcategory);
            $subcategory.toggleClass('hidden');
            $header.toggleClass('expanded');
        } else {
            console.log('No subcategory found or incorrect structure.');
        }
    });







    $(".category-list ul li a").click(function (e) {
        e.preventDefault();

        // Update selected style
        $(".category-list ul li a").removeClass("selected");
        $(this).addClass("selected");

        // Get clicked category
        const selectedCategory = $(this).data("category");

        // Show only matching category-box
        $(".category-box").hide(); // Hide all
        $(`.category-box[data-category='${selectedCategory}']`).show(); // Show matching
    });

    // Show only the first category by default
    const firstCategory = $(".category-list ul li a.selected").data("category");
    $(".category-box").hide();
    $(`.category-box[data-category='${firstCategory}']`).show();












    

    $('.item-section').on('click', '.item-card:not(.disabled)', function () {
    const itemName = $(this).find('p').text().trim();
    const sanitizedName = itemName.replace(/\s+/g, '_'); // For safe input naming

    // Check if item already exists
    let $existing = $('.order-list li').filter(function () {
        return $(this).data('item-name') === itemName;
    });

    if ($existing.length) {
        // Increment count if item exists
        const $span = $existing.find('span');
        const match = $span.text().match(/\((\d+)\)$/);
        let count = match ? parseInt(match[1]) + 1 : 2;
        $span.text(`${itemName} (${count})`);

        // Update hidden input value
        $existing.find(`input[name="orders[${sanitizedName}][quantity]"]`).val(count);
    } else {
        // Get price from item card's data attribute
        const price = $(this).data('price');

        $('.order-list').append(`
            <li data-item-name="${itemName}">
                <span>${itemName} (1)</span>
                <input type="hidden" name="orders[${sanitizedName}][quantity]" value="1">
                <input type="hidden" name="orders[${sanitizedName}][price]" value="${price}">
                <button type="button" class="remove-order">✖</button>
            </li>
        `);
    }
});


    // Remove item from order list
    $('.order-list').on('click', '.remove-order', function () {
        $(this).closest('li').remove();
    });


});







