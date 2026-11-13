<script>
(function () {
    if (window.__supplierChoicesSyncInstalled) return;
    window.__supplierChoicesSyncInstalled = true;

    var OrigChoices = window.Choices;
    if (!OrigChoices) {
        console.warn('Choices.js tidak ditemukan, sinkronisasi produsen/distributor tidak aktif.');
        return;
    }

    var SELECTOR = 'select.produsen-master-select, select.distributor-master-select';
    var registry = new WeakMap();
    var labelMaps = new WeakMap();
    var locked = false;
    var observer = null;

    function isSupplierSelect(el) {
        return !!(el && el.matches && el.matches(SELECTOR));
    }

    function WrappedChoices(el, cfg) {
        var labels = null;
        if (el instanceof HTMLSelectElement && isSupplierSelect(el)) {
            labels = {};
            Array.from(el.options).forEach(function (option) { labels[option.value] = option.text; });
        }
        var instance = new OrigChoices(el, cfg);
        if (labels) {
            labelMaps.set(el, labels);
            registry.set(el, instance);
            setTimeout(function () { initialSync(el); }, 0);
        }
        return instance;
    }
    WrappedChoices.prototype = OrigChoices.prototype;
    Object.getOwnPropertyNames(OrigChoices).forEach(function (key) {
        if (key === 'length' || key === 'name' || key === 'prototype') return;
        try { Object.defineProperty(WrappedChoices, key, Object.getOwnPropertyDescriptor(OrigChoices, key)); } catch (error) {}
    });
    window.Choices = WrappedChoices;

    function toArray(value) {
        if (Array.isArray(value)) return value.map(function (item) { return String(item).trim(); }).filter(Boolean);
        if (value === null || value === undefined || value === '') return [];
        return [String(value).trim()];
    }

    function typeOf(select) {
        return select.classList.contains('produsen-master-select') ? 'produsen' : 'distributor';
    }

    function labelOf(select, value) {
        var labels = labelMaps.get(select);
        if (labels && labels[value] !== undefined) return labels[value];
        var option = Array.from(select.options).find(function (item) { return String(item.value).trim() === value; });
        return option ? option.text : value;
    }

    function selectValues(select) {
        return Array.from(select.selectedOptions).map(function (option) { return String(option.value).trim(); }).filter(Boolean);
    }

    function choicesValues(select) {
        var instance = registry.get(select);
        return instance ? toArray(instance.getValue(true)) : selectValues(select);
    }

    function badgeValues(row, type) {
        return Array.from(row.querySelectorAll('.' + type + '-badges .badge-removable'))
            .map(function (badge) { return String(badge.dataset.id || badge.dataset.value || '').trim(); })
            .filter(Boolean);
    }

    function sameList(a, b) {
        if (a.length !== b.length) return false;
        var set = new Set(a);
        return b.every(function (value) { return set.has(value); });
    }

    function hasStray(row, type) {
        var container = row.querySelector('.' + type + '-badges');
        if (!container) return false;
        return container.children.length === 0 || !!container.querySelector('.badge:not(.badge-removable)');
    }

    function render(row, type, values) {
        var select = row.querySelector('select.' + type + '-master-select');
        var badges = row.querySelector('.' + type + '-badges');
        if (!badges) return;

        var badgeClass = type === 'produsen' ? 'badge bg-light-primary text-primary' : 'badge bg-light-info text-info';
        badges.innerHTML = '';
        if (!values.length) {
            badges.innerHTML = '<span class="text-muted small badge-empty">-</span>';
        } else {
            values.forEach(function (value) {
                var label = select ? labelOf(select, value) : value;
                var badge = document.createElement('span');
                badge.className = badgeClass + ' badge-removable';
                badge.dataset.value = label;
                badge.dataset.id = value;
                badge.appendChild(document.createTextNode(label + ' '));

                var button = document.createElement('button');
                button.type = 'button';
                button.className = 'badge-remove-btn';
                button.textContent = 'Hapus';
                button.setAttribute('onclick', "removeBadgeItem(this, '" + type + "')");
                badge.appendChild(button);
                badges.appendChild(badge);
            });
        }

        var wrap = row.querySelector('.' + type + '-hidden-inputs');
        if (wrap) {
            var rowIndex = row.dataset.rowIndex || row.getAttribute('data-row-index') || '0';
            wrap.innerHTML = '';
            values.forEach(function (value) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = type + '[' + rowIndex + '][]';
                input.value = value;
                wrap.appendChild(input);
            });
        }

        var idInput = row.querySelector('input.id-' + type + '-hidden');
        if (idInput) idInput.value = JSON.stringify(values);

        var plainInput = row.querySelector('input.' + type + '-hidden');
        if (plainInput) plainInput.value = JSON.stringify(values);

        if (observer) observer.takeRecords();
        if (typeof window.syncHeaderToDetails === 'function') window.syncHeaderToDetails(row);
    }

    function pushToChoices(select, values) {
        var instance = registry.get(select);
        if (!instance || sameList(toArray(instance.getValue(true)), values)) return;

        locked = true;
        try {
            instance.removeActiveItems();
            if (values.length) instance.setChoiceByValue(values);
        } finally {
            locked = false;
        }
    }

    function initialSync(select) {
        var row = select.closest('.unified-row');
        if (!row) return;
        var type = typeOf(select);
        var values = selectValues(select);
        if (values.length) {
            render(row, type, values);
        } else {
            var fromBadges = badgeValues(row, type);
            if (fromBadges.length) pushToChoices(select, fromBadges);
            else if (hasStray(row, type)) render(row, type, []);
        }
    }

    function reconcile(select) {
        var row = select.closest('.unified-row');
        if (!row) return;
        var type = typeOf(select);
        var values = selectValues(select);
        pushToChoices(select, values);
        if (!sameList(badgeValues(row, type), values) || hasStray(row, type)) render(row, type, values);
    }

    document.addEventListener('change', function (event) {
        var select = event.target;
        if (!isSupplierSelect(select)) return;
        event.stopImmediatePropagation();
        if (locked) return;
        var row = select.closest('.unified-row');
        if (row) render(row, typeOf(select), choicesValues(select));
    }, true);

    var pending = new Set();
    observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            var element = mutation.target.nodeType === 1 ? mutation.target : mutation.target.parentElement;
            var container = element && element.closest ? element.closest('.produsen-badges, .distributor-badges') : null;
            if (!container) return;
            var row = container.closest('.unified-row');
            if (!row) return;
            var type = container.classList.contains('produsen-badges') ? 'produsen' : 'distributor';
            var select = row.querySelector('select.' + type + '-master-select');
            if (select) pending.add(select);
        });
        pending.forEach(reconcile);
        pending.clear();
    });
    observer.observe(document.body, { childList: true, subtree: true });

    window.removeBadgeItem = function (button, type) {
        var badge = button.closest('.badge-removable');
        if (!badge) return;
        var row = badge.closest('.unified-row');
        var value = String(badge.dataset.id || badge.dataset.value || '').trim();
        badge.remove();
        if (!row) return;

        var select = row.querySelector('select.' + type + '-master-select');
        if (select) {
            var instance = registry.get(select);
            locked = true;
            try {
                if (instance) instance.removeActiveItemsByValue(value);
                Array.from(select.options).forEach(function (option) {
                    if (String(option.value).trim() === value) option.selected = false;
                });
            } finally {
                locked = false;
            }
        }
        render(row, type, badgeValues(row, type));
    };

    window.removeFinishGoodBadge = window.removeBadgeItem;
})();
</script>
