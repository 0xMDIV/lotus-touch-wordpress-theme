/**
 * Lotus Touch Theme - Main JavaScript
 *
 * Features:
 *  - GSAP animations (fade-up, fade-in, scale-in)
 *  - ScrollTrigger for on-scroll animations
 *  - Header scroll effect, mobile menu
 *  - Massage detail modal (with multiple prices)
 *  - Contact form AJAX
 *  - Search page AJAX (separate /suche/ page)
 *  - Smooth scroll for anchor links
 *
 * @package LotusTouch
 * @version 2.0.0
 */

(function () {
    'use strict';

    // Mark JS as enabled
    document.documentElement.classList.remove('no-js');
    document.documentElement.classList.add('js');

    // Safety net: ensure all animated elements are visible after 2s
    setTimeout(function () {
        document.querySelectorAll('.fade-up, .fade-in, .scale-in').forEach(function (el) {
            if (parseFloat(getComputedStyle(el).opacity) < 0.05) {
                el.style.opacity = '1';
                el.style.transform = 'none';
            }
        });
    }, 2000);

    const hasGsap = typeof gsap !== 'undefined';
    if (hasGsap && typeof ScrollTrigger !== 'undefined') {
        gsap.registerPlugin(ScrollTrigger);
    }
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // ============================================
    // 1. HEADER SCROLL EFFECT
    // ============================================
    const header = document.getElementById('siteHeader');
    if (header) {
        const onScroll = () => {
            if (window.scrollY > 50) header.classList.add('scrolled');
            else header.classList.remove('scrolled');
        };
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }

    // ============================================
    // 2. MOBILE MENU TOGGLE
    // ============================================
    const menuToggle = document.getElementById('menuToggle');
    const primaryNav = document.getElementById('primaryNav');
    if (menuToggle && primaryNav) {
        menuToggle.addEventListener('click', () => {
            const isOpen = primaryNav.classList.toggle('toggled');
            menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            const icon = menuToggle.querySelector('span');
            if (icon) icon.textContent = isOpen ? '✕' : '☰';
        });
        primaryNav.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                primaryNav.classList.remove('toggled');
                menuToggle.setAttribute('aria-expanded', 'false');
                const icon = menuToggle.querySelector('span');
                if (icon) icon.textContent = '☰';
            });
        });
    }

    // ============================================
    // 3. GSAP ANIMATIONS
    // ============================================
    if (hasGsap && !prefersReducedMotion) {
        const heroTl = gsap.timeline({ defaults: { ease: 'power3.out' } });
        heroTl
            .from('.hero-logo', { opacity: 0, y: 30, duration: 0.8, delay: 0.2 })
            .from('.hero-tagline', { opacity: 0, y: 20, duration: 0.7 }, '-=0.5')
            .from('.hero-title', { opacity: 0, y: 40, duration: 1 }, '-=0.4')
            .from('.hero-description', { opacity: 0, y: 20, duration: 0.7 }, '-=0.5')
            .from('.hero-cta .btn', { opacity: 0, y: 20, duration: 0.6, stagger: 0.15 }, '-=0.4')
            .from('.scroll-indicator', { opacity: 0, y: -10, duration: 0.6 }, '-=0.3');

        if (typeof ScrollTrigger !== 'undefined') {
            gsap.utils.toArray('.fade-up:not(.hero-title):not(.hero-description):not(.hero-cta)').forEach((el) => {
                gsap.fromTo(el,
                    { opacity: 0, y: 40 },
                    { opacity: 1, y: 0, duration: 0.9, ease: 'power3.out',
                        scrollTrigger: { trigger: el, start: 'top 92%', toggleActions: 'play none none none' } }
                );
            });
            gsap.utils.toArray('.fade-in').forEach((el) => {
                gsap.fromTo(el,
                    { opacity: 0 },
                    { opacity: 1, duration: 1, ease: 'power2.out',
                        scrollTrigger: { trigger: el, start: 'top 92%' } }
                );
            });
            gsap.utils.toArray('.scale-in').forEach((el) => {
                gsap.fromTo(el,
                    { opacity: 0, scale: 0.92 },
                    { opacity: 1, scale: 1, duration: 1, ease: 'power3.out',
                        scrollTrigger: { trigger: el, start: 'top 90%' } }
                );
            });
            gsap.utils.toArray('.services-grid').forEach((grid) => {
                const cards = grid.querySelectorAll('.service-card');
                gsap.fromTo(cards,
                    { opacity: 0, y: 50 },
                    { opacity: 1, y: 0, duration: 0.8, stagger: 0.1, ease: 'power3.out',
                        scrollTrigger: { trigger: grid, start: 'top 90%' } }
                );
            });
            gsap.utils.toArray('.section-eyebrow').forEach((el) => {
                gsap.fromTo(el,
                    { opacity: 0, y: 20 },
                    { opacity: 1, y: 0, duration: 0.7, ease: 'back.out(1.5)',
                        scrollTrigger: { trigger: el, start: 'top 95%' } }
                );
            });
            gsap.utils.toArray('.section-title').forEach((el) => {
                gsap.fromTo(el,
                    { opacity: 0, y: 30 },
                    { opacity: 1, y: 0, duration: 0.9, ease: 'power3.out',
                        scrollTrigger: { trigger: el, start: 'top 92%' } }
                );
            });
            ScrollTrigger.refresh();
        }

        const hero = document.querySelector('.hero');
        if (hero) {
            hero.addEventListener('mousemove', (e) => {
                const rect = hero.getBoundingClientRect();
                const x = (e.clientX - rect.left) / rect.width - 0.5;
                const y = (e.clientY - rect.top) / rect.height - 0.5;
                gsap.to(hero, {
                    duration: 1.5,
                    backgroundPositionX: x * 30 + 'px',
                    backgroundPositionY: y * 30 + 'px',
                    ease: 'power2.out',
                });
            });
        }
    } else {
        document.querySelectorAll('.fade-up, .fade-in, .scale-in').forEach((el) => {
            el.style.opacity = '1';
            el.style.transform = 'none';
        });
    }

    // ============================================
    // 4. HEADER SEARCH → /suche/?s=...
    // ============================================
    const headerSearch = document.getElementById('lotusSiteSearch');
    if (headerSearch) {
        // The header search form is in header.php; we just need to handle Enter
        const headerForm = headerSearch.closest('form');
        if (headerForm) {
            headerForm.addEventListener('submit', function (e) {
                e.preventDefault();
                const q = headerSearch.value.trim();
                if (q.length >= 2) {
                    window.location.href = (window.lotusTouch && window.lotusTouch.searchUrl || '/suche/') + '?q=' + encodeURIComponent(q);
                }
            });
        }
    }

    // ============================================
    // 5. SEARCH PAGE (AJAX) – only on search.php
    // ============================================
    const searchPageForm = document.getElementById('lotusSearchForm');
    const searchPageInput = document.getElementById('lotusSearchInput');
    const searchResults = document.getElementById('searchResults');
    if (searchPageForm && searchResults) {
        const ajaxUrl = (window.lotusTouchSearch && window.lotusTouchSearch.ajaxUrl) || '/wp-admin/admin-ajax.php';

        const renderResults = (data) => {
            if (!data.success) {
                searchResults.innerHTML = '<p class="search-empty">' + (data.data && data.data.message || 'Fehler') + '</p>';
                return;
            }
            const { query, count, results } = data.data;
            if (count === 0) {
                searchResults.innerHTML = `
                    <div class="search-empty">
                        <h2>Keine Treffer für „${escapeHtml(query)}"</h2>
                        <p>Versuche es mit einem anderen Suchbegriff oder stöbere unten in unseren Kategorien.</p>
                    </div>`;
                return;
            }
            let html = `<div class="search-summary">
                <h2>${count} Treffer für „${escapeHtml(query)}"</h2>
                <p>Klicke auf einen Treffer, um direkt zur passenden Stelle zu springen.</p>
            </div><ul class="search-result-list">`;

            results.forEach(r => {
                const typeLabel = r.type === 'massage' ? '💆 Massage' : (r.type === 'page' ? '📄 Seite' : '📍 Sektion');
                const thumb = r.thumb ? `<img src="${r.thumb}" alt="" class="search-result-thumb" loading="lazy" />` : '';
                const meta = r.meta ? `<span class="search-result-meta">${escapeHtml(r.meta)}</span>` : '';
                html += `
                    <li class="search-result-item">
                        <a href="${r.url}" class="search-result-link">
                            ${thumb}
                            <div class="search-result-content">
                                <span class="search-result-type">${typeLabel}</span>
                                <h3>${escapeHtml(r.title)}</h3>
                                ${meta}
                                ${r.excerpt ? `<p>${escapeHtml(r.excerpt)}</p>` : ''}
                                <span class="search-result-arrow">Öffnen →</span>
                            </div>
                        </a>
                    </li>`;
            });
            html += '</ul>';
            searchResults.innerHTML = html;
        };

        const escapeHtml = (s) => String(s).replace(/[&<>"']/g, c => ({ '&': '&', '<': '<', '>': '>', '"': '"', "'": '&#39;' }[c]));

        const runSearch = (q) => {
            if (q.length < 2) {
                searchResults.innerHTML = '<p class="search-hint">' +
                    'Tipp: Gib mindestens 2 Zeichen ein. Die Suche zeigt alle passenden Behandlungen, Seiten und Sektionen mit Direktlinks.' +
                    '</p>';
                return;
            }
            searchResults.innerHTML = '<p class="search-loading">🔍 Suche läuft…</p>';
            const url = ajaxUrl + '?action=lotus_search&q=' + encodeURIComponent(q);
            fetch(url).then(r => r.json()).then(renderResults).catch(() => {
                searchResults.innerHTML = '<p class="search-empty">Netzwerkfehler – bitte erneut versuchen.</p>';
            });
        };

        // Form submit (button click)
        searchPageForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const q = searchPageInput.value.trim();
            if (q) {
                // Update URL without reload
                const newUrl = window.location.pathname + '?q=' + encodeURIComponent(q);
                history.replaceState(null, '', newUrl);
                runSearch(q);
            }
        });

        // Initial load: if ?q= present, run
        const initialQ = new URLSearchParams(window.location.search).get('q') || new URLSearchParams(window.location.search).get('s');
        if (initialQ) {
            searchPageInput.value = initialQ;
            runSearch(initialQ);
        }
    }

    // ============================================
    // 6. CONTACT FORM (AJAX)
    // ============================================
    const contactForm = document.getElementById('lotusContactForm');
    const formStatus = document.getElementById('formStatus');

    if (contactForm && formStatus) {
        contactForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const submitBtn = contactForm.querySelector('button[type="submit"]');
            const btnText = submitBtn ? submitBtn.querySelector('.btn-text') : null;
            const originalText = btnText ? btnText.textContent : '';
            if (btnText) btnText.textContent = '...';
            if (submitBtn) submitBtn.disabled = true;

            const formData = new FormData(contactForm);
            const data = {
                action: 'lotus_contact',
                nonce: (window.lotusTouchContact && window.lotusTouchContact.nonce) || '',
                name: formData.get('name'),
                email: formData.get('email'),
                phone: formData.get('phone'),
                subject: formData.get('subject'),
                message: formData.get('message'),
            };

            try {
                const ajaxUrl = (window.lotusTouchContact && window.lotusTouchContact.ajaxUrl) || '/wp-admin/admin-ajax.php';
                const response = await fetch(ajaxUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams(data),
                });
                const result = await response.json();

                formStatus.style.display = 'block';
                formStatus.style.padding = '0.875rem 1rem';
                formStatus.style.borderRadius = '8px';

                if (result.success) {
                    formStatus.style.color = '#2f7a3e';
                    formStatus.style.background = 'rgba(46, 125, 50, 0.08)';
                    formStatus.textContent = result.data.message;
                    contactForm.reset();
                } else {
                    formStatus.style.color = '#c62828';
                    formStatus.style.background = 'rgba(198, 40, 40, 0.08)';
                    formStatus.textContent = result.data.message;
                }
            } catch (err) {
                formStatus.style.display = 'block';
                formStatus.style.color = '#c62828';
                formStatus.style.background = 'rgba(198, 40, 40, 0.08)';
                formStatus.style.padding = '0.875rem 1rem';
                formStatus.style.borderRadius = '8px';
                formStatus.textContent = 'Netzwerkfehler – bitte erneut versuchen.';
            } finally {
                if (btnText) btnText.textContent = originalText;
                if (submitBtn) submitBtn.disabled = false;
            }
        });
    }

    // ============================================
    // 7. SMOOTH SCROLL for anchor links
    // ============================================
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href === '#' || href === '#!') return;
            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                const headerHeight = header ? header.offsetHeight : 0;
                const top = target.getBoundingClientRect().top + window.scrollY - headerHeight - 20;
                window.scrollTo({ top, behavior: 'smooth' });
            }
        });
    });

    // ============================================
    // 8. LAZY-LOAD FALLBACK
    // ============================================
    if ('IntersectionObserver' in window) {
        document.querySelectorAll('img[loading="lazy"]').forEach(img => {
            if (img.complete) return;
            img.style.opacity = '0';
            img.style.transition = 'opacity 0.5s ease';
            img.addEventListener('load', () => { img.style.opacity = '1'; });
            img.addEventListener('error', () => { img.style.opacity = '1'; });
        });
    }

    // ============================================
    // 9. MASSAGE MODAL (multiple prices, content)
    // ============================================
    const modal = document.getElementById('massageModal');
    if (modal) {
        const modalEyebrow  = modal.querySelector('.massage-modal-eyebrow');
        const modalTitle    = modal.querySelector('.massage-modal-title');
        const modalMedia    = modal.querySelector('.massage-modal-media');
        const modalMeta     = modal.querySelector('.massage-modal-meta');
        const modalExcerpt  = modal.querySelector('.massage-modal-excerpt');
        const modalContent  = modal.querySelector('.massage-modal-content');
        const closeButtons  = modal.querySelectorAll('[data-modal-close]');
        let lastFocused = null;

        const setScrollbarWidth = () => {
            const w = window.innerWidth - document.documentElement.clientWidth;
            document.body.style.setProperty('--scrollbar-width', w + 'px');
        };

        const openModal = (data) => {
            // Eyebrow
            modalEyebrow.textContent = data.featured === '1' ? '✦ Beliebte Behandlung' : 'Behandlung';

            // Title
            modalTitle.textContent = data.title || '';

            // Image
            if (data.image) {
                modalMedia.innerHTML = '';
                const img = document.createElement('img');
                img.src = data.image;
                img.alt = data.title || '';
                modalMedia.appendChild(img);
            } else {
                modalMedia.innerHTML =
                    '<svg class="massage-modal-media-placeholder" width="100" height="100" viewBox="0 0 100 100" fill="currentColor">' +
                    '<path d="M50 10 C 30 30, 20 50, 50 90 C 80 50, 70 30, 50 10 Z" />' +
                    '<path d="M50 30 C 40 45, 35 55, 50 75 C 65 55, 60 45, 50 30 Z" fill="var(--color-accent)" />' +
                    '</svg>';
            }

            // Prices (multiple)
            let prices = [];
            try {
                if (data.prices) {
                    prices = typeof data.prices === 'string' ? JSON.parse(data.prices) : data.prices;
                }
            } catch (e) {
                prices = [];
            }
            if (prices && prices.length) {
                const i18n = (window.lotusTouch && window.lotusTouch.i18n) || {};
                const labelRegular = i18n.regular || 'Regulär';
                const labelReduced = i18n.reduced || 'Ermäßigt';

                // Helper to build a single price cell
                const buildCell = (p, isReduced) => {
                    const cls = 'massage-modal-price-cell price-type-' + (isReduced ? 'reduced' : 'regular');
                    let html = '<div class="' + cls + '">';
                    if (isReduced) {
                        html += '<div class="cell-label">💚 ' + escapeHTML(labelReduced) + '</div>';
                    } else {
                        html += '<div class="cell-label">🏷️ ' + escapeHTML(labelRegular) + '</div>';
                    }
                    html += '<div class="cell-amount-wrap">';
                    if (isReduced && p.original) {
                        html += '<span class="cell-original">' + escapeHTML(p.original) + ' ' + escapeHTML(p.unit || '€') + '</span>';
                    }
                    if (p.price) {
                        html += '<span class="cell-amount">' + escapeHTML(p.price) + ' ' + escapeHTML(p.unit || '€') + '</span>';
                    }
                    html += '</div></div>';
                    return html;
                };

                // Group by duration: pair regular + reduced when same duration exists
                const validPrices = prices.filter(p => p.price || p.duration);
                const durationMap = {};
                validPrices.forEach(p => {
                    const d = (p.duration || '').trim() || '–';
                    if (!durationMap[d]) durationMap[d] = { regular: null, reduced: null };
                    const type = p.type || 'regular';
                    if (type === 'reduced' && p.price) durationMap[d].reduced = p;
                    else if (p.price) durationMap[d].regular = p;
                });

                let priceHTML = '<div class="massage-modal-prices">';
                priceHTML += '<div class="massage-modal-prices-table">';

                // Header row
                const hasAnyReduced = validPrices.some(p => p.type === 'reduced');
                if (hasAnyReduced) {
                    priceHTML += '<div class="massage-modal-price-row massage-modal-price-row-header">';
                    priceHTML += '<div class="massage-modal-price-duration-head"></div>';
                    priceHTML += '<div class="massage-modal-price-cells-head">';
                    priceHTML += '<div class="cell-head">🏷️ ' + escapeHTML(labelRegular) + '</div>';
                    priceHTML += '<div class="cell-head cell-head-reduced">💚 ' + escapeHTML(labelReduced) + '</div>';
                    priceHTML += '</div></div>';
                }

                // Data rows
                Object.keys(durationMap).forEach(duration => {
                    const pair = durationMap[duration];
                    const hasBoth = pair.regular && pair.reduced;
                    priceHTML += '<div class="massage-modal-price-row' + (hasBoth ? ' has-pair' : '') + '">';
                    priceHTML += '<div class="massage-modal-price-duration">' + escapeHTML(duration) + '</div>';
                    priceHTML += '<div class="massage-modal-price-cells">';
                    if (hasBoth) {
                        // Both side-by-side
                        priceHTML += buildCell(pair.regular, false);
                        priceHTML += buildCell(pair.reduced, true);
                    } else if (pair.regular) {
                        // Only regular - take full width
                        priceHTML += '<div class="massage-modal-price-cell-single">' + buildCell(pair.regular, false) + '</div>';
                    } else if (pair.reduced) {
                        // Only reduced - take full width
                        priceHTML += '<div class="massage-modal-price-cell-single">' + buildCell(pair.reduced, true) + '</div>';
                    }
                    priceHTML += '</div></div>';
                });

                priceHTML += '</div></div>';
                modalMeta.innerHTML = priceHTML;
            } else {
                modalMeta.innerHTML = '';
            }

            // Excerpt
            if (data.excerpt) {
                modalExcerpt.textContent = data.excerpt;
                modalExcerpt.style.display = '';
            } else {
                modalExcerpt.style.display = 'none';
            }

            // Long content (HTML)
            if (data.contentHtml && data.contentHtml.trim()) {
                modalContent.innerHTML = data.contentHtml;
                modalContent.style.display = '';
            } else {
                modalContent.style.display = 'none';
            }

            // Show
            lastFocused = document.activeElement;
            setScrollbarWidth();
            modal.hidden = false;
            document.body.classList.add('modal-open');

            requestAnimationFrame(() => {
                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');

                if (hasGsap && !prefersReducedMotion) {
                    const children = [
                        modal.querySelector('.massage-modal-title'),
                        modal.querySelector('.massage-modal-meta'),
                        modal.querySelector('.massage-modal-excerpt'),
                        modal.querySelector('.massage-modal-content'),
                        modal.querySelector('.massage-modal-actions'),
                    ].filter(Boolean);
                    gsap.fromTo(children,
                        { opacity: 0, y: 20 },
                        { opacity: 1, y: 0, duration: 0.6, stagger: 0.08, ease: 'power3.out', delay: 0.2 }
                    );
                }
                setTimeout(() => {
                    const firstClose = modal.querySelector('.massage-modal-close');
                    if (firstClose) firstClose.focus();
                }, 100);
            });
        };

        const closeModal = () => {
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
            setTimeout(() => {
                modal.hidden = true;
                document.body.classList.remove('modal-open');
                if (lastFocused && typeof lastFocused.focus === 'function') {
                    lastFocused.focus();
                }
            }, 400);
        };

        const escapeHTML = (s) => String(s == null ? '' : s).replace(/[&<>"']/g, c => ({ '&': '&', '<': '<', '>': '>', '"': '"', "'": '&#39;' }[c]));

        document.querySelectorAll('[data-massage-modal]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                openModal(btn.dataset);
            });
        });

        closeButtons.forEach(b => b.addEventListener('click', closeModal));

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.classList.contains('is-open')) {
                closeModal();
            }
        });

        modal.addEventListener('keydown', (e) => {
            if (e.key !== 'Tab' || !modal.classList.contains('is-open')) return;
            const focusables = modal.querySelectorAll(
                'button, a[href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
            );
            const visible = Array.from(focusables).filter(el =>
                !el.disabled && el.offsetParent !== null
            );
            if (visible.length === 0) return;
            const first = visible[0];
            const last = visible[visible.length - 1];
            if (e.shiftKey && document.activeElement === first) {
                e.preventDefault();
                last.focus();
            } else if (!e.shiftKey && document.activeElement === last) {
                e.preventDefault();
                first.focus();
            }
        });
    }

})();