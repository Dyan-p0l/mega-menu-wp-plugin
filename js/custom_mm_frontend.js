document.addEventListener('DOMContentLoaded', function () {

  const images = document.querySelectorAll('.menu-item-img');
  const items = document.querySelectorAll('.sub-menu-item');
  const menuItemText = document.querySelectorAll('.menu-item-text');
  const itemTitle = document.querySelectorAll('.item-title');
  const contentContainer = document.querySelector('.mega-content-container');
  const megaParents = document.querySelectorAll('.mega-parent');
  const parents = document.querySelectorAll('.sub-menu');
  const pageContent = document.querySelectorAll('.page-content');
  
  let hoveredContainer = true;
  let activeMegaId = null;
  let parentHovered = false;
  let lastHoveredParent = null;

  pageContent.forEach(content => {
    const contentMegaParent = content.closest('.mega-parent');
    const cont = contentMegaParent?.querySelector('.mega-content-container');
    const inner = content.querySelector('.mega-menu-content');
    if (!cont && !contentMegaParent) return;    
    
    
    if (!contentMegaParent.classList.contains('page-loaded')) {
      const left = cont.querySelector('.mega-content-left');
      const right = cont.querySelector('.mega-content-right');
      if (left) left.remove();
      if (right) right.remove();

      contentMegaParent.classList.add('page-loaded');
    }

    if (!cont.contains(content)) {
      cont.appendChild(content);
    }

    inner.style.display = 'none';
    content.style.display = 'none';

  });

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
        const inner = document.querySelector('.mega-menu-content');

        if (!el.classList.contains('original-hidden')) {
          if (el.classList.contains('sub-menu-item')) el.classList.remove('visible');
          el.style.display = 'none';
          if (inner) inner.style.display = 'none'; 
        }
      });
      activeMegaId = null;
    }
  });

  parents.forEach(parent => {
    const id = parent.dataset.megaId + "1";
    
    parent.addEventListener('mouseenter', () => {
      parentHovered = true;

      if (lastHoveredParent && lastHoveredParent !== parent) {
        lastHoveredParent.classList.remove('hovered');
      }
      parent.classList.add('hovered');
      // Hide any previously visible content
      if (activeMegaId && activeMegaId !== id) {
        
        document.querySelectorAll(`[data-mega-id="${activeMegaId}"]`).forEach(el => {
          const inner = el.querySelector('.mega-menu-content');
          if (!el.classList.contains('original-hidden')) {
            if (el.classList.contains('sub-menu-item')) el.classList.remove('visible');
            el.style.display = 'none';
            if (inner) inner.style.display = 'none';
          }
        });
      }

      document.querySelectorAll(`[data-mega-id="${id}"]`).forEach(el => {
        const inner = el.querySelector('.mega-menu-content');
        if (!el.classList.contains('original-hidden')) {
          if (el.classList.contains('sub-menu-item')) el.classList.add('visible');
          el.style.display = 'block';
          if (inner) inner.style.display = 'flex';
        }
      });

      activeMegaId = id;

      lastHoveredParent = parent;
    });

    parent.addEventListener('mouseleave', () => {
      if (!hoveredContainer) {
        document.querySelectorAll(`[data-mega-id="${id}"]`).forEach(el => {
          const inner = el.querySelector('.mega-menu-content');
          if (!el.classList.contains('original-hidden')) {
            if (el.classList.contains('sub-menu-item')) el.classList.remove('visible');
            el.style.display = 'none';
            if (inner) inner.style.display = 'none';
          }
        });
        activeMegaId = null;
      }
      
      if (!parentHovered && !hoveredContainer) {
        parent.classList.remove('hovered');
        parentHovered = false;
      }
    });
  });

  //FOR DEFAULT HOVERED SUB-MENU
  megaParents.forEach(megaparent => {
    megaparent.addEventListener('mouseenter', () => {
      const defaultHoverItem = megaparent.querySelector('.sub-menu');
      if (defaultHoverItem) defaultHoverItem.dispatchEvent(new MouseEvent('mouseenter'));
    });
  });

});
