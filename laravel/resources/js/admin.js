import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import { createIcons, icons } from 'lucide';
import Sortable from 'sortablejs';
import TomSelect from 'tom-select';
import Toastify from 'toastify-js';
import 'toastify-js/src/toastify.css';
import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css';
import Swal from 'sweetalert2';
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';
import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';
import Link from '@tiptap/extension-link';
import Image from '@tiptap/extension-image';
import Placeholder from '@tiptap/extension-placeholder';
import TextAlign from '@tiptap/extension-text-align';
import Underline from '@tiptap/extension-underline';
import Cropper from 'cropperjs';
import ApexCharts from 'apexcharts';

window.Alpine = Alpine;
window.TomSelect = TomSelect;
window.Toastify = Toastify;
window.ApexCharts = ApexCharts;
window.refreshIcons = () => createIcons({ icons, attrs: { 'stroke-width': 1.5 } });

// ── SweetAlert2 themed confirm ───────────────────────────────────────────────
window.adminConfirm = (options = {}) => {
    return Swal.fire({
        title: options.title || 'Are you sure?',
        text: options.text || 'This action cannot be undone.',
        icon: options.icon || 'warning',
        showCancelButton: true,
        confirmButtonText: options.confirmText || 'Yes, delete',
        cancelButtonText: options.cancelText || 'Cancel',
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        customClass: {
            popup: 'rounded-2xl',
            confirmButton: 'rounded-xl text-sm font-medium px-5 py-2.5',
            cancelButton: 'rounded-xl text-sm font-medium px-5 py-2.5',
        },
        reverseButtons: true,
    });
};

// ── Tippy init ────────────────────────────────────────────────────────────────
window.refreshTippy = () => {
    document.querySelectorAll('[data-tippy-content]').forEach(el => {
        if (!el._tippy) {
            tippy(el, {
                theme: 'admin-tooltip',
                placement: 'top',
                arrow: true,
                delay: [100, 0],
                maxWidth: 300,
                interactive: false,
            });
        }
    });
};

// ── Toast notifications ───────────────────────────────────────────────────────
const TOAST_STYLES = {
    success: { bg: '#27452B', border: '#3A6B41', icon: '✓' },
    error:   { bg: '#7f1d1d', border: '#dc2626', icon: '✕' },
    warning: { bg: '#78350f', border: '#d97706', icon: '!' },
    info:    { bg: '#1e3a5f', border: '#3b82f6', icon: 'i' },
};

window.adminToast = (message, type = 'success', duration = 3000) => {
    const s = TOAST_STYLES[type] || TOAST_STYLES.success;
    Toastify({
        text: message,
        duration,
        close: true,
        gravity: 'top',
        position: 'right',
        stopOnFocus: true,
        offset: { x: 16, y: 16 },
        style: {
            background: s.bg,
            border: `1px solid ${s.border}`,
            borderRadius: '0.75rem',
            padding: '0.75rem 1.125rem',
            fontSize: '0.8125rem',
            fontFamily: 'inherit',
            fontWeight: '500',
            boxShadow: '0 8px 32px rgba(0,0,0,.22)',
            maxWidth: '380px',
            lineHeight: '1.5',
        },
    }).showToast();
};

window.insertVariableTokenIntoFocusedField = (token) => {
    const field = document.activeElement;

    if (!field || !['INPUT', 'TEXTAREA'].includes(field.tagName)) {
        window.adminToast('Click into a text field first, then insert a variable.', 'warning');
        return;
    }

    const insertion = `{${token}}`;
    const currentValue = field.value || '';
    const start = Number.isInteger(field.selectionStart) ? field.selectionStart : currentValue.length;
    const end = Number.isInteger(field.selectionEnd) ? field.selectionEnd : currentValue.length;
    const nextValue = `${currentValue.slice(0, start)}${insertion}${currentValue.slice(end)}`;

    field.value = nextValue;
    field.dispatchEvent(new Event('input', { bubbles: true }));
    field.focus();

    const nextCursor = start + insertion.length;
    if (typeof field.setSelectionRange === 'function') {
        field.setSelectionRange(nextCursor, nextCursor);
    }
};

// ── Button loading state ──────────────────────────────────────────────────────
const SPINNER_SVG = `<svg class="inline w-3.5 h-3.5 mr-1.5 animate-spin" fill="none" viewBox="0 0 24 24">
    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
</svg>`;

window.setButtonLoading = (btn, loading, label = 'Saving…') => {
    if (!btn) return;
    if (loading) {
        btn.disabled = true;
        btn.dataset.originalHtml = btn.innerHTML;
        btn.innerHTML = SPINNER_SVG + label;
    } else {
        btn.disabled = false;
        if (btn.dataset.originalHtml) {
            btn.innerHTML = btn.dataset.originalHtml;
            delete btn.dataset.originalHtml;
        }
    }
};

// ── Inline validation error helpers ──────────────────────────────────────────
window.showFormErrors = (form, errors) => {
    window.clearFormErrors(form);
    Object.entries(errors).forEach(([field, messages]) => {
        const selectors = [
            `[name="${field}"]`,
            `[name="${field.replace(/\.([^.]+)/g, '[$1]')}"]`,
            `#${field.replace(/[.\[\]]/g, '_')}`,
        ];
        let input = null;
        for (const sel of selectors) {
            try { input = form.querySelector(sel); } catch {}
            if (input) break;
        }
        const msg = Array.isArray(messages) ? messages[0] : messages;
        if (input) {
            input.classList.add('!border-red-400', 'ring-1', 'ring-red-300');
            const err = document.createElement('p');
            err.dataset.fieldError = '1';
            err.className = 'text-xs text-red-600 mt-1 flex items-center gap-1';
            err.innerHTML = `<svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 16 16"><path d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zm0 3.25a.75.75 0 0 1 .75.75v3a.75.75 0 1 1-1.5 0V5a.75.75 0 0 1 .75-.75zM8 11a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/></svg>`;
            err.appendChild(document.createTextNode(msg));
            input.after(err);
        } else {
            let topErrors = form.querySelector('[data-form-errors-top]');
            if (!topErrors) {
                topErrors = document.createElement('div');
                topErrors.dataset.formErrorsTop = '1';
                topErrors.dataset.fieldError = '1';
                topErrors.className = 'mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-xs text-red-700 space-y-1';
                form.prepend(topErrors);
            }
            const li = document.createElement('p');
            li.textContent = `${field}: ${msg}`;
            topErrors.appendChild(li);
        }
    });
    const first = form.querySelector('[data-field-error]');
    if (first) first.scrollIntoView({ behavior: 'smooth', block: 'center' });
};

window.clearFormErrors = (form) => {
    form.querySelectorAll('[data-field-error]').forEach(el => el.remove());
    form.querySelectorAll('.\\!border-red-400').forEach(el => {
        el.classList.remove('!border-red-400', 'ring-1', 'ring-red-300');
    });
};

// ── Core AJAX form submit ─────────────────────────────────────────────────────
window.ajaxSubmit = async (form, options = {}) => {
    const submitBtn   = form.querySelector('[type="submit"]');
    const csrf        = document.querySelector('meta[name="csrf-token"]')?.content;
    const url         = form.action || window.location.href;
    const loadingLabel = options.loadingLabel || submitBtn?.dataset.loadingLabel || 'Saving…';

    window.setButtonLoading(submitBtn, true, loadingLabel);
    window.clearFormErrors(form);

    try {
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            body: new FormData(form),
        });

        let data = {};
        try { data = await res.json(); } catch {}

        if (res.ok) {
            const msg = data.message || options.successMessage || 'Saved successfully.';
            window.adminToast(msg, 'success');
            if (options.onSuccess) {
                options.onSuccess(data);
            } else if (data.redirect) {
                setTimeout(() => { window.location.href = data.redirect; }, 900);
                return;
            }
        } else if (res.status === 422) {
            const msg = data.message || 'Please fix the errors below.';
            window.adminToast(msg, 'error');
            if (data.errors) window.showFormErrors(form, data.errors);
        } else if (res.status === 403) {
            window.adminToast('You do not have permission to perform this action.', 'error');
        } else {
            window.adminToast(data.message || 'An unexpected error occurred. Please try again.', 'error');
        }
    } catch {
        window.adminToast('Network error — please check your connection and try again.', 'error');
    } finally {
        window.setButtonLoading(submitBtn, false);
    }
};

// ── AJAX delete handler (SweetAlert2 confirmation) ────────────────────────────
document.addEventListener('click', async (e) => {
    const btn = e.target.closest('[data-ajax-delete]');
    if (!btn) return;
    e.preventDefault();

    const confirmMsg = btn.dataset.confirm || 'This action cannot be undone.';
    const result = await window.adminConfirm({
        title: 'Delete this item?',
        text: confirmMsg,
        confirmText: 'Yes, delete',
    });
    if (!result.isConfirmed) return;

    const url  = btn.dataset.ajaxDelete;
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    const row  = btn.closest('[data-delete-row]') || btn.closest('tr') || btn.closest('li');

    btn.disabled = true;

    try {
        const res = await fetch(url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
        });

        let data = {};
        try { data = await res.json(); } catch {}

        if (res.ok) {
            window.adminToast(data.message || 'Deleted successfully.', 'success');
            if (row) {
                row.style.transition = 'opacity 0.25s ease, transform 0.25s ease, max-height 0.3s ease';
                row.style.opacity = '0';
                row.style.transform = 'translateX(-8px)';
                setTimeout(() => {
                    row.style.maxHeight = '0';
                    row.style.overflow = 'hidden';
                    setTimeout(() => row.remove(), 300);
                }, 250);
            }
        } else {
            window.adminToast(data.message || 'Could not delete this item.', 'error');
            btn.disabled = false;
        }
    } catch {
        window.adminToast('Network error — please try again.', 'error');
        btn.disabled = false;
    }
});

// ── AJAX toggle handler (global event delegation) ─────────────────────────────
document.addEventListener('click', async (e) => {
    const btn = e.target.closest('[data-ajax-toggle]');
    if (!btn) return;
    e.preventDefault();

    const url   = btn.dataset.ajaxToggle;
    const csrf  = document.querySelector('meta[name="csrf-token"]')?.content;
    const field = btn.dataset.toggleField || 'is_active';

    btn.disabled = true;
    const originalHtml = btn.innerHTML;
    btn.innerHTML = SPINNER_SVG;

    try {
        const res = await fetch(url, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({}),
        });

        let data = {};
        try { data = await res.json(); } catch {}

        if (res.ok) {
            const newVal = data[field] ?? data.value;
            window.adminToast(data.message || 'Updated.', 'success');
            btn.dispatchEvent(new CustomEvent('ajax-toggled', { bubbles: true, detail: { value: newVal, data } }));
        } else {
            window.adminToast(data.message || 'Could not update.', 'error');
        }
    } catch {
        window.adminToast('Network error — please try again.', 'error');
    } finally {
        btn.innerHTML = originalHtml;
        btn.disabled = false;
    }
});

// ── Global form submit interception for [data-ajax-form] ──────────────────────
document.addEventListener('submit', async (e) => {
    const form = e.target.closest('[data-ajax-form]');
    if (!form) return;
    e.preventDefault();

    await window.ajaxSubmit(form, {
        successMessage: form.dataset.successMessage || 'Saved successfully.',
        loadingLabel:   form.dataset.loadingLabel || 'Saving…',
    });
});

// ── Alpine components ─────────────────────────────────────────────────────────
Alpine.plugin(collapse);

Alpine.data('mediaPickerModal', (fieldName, currentId, currentUrl) => ({
    fieldName: fieldName,
    fieldId: currentId,
    previewUrl: currentUrl || '',
    open: false,
    tab: 'library',
    search: '',
    mediaType: '',
    loading: false,
    assets: [],
    meta: {},
    uploading: false,
    uploadError: '',
    uploadPreviewSrc: '',
    uploadFileName: '',
    // Cropper.js
    cropOpen: false,
    cropper: null,
    cropData: '',
    cropPresets: [
        { label: 'Free', ratio: NaN },
        { label: '16:9', ratio: 16 / 9 },
        { label: '4:3', ratio: 4 / 3 },
        { label: '1:1', ratio: 1 },
        { label: 'OG', ratio: 1200 / 630 },
    ],

    init() {
        this.fieldId = this.normalizeFieldId(this.fieldId);

        if (this.fieldId && !this.previewUrl) {
            this.hydrateSelectedAsset();
        }
    },

    normalizeFieldId(value) {
        if (value === undefined || value === null || value === '') {
            return null;
        }

        const normalized = parseInt(value, 10);

        return Number.isFinite(normalized) ? normalized : null;
    },

    handleFileSelect() {
        const file = this.$refs.fileInput.files[0];
        if (!file) return;
        this.uploadFileName = file.name;
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => { this.uploadPreviewSrc = e.target.result; };
            reader.readAsDataURL(file);
        } else {
            this.uploadPreviewSrc = '';
        }
    },

    openModal() {
        this.open = true;
        if (this.assets.length === 0) this.fetchMedia(1);
    },

    clear() {
        this.fieldId = null;
        this.previewUrl = '';
        this.cropData = '';
    },

    openCrop() {
        if (!this.previewUrl) { window.adminToast('Select an image first.', 'warning'); return; }
        this.cropOpen = true;
        this.$nextTick(() => {
            const img = this.$refs.cropImage;
            if (!img) return;
            img.src = this.previewUrl;
            img.onload = () => {
                if (this.cropper) this.cropper.destroy();
                this.cropper = new Cropper(img);
                const sel = this.cropper.getCropperSelection();
                if (sel) {
                    sel.initialCoverage = 0.9;
                    sel.movable = true;
                    sel.resizable = true;
                }
            };
        });
    },

    setCropAspect(ratio) {
        if (!this.cropper) return;
        const sel = this.cropper.getCropperSelection();
        if (sel) sel.aspectRatio = isNaN(ratio) ? NaN : ratio;
    },

    applyCrop() {
        if (!this.cropper) return;
        const sel = this.cropper.getCropperSelection();
        if (sel) {
            this.cropData = JSON.stringify({ x: Math.round(sel.x), y: Math.round(sel.y), width: Math.round(sel.width), height: Math.round(sel.height), rotate: 0 });
        }
        this.cropper.destroy();
        this.cropper = null;
        this.cropOpen = false;
        window.adminToast('Crop data saved.', 'success');
    },

    cancelCrop() {
        if (this.cropper) { this.cropper.destroy(); this.cropper = null; }
        this.cropOpen = false;
    },

    select(asset) {
        this.fieldId = asset.id;
        this.previewUrl = asset.url;
        this.open = false;
    },

    async hydrateSelectedAsset() {
        const id = this.normalizeFieldId(this.fieldId);
        if (!id) {
            this.previewUrl = '';

            return;
        }

        try {
            const res = await fetch(`/admin/media/json?id=${id}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            });

            if (!res.ok) {
                if (res.status === 404) {
                    this.clear();
                }

                return;
            }

            const asset = await res.json();
            this.fieldId = asset.id;
            this.previewUrl = asset.url || '';
        } catch {
            this.previewUrl = '';
        }
    },

    async fetchMedia(page) {
        this.loading = true;
        try {
            const params = new URLSearchParams({ page });
            if (this.search) params.set('search', this.search);
            if (this.mediaType) params.set('type', this.mediaType);
            const res = await fetch(`/admin/media/json?${params}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            });
            const data = await res.json();
            this.assets = data.data;
            this.meta = { current_page: data.current_page, last_page: data.last_page };
        } catch {
            window.adminToast('Could not load media library. Please try again.', 'error');
            this.assets = [];
        } finally {
            this.loading = false;
        }
    },

    async uploadFile() {
        this.uploadError = '';
        const container = this.$refs.uploadForm;
        const file = this.$refs.fileInput.files[0];
        if (!file) { this.uploadError = 'Please select a file.'; return; }
        const title = container.querySelector('[data-upload="title"]').value.trim();
        if (!title) { this.uploadError = 'Internal title is required.'; return; }
        const desc = container.querySelector('[data-upload="description"]').value.trim() || 'Uploaded via media picker';
        const alt  = container.querySelector('[data-upload="alt"]').value.trim() || title;
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
        const fd   = new FormData();
        fd.append('file', file);
        fd.append('internal_title', title);
        fd.append('description', desc);
        fd.append('default_alt_text', alt);
        this.uploading = true;
        try {
            const res  = await fetch('/admin/media', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                body: fd,
            });
            const data = await res.json();
            if (!res.ok) { this.uploadError = data.error || 'Upload failed.'; return; }
            this.select(data.asset);
            this.tab    = 'library';
            this.assets = [];
            this.fetchMedia(1);
        } catch {
            this.uploadError = 'Network error during upload.';
        } finally {
            this.uploading = false;
        }
    },
}));

Alpine.data('mediaMultiPickerModal', (fieldName, currentIds = [], preferredType = 'image') => ({
    fieldName,
    open: false,
    tab: 'library',
    search: '',
    filterType: preferredType || '',
    loading: false,
    assets: [],
    meta: {},
    uploading: false,
    uploadError: '',
    uploadPreviewSrc: '',
    uploadFileName: '',
    selectedIds: [],
    selectedAssets: [],

    init() {
        this.selectedIds = this.normalizeIds(currentIds);
        if (this.selectedIds.length) {
            this.hydrateSelectedAssets();
        }
    },

    normalizeIds(value) {
        if (Array.isArray(value)) {
            return value.map((id) => parseInt(id, 10)).filter(Number.isFinite);
        }

        if (typeof value === 'string') {
            return value
                .split(',')
                .map((id) => parseInt(id.trim(), 10))
                .filter(Number.isFinite);
        }

        return [];
    },

    isSelected(id) {
        return this.selectedIds.includes(id);
    },

    openModal() {
        this.open = true;
        if (this.assets.length === 0) {
            this.fetchMedia(1);
        }
    },

    clear() {
        this.selectedIds = [];
        this.selectedAssets = [];
    },

    remove(id) {
        this.selectedIds = this.selectedIds.filter((assetId) => assetId !== id);
        this.selectedAssets = this.selectedAssets.filter((asset) => asset.id !== id);
    },

    moveSelected(index, direction) {
        const target = index + direction;
        if (target < 0 || target >= this.selectedIds.length) {
            return;
        }

        [this.selectedIds[index], this.selectedIds[target]] = [this.selectedIds[target], this.selectedIds[index]];
        [this.selectedAssets[index], this.selectedAssets[target]] = [this.selectedAssets[target], this.selectedAssets[index]];
    },

    toggle(asset) {
        if (this.isSelected(asset.id)) {
            this.remove(asset.id);
            return;
        }

        this.selectedIds.push(asset.id);
        this.selectedAssets.push(asset);
    },

    handleFileSelect() {
        const file = this.$refs.fileInput.files[0];
        if (!file) return;
        this.uploadFileName = file.name;
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => { this.uploadPreviewSrc = e.target.result; };
            reader.readAsDataURL(file);
        } else {
            this.uploadPreviewSrc = '';
        }
    },

    async hydrateSelectedAssets() {
        if (this.selectedIds.length === 0) {
            this.selectedAssets = [];
            return;
        }

        try {
            const params = new URLSearchParams({ ids: this.selectedIds.join(',') });
            const res = await fetch(`/admin/media/json?${params}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            });

            if (!res.ok) {
                return;
            }

            const assets = await res.json();
            this.selectedAssets = this.selectedIds
                .map((id) => assets.find((asset) => asset.id === id))
                .filter(Boolean);
        } catch {
            this.selectedAssets = [];
        }
    },

    async fetchMedia(page) {
        this.loading = true;
        try {
            const params = new URLSearchParams({ page });
            if (this.search) params.set('search', this.search);
            if (this.filterType) params.set('type', this.filterType);
            const res = await fetch(`/admin/media/json?${params}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            });
            const data = await res.json();
            this.assets = data.data;
            this.meta = { current_page: data.current_page, last_page: data.last_page };
        } catch {
            window.adminToast('Could not load media library. Please try again.', 'error');
            this.assets = [];
        } finally {
            this.loading = false;
        }
    },

    async uploadFile() {
        this.uploadError = '';
        const container = this.$refs.uploadForm;
        const file = this.$refs.fileInput.files[0];
        if (!file) { this.uploadError = 'Please select a file.'; return; }
        const title = container.querySelector('[data-upload="title"]').value.trim();
        if (!title) { this.uploadError = 'Internal title is required.'; return; }
        const desc = container.querySelector('[data-upload="description"]').value.trim() || 'Uploaded via media picker';
        const alt  = container.querySelector('[data-upload="alt"]').value.trim() || title;
        const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
        const fd   = new FormData();
        fd.append('file', file);
        fd.append('internal_title', title);
        fd.append('description', desc);
        fd.append('default_alt_text', alt);
        this.uploading = true;
        try {
            const res  = await fetch('/admin/media', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                body: fd,
            });
            const data = await res.json();
            if (!res.ok) { this.uploadError = data.error || 'Upload failed.'; return; }
            this.toggle(data.asset);
            this.tab = 'library';
            this.assets = [];
            this.fetchMedia(1);
        } catch {
            this.uploadError = 'Network error during upload.';
        } finally {
            this.uploading = false;
        }
    },
}));

Alpine.data('blockEditor', (pageType, initialBlocks, blockTypes, styleFields = [], styleDefaults = { desktop: {}, tablet: {}, mobile: {} }, dynamicVariableGroups = []) => {
    const filteredBlockTypes = (blockTypes || []).filter((t) => {
        const category = t?.category ?? 'content';
        if (category === 'theme' && pageType !== 'theme_layout') {
            return false;
        }

        const allowed = t?.governance?.allowed_page_types;
        if (Array.isArray(allowed) && allowed.length > 0) {
            return allowed.includes(pageType);
        }

        return true;
    });

    const typeMap = {};
    filteredBlockTypes.forEach(t => { typeMap[t.key] = t; });
    const normalizedVariableGroups = (dynamicVariableGroups || []).map(group => ({
        ...group,
        variables: Array.isArray(group.variables) ? group.variables : [],
    }));

    const deepClone = (value) => JSON.parse(JSON.stringify(value ?? null));
    const categoryOrder = ['data', 'content', 'layout', 'media', 'interactive', 'theme'];

    const editableValue = (value) => {
        if (value === null) return 'null';
        if (value === true) return 'true';
        if (value === false) return 'false';
        if (Array.isArray(value)) return value.join(', ');

        return value === undefined ? '' : String(value);
    };

    const parsedValue = (value) => {
        const trimmed = String(value ?? '').trim();
        if (trimmed === '') return '';
        if (trimmed === 'null') return null;
        if (trimmed === 'true') return true;
        if (trimmed === 'false') return false;
        if (/^-?\d+$/.test(trimmed)) return Number(trimmed);

        return trimmed;
    };

    const pairsFromObject = (objectValue) => Object.entries(objectValue || {}).map(([key, value]) => ({
        key,
        value: editableValue(value),
    }));

    const objectFromPairs = (pairs, { parseValues = false } = {}) => {
        return (pairs || []).reduce((result, pair) => {
            const key = String(pair?.key ?? '').trim();
            if (!key) return result;

            const value = parseValues ? parsedValue(pair?.value) : String(pair?.value ?? '');
            result[key] = value;

            return result;
        }, {});
    };

    const normalizeDateTimeLocal = (value) => {
        if (!value) return null;
        const normalized = String(value).replace(' ', 'T');

        return normalized.length >= 16 ? normalized.slice(0, 16) : normalized;
    };

    const normalizeDataSourceDraft = (blockType, incoming) => {
        const draft = deepClone(incoming ?? typeMap[blockType]?.data_source ?? null);
        if (!draft) return null;

        if (Array.isArray(draft.manual_ids)) {
            draft.manual_ids = draft.manual_ids.join(', ');
        } else if (draft.manual_ids === undefined || draft.manual_ids === null) {
            draft.manual_ids = '';
        }

        delete draft.filters;

        return draft;
    };

    const childSlotOptionsByType = {
        theme_header_shell: [
            { value: 'left', label: 'Left / Brand' },
            { value: 'center', label: 'Center / Navigation' },
            { value: 'right', label: 'Right / Actions' },
            { value: 'mobile', label: 'Mobile Overlay' },
        ],
        two_column: [
            { value: 'left', label: 'Left Column' },
            { value: 'right', label: 'Right Column' },
        ],
        three_column: [
            { value: 'col1', label: 'Column 1' },
            { value: 'col2', label: 'Column 2' },
            { value: 'col3', label: 'Column 3' },
        ],
    };

    const childSlotOptionsFor = (blockType) => childSlotOptionsByType[blockType] ?? [];

    const governanceForType = (blockType) => typeMap[blockType]?.governance ?? null;

    const requiredFieldsForBlock = (block) => {
        const blockType = block?.block_type;
        const governance = governanceForType(blockType);
        const required = governance?.required_fields;
        if (!required) return [];

        const variant = typeof block?.content?.variant === 'string' ? block.content.variant : null;
        if (variant && Array.isArray(required?.[variant])) {
            return required[variant].filter((v) => typeof v === 'string');
        }

        if (Array.isArray(required)) {
            return required.filter((v) => typeof v === 'string');
        }

        return [];
    };

    const visibleFieldsForBlock = (blockType, block) => {
        const fields = typeMap[blockType]?.content_fields ?? [];
        const governance = governanceForType(blockType);
        const variantKey = typeof block?.content?.variant === 'string' ? block.content.variant : null;
        const variants = governance?.variants;

        if (variantKey && variants && typeof variants === 'object' && variants[variantKey]?.visible_fields) {
            const allowed = variants[variantKey].visible_fields.filter((v) => typeof v === 'string');
            const allowSet = new Set([...allowed, 'variant']);
            return fields.filter((field) => allowSet.has(field.key));
        }

        return fields;
    };

    const defaultChildSlot = (parentBlock, childIndex = 0) => {
        const options = childSlotOptionsFor(parentBlock?.block_type);
        if (options.length === 0) {
            return '';
        }

        return options[childIndex % options.length].value;
    };

    let _uid = 0;
    const prep = (b, parentBlock = null, childIndex = 0) => {
        const content = b.content || {};
        const styles = deepClone(b.styles || styleDefaults);
        if (!styles.desktop) styles.desktop = {};
        if (!styles.tablet) styles.tablet = {};
        if (!styles.mobile) styles.mobile = {};

        if (content._wrapper) {
            styles.desktop = { ...styles.desktop, ...content._wrapper };
            delete content._wrapper;
        }

        const prepared = {
            ...b,
            _open: false,
            _styleOpen: false,
            _advancedOpen: false,
            _dataSourceOpen: false,
            _uid: ++_uid,
            is_layout_section: b.is_layout_section ?? typeMap[b.block_type]?.is_layout_section ?? false,
            category: b.category ?? typeMap[b.block_type]?.category ?? 'content',
            show_on_desktop: b.show_on_desktop ?? true,
            show_on_tablet: b.show_on_tablet ?? true,
            show_on_mobile: b.show_on_mobile ?? true,
            visible_from: normalizeDateTimeLocal(b.visible_from ?? null),
            visible_until: normalizeDateTimeLocal(b.visible_until ?? null),
            custom_id: b.custom_id ?? null,
            animation: b.animation ?? null,
            content,
            styles,
            data_source: normalizeDataSourceDraft(b.block_type, b.data_source ?? null),
            _filterPairs: pairsFromObject(b.data_source?.filters ?? typeMap[b.block_type]?.data_source?.filters ?? {}),
            _attributePairs: pairsFromObject(b.attributes ?? {}),
            supports_children: typeMap[b.block_type]?.supports_children ?? false,
            has_data_source: Boolean(typeMap[b.block_type]?.data_source ?? b.data_source),
            children: [],
        };

        if (parentBlock && childSlotOptionsFor(parentBlock.block_type).length > 0 && !prepared.content._layout_slot) {
            prepared.content._layout_slot = defaultChildSlot(parentBlock, childIndex);
        }

        prepared.children = (b.children || []).map((child, index) => prep(child, prepared, index));

        return prepared;
    };

    return {
        pageType,
        blocks: (initialBlocks || []).map(b => prep(b)),
        blockTypes: filteredBlockTypes,
        styleFields,
        styleDefaults,
        dynamicVariableGroups: normalizedVariableGroups,
        animationOptions: [
            { value: '', label: 'None' },
            { value: 'fade-up', label: 'Fade Up' },
            { value: 'fade-down', label: 'Fade Down' },
            { value: 'fade-left', label: 'Fade Left' },
            { value: 'fade-right', label: 'Fade Right' },
            { value: 'zoom-in', label: 'Zoom In' },
            { value: 'slide-up', label: 'Slide Up' },
        ],
        spacingFieldKeys: ['padding_top', 'padding_right', 'padding_bottom', 'padding_left', 'margin_top', 'margin_bottom'],

        init() {
            this.$nextTick(() => {
                window.refreshIcons && window.refreshIcons();
                const list = this.$refs.blockList;
                if (list && typeof Sortable !== 'undefined') {
                    Sortable.create(list, {
                        handle:     '[data-drag-handle]',
                        draggable:  '[data-block-item]',
                        animation:  150,
                        ghostClass: 'opacity-30',
                        chosenClass:'ring-2 ring-forest/30',
                        onEnd: () => {
                            const items = [...list.querySelectorAll('[data-block-item]')];
                            const uidOrder = items.map(el => parseInt(el.dataset.uid, 10));
                            this.blocks.sort((a, b) => uidOrder.indexOf(a._uid) - uidOrder.indexOf(b._uid));
                        },
                    });
                }

                const form = this.$el.closest('form');
                if (form && !form.__lushBlocksGovernanceBound) {
                    form.__lushBlocksGovernanceBound = true;
                    form.addEventListener('submit', (event) => {
                        const errors = this.validateBlocks();
                        if (errors.length === 0) {
                            return;
                        }

                        event.preventDefault();
                        errors.forEach((error) => {
                            const block = this.findBlockByUid(error.uid);
                            if (block) {
                                block._open = true;
                            }
                        });

                        window.adminToast(errors[0]?.message || 'Please fix block validation errors before saving.', 'error');
                    });
                }
            });
        },

        get blocksJson() {
            setTimeout(() => { window.refreshIcons && window.refreshIcons(); }, 10);

            return JSON.stringify(this.blocks.map(block => this.serializeBlock(block, { includeId: true })));
        },

        serializeBlock(block, { includeId = true } = {}) {
            const dataSource = block.data_source ? deepClone(block.data_source) : null;
            if (dataSource) {
                dataSource.filters = objectFromPairs(block._filterPairs, { parseValues: true });
                dataSource.manual_ids = String(dataSource.manual_ids ?? '')
                    .split(',')
                    .map(value => parseInt(value.trim(), 10))
                    .filter(Number.isFinite);
            }

            return {
                id: includeId ? (block.id ?? null) : null,
                block_type: block.block_type,
                is_layout_section: block.is_layout_section,
                category: block.category ?? null,
                is_enabled: block.is_enabled,
                show_on_desktop: block.show_on_desktop,
                show_on_tablet: block.show_on_tablet ?? true,
                show_on_mobile: block.show_on_mobile,
                visible_from: block.visible_from ?? null,
                visible_until: block.visible_until ?? null,
                content: block.content,
                data_source: dataSource,
                styles: block.styles,
                custom_id: includeId ? (block.custom_id ?? null) : null,
                attributes: objectFromPairs(block._attributePairs, { parseValues: false }),
                animation: block.animation ?? null,
                children: (block.children || []).map(child => this.serializeBlock(child, { includeId })),
            };
        },

        createBlock(type, parentBlock = null) {
            const typeObj = typeMap[type];
            if (!typeObj) return null;

            const childPosition = parentBlock && Array.isArray(parentBlock.children) ? parentBlock.children.length : 0;

            return prep({
                block_type: type,
                is_enabled: true,
                is_layout_section: typeObj.is_layout_section ?? false,
                category: typeObj.category || 'content',
                content: deepClone(typeObj.defaults || {}),
                data_source: deepClone(typeObj.data_source || null),
                styles: deepClone(styleDefaults),
                show_on_desktop: true,
                show_on_tablet: true,
                show_on_mobile: true,
                children: [],
            }, parentBlock, childPosition);
        },

        addBlock(type) {
            const newBlock = this.createBlock(type);
            if (!newBlock) return;

            this.blocks.push(newBlock);

            this.$nextTick(() => {
                this.blocks[this.blocks.length - 1]._open = true;
                window.refreshIcons && window.refreshIcons();
            });
        },

        addChildBlock(parentBlock, type) {
            const newBlock = this.createBlock(type, parentBlock);
            if (!newBlock) return;

            if (!Array.isArray(parentBlock.children)) {
                parentBlock.children = [];
            }

            parentBlock.children.push(newBlock);
            parentBlock._open = true;

            this.$nextTick(() => { window.refreshIcons && window.refreshIcons(); });
        },

        async duplicateBlock(index) {
            await this.duplicateBlockInCollection(this.blocks, index);
        },

        async duplicateBlockInCollection(collection, index, parentBlock = null) {
            const source = collection[index];
            if (!source) return;

            const payload = this.serializeBlock(source, { includeId: false });
            payload.custom_id = null;
            const clone = prep(payload, parentBlock);
            clone._open = true;

            collection.splice(index + 1, 0, clone);
            this.$nextTick(() => { window.refreshIcons && window.refreshIcons(); });
        },

        async copyBlock(index) {
            await this.copyBlockNode(this.blocks[index]);
        },

        async copyBlockNode(source) {
            if (!source) return;
            if (!navigator.clipboard?.writeText) {
                window.adminToast('Clipboard copy is not available in this browser.', 'warning');
                return;
            }

            try {
                const payload = this.serializeBlock(source, { includeId: false });
                payload.custom_id = null;
                await navigator.clipboard.writeText(JSON.stringify(payload, null, 2));
                window.adminToast('Block configuration copied to clipboard.', 'success');
            } catch {
                window.adminToast('Could not copy this block to the clipboard.', 'error');
            }
        },

        async deleteBlock(index) {
            await this.deleteBlockFromCollection(this.blocks, index);
        },

        async deleteBlockFromCollection(collection, index) {
            const result = await window.adminConfirm({
                title: 'Remove this block?',
                text: 'The block content will be lost.',
                confirmText: 'Yes, remove',
            });
            if (result.isConfirmed) collection.splice(index, 1);
        },

        moveBlock(index, dir) {
            this.moveBlockInCollection(this.blocks, index, dir);
        },

        moveBlockInCollection(collection, index, dir) {
            const target = index + dir;
            if (target < 0 || target >= collection.length) return;
            [collection[index], collection[target]] = [collection[target], collection[index]];
        },

        availableBlockCategories() {
            return [...new Set(this.blockTypes.map(type => type.category || 'content'))]
                .sort((a, b) => {
                    const aIndex = categoryOrder.indexOf(a);
                    const bIndex = categoryOrder.indexOf(b);
                    if (aIndex === -1 && bIndex === -1) return a.localeCompare(b);
                    if (aIndex === -1) return 1;
                    if (bIndex === -1) return -1;

                    return aIndex - bIndex;
                });
        },

        filteredAddBlockTypes(search = '', category = 'all') {
            const term = String(search || '').trim().toLowerCase();

            return this.blockTypes.filter(type => {
                if (category !== 'all' && (type.category || 'content') !== category) {
                    return false;
                }

                if (!term) {
                    return true;
                }

                const haystack = [
                    type.label,
                    type.key,
                    type.category,
                    type.supports_children ? 'nested children container columns layout' : '',
                    type.data_source ? 'dynamic data query loop' : '',
                ].join(' ').toLowerCase();

                return haystack.includes(term);
            });
        },

        filteredChildBlockTypes(parentBlock, search = '') {
            const term = String(search || '').trim().toLowerCase();

            return this.blockTypes.filter(type => {
                if (type.is_layout_section || type.supports_children) {
                    return false;
                }

                if (!term) {
                    return true;
                }

                const haystack = [
                    type.label,
                    type.key,
                    type.category,
                    type.data_source ? 'dynamic data query loop' : '',
                ].join(' ').toLowerCase();

                return haystack.includes(term);
            });
        },

        childSlotOptions(parentBlock) {
            return childSlotOptionsFor(parentBlock?.block_type);
        },

        childSlotLabel(parentBlock, childBlock) {
            const slot = childBlock?.content?._layout_slot;
            const options = this.childSlotOptions(parentBlock);
            const match = options.find(option => option.value === slot);

            return match?.label || 'Auto';
        },

        getTypeIcon(key)   { return typeMap[key]?.icon  ?? 'square'; },
        getTypeLabel(key)  { return typeMap[key]?.label ?? key; },
        getTypeFields(subject) {
            const blockType = typeof subject === 'string' ? subject : subject?.block_type;
            if (!blockType) return [];

            if (typeof subject === 'object') {
                return visibleFieldsForBlock(blockType, subject);
            }

            return typeMap[blockType]?.content_fields ?? [];
        },

        validateBlocks() {
            const errors = [];

            const validateNode = (block, path = []) => {
                if (!block || !block.block_type) return;

                const blockType = block.block_type;
                const governance = governanceForType(blockType);
                const allowedPageTypes = governance?.allowed_page_types;
                if (Array.isArray(allowedPageTypes) && allowedPageTypes.length > 0 && !allowedPageTypes.includes(this.pageType)) {
                    errors.push({ uid: block._uid, message: `${this.getTypeLabel(blockType)} is not allowed on this page type.` });
                }

                if (block.is_enabled) {
                    const requiredFields = requiredFieldsForBlock(block);
                    requiredFields.forEach((field) => {
                        const value = block?.content?.[field];
                        const ok = (typeof value === 'string' && value.trim() !== '')
                            || (typeof value === 'number' && Number.isFinite(value))
                            || (Array.isArray(value) && value.length > 0)
                            || (value !== null && value !== undefined && value !== false);
                        if (!ok) {
                            errors.push({ uid: block._uid, message: `${this.getTypeLabel(blockType)} is missing required field: ${field}` });
                        }
                    });

                    if (block.content?.cta_primary_text && !block.content?.cta_primary_url) {
                        errors.push({ uid: block._uid, message: `${this.getTypeLabel(blockType)} has CTA text but no CTA URL.` });
                    }
                    if (block.content?.cta_secondary_text && !block.content?.cta_secondary_url) {
                        errors.push({ uid: block._uid, message: `${this.getTypeLabel(blockType)} has secondary CTA text but no secondary CTA URL.` });
                    }
                    if (block.content?.cta_text && !block.content?.cta_url) {
                        errors.push({ uid: block._uid, message: `${this.getTypeLabel(blockType)} has CTA text but no CTA URL.` });
                    }
                    if (block.content?.button_text && !block.content?.button_url) {
                        errors.push({ uid: block._uid, message: `${this.getTypeLabel(blockType)} has button text but no button URL.` });
                    }
                }

                const childRules = governance?.supports_children_rules;
                if (childRules && Array.isArray(block.children) && block.children.length > 0) {
                    const slotKey = typeof childRules?.slot_key === 'string' ? childRules.slot_key : '_layout_slot';
                    const allowedSlots = Array.isArray(childRules?.allowed_slots) ? childRules.allowed_slots : [];
                    const requiredSlots = Array.isArray(childRules?.required_slots) ? childRules.required_slots : [];

                    const seen = [];
                    block.children.forEach((child) => {
                        const slot = child?.content?.[slotKey] || '';
                        if (slot) {
                            seen.push(slot);
                        }
                        if (allowedSlots.length > 0 && (!slot || !allowedSlots.includes(slot))) {
                            errors.push({ uid: block._uid, message: `${this.getTypeLabel(blockType)} children must include slot assignments: ${allowedSlots.join(', ')}` });
                        }
                    });

                    requiredSlots.forEach((requiredSlot) => {
                        if (requiredSlot && !seen.includes(requiredSlot)) {
                            errors.push({ uid: block._uid, message: `${this.getTypeLabel(blockType)} is missing required child slot: ${requiredSlot}` });
                        }
                    });
                }

                (block.children || []).forEach((child, index) => validateNode(child, [...path, index]));
            };

            this.blocks.forEach((block, index) => validateNode(block, [index]));

            return errors;
        },

        findBlockByUid(uid) {
            if (!uid) return null;

            const scan = (blocks) => {
                for (const block of blocks || []) {
                    if (block._uid === uid) {
                        return block;
                    }
                    const found = scan(block.children);
                    if (found) return found;
                }
                return null;
            };

            return scan(this.blocks);
        },

        hasDataSource(block) {
            return Boolean(block?.has_data_source || typeMap[block?.block_type]?.data_source);
        },

        dataSourceSummary(block) {
            const model = block?.data_source?.model || typeMap[block?.block_type]?.data_source?.model || '';
            if (model === 'auto') {
                return this.shortModelName(block?.content?.data_model || 'Auto');
            }

            return this.shortModelName(model);
        },

        shortModelName(model) {
            return String(model || '').split('\\').pop() || 'Unknown';
        },

        resetDataSource(block) {
            block.data_source = normalizeDataSourceDraft(block.block_type, null);
            block._filterPairs = pairsFromObject(typeMap[block.block_type]?.data_source?.filters ?? {});
        },

        addDataFilter(block) {
            if (!Array.isArray(block._filterPairs)) {
                block._filterPairs = [];
            }

            block._filterPairs.push({ key: '', value: 'auto' });
        },

        removeDataFilter(block, index) {
            block._filterPairs.splice(index, 1);
        },

        addAttribute(block) {
            if (!Array.isArray(block._attributePairs)) {
                block._attributePairs = [];
            }

            block._attributePairs.push({ key: '', value: '' });
        },

        removeAttribute(block, index) {
            block._attributePairs.splice(index, 1);
        },

        filteredDynamicVariableGroups(search = '') {
            const term = String(search || '').trim().toLowerCase();
            if (!term) {
                return this.dynamicVariableGroups;
            }

            return this.dynamicVariableGroups
                .map(group => ({
                    ...group,
                    variables: group.variables.filter(variable => {
                        return [variable.token, variable.label, variable.description]
                            .some(value => String(value || '').toLowerCase().includes(term));
                    }),
                }))
                .filter(group => {
                    if (group.variables.length > 0) {
                        return true;
                    }

                    return [group.label, group.description]
                        .some(value => String(value || '').toLowerCase().includes(term));
                });
        },

        insertDynamicVariable(block, fieldKey, token) {
            if (!block.content) {
                block.content = {};
            }

            const currentValue = String(block.content[fieldKey] ?? '');
            block.content[fieldKey] = `${currentValue}{${token}}`;
        },

        groupedStyleFields(kind = 'padding') {
            const orderedKeys = kind === 'margin'
                ? ['margin_top', 'margin_bottom']
                : ['padding_top', 'padding_right', 'padding_bottom', 'padding_left'];

            return orderedKeys
                .map((key) => this.styleFields.find((field) => field.key === key))
                .filter(Boolean);
        },

        renderableStyleFields() {
            return this.styleFields.filter((field) => !this.spacingFieldKeys.includes(field.key));
        },

        initVisibilityRangePicker(element, block) {
            if (!element) return;

            const defaultDate = [block.visible_from, block.visible_until]
                .filter(Boolean)
                .map((value) => value.replace('T', ' '));

            if (element._flatpickr) {
                element._flatpickr.destroy();
            }

            flatpickr(element, {
                mode: 'range',
                enableTime: true,
                time_24hr: true,
                allowInput: true,
                dateFormat: 'Y-m-d H:i',
                defaultDate,
                onChange: (selectedDates) => {
                    block.visible_from = selectedDates[0] ? this.formatDateTimeLocal(selectedDates[0]) : null;
                    block.visible_until = selectedDates[1] ? this.formatDateTimeLocal(selectedDates[1]) : null;
                },
            });
        },

        formatDateTimeLocal(date) {
            const pad = (value) => String(value).padStart(2, '0');

            return [
                date.getFullYear(),
                pad(date.getMonth() + 1),
                pad(date.getDate()),
            ].join('-') + 'T' + [pad(date.getHours()), pad(date.getMinutes())].join(':');
        },

        clearVisibilityRange(block, element) {
            block.visible_from = null;
            block.visible_until = null;
            if (element?._flatpickr) {
                element._flatpickr.clear();
            }
        },

        blockPreview(block) {
            if (block.is_layout_section) {
                return block.content?.heading || '';
            }

            const fields = this.getTypeFields(block);
            if (fields && fields.length > 0) {
                const textKey = fields.find(f => f.type === 'text' || f.type === 'textarea')?.key;
                if (textKey && block.content) {
                    const value = block.content[textKey];
                    if (value) return String(value).replace(/<[^>]+>/g, '').substring(0, 60);
                }
            }

            return '';
        },
    };
});

Alpine.data('sectionManager', (initialSections) => ({
    sections: initialSections,

    init() {
        this.$nextTick(() => {
            window.refreshIcons && window.refreshIcons();
            const list = this.$refs.sectionList;
            if (list && typeof Sortable !== 'undefined') {
                Sortable.create(list, {
                    handle:     '[data-section-drag]',
                    draggable:  '[data-section-item]',
                    animation:  150,
                    ghostClass: 'opacity-30',
                    chosenClass:'opacity-75',
                    onEnd: () => {
                        const items   = [...list.querySelectorAll('[data-section-item]')];
                        const keyOrder = items.map(el => el.dataset.key);
                        this.sections.sort((a, b) => keyOrder.indexOf(a.key) - keyOrder.indexOf(b.key));
                    },
                });
            }
        });
    },

    get sectionsJson() {
        const obj = {};
        this.sections.forEach((s, i) => {
            obj[s.key] = {
                is_enabled: s.is_enabled,
                desktop:    s.desktop,
                mobile:     s.mobile,
                sort_order: (i + 1) * 10,
                settings:   s.settings,
            };
        });
        return JSON.stringify(obj);
    },
}));

// ── Tiptap rich text editor Alpine component ──────────────────────────────────
Alpine.data('richEditor', (fieldName, initialContent = '') => ({
    content: initialContent,
    editor: null,

    init() {
        this.editor = new Editor({
            element: this.$refs.editorContent,
            extensions: [
                StarterKit.configure({
                    heading: { levels: [2, 3, 4] },
                    link: false,
                    underline: false,
                }),
                Link.configure({ openOnClick: false, HTMLAttributes: { class: 'text-forest underline' } }),
                Image,
                Underline,
                TextAlign.configure({ types: ['heading', 'paragraph'] }),
                Placeholder.configure({ placeholder: 'Start writing...' }),
            ],
            content: this.content,
            editorProps: {
                attributes: {
                    class: 'prose prose-sm max-w-none min-h-[200px] px-4 py-3 focus:outline-none',
                },
            },
            onUpdate: ({ editor }) => {
                this.content = editor.getHTML();
            },
        });
    },

    destroy() {
        this.editor?.destroy();
    },

    isActive(type, attrs = {}) {
        return this.editor?.isActive(type, attrs) ?? false;
    },

    toggleBold()      { this.editor.chain().focus().toggleBold().run(); },
    toggleItalic()    { this.editor.chain().focus().toggleItalic().run(); },
    toggleUnderline() { this.editor.chain().focus().toggleUnderline().run(); },
    toggleStrike()    { this.editor.chain().focus().toggleStrike().run(); },
    toggleH2()        { this.editor.chain().focus().toggleHeading({ level: 2 }).run(); },
    toggleH3()        { this.editor.chain().focus().toggleHeading({ level: 3 }).run(); },
    toggleH4()        { this.editor.chain().focus().toggleHeading({ level: 4 }).run(); },
    toggleBulletList(){ this.editor.chain().focus().toggleBulletList().run(); },
    toggleOrderedList(){ this.editor.chain().focus().toggleOrderedList().run(); },
    toggleBlockquote(){ this.editor.chain().focus().toggleBlockquote().run(); },
    setHorizontalRule(){ this.editor.chain().focus().setHorizontalRule().run(); },
    undo()            { this.editor.chain().focus().undo().run(); },
    redo()            { this.editor.chain().focus().redo().run(); },
    alignLeft()       { this.editor.chain().focus().setTextAlign('left').run(); },
    alignCenter()     { this.editor.chain().focus().setTextAlign('center').run(); },
    alignRight()      { this.editor.chain().focus().setTextAlign('right').run(); },

    setLink() {
        const prev = this.editor.getAttributes('link').href ?? '';
        const url = prompt('Enter URL:', prev);
        if (url === null) return;
        if (url === '') { this.editor.chain().focus().unsetLink().run(); return; }
        this.editor.chain().focus().extendMarkRange('link').setLink({ href: url }).run();
    },

    insertImage() {
        const url = prompt('Enter image URL:');
        if (url) this.editor.chain().focus().setImage({ src: url }).run();
    },
}));

// ── AI content generation Alpine component ──────────────────────────────────
Alpine.data('aiGenerate', (fieldName, fieldContext, pageContext) => ({
    open: false,
    loading: false,
    result: null,
    errorMsg: null,
    customInstructions: '',

    toggle() {
        this.open = !this.open;
        if (this.open) {
            this.result = null;
            this.errorMsg = null;
        }
    },

    async generateContent() {
        this.loading = true;
        this.errorMsg = null;
        this.result = null;

        const fieldEl = document.querySelector(`[name="${fieldName}"]`);
        const currentValue = fieldEl ? fieldEl.value : '';
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        try {
            const res = await fetch('/admin/ai/generate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    field_context: fieldContext,
                    current_value: currentValue,
                    custom_instructions: this.customInstructions,
                    page_context: pageContext,
                }),
            });

            const data = await res.json();
            if (data.success && data.content) {
                this.result = data.content;
            } else {
                this.errorMsg = data.message || 'Generation failed.';
            }
        } catch {
            this.errorMsg = 'Network error. Please try again.';
        } finally {
            this.loading = false;
        }
    },

    acceptContent() {
        const fieldEl = document.querySelector(`[name="${fieldName}"]`);
        if (fieldEl) {
            fieldEl.value = this.result;
            fieldEl.dispatchEvent(new Event('input', { bubbles: true }));
        }
        this.open = false;
        this.result = null;
    },
}));

Alpine.start();

// ── DOMContentLoaded init ─────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    createIcons({ icons, attrs: { 'stroke-width': 1.5 } });
    window.refreshTippy();

    document.querySelectorAll('.tom-select').forEach(el => {
        new TomSelect(el, { allowEmptyOption: true });
    });

    // Flatpickr auto-init for date/datetime inputs
    document.querySelectorAll('input[type="date"]').forEach(el => {
        flatpickr(el, { dateFormat: 'Y-m-d', allowInput: true });
    });
    document.querySelectorAll('input[type="datetime-local"]').forEach(el => {
        flatpickr(el, { dateFormat: 'Y-m-d H:i', enableTime: true, time_24hr: true, allowInput: true });
    });
});

// ── Re-run Lucide + Tippy after Alpine mutations ──────────────────────────────
document.addEventListener('alpine:initialized', () => {
    let iconTimer, tippyTimer;
    const observer = new MutationObserver(() => {
        clearTimeout(iconTimer);
        iconTimer = setTimeout(() => { window.refreshIcons && window.refreshIcons(); }, 80);
        clearTimeout(tippyTimer);
        tippyTimer = setTimeout(() => { window.refreshTippy && window.refreshTippy(); }, 200);
    });
    observer.observe(document.body, { childList: true, subtree: true });
});
