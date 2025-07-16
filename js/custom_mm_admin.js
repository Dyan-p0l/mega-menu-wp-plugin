    jQuery(document).ready(function($) {
        let currentItemId = null;

        $('.configure_mega_menu_btn').on('click', function() {
            currentItemId = $(this).data('item-id');
            const isMega = $(this).data('is-mega');

            if (!isMega) {
                $('#not-mega-menu').dialog({
                        modal: true,
                        width: 400,
                        title: 'Mega menu'
                });

                return;
            }

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
                                const image = item.image ? `<img src="${item.image}" style="width: 20px; height: 20px; vertical-align: middle; margin-right: 5px;">` : '';
                                const icon = item.icon ? `<i class="${item.icon}" style="margin-right: 5px;"></i>` : '';
                                $('#sortable-submenus').append(`
                                    <li class="ui-state-default" data-id="${item.ID}">
                                        <span class="dashicons dashicons-menu"></span> ${image}${icon}${item.title}
                                    </li>
                                `);
                            });

                        $('#sortable-submenus').sortable();

                        $('#mega-menu-popup').dialog({
                            modal: true,
                            width: 1070,
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
                    $('#save-popup').dialog({
                        modal: true,
                        width: 400,
                        title: 'Submenu order'
                    });

                    $('#mega-menu-popup').dialog('close');
                }
            });
        });

        $('.select-menu-image').on('click', function(e) {
            e.preventDefault();
            const button = $(this);
            const inputID = button.data('input-id');
            const inputField = $('#' + inputID);

            const frame = wp.media({
                title: 'Select or Upload Image',
                button: { text: 'Use this image' },
                multiple: false
            });

            frame.on('select', function() {
                const attachment = frame.state().get('selection').first().toJSON();
                inputField.val(attachment.url);
            });

            frame.open();
        });

        $('#close-mega-menu').on('click', function() {
            $('#mega-menu-popup').dialog('close');
        }); 

        $('.color-picker').wpColorPicker({
            hide: false,
            palettes: true
        });
        
    });