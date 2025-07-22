document.addEventListener('DOMContentLoaded', function () {

  const images = document.querySelectorAll('.menu-item-img');
  const items = document.querySelectorAll('.sub-menu-item');
  const menuItemText = document.querySelectorAll('.menu-item-text');
  const itemTitle = document.querySelectorAll('.item-title');
  const contentContainer = document.querySelector('.mega-content-container');
  const parents = document.querySelectorAll('.sub-menu');

  let hoveredContainer = true;
  let activeMegaId = null;

  images.forEach(img => {
    const container = img.closest('.mega-parent')?.querySelector('.mega-content-right');
    const parent = img.closest('.sub-menu');
    if (container && parent) {
      const megaId = parent.dataset.megaId + "1";
      img.dataset.megaId = megaId;
      const clone = img.cloneNode(true);
      container.appendChild(clone);
      img.classList.add('original-hidden');
      img.dataset.cloned = "true";
      img.style.display = 'none';
      clone.style.display = 'none';
    }
  });

  itemTitle.forEach(title => {
    const container = title.closest('.mega-parent')?.querySelector('.mega-content-text');
    const parent = title.closest('.sub-menu');

    if (container && parent) {
      const megaId = parent.dataset.megaId + "1";
      title.dataset.megaId = megaId;
      const clone = title.cloneNode(true);
      container.appendChild(clone);
      title.dataset.cloned = "true";
      clone.style.display = 'none';
      title.classList.add('original-hidden');
    }
  });

  menuItemText.forEach(item => {
    const container = item.closest('.mega-parent')?.querySelector('.mega-content-text');
    const parent = item.closest('.sub-menu');
    if (container && parent) {
      const megaId = parent.dataset.megaId + "1";
      item.dataset.megaId = megaId;
      const clone = item.cloneNode(true);
      container.appendChild(clone);
      item.dataset.cloned = "true";
      item.style.display = 'none';
      item.classList.add('original-hidden');
      clone.style.display = 'none';
    }
  });

  items.forEach(item => {
    const container = item.closest('.mega-parent')?.querySelector('.mega-content-left');
    const parent = item.closest('.sub-menu');
    if (container && parent) {
      const megaId = parent.dataset.megaId + "1";
      item.dataset.megaId = megaId;
      const clone = item.cloneNode(true);
      container.appendChild(clone);
      item.dataset.cloned = "true";
      item.style.display = 'none';
      item.classList.add('original-hidden');
      clone.style.display = 'none';
    }
  });
  
  
  contentContainer.addEventListener('mouseenter', () => {
    hoveredContainer = true;
  });

  contentContainer.addEventListener('mouseleave', () => {
    hoveredContainer = false;

    if (activeMegaId) {
      document.querySelectorAll(`[data-mega-id="${activeMegaId}"]`).forEach(el => {
        if (!el.classList.contains('original-hidden')) {
          if (el.classList.contains('sub-menu-item')) el.classList.remove('visible');
          el.style.display = 'none';
        }
      });
      activeMegaId = null;
    }
  });

  parents.forEach(parent => {
  const id = parent.dataset.megaId + "1";

    parent.addEventListener('mouseenter', () => {
      // Hide any previously visible content
      if (activeMegaId && activeMegaId !== id) {
        document.querySelectorAll(`[data-mega-id="${activeMegaId}"]`).forEach(el => {
          if (!el.classList.contains('original-hidden')) {
            if (el.classList.contains('sub-menu-item')) el.classList.remove('visible');
            el.style.display = 'none';
          }
        });
      }

      // Show the current submenu content
      document.querySelectorAll(`[data-mega-id="${id}"]`).forEach(el => {
        if (!el.classList.contains('original-hidden')) {
          if (el.classList.contains('sub-menu-item')) el.classList.add('visible');
          el.style.display = 'block';
        }
      });

      activeMegaId = id;
    });

    parent.addEventListener('mouseleave', () => {
      if (!hoveredContainer) {
        document.querySelectorAll(`[data-mega-id="${id}"]`).forEach(el => {
          if (!el.classList.contains('original-hidden')) {
            if (el.classList.contains('sub-menu-item')) el.classList.remove('visible');
            el.style.display = 'none';
          }
        });
        activeMegaId = null;
      }
    });
  });


});
