document.addEventListener('DOMContentLoaded', function () {
    const sliderWrapper = document.getElementById('sliderWrapper');
    const sliderThumb = document.getElementById('sliderThumb');
    const sliderProgress = document.getElementById('sliderProgress');

    if (!sliderWrapper || !sliderThumb || !sliderProgress) return;

    let isDragging = false;
    let startX = 0;
    let maxSlide = sliderWrapper.clientWidth - sliderThumb.clientWidth - 8; // 8px for margins

    // Update maxSlide on resize
    window.addEventListener('resize', () => {
        maxSlide = sliderWrapper.clientWidth - sliderThumb.clientWidth - 8;
    });

    const getClientX = (e) => {
        return e.type.includes('touch') ? e.touches[0].clientX : e.clientX;
    };

    const dragStart = (e) => {
        isDragging = true;
        startX = getClientX(e) - sliderThumb.offsetLeft;
        sliderThumb.style.transition = 'none';
        sliderProgress.style.transition = 'none';
    };

    const dragMove = (e) => {
        if (!isDragging) return;
        
        let clientX = getClientX(e);
        let x = clientX - startX;
        
        if (x < 4) x = 4;
        if (x > maxSlide) x = maxSlide;
        
        sliderThumb.style.left = `${x}px`;
        sliderProgress.style.width = `${x + 24}px`; // center alignment of color progress
    };

    const dragEnd = () => {
        if (!isDragging) return;
        isDragging = false;

        const currentLeft = parseInt(sliderThumb.style.left || 4);
        
        // Check if verified (e.g. 95% of maxSlide)
        if (currentLeft >= maxSlide * 0.95) {
            // Lock at the end
            sliderThumb.style.left = `${maxSlide}px`;
            sliderProgress.style.width = '100%';
            sliderThumb.style.backgroundColor = '#10b981'; // Green color for success
            sliderThumb.innerHTML = '<i class="bi bi-check-lg text-white"></i>';
            
            // Send verification request to server
            verifySession();
        } else {
            // Reset to start
            sliderThumb.style.transition = 'left 0.3s ease-out';
            sliderProgress.style.transition = 'width 0.3s ease-out';
            sliderThumb.style.left = '4px';
            sliderProgress.style.width = '0px';
        }
    };

    sliderThumb.addEventListener('mousedown', dragStart);
    window.addEventListener('mousemove', dragMove);
    window.addEventListener('mouseup', dragEnd);

    sliderThumb.addEventListener('touchstart', dragStart, { passive: true });
    window.addEventListener('touchmove', dragMove, { passive: true });
    window.addEventListener('touchend', dragEnd);

    function verifySession() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        
        fetch('/verify', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect to home/landing page
                window.location.href = '/home';
            }
        })
        .catch(err => {
            console.error('Verification failed', err);
            // Reset slider on error
            sliderThumb.style.left = '4px';
            sliderProgress.style.width = '0px';
            sliderThumb.style.backgroundColor = '#3b82f6';
            sliderThumb.innerHTML = '<i class="bi bi-chevron-double-right"></i>';
        });
    }
});
