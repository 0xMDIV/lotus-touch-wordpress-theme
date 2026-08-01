/**
 * Lotus Touch Admin Meta JS
 * Handles:
 *  - Repeatable price rows in the Massage meta box
 *  - Toggle "original" price field when type changes
 *  - Live preview of how the prices will look
 */
(function () {
    'use strict';

    function ready(fn) {
        if (document.readyState !== 'loading') fn();
        else document.addEventListener('DOMContentLoaded', fn);
    }

    ready(function () {
        const list = document.getElementById('lotus-prices-list');
        const tmpl = document.getElementById('lotus-price-template');
        const addBtn = document.getElementById('lotus-price-add');
        if (!list || !tmpl || !addBtn) return;

        let index = list.querySelectorAll('.lotus-price-row').length;

        function refreshIndices() {
            const rows = list.querySelectorAll('.lotus-price-row');
            rows.forEach((row, i) => {
                row.querySelectorAll('input, select').forEach(el => {
                    if (el.name) {
                        el.name = el.name.replace(/lotus_massage_prices\[\d+\]/, 'lotus_massage_prices[' + i + ']');
                    }
                });
            });
            index = rows.length;
        }

        function toggleOriginalField(row) {
            const typeSel = row.querySelector('.lotus-price-type');
            const origInput = row.querySelector('.lotus-price-original');
            if (!typeSel || !origInput) return;
            if (typeSel.value === 'reduced') {
                origInput.style.display = '';
                origInput.removeAttribute('disabled');
            } else {
                origInput.style.display = 'none';
                origInput.value = '';
            }
        }

        // Apply toggle to existing rows (page load)
        list.querySelectorAll('.lotus-price-row').forEach(row => toggleOriginalField(row));

        // Delegate type change
        list.addEventListener('change', function (e) {
            if (e.target.classList.contains('lotus-price-type')) {
                const row = e.target.closest('.lotus-price-row');
                if (row) {
                    toggleOriginalField(row);
                    updatePreview();
                }
            }
            // Any change in price fields updates preview
            if (e.target.matches('input[type="text"]')) {
                updatePreview();
            }
        });
        // Real-time text input updates
        list.addEventListener('input', function (e) {
            if (e.target.matches('input[type="text"]')) {
                updatePreview();
            }
        });

        function addRow() {
            const clone = tmpl.content.cloneNode(true);
            const row = clone.querySelector('.lotus-price-row');
            row.querySelectorAll('input, select').forEach(el => {
                el.name = el.name.replace('__INDEX__', index);
            });
            list.appendChild(clone);
            toggleOriginalField(row);
            index++;
            updatePreview();
        }

        function removeRow(btn) {
            const row = btn.closest('.lotus-price-row');
            if (row) {
                row.remove();
                refreshIndices();
                updatePreview();
            }
        }

        // Add
        addBtn.addEventListener('click', addRow);

        // Remove (delegated)
        list.addEventListener('click', function (e) {
            if (e.target.classList.contains('lotus-price-remove')) {
                e.preventDefault();
                removeRow(e.target);
            }
        });

        // ===== LIVE PREVIEW =====
        function collectPrices() {
            const prices = [];
            list.querySelectorAll('.lotus-price-row').forEach(row => {
                const type = row.querySelector('.lotus-price-type').value;
                const duration = row.querySelector('input[name*="[duration]"]').value || '';
                const price = row.querySelector('input[name*="[price]"]').value || '';
                const unit = row.querySelector('select[name*="[unit]"]').value || '€';
                const originalInput = row.querySelector('input[name*="[original]"]');
                const original = originalInput && originalInput.offsetParent !== null ? originalInput.value : '';
                if (duration || price) {
                    prices.push({ type, duration, price, unit, original });
                }
            });
            return prices;
        }

        function ensurePreview() {
            let preview = document.getElementById('lotus-price-preview');
            if (!preview) {
                preview = document.createElement('div');
                preview.id = 'lotus-price-preview';
                preview.className = 'lotus-price-preview';
                preview.innerHTML = '<h4 class="lotus-price-preview-title">👁️ Vorschau <span class="lotus-price-preview-hint">(aktualisiert sich live)</span></h4><div class="lotus-price-preview-body"></div>';
                // Insert after the description (before add button)
                const desc = list.parentElement.querySelector('.description:last-of-type');
                if (desc) {
                    desc.insertAdjacentElement('afterend', preview);
                } else {
                    addBtn.parentElement.insertAdjacentElement('beforebegin', preview);
                }
            }
            return preview;
        }

        function updatePreview() {
            const preview = ensurePreview();
            const body = preview.querySelector('.lotus-price-preview-body');
            const prices = collectPrices();

            if (!prices.length) {
                body.innerHTML = '<p class="lotus-preview-empty">Noch keine Preise eingetragen – füge welche oben hinzu, um die Vorschau zu sehen.</p>';
                return;
            }

            const regular = prices.filter(p => p.type === 'regular');
            const reduced = prices.filter(p => p.type === 'reduced');

            let html = '';

            if (regular.length) {
                html += '<div class="lotus-preview-group"><div class="lotus-preview-group-label">🏷️ Regulär</div>';
                regular.forEach(p => {
                    const hasOriginal = false;
                    html += '<div class="lotus-preview-item">';
                    html += '<span class="lotus-preview-duration">' + escape(p.duration || '—') + '</span>';
                    if (p.price) {
                        html += '<span class="lotus-preview-amount">' + escape(p.price + ' ' + p.unit) + '</span>';
                    }
                    html += '</div>';
                });
                html += '</div>';
            }

            if (reduced.length) {
                html += '<div class="lotus-preview-group lotus-preview-group-reduced"><div class="lotus-preview-group-label">💚 Ermäßigt</div>';
                reduced.forEach(p => {
                    html += '<div class="lotus-preview-item lotus-preview-item-reduced">';
                    html += '<span class="lotus-preview-duration">' + escape(p.duration || '—') + '</span>';
                    if (p.original) {
                        html += '<span class="lotus-preview-original">' + escape(p.original + ' ' + p.unit) + '</span>';
                    }
                    if (p.price) {
                        html += '<span class="lotus-preview-amount">' + escape(p.price + ' ' + p.unit) + '</span>';
                    }
                    html += '</div>';
                });
                html += '</div>';
            }

            if (!regular.length && !reduced.length) {
                html = '<p class="lotus-preview-empty">Bitte Dauer oder Preis eintragen.</p>';
            }

            body.innerHTML = html;
        }

        function escape(s) {
            return String(s == null ? '' : s)
                .replace(/&/g, '&')
                .replace(/</g, '<')
                .replace(/>/g, '>')
                .replace(/"/g, '"');
        }

        // Initial preview render
        updatePreview();
    });
})();