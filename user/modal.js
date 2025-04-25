const createBtn = document.getElementById('createBtn');
    const popupModal = document.getElementById('popupModal');
    const closeBtn = popupModal.querySelector('.close-btn');

    createBtn.addEventListener('click', () => {
      popupModal.style.display = 'block';
    });

    closeBtn.addEventListener('click', () => {
      popupModal.style.display = 'none';
    });

    window.addEventListener('click', (e) => {
      if (e.target === popupModal) {
        popupModal.style.display = 'none';
      }
    });