import './bootstrap';

const menuButton = document.querySelector('[data-menu-button]');
const mobileMenu = document.querySelector('[data-mobile-menu]');
const openIcon = document.querySelector('[data-menu-open-icon]');
const closeIcon = document.querySelector('[data-menu-close-icon]');
const mobileProfileButton = document.querySelector('[data-mobile-profile-button]');
const mobileProfileMenu = document.querySelector('[data-mobile-profile-menu]');
const mobileProfileIcon = document.querySelector('[data-mobile-profile-icon]');
const mobileAcademicButton = document.querySelector('[data-mobile-academic-button]');
const mobileAcademicMenu = document.querySelector('[data-mobile-academic-menu]');
const mobileAcademicIcon = document.querySelector('[data-mobile-academic-icon]');

if (menuButton && mobileMenu) {
    const setMenuState = (isOpen) => {
        mobileMenu.classList.toggle('hidden', !isOpen);
        mobileMenu.classList.toggle('soft-reveal', isOpen);
        menuButton.setAttribute('aria-expanded', String(isOpen));
        openIcon?.classList.toggle('hidden', isOpen);
        closeIcon?.classList.toggle('hidden', !isOpen);
    };

    menuButton.addEventListener('click', () => {
        setMenuState(mobileMenu.classList.contains('hidden'));
    });

    mobileMenu.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => setMenuState(false));
    });

    mobileProfileButton?.addEventListener('click', () => {
        const isOpen = mobileProfileButton.getAttribute('aria-expanded') === 'true';

        mobileProfileButton.setAttribute('aria-expanded', String(!isOpen));
        mobileProfileMenu?.classList.toggle('hidden', isOpen);
        mobileProfileIcon?.classList.toggle('rotate-180', !isOpen);
    });

    mobileAcademicButton?.addEventListener('click', () => {
        const isOpen = mobileAcademicButton.getAttribute('aria-expanded') === 'true';

        mobileAcademicButton.setAttribute('aria-expanded', String(!isOpen));
        mobileAcademicMenu?.classList.toggle('hidden', isOpen);
        mobileAcademicIcon?.classList.toggle('rotate-180', !isOpen);
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            setMenuState(false);
        }
    });
}

const heroCarousel = document.querySelector('[data-hero-carousel]');

if (heroCarousel) {
    const slides = heroCarousel.querySelectorAll('[data-hero-slide]');
    const indicators = heroCarousel.querySelectorAll('[data-hero-indicator]');
    const previousButton = heroCarousel.querySelector('[data-hero-prev]');
    const nextButton = heroCarousel.querySelector('[data-hero-next]');
    let currentSlide = 0;
    let autoplayId = null;

    const showHeroSlide = (index) => {
        currentSlide = (index + slides.length) % slides.length;

        slides.forEach((slide, slideIndex) => {
            const isActive = slideIndex === currentSlide;

            slide.classList.toggle('opacity-100', isActive);
            slide.classList.toggle('opacity-0', !isActive);
            slide.classList.toggle('pointer-events-none', !isActive);
        });

        indicators.forEach((indicator, indicatorIndex) => {
            const isActive = indicatorIndex === currentSlide;

            indicator.classList.toggle('w-6', isActive);
            indicator.classList.toggle('w-2', !isActive);
            indicator.classList.toggle('bg-white', isActive);
            indicator.classList.toggle('bg-white/45', !isActive);
            indicator.setAttribute('aria-current', String(isActive));
        });
    };

    const stopHeroAutoplay = () => {
        if (autoplayId) {
            window.clearInterval(autoplayId);
            autoplayId = null;
        }
    };

    const startHeroAutoplay = () => {
        if (slides.length < 2 || autoplayId) {
            return;
        }

        autoplayId = window.setInterval(() => showHeroSlide(currentSlide + 1), 4800);
    };

    previousButton?.addEventListener('click', () => {
        showHeroSlide(currentSlide - 1);
        stopHeroAutoplay();
        startHeroAutoplay();
    });

    nextButton?.addEventListener('click', () => {
        showHeroSlide(currentSlide + 1);
        stopHeroAutoplay();
        startHeroAutoplay();
    });

    indicators.forEach((indicator) => {
        indicator.addEventListener('click', () => {
            showHeroSlide(Number(indicator.dataset.heroIndicator || 0));
            stopHeroAutoplay();
            startHeroAutoplay();
        });
    });

    heroCarousel.addEventListener('mouseenter', stopHeroAutoplay);
    heroCarousel.addEventListener('mouseleave', startHeroAutoplay);
    heroCarousel.addEventListener('focusin', stopHeroAutoplay);
    heroCarousel.addEventListener('focusout', startHeroAutoplay);

    showHeroSlide(0);
    startHeroAutoplay();
}

const galleryModal = document.querySelector('[data-gallery-modal]');
const galleryModalImage = document.querySelector('[data-gallery-modal-image]');
const galleryModalTitle = document.querySelector('[data-gallery-modal-title]');
const galleryModalDate = document.querySelector('[data-gallery-modal-date]');
const galleryModalDescription = document.querySelector('[data-gallery-modal-description]');
const galleryPreviousButton = document.querySelector('[data-gallery-prev]');
const galleryNextButton = document.querySelector('[data-gallery-next]');
const galleryCounter = document.querySelector('[data-gallery-counter]');
const galleryCloseButton = galleryModal?.querySelector('[data-gallery-close]:not(.absolute.inset-0)');
const galleryTriggers = [...document.querySelectorAll('[data-gallery-view]')];

if (galleryModal && galleryModalImage && galleryModalTitle && galleryModalDescription && galleryTriggers.length) {
    let activeGalleryItems = galleryTriggers;
    let activeGalleryIndex = 0;
    let lastGalleryTrigger = null;
    let touchStartX = null;

    const setGalleryNavigationVisibility = () => {
        const hasMultipleItems = activeGalleryItems.length > 1;

        galleryPreviousButton?.classList.toggle('hidden', !hasMultipleItems);
        galleryNextButton?.classList.toggle('hidden', !hasMultipleItems);
        galleryCounter?.classList.toggle('hidden', !hasMultipleItems);
    };

    const showGalleryItem = (index) => {
        if (!activeGalleryItems.length) {
            return;
        }

        activeGalleryIndex = (index + activeGalleryItems.length) % activeGalleryItems.length;
        const trigger = activeGalleryItems[activeGalleryIndex];

        galleryModalImage.classList.add('opacity-60');
        galleryModalImage.src = trigger.dataset.gallerySrc || '';
        galleryModalImage.alt = trigger.dataset.galleryAlt || trigger.dataset.galleryTitle || 'Foto galeri';
        galleryModalTitle.textContent = trigger.dataset.galleryTitle || 'Dokumentasi';

        if (galleryModalDate) {
            galleryModalDate.textContent = trigger.dataset.galleryDate || '';
            galleryModalDate.classList.toggle('hidden', !trigger.dataset.galleryDate);
        }

        galleryModalDescription.textContent = trigger.dataset.galleryDescription || '';

        if (galleryCounter) {
            galleryCounter.textContent = `${activeGalleryIndex + 1} / ${activeGalleryItems.length}`;
        }

        const nextItem = activeGalleryItems[(activeGalleryIndex + 1) % activeGalleryItems.length];
        if (nextItem && nextItem !== trigger) {
            const preloadImage = new Image();
            preloadImage.src = nextItem.dataset.gallerySrc || '';
        }
    };

    const closeGalleryModal = () => {
        galleryModal.classList.add('hidden');
        galleryModal.classList.remove('flex');
        galleryModal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('overflow-hidden');
        lastGalleryTrigger?.focus();
    };

    galleryTriggers.forEach((trigger) => {
        trigger.addEventListener('click', () => {
            const galleryGroup = trigger.dataset.galleryGroup;

            activeGalleryItems = galleryGroup
                ? galleryTriggers.filter((item) => item.dataset.galleryGroup === galleryGroup)
                : galleryTriggers;
            activeGalleryIndex = activeGalleryItems.indexOf(trigger);
            lastGalleryTrigger = trigger;

            galleryModal.classList.remove('hidden');
            galleryModal.classList.add('flex');
            galleryModal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('overflow-hidden');
            setGalleryNavigationVisibility();
            showGalleryItem(activeGalleryIndex);

            if (activeGalleryItems.length > 1) {
                galleryNextButton?.focus();
            } else {
                galleryCloseButton?.focus();
            }
        });
    });

    galleryModalImage.addEventListener('load', () => {
        galleryModalImage.classList.remove('opacity-60');
    });

    galleryPreviousButton?.addEventListener('click', () => showGalleryItem(activeGalleryIndex - 1));
    galleryNextButton?.addEventListener('click', () => showGalleryItem(activeGalleryIndex + 1));

    galleryModal.addEventListener('touchstart', (event) => {
        touchStartX = event.changedTouches[0]?.clientX ?? null;
    }, { passive: true });

    galleryModal.addEventListener('touchend', (event) => {
        if (touchStartX === null || activeGalleryItems.length < 2) {
            return;
        }

        const touchEndX = event.changedTouches[0]?.clientX ?? touchStartX;
        const swipeDistance = touchEndX - touchStartX;
        touchStartX = null;

        if (Math.abs(swipeDistance) < 45) {
            return;
        }

        showGalleryItem(activeGalleryIndex + (swipeDistance < 0 ? 1 : -1));
    }, { passive: true });

    galleryModal.querySelectorAll('[data-gallery-close]').forEach((button) => {
        button.addEventListener('click', closeGalleryModal);
    });

    document.addEventListener('keydown', (event) => {
        if (galleryModal.classList.contains('hidden')) {
            return;
        }

        if (event.key === 'Escape') {
            closeGalleryModal();
        } else if (event.key === 'ArrowLeft') {
            showGalleryItem(activeGalleryIndex - 1);
        } else if (event.key === 'ArrowRight') {
            showGalleryItem(activeGalleryIndex + 1);
        }
    });
}
