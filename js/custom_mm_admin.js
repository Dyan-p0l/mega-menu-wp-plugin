jQuery(document).ready(function($) {
    let currentItemId = null;

    $('.configure_mega_menu_btn').on('click', function() {
        currentItemId = $(this).data('item-id');
        $('#sortable-submenus').empty();

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'get_submenu_items',
                parent_id: currentItemId
            },
            success: function(response) {
                if (response.success) {
                    const items = response.data;
                    items.forEach(function(item) {
                        $('#sortable-submenus').append(`
                            <li class="ui-state-default" data-id="${item.ID}">
                                <span class="dashicons dashicons-menu"></span> ${item.title}
                            </li>
                        `);
                    });

                    $('#sortable-submenus').sortable();

                    $('#mega-menu-popup').dialog({
                        modal: true,
                        width: 970,
                        title: 'Configure Mega Menu'
                    });
                }
            }
        });
    });

    $('#save-mega-menu').on('click', function() {
        const newOrder = $('#sortable-submenus li').map(function() {
            return $(this).data('id');
        }).get();

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'save_submenu_order',
                parent_id: currentItemId,
                order: newOrder
            },
            success: function(response) {
                alert('Order saved!');
                $('#mega-menu-popup').dialog('close');
            }
        });
    });

    $('#close-mega-menu').on('click', function() {
        $('#mega-menu-popup').dialog('close');
    });
});