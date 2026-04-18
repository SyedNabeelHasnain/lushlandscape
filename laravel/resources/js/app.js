import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import intersect from '@alpinejs/intersect';
import focus from '@alpinejs/focus';
import { createIcons, icons } from 'lucide';
import Swiper from 'swiper';
import { Pagination, Navigation as SwiperNavigation, Autoplay, Keyboard, EffectFade } from 'swiper/modules';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Lenis from 'lenis';
import 'swiper/css';
import 'swiper/css/pagination';
import 'swiper/css/navigation';
import 'swiper/css/effect-fade';
import 'lenis/dist/lenis.css';
import './contact-form.js';

gsap.registerPlugin(ScrollTrigger);

// Expose globally so CMS embed-code / custom HTML blocks can use gsap
window.gsap = gsap;
window.ScrollTrigger = ScrollTrigger;

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

const scrollAnimationPresets = {
    'fade-up': { from: { y: 32 }, to: { y: 0 }, duration: 0.85 },
    'fade-down': { from: { y: -32 }, to: { y: 0 }, duration: 0.85 },
    'fade-left': { from: { x: 32 }, to: { x: 0 }, duration: 0.85 },
    'fade-right': { from: { x: -32 }, to: { x: 0 }, duration: 0.85 },
    'zoom-in': { from: { scale: 0.92 }, to: { scale: 1 }, duration: 0.8 },
    'slide-up': { from: { y: 64 }, to: { y: 0 }, duration: 0.95 },
};

const motionPreset = document.documentElement.dataset.motionPreset || 'refined';
const motionPresetMap = {
    none: {
        offsetMultiplier: 0,
        durationMultiplier: 0,
        triggerStart: 'top 100%',
        staggerTriggerStart: 'top 100%',
        lenisDuration: 0,
        parallaxScrub: 0,
        heroDelay: 0,
    },
    subtle: {
        offsetMultiplier: 0.8,
        durationMultiplier: 0.9,
        triggerStart: 'top 90%',
        staggerTriggerStart: 'top 88%',
        lenisDuration: 1.3,
        parallaxScrub: 1.15,
        heroDelay: 0.12,
    },
    refined: {
        offsetMultiplier: 1,
        durationMultiplier: 1,
        triggerStart: 'top 88%',
        staggerTriggerStart: 'top 85%',
        lenisDuration: 1.6,
        parallaxScrub: 1.5,
        heroDelay: 0.2,
    },
    cinematic: {
        offsetMultiplier: 1.15,
        durationMultiplier: 1.08,
        triggerStart: 'top 84%',
        staggerTriggerStart: 'top 82%',
        lenisDuration: 1.85,
        parallaxScrub: 1.85,
        heroDelay: 0.24,
    },
};
const motionSettings = motionPresetMap[motionPreset] || motionPresetMap.refined;
const scaleMotionVector = (vector = {}, multiplier = 1) => Object.fromEntries(
    Object.entries(vector).map(([key, value]) => [key, typeof value === 'number' ? value * multiplier : value]),
);
const resolvedScrollAnimationPresets = Object.fromEntries(
    Object.entries(scrollAnimationPresets).map(([name, preset]) => [
        name,
        {
            from: scaleMotionVector(preset.from, motionSettings.offsetMultiplier),
            to: preset.to,
            duration: Number((preset.duration * motionSettings.durationMultiplier).toFixed(2)),
        },
    ]),
);

const initScrollAnimations = () => {
    if (prefersReducedMotion || motionPreset === 'none') return;

    document.querySelectorAll('[data-animate], .animate-on-scroll[data-animation]').forEach((element) => {
        if (element.dataset.animationBound === 'true') return;

        const animationName = element.dataset.animate || element.dataset.animation || 'fade-up';
        const preset = resolvedScrollAnimationPresets[animationName] || resolvedScrollAnimationPresets['fade-up'];
        const delay = Number.parseInt(element.dataset.delay || element.dataset.animateDelay || '0', 10) || 0;

        element.dataset.animationBound = 'true';

        gsap.fromTo(element, {
            autoAlpha: 0,
            ...preset.from,
        }, {
            autoAlpha: 1,
            ...preset.to,
            duration: preset.duration,
            delay: delay / 1000,
            ease: 'power2.out',
            clearProps: 'opacity,visibility,transform',
            scrollTrigger: {
                trigger: element,
                start: motionSettings.triggerStart,
                once: true,
            },
        });
    });
};

// ─── Hero Slider Initialization ──────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    // Initialize Lucide icons
    createIcons({ icons });

    document.querySelectorAll('.hero-swiper').forEach(el => {
        const captionEl = el.querySelector('[data-hero-caption-display]');
        const updateCaption = (swiper) => {
            if (!captionEl) return;
            const slide = swiper?.slides?.[swiper.activeIndex];
            if (!slide) return;
            const caption = slide.dataset.heroCaption || slide.querySelector('img')?.getAttribute('alt') || '';
            captionEl.textContent = caption;
        };

        new Swiper(el, {
            modules: [Pagination, SwiperNavigation, Autoplay, Keyboard, EffectFade],
            slidesPerView: 1,
            loop: true,
            speed: 1000,
            effect: 'fade',
            fadeEffect: { crossFade: true },
            keyboard: { enabled: true, onlyInViewport: true },
            autoplay: (prefersReducedMotion || motionPreset === 'none') ? false : { delay: 5000, disableOnInteraction: false, pauseOnMouseEnter: true },
            pagination: { el: el.querySelector('.hero-pagination'), clickable: true },
            navigation: {
                nextEl: el.querySelector('.hero-next'),
                prevEl: el.querySelector('.hero-prev'),
            },
            on: {
                init: function () {
                    updateCaption(this);
                },
                slideChange: function () {
                    updateCaption(this);
                },
            }
        });
    });

    initScrollAnimations();

    if (prefersReducedMotion || motionPreset === 'none') {
        document.querySelectorAll('[data-hero-video]').forEach((video) => {
            if (!(video instanceof window.HTMLVideoElement)) return;
            video.pause();
            video.removeAttribute('autoplay');
            video.currentTime = 0;
        });
    }
});

// ─── Lenis Smooth Scroll ─────────────────────────────────────────────────────
if (!prefersReducedMotion && motionPreset !== 'none') {
    const lenis = new Lenis({
        duration: motionSettings.lenisDuration,
        easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
        smoothWheel: true,
    });

    lenis.on('scroll', ScrollTrigger.update);
    gsap.ticker.add((time) => lenis.raf(time * 1000));
    gsap.ticker.lagSmoothing(0);

    window.lenis = lenis;
}

// Alpine plugins
Alpine.plugin(collapse);
Alpine.plugin(intersect);
Alpine.plugin(focus);
window.Alpine = Alpine;

// ─── Popup Alpine Component ───────────────────────────────────────────────────
Alpine.data('lushPopup', (id, suppressDays, triggerType, delaySeconds, scrollPercent, excludedPages, showOnMobile, showToReturning) => ({
    visible: false,

    init() {
        if (this._getCookie(`popup_dismissed_${id}`)) return;

        const path = window.location.pathname;
        if (Array.isArray(excludedPages) && excludedPages.some(p => p && path.startsWith(p))) return;

        if (!showOnMobile && window.innerWidth < 768) return;

        const isReturning = localStorage.getItem('lush_site_visited') === '1';
        if (!showToReturning && isReturning) return;

        localStorage.setItem('lush_site_visited', '1');

        if (triggerType === 'delay') {
            setTimeout(() => { this.visible = true; }, (delaySeconds || 5) * 1000);
        } else if (triggerType === 'scroll_percent') {
            const handler = () => {
                const docHeight = document.body.scrollHeight - window.innerHeight;
                if (docHeight <= 0) return;
                const pct = (window.scrollY / docHeight) * 100;
                if (pct >= (scrollPercent || 50)) {
                    this.visible = true;
                    window.removeEventListener('scroll', handler);
                }
            };
            window.addEventListener('scroll', handler, { passive: true });
        } else if (triggerType === 'exit_intent') {
            const handler = (e) => {
                if (e.clientY <= 0) {
                    this.visible = true;
                    document.removeEventListener('mouseleave', handler);
                }
            };
            document.addEventListener('mouseleave', handler);
        }
    },

    dismiss() {
        this.visible = false;
        if (suppressDays > 0) {
            const expires = new Date();
            expires.setDate(expires.getDate() + suppressDays);
            document.cookie = `popup_dismissed_${id}=1; expires=${expires.toUTCString()}; path=/; SameSite=Lax; Secure`;
        }
    },

    _getCookie(name) {
        return document.cookie.split(';').some(c => c.trim().startsWith(name + '='));
    },
}));

// ─── Cookie Consent Alpine Component ─────────────────────────────────────────
Alpine.data('cookieConsent', () => ({
    visible: false,

    init() {
        if (!document.cookie.split(';').some(c => c.trim().startsWith('lush_cookie_consent='))) {
            setTimeout(() => { this.visible = true; }, 2000);
        }
    },

    accept() {
        this.visible = false;
        const expires = new Date();
        expires.setFullYear(expires.getFullYear() + 1);
        document.cookie = `lush_cookie_consent=accepted; expires=${expires.toUTCString()}; path=/; SameSite=Lax; Secure`;
    },

    decline() {
        this.visible = false;
        const expires = new Date();
        expires.setDate(expires.getDate() + 30);
        document.cookie = `lush_cookie_consent=declined; expires=${expires.toUTCString()}; path=/; SameSite=Lax; Secure`;
    },
}));

// ─── Interactive Map Alpine Component (Google Maps) ─────────────────────────
Alpine.data('interactiveMap', ({ mapId, markers: _markers, filters: _filters, center, zoom, pinColor, mapMode: _mapMode, apiKey }) => ({
    googleMap: null,
    markers: _markers,
    filters: _filters,
    googleMarkers: [],
    infoWindow: null,
    activeFilter: null,
    activeDetail: null,

    initMap() {
        if (window.google?.maps) {
            this._buildMap();
            return;
        }

        if (!apiKey) return;

        const cbName = '__gmInit_' + mapId.replace(/[^a-zA-Z0-9]/g, '');
        window[cbName] = () => {
            delete window[cbName];
            this._buildMap();
        };

        const script = document.createElement('script');
        script.src = `https://maps.googleapis.com/maps/api/js?key=${apiKey}&callback=${cbName}&loading=async`;
        script.async = true;
        script.defer = true;
        document.head.appendChild(script);
    },

    _buildMap() {
        this.googleMap = new google.maps.Map(document.getElementById(mapId), {
            center: { lat: center[0], lng: center[1] },
            zoom: zoom,
            scrollwheel: false,
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: false,
            zoomControlOptions: { position: google.maps.ControlPosition.RIGHT_CENTER },
            styles: [
                { featureType: 'poi', stylers: [{ visibility: 'off' }] },
                { featureType: 'transit', stylers: [{ visibility: 'off' }] },
                { featureType: 'water', stylers: [{ color: '#c9e2d4' }] },
                { featureType: 'landscape', stylers: [{ color: '#f0f5f1' }] },
                { featureType: 'road.highway', elementType: 'geometry', stylers: [{ color: '#daddd8' }] },
                { featureType: 'road.local', elementType: 'geometry', stylers: [{ color: '#ffffff' }] },
            ],
        });

        this.infoWindow = new google.maps.InfoWindow();
        this._renderMarkers(this.markers);
    },

    _createIconUrl(color, size = 'large') {
        const w = size === 'large' ? 30 : 22;
        const h = size === 'large' ? 42 : 32;
        const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="${w}" height="${h}" viewBox="0 0 24 36"><path d="M12 0C5.4 0 0 5.4 0 12c0 9 12 24 12 24s12-15 12-24C24 5.4 18.6 0 12 0z" fill="${color}" stroke="%23fff" stroke-width="1.5"/><circle cx="12" cy="11" r="5" fill="%23fff" opacity="0.9"/><circle cx="12" cy="11" r="3" fill="${color}"/></svg>`;
        return 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svg.replace(/%23/g, '#'));
    },

    _renderMarkers(list) {
        this.googleMarkers.forEach(m => m.setMap(null));
        this.googleMarkers = [];

        list.forEach(m => {
            const isCity = m.type === 'city';
            const iconSize = isCity ? 'large' : 'small';
            const w = isCity ? 30 : 22;
            const h = isCity ? 42 : 32;

            const marker = new google.maps.Marker({
                position: { lat: m.lat, lng: m.lng },
                map: this.googleMap,
                title: m.name,
                icon: {
                    url: this._createIconUrl(pinColor, iconSize),
                    scaledSize: new google.maps.Size(w, h),
                    anchor: new google.maps.Point(w / 2, h),
                },
            });

            marker._lushData = m;

            marker.addListener('click', () => {
                const services = m.services ? m.services.split(',').filter(Boolean).map(s => s.trim()) : [];
                let popupHtml = `<div style="min-width:220px;max-width:280px;padding:14px 16px;font-family:'Times New Roman',serif;">`;
                popupHtml += `<h3 style="font-family:'Playfair Display',serif;font-size:15px;font-weight:700;margin:0 0 6px;color:#1E4A2D;">${this._escHtml(m.heading)}</h3>`;
                popupHtml += `<p style="font-size:13px;color:#555;margin:0 0 8px;line-height:1.4;">${this._escHtml(m.desc)}</p>`;
                if (services.length > 0) {
                    popupHtml += `<div style="display:flex;flex-wrap:wrap;gap:4px;margin-bottom:8px;">`;
                    services.forEach(s => {
                        popupHtml += `<span style="font-size:11px;background:#f0f5f1;color:#1E4A2D;padding:2px 8px;border:1px solid #d4e5d8;">${this._escHtml(s)}</span>`;
                    });
                    popupHtml += `</div>`;
                }
                popupHtml += `<a href="${this._escHtml(m.cta_url)}" style="display:inline-flex;align-items:center;gap:6px;background:#1E4A2D;color:#fff;padding:8px 16px;font-size:13px;font-weight:600;text-decoration:none;transition:background 0.2s;" onmouseover="this.style.background='#163823'" onmouseout="this.style.background='#1E4A2D'">`;
                popupHtml += `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6A19.79 19.79 0 012.12 4.18 2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>`;
                popupHtml += `${this._escHtml(m.cta_text)}</a>`;
                popupHtml += `</div>`;

                this.infoWindow.setContent(popupHtml);
                this.infoWindow.open(this.googleMap, marker);
                this.activeDetail = m;
            });

            this.googleMarkers.push(marker);
        });
    },

    filterTo(f) {
        this.activeFilter = f.slug;
        const marker = this.googleMarkers.find(m => m._lushData.slug === f.slug);

        if (marker) {
            const data = marker._lushData;
            this.activeDetail = data;

            if (data.type === 'city' && data.hoods && data.hoods.length > 0) {
                const hoodMarkers = data.hoods.map(h => ({
                    name: h.name,
                    lat: h.lat,
                    lng: h.lng,
                    type: 'neighborhood',
                    slug: h.slug,
                    heading: h.name + ', ' + data.name,
                    desc: 'Professional landscaping services available in ' + h.name + '. Contact us to begin your project inquiry.',
                    cta_text: data.cta_text,
                    cta_url: data.cta_url,
                    services: data.services,
                    hoods: [],
                }));

                this._renderMarkers([data, ...hoodMarkers]);
                this.googleMap.panTo({ lat: data.lat, lng: data.lng });
                this.googleMap.setZoom(12);
            } else {
                this.googleMap.panTo({ lat: data.lat, lng: data.lng });
                this.googleMap.setZoom(14);
                google.maps.event.trigger(marker, 'click');
            }
        }
    },

    resetFilter() {
        this.activeFilter = null;
        this.activeDetail = null;
        if (this.infoWindow) this.infoWindow.close();
        this._renderMarkers(this.markers);
        this.googleMap.panTo({ lat: center[0], lng: center[1] });
        this.googleMap.setZoom(zoom);
    },

    _escHtml(str) {
        if (!str) return '';
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    },
}));

// ─── 3D Globe Alpine Component (Enterprise) ────────────────────────────────
Alpine.data('lushGlobe', () => ({
    canvas: null, ctx: null,
    w: 0, h: 0, r: 0, dpr: 1,
    rotY: 0, rotX: -0.3,
    velY: 0, velX: 0,
    dragging: false, dragX: 0, dragY: 0, dragRotY: 0, dragRotX: 0,
    lastMoveTime: 0, lastMoveX: 0, lastMoveY: 0,
    running: false, rafId: null,
    hoveredCity: null,
    projections: [],
    _resizeFn: null,
    _reduced: false,
    _idleTimer: null,
    _idle: true,
    _time: 0,

    // Approximate land regions [latMin, latMax, lngMin, lngMax]
    _land: [
        [25,75,-170,-50],[7,25,-120,-75],[-57,12,-82,-34],
        [35,72,-12,40],[-35,37,-20,52],[40,75,40,180],
        [8,55,72,135],[5,35,68,90],[12,40,30,60],
        [30,45,125,145],[-45,-10,112,155],[-8,5,95,140],
        [50,60,-8,2],[55,72,5,30],[60,84,-55,-15],[55,72,-170,-130],
    ],

    _isLand(la, ln) {
        for (const r of this._land) {
            if (la >= r[0] && la <= r[1] && ln >= r[2] && ln <= r[3]) return true;
        }
        return false;
    },

    // Precomputed grid points (computed once)
    _grid: null,
    _buildGrid() {
        this._grid = [];
        for (let la = -80; la <= 80; la += 6) {
            for (let ln = -180; ln < 180; ln += 6) {
                const land = this._isLand(la, ln);
                const phi = (90 - la) * 0.017453;
                const theta = (ln + 180) * 0.017453;
                const sp = Math.sin(phi);
                this._grid.push({
                    x: sp * Math.cos(theta),
                    y: Math.cos(phi),
                    z: sp * Math.sin(theta),
                    land,
                });
            }
        }
    },

    init() {
        this.canvas = this.$refs.globeCanvas;
        if (!this.canvas) return;
        this.ctx = this.canvas.getContext('2d');
        this._reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        this._buildGrid();
        this._resize();

        this._resizeFn = () => { this._resize(); this._render(); };
        this._mdownFn = (e) => this._mdown(e);
        this._mmoveFn = (e) => this._mmove(e);
        this._mupFn = () => this._mup();
        this._mleaveFn = () => { if (!this.dragging) this._mleave(); };
        this._tstartFn = (e) => this._tstart(e);
        this._tmoveFn = (e) => this._tmove(e);
        this._clickFn = (e) => this._click(e);

        window.addEventListener('resize', this._resizeFn);
        window.addEventListener('mousemove', this._mmoveFn);
        window.addEventListener('mouseup', this._mupFn);
        this.canvas.addEventListener('mousedown', this._mdownFn);
        this.canvas.addEventListener('mouseleave', this._mleaveFn);
        this.canvas.addEventListener('touchstart', this._tstartFn, { passive: false });
        this.canvas.addEventListener('touchmove', this._tmoveFn, { passive: false });
        this.canvas.addEventListener('touchend', this._mupFn);
        this.canvas.addEventListener('click', this._clickFn);

        this.$watch('activeCity', () => { if (!this.running) this._render(); });
        this._render();
    },

    start() {
        if (this.running) return;
        this.running = true;
        this._loop();
    },

    destroy() {
        this.running = false;
        if (this.rafId) cancelAnimationFrame(this.rafId);
        window.removeEventListener('resize', this._resizeFn);
        window.removeEventListener('mousemove', this._mmoveFn);
        window.removeEventListener('mouseup', this._mupFn);
        if (this.canvas) {
            this.canvas.removeEventListener('mousedown', this._mdownFn);
            this.canvas.removeEventListener('mouseleave', this._mleaveFn);
            this.canvas.removeEventListener('touchstart', this._tstartFn);
            this.canvas.removeEventListener('touchmove', this._tmoveFn);
            this.canvas.removeEventListener('touchend', this._mupFn);
            this.canvas.removeEventListener('click', this._clickFn);
        }
    },

    _resize() {
        const rect = this.canvas.getBoundingClientRect();
        this.dpr = Math.min(window.devicePixelRatio || 1, 2);
        this.w = rect.width; this.h = rect.height;
        this.canvas.width = this.w * this.dpr;
        this.canvas.height = this.h * this.dpr;
        this.ctx.setTransform(this.dpr, 0, 0, this.dpr, 0, 0);
        this.r = Math.min(this.w, this.h) * 0.40;
    },

    _loop() {
        if (!this.running) return;
        this._time++;

        // Auto-rotate when idle
        if (this._idle && !this._reduced) {
            this.rotY += 0.0006;
        }

        // Apply velocity with friction
        if (!this.dragging) {
            this.rotY += this.velY;
            this.rotX += this.velX;
            this.velY *= 0.95;
            this.velX *= 0.95;
            if (Math.abs(this.velY) < 0.00001) this.velY = 0;
            if (Math.abs(this.velX) < 0.00001) this.velX = 0;
        }

        // Clamp X rotation
        this.rotX = Math.max(-1.2, Math.min(1.2, this.rotX));

        this._render();
        this.rafId = requestAnimationFrame(() => this._loop());
    },

    _proj(x, y, z) {
        const cy = Math.cos(this.rotY), sy = Math.sin(this.rotY);
        const rx = x * cy - z * sy;
        const rz = x * sy + z * cy;
        const cx = Math.cos(this.rotX), sx = Math.sin(this.rotX);
        const ty = y * cx - rz * sx;
        const tz = y * sx + rz * cx;
        return [this.w / 2 + rx * this.r, this.h / 2 - ty * this.r, tz];
    },

    _render() {
        const { ctx, w, h, r } = this;
        if (!ctx || !w) return;
        ctx.clearRect(0, 0, w, h);
        const cx = w / 2, cy = h / 2;

        // Layer 1: Outer atmospheric glow
        const g1 = ctx.createRadialGradient(cx, cy, r * 0.95, cx, cy, r * 1.6);
        g1.addColorStop(0, 'rgba(30, 74, 45, 0.07)');
        g1.addColorStop(0.5, 'rgba(30, 74, 45, 0.03)');
        g1.addColorStop(1, 'rgba(30, 74, 45, 0)');
        ctx.fillStyle = g1;
        ctx.fillRect(0, 0, w, h);

        // Layer 2: Sphere body with 3D lighting
        const g2 = ctx.createRadialGradient(cx - r * 0.25, cy - r * 0.25, r * 0.05, cx, cy, r);
        g2.addColorStop(0, 'rgba(30, 74, 45, 0.08)');
        g2.addColorStop(0.5, 'rgba(30, 74, 45, 0.04)');
        g2.addColorStop(0.85, 'rgba(30, 74, 45, 0.015)');
        g2.addColorStop(1, 'rgba(30, 74, 45, 0.06)');
        ctx.beginPath();
        ctx.arc(cx, cy, r, 0, Math.PI * 2);
        ctx.fillStyle = g2;
        ctx.fill();

        // Layer 3: Rim light (edge glow)
        ctx.beginPath();
        ctx.arc(cx, cy, r, 0, Math.PI * 2);
        ctx.strokeStyle = 'rgba(30, 74, 45, 0.12)';
        ctx.lineWidth = 1.5;
        ctx.stroke();

        // Layer 4: Grid dots (land vs ocean)
        this._drawDots();

        // Layer 5: Arcs between cities
        this._drawArcs();

        // Layer 6: City markers
        this._drawMarkers();

        // Layer 7: Specular highlight (top-left reflection)
        const g3 = ctx.createRadialGradient(cx - r * 0.3, cy - r * 0.35, 0, cx - r * 0.3, cy - r * 0.35, r * 0.6);
        g3.addColorStop(0, 'rgba(255, 255, 255, 0.06)');
        g3.addColorStop(1, 'rgba(255, 255, 255, 0)');
        ctx.beginPath();
        ctx.arc(cx, cy, r, 0, Math.PI * 2);
        ctx.fillStyle = g3;
        ctx.fill();
    },

    _drawDots() {
        const { ctx } = this;
        if (!this._grid) return;

        for (const pt of this._grid) {
            const [sx, sy, sz] = this._proj(pt.x, pt.y, pt.z);
            if (sz < 0.05) continue;

            const depth = sz;
            if (pt.land) {
                const a = 0.08 + depth * 0.22;
                const sz2 = 0.8 + depth * 0.5;
                ctx.beginPath();
                ctx.arc(sx, sy, sz2, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(30, 74, 45, ${a})`;
                ctx.fill();
            } else {
                const a = 0.02 + depth * 0.04;
                ctx.beginPath();
                ctx.arc(sx, sy, 0.4, 0, Math.PI * 2);
                ctx.fillStyle = `rgba(30, 74, 45, ${a})`;
                ctx.fill();
            }
        }
    },

    _drawArcs() {
        const { ctx } = this;
        const cities = this.cities || [];
        if (cities.length < 2) return;

        const t = this._time * 0.02;

        for (let i = 0; i < cities.length; i++) {
            const c1 = cities[i];
            const c2 = cities[(i + 1) % cities.length];
            const steps = 30;
            const pts = [];

            for (let s = 0; s <= steps; s++) {
                const frac = s / steps;
                const la = c1.lat + (c2.lat - c1.lat) * frac;
                const ln = c1.lng + (c2.lng - c1.lng) * frac;
                const lift = 1 + Math.sin(frac * Math.PI) * 0.06;
                const phi = (90 - la) * 0.017453;
                const theta = (ln + 180) * 0.017453;
                const sp = Math.sin(phi);
                const [sx, sy, sz] = this._proj(sp * Math.cos(theta) * lift, Math.cos(phi) * lift, sp * Math.sin(theta) * lift);
                if (sz > 0) pts.push([sx, sy, sz, frac]);
            }

            if (pts.length < 3) continue;

            // Draw arc with animated gradient
            for (let j = 1; j < pts.length; j++) {
                const p1 = pts[j - 1], p2 = pts[j];
                const segAlpha = Math.sin(p1[3] * Math.PI);
                const wave = (Math.sin(t - p1[3] * 6 + i * 2) * 0.5 + 0.5);
                const a = segAlpha * (0.06 + wave * 0.14) * Math.min(p1[2], p2[2]);
                ctx.beginPath();
                ctx.moveTo(p1[0], p1[1]);
                ctx.lineTo(p2[0], p2[1]);
                ctx.strokeStyle = `rgba(30, 74, 45, ${a})`;
                ctx.lineWidth = 1 + wave * 0.5;
                ctx.stroke();
            }
        }
    },

    _drawMarkers() {
        const { ctx } = this;
        const cities = this.cities || [];
        const activeSlug = this.activeCity;
        const t = this._time;

        this.projections = [];

        // Draw non-active first, then active on top
        const sorted = [...cities].sort((a, b) =>
            (a.slug === activeSlug ? 1 : 0) - (b.slug === activeSlug ? 1 : 0)
        );

        sorted.forEach(city => {
            const phi = (90 - city.lat) * 0.017453;
            const theta = (city.lng + 180) * 0.017453;
            const sp = Math.sin(phi);
            const [sx, sy, sz] = this._proj(sp * Math.cos(theta), Math.cos(phi), sp * Math.sin(theta));
            const vis = sz > 0.1;

            this.projections.push({ slug: city.slug, sx, sy, vis });
            if (!vis) return;

            const active = city.slug === activeSlug;
            const hovered = this.hoveredCity === city.slug;
            const hl = active || hovered;
            const pulse = this._reduced ? 0.5 : (Math.sin(t * 0.05 + city.lat * 0.3) * 0.5 + 0.5);

            // Outer glow rings (always visible, more prominent when highlighted)
            if (hl) {
                // Ring 3 (largest, faintest)
                const r3 = 16 + pulse * 10;
                const g3 = ctx.createRadialGradient(sx, sy, 0, sx, sy, r3);
                g3.addColorStop(0, 'rgba(30, 74, 45, 0.12)');
                g3.addColorStop(1, 'rgba(30, 74, 45, 0)');
                ctx.beginPath(); ctx.arc(sx, sy, r3, 0, Math.PI * 2);
                ctx.fillStyle = g3; ctx.fill();

                // Ring 2
                const r2 = 9 + pulse * 4;
                const g2 = ctx.createRadialGradient(sx, sy, 0, sx, sy, r2);
                g2.addColorStop(0, 'rgba(30, 74, 45, 0.2)');
                g2.addColorStop(1, 'rgba(30, 74, 45, 0)');
                ctx.beginPath(); ctx.arc(sx, sy, r2, 0, Math.PI * 2);
                ctx.fillStyle = g2; ctx.fill();
            } else {
                // Subtle glow for non-active
                const rg = 6 + pulse * 2;
                const gg = ctx.createRadialGradient(sx, sy, 0, sx, sy, rg);
                gg.addColorStop(0, `rgba(30, 74, 45, ${0.06 + pulse * 0.04})`);
                gg.addColorStop(1, 'rgba(30, 74, 45, 0)');
                ctx.beginPath(); ctx.arc(sx, sy, rg, 0, Math.PI * 2);
                ctx.fillStyle = gg; ctx.fill();
            }

            // Core marker
            const ms = hl ? 5 : 3;
            ctx.beginPath(); ctx.arc(sx, sy, ms, 0, Math.PI * 2);
            ctx.fillStyle = hl ? '#1E4A2D' : `rgba(30, 74, 45, ${0.4 + sz * 0.3})`;
            ctx.fill();

            // Inner bright dot
            ctx.beginPath(); ctx.arc(sx, sy, 1.5, 0, Math.PI * 2);
            ctx.fillStyle = hl ? '#fff' : 'rgba(255,255,255,0.7)';
            ctx.fill();

            // Label
            if (hl) {
                const font = '600 11px "Playfair Display", serif';
                ctx.font = font;
                const tw = ctx.measureText(city.name).width;
                const lx = sx + ms + 10, ly = sy + 4;
                const pad = 5;

                // Label background
                ctx.fillStyle = 'rgba(30, 74, 45, 0.92)';
                ctx.beginPath();
                const bx = lx - pad, by = ly - 12, bw = tw + pad * 2, bh = 18;
                ctx.moveTo(bx + 3, by);
                ctx.lineTo(bx + bw - 3, by);
                ctx.quadraticCurveTo(bx + bw, by, bx + bw, by + 3);
                ctx.lineTo(bx + bw, by + bh - 3);
                ctx.quadraticCurveTo(bx + bw, by + bh, bx + bw - 3, by + bh);
                ctx.lineTo(bx + 3, by + bh);
                ctx.quadraticCurveTo(bx, by + bh, bx, by + bh - 3);
                ctx.lineTo(bx, by + 3);
                ctx.quadraticCurveTo(bx, by, bx + 3, by);
                ctx.fill();

                ctx.fillStyle = '#fff';
                ctx.textAlign = 'left';
                ctx.fillText(city.name, lx, ly);
            }
        });
    },

    _closest(mx, my) {
        let best = null, bd = 28;
        for (const p of this.projections) {
            if (!p.vis) continue;
            const d = Math.hypot(p.sx - mx, p.sy - my);
            if (d < bd) { bd = d; best = p; }
        }
        return best;
    },

    _resetIdle() {
        this._idle = false;
        clearTimeout(this._idleTimer);
        this._idleTimer = setTimeout(() => { this._idle = true; }, 3000);
    },

    _mdown(e) {
        this.dragging = true;
        this.dragX = e.clientX; this.dragY = e.clientY;
        this.dragRotY = this.rotY; this.dragRotX = this.rotX;
        this.velY = 0; this.velX = 0;
        this.lastMoveTime = Date.now();
        this.lastMoveX = e.clientX; this.lastMoveY = e.clientY;
        this._resetIdle();
        e.preventDefault();
    },

    _mmove(e) {
        if (this.dragging) {
            const dx = e.clientX - this.dragX, dy = e.clientY - this.dragY;
            this.rotY = this.dragRotY + dx * 0.005;
            this.rotX = this.dragRotX - dy * 0.005;

            const now = Date.now(), dt = Math.max(now - this.lastMoveTime, 1);
            this.velY = (e.clientX - this.lastMoveX) * 0.003 / (dt / 16);
            this.velX = -(e.clientY - this.lastMoveY) * 0.003 / (dt / 16);
            this.lastMoveTime = now;
            this.lastMoveX = e.clientX; this.lastMoveY = e.clientY;
            return;
        }

        // Hover detection
        const rect = this.canvas.getBoundingClientRect();
        const city = this._closest(e.clientX - rect.left, e.clientY - rect.top);
        if (city) {
            this.canvas.style.cursor = 'pointer';
            this.hoveredCity = city.slug;
            this.setActive(city.slug);
        } else if (this.hoveredCity) {
            this.canvas.style.cursor = 'grab';
            this.hoveredCity = null;
            this.clearActive();
        }
    },

    _mup() {
        if (this.dragging) {
            this.dragging = false;
            this._resetIdle();
        }
    },

    _mleave() {
        if (this.hoveredCity) {
            this.hoveredCity = null;
            this.canvas.style.cursor = 'grab';
            this.clearActive();
        }
    },

    _tstart(e) {
        if (e.touches.length !== 1) return;
        const t = e.touches[0];
        this.dragging = true;
        this.dragX = t.clientX; this.dragY = t.clientY;
        this.dragRotY = this.rotY; this.dragRotX = this.rotX;
        this.velY = 0; this.velX = 0;
        this._resetIdle();
        e.preventDefault();
    },

    _tmove(e) {
        if (!this.dragging || e.touches.length !== 1) return;
        const t = e.touches[0];
        this.rotY = this.dragRotY + (t.clientX - this.dragX) * 0.005;
        this.rotX = this.dragRotX - (t.clientY - this.dragY) * 0.005;
        e.preventDefault();
    },

    _click(e) {
        const rect = this.canvas.getBoundingClientRect();
        const city = this._closest(e.clientX - rect.left, e.clientY - rect.top);
        if (city) this.setActive(city.slug);
    },
}));

Alpine.data('themeHeaderShell', ({ compactOnScroll = true, breakpoint = 1024 } = {}) => ({
    mobileOpen: false,
    isScrolled: false,
    compactOnScroll,
    breakpoint,

    init() {
        const syncState = () => {
            this.isScrolled = window.scrollY > 70;

            if (window.innerWidth >= this.breakpoint) {
                this.mobileOpen = false;
            }
        };

        syncState();
        this._onScroll = () => syncState();
        this._onResize = () => syncState();

        window.addEventListener('scroll', this._onScroll, { passive: true });
        window.addEventListener('resize', this._onResize);

        this.$watch('mobileOpen', (value) => {
            document.documentElement.classList.toggle('overflow-hidden', value);
            document.body.classList.toggle('overflow-hidden', value);
        });
    },

    toggleMobile() {
        this.mobileOpen = !this.mobileOpen;
    },

    closeMobile() {
        this.mobileOpen = false;
    },
}));

Alpine.start();

// ─── Lucide Icons ─────────────────────────────────────────────────────────────
window.renderIcons = function() {
    createIcons({ icons, attrs: { 'stroke-width': 1.5 } });
}

document.addEventListener('DOMContentLoaded', window.renderIcons);

// ─── GSAP: Luxury Reveal Animations ──────────────────────────────────────────
if (!prefersReducedMotion && motionPreset !== 'none') {
    document.addEventListener('DOMContentLoaded', () => {
        gsap.utils.toArray('.reveal').forEach(el => {
            gsap.to(el, {
                y: 0,
                opacity: 1,
                duration: Number((1.2 * motionSettings.durationMultiplier).toFixed(2)),
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: el,
                    start: motionSettings.triggerStart,
                    once: true,
                },
            });
        });

        // Hero heading: already visible (opacity: 1) for LCP, just animate the subtle translateY
        gsap.utils.toArray('.reveal-hero').forEach(el => {
            gsap.to(el, {
                y: 0,
                duration: Number((1.0 * motionSettings.durationMultiplier).toFixed(2)),
                ease: 'power3.out',
                delay: motionSettings.heroDelay,
            });
        });

        gsap.utils.toArray('.reveal-left, .reveal-right').forEach(el => {
            gsap.to(el, {
                x: 0,
                opacity: 1,
                duration: Number((1.2 * motionSettings.durationMultiplier).toFixed(2)),
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: el,
                    start: motionSettings.triggerStart,
                    once: true,
                },
            });
        });

        gsap.utils.toArray('.reveal-stagger').forEach(parent => {
            const children = parent.children;
            if (!children.length) return;
            gsap.fromTo(children,
                { y: 40 * motionSettings.offsetMultiplier, opacity: 0 },
                {
                    y: 0,
                    opacity: 1,
                    duration: Number((1.0 * motionSettings.durationMultiplier).toFixed(2)),
                    stagger: 0.15,
                    ease: 'power3.out',
                    scrollTrigger: {
                        trigger: parent,
                        start: motionSettings.staggerTriggerStart,
                        once: true,
                    },
                }
            );
        });

        // Parallax hero image
        gsap.utils.toArray('.parallax-hero').forEach(el => {
            gsap.to(el, {
                yPercent: 15,
                ease: 'none',
                scrollTrigger: {
                    trigger: el.closest('section') || el,
                    start: 'top top',
                    end: 'bottom top',
                    scrub: motionSettings.parallaxScrub,
                },
            });
        });

        ScrollTrigger.refresh();
    });
}

// ─── Testimonials Swiper ──────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    const el = document.querySelector('.testimonials-swiper');
    if (el) {
        new Swiper('.testimonials-swiper', {
            modules: [Pagination, Autoplay, Keyboard],
            slidesPerView: 1,
            spaceBetween: 24,
            loop: true,
            keyboard: { enabled: true, onlyInViewport: true },
            autoplay: (prefersReducedMotion || motionPreset === 'none') ? false : { delay: 6000, disableOnInteraction: true },
            pagination: { el: '.testimonials-pagination', clickable: true },
            breakpoints: {
                640:  { slidesPerView: 2 },
                1024: { slidesPerView: 3 },
            },
        });
    }
});

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.portfolio-gallery-swiper').forEach((el) => {
        const columns = parseInt(el.dataset.columns || '3', 10);
        const tabletSlides = Math.max(1, Math.min(2, columns));
        const desktopSlides = Math.max(1, Math.min(4, columns));

        new Swiper(el, {
            modules: [Pagination, SwiperNavigation, Keyboard, Autoplay],
            slidesPerView: 1.1,
            spaceBetween: 18,
            loop: false,
            keyboard: { enabled: true, onlyInViewport: true },
            autoplay: (prefersReducedMotion || motionPreset === 'none') ? false : { delay: 7000, disableOnInteraction: true },
            pagination: { el: el.querySelector('.portfolio-gallery-pagination'), clickable: true },
            navigation: {
                nextEl: el.querySelector('.portfolio-gallery-next'),
                prevEl: el.querySelector('.portfolio-gallery-prev'),
            },
            breakpoints: {
                640: { slidesPerView: tabletSlides, spaceBetween: 22 },
                1024: { slidesPerView: desktopSlides, spaceBetween: 28 },
            },
        });
    });
});

// ─── Phase 1: Designer JS Integration ──────────────────────────────────────

document.addEventListener('DOMContentLoaded', () => {
    // Portfolio horizontal scroll - FIXED calculation
    let mm = gsap.matchMedia();
    mm.add("(min-width: 1024px)", () => {
        const portfolioWrapper = document.querySelector('.portfolio-pin-wrapper');
        const portfolioTrack = document.getElementById('portfolio-track');
        
        if (!portfolioWrapper || !portfolioTrack || prefersReducedMotion) return;
        
        // Calculate exact scroll distance
        const getScrollAmount = () => {
            const track = document.getElementById('portfolio-track');
            if (!track) return 0;
            
            const wrapperStyle = window.getComputedStyle(portfolioWrapper);
            const trackStyle = window.getComputedStyle(track);
            
            const wrapperPaddingX = parseFloat(wrapperStyle.paddingLeft) + parseFloat(wrapperStyle.paddingRight);
            const trackGap = parseFloat(trackStyle.gap) || 40; // lg:gap-10 = 40px
            const cardCount = track.querySelectorAll('.mobile-swipe-item').length;
            
            const totalCardWidth = Array.from(track.querySelectorAll('.mobile-swipe-item'))
                .reduce((sum, card) => sum + card.offsetWidth, 0);
            const totalGaps = trackGap * (cardCount - 1);
            const totalWidth = totalCardWidth + totalGaps + wrapperPaddingX;
            
            return -(totalWidth - window.innerWidth);
        };
        
        const portfolioScroll = gsap.to(portfolioTrack, {
            x: getScrollAmount,
            ease: "none",
            scrollTrigger: {
                trigger: portfolioWrapper,
                pin: true,
                scrub: 1,
                start: "top top",
                end: () => {
                    const track = document.getElementById('portfolio-track');
                    if (!track) return "+=1000";
                    
                    const wrapperPaddingX = 96; // lg:px-12 * 2
                    const trackWidth = track.scrollWidth;
                    const viewportWidth = window.innerWidth;
                    
                    return `+=${Math.max(0, trackWidth - viewportWidth + wrapperPaddingX)}`;
                },
                invalidateOnRefresh: true,
                anticipatePin: 1
            }
        });
        
        ScrollTrigger.addEventListener('refresh', () => {
            portfolioScroll.scrollTrigger.end = () => {
                const track = document.getElementById('portfolio-track');
                if (!track) return "+=1000";
                const wrapperPaddingX = 96;
                const trackWidth = track.scrollWidth;
                const viewportWidth = window.innerWidth;
                return `+=${Math.max(0, trackWidth - viewportWidth + wrapperPaddingX)}`;
            };
        });
        
        return () => portfolioScroll.scrollTrigger.kill(); // Cleanup
    });

    // Portfolio navigation buttons (mobile + desktop fallback)
    const track = document.getElementById('portfolio-track');
    const prevBtn = document.getElementById('portfolio-prev');
    const nextBtn = document.getElementById('portfolio-next');
    
    if (track && prevBtn && nextBtn) {
        const getCardWidth = () => {
            const card = track.querySelector('.mobile-swipe-item');
            if (!card) return 300;
            const cardWidth = card.offsetWidth;
            const trackStyle = window.getComputedStyle(track);
            const gap = parseFloat(trackStyle.gap) || 20;
            return cardWidth + gap;
        };
        
        prevBtn.addEventListener('click', () => {
            track.scrollBy({ left: -getCardWidth(), behavior: 'smooth' });
        });
        nextBtn.addEventListener('click', () => {
            track.scrollBy({ left: getCardWidth(), behavior: 'smooth' });
        });
    }

    // Parallax
    gsap.utils.toArray('.parallax-img').forEach((img) => {
        if (prefersReducedMotion) {
            img.style.transform = 'none';
            return;
        }
        const speed = parseFloat(img.dataset.speed) || 0.15;
        gsap.to(img, {
            yPercent: speed * 100,
            ease: "none",
            scrollTrigger: { 
                trigger: img.parentElement, 
                start: "top bottom", 
                end: "bottom top", 
                scrub: true 
            }
        });
    });

    // Header scroll detection using Lenis scroll event
    const header = document.getElementById('siteHeader');
    if (header) {
        function syncHeader() {
            const scrollY = window.lenis ? window.lenis.scroll : window.scrollY;
            header.classList.toggle('is-scrolled', scrollY > 50);
        }
        
        if (window.lenis) {
            window.lenis.on('scroll', syncHeader);
        } else {
            window.addEventListener('scroll', syncHeader, { passive: true });
        }
        syncHeader();
    }

    // Anchor link handling with Lenis + header offset
    const headerOffset = 100;
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href === '#' || href === '') return;
            
            const target = document.querySelector(href);
            if (!target) return;
            
            e.preventDefault();
            
            // Close mobile menu if open (assuming it's managed via Alpine or similar)
            const mobileMenuBtn = document.querySelector('[aria-label="Toggle mobile menu"]');
            if (mobileMenuBtn && mobileMenuBtn.getAttribute('aria-expanded') === 'true') {
                mobileMenuBtn.click();
            }
            
            const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerOffset;
            
            if (window.lenis) {
                window.lenis.scrollTo(targetPosition, {
                    offset: 0,
                    duration: 1.2,
                    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
                });
            } else {
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Explicitly handle .gs-reveal for elements that aren't picked up by the global initScrollAnimations
    document.querySelectorAll('.gs-reveal:not(.is-revealed)').forEach(el => {
        if (prefersReducedMotion) {
            el.classList.add('is-revealed');
            return;
        }
        ScrollTrigger.create({
            trigger: el,
            start: 'top 90%',
            onEnter: () => el.classList.add('is-revealed'),
            once: true
        });
    });
});
