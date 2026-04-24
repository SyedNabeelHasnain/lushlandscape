@php
    $gtmId = \App\Models\Setting::get('analytics_gtm_id', '');
    $fbPixelId = \App\Models\Setting::get('analytics_fb_pixel_id', '');
    $ga4Id = \App\Models\Setting::get('analytics_ga4_id', '');
@endphp

<script>
    // Initialize WMS DataLayer
    window.WMS_DataLayer = window.WMS_DataLayer || [];
    
    // Core push function
    window.WMS_DataLayer.push = function(eventData) {
        Array.prototype.push.call(window.WMS_DataLayer, eventData);
        
        // Dispatch custom DOM event for frontend components to hook into
        const customEvent = new CustomEvent('wms:datalayer:push', { detail: eventData });
        window.dispatchEvent(customEvent);

        // Forward to GTM if available
        if (window.dataLayer) {
            window.dataLayer.push(eventData);
        }

        // Forward to GA4 (gtag) if available
        if (window.gtag && eventData.event) {
            let params = Object.assign({}, eventData);
            delete params.event;
            window.gtag('event', eventData.event, params);
        }

        // Forward to Meta Pixel if available
        if (window.fbq && eventData.event) {
            // Map standard events to FB standard events where applicable
            const fbEventMap = {
                'page_view': 'PageView',
                'generate_lead': 'Lead',
                'contact': 'Contact',
                'form_submission': 'Lead'
            };
            const fbEventName = fbEventMap[eventData.event] || 'CustomEvent';
            
            let params = Object.assign({}, eventData);
            delete params.event;

            if (fbEventName === 'CustomEvent') {
                window.fbq('trackCustom', eventData.event, params);
            } else {
                window.fbq('track', fbEventName, params);
            }
        }
    };
</script>

@if($gtmId)
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','{{ $gtmId }}');</script>
<!-- End Google Tag Manager -->
@endif

@if($ga4Id)
<!-- Google Analytics 4 -->
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga4Id }}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  window.gtag = gtag;
  gtag('js', new Date());
  gtag('config', '{{ $ga4Id }}');
</script>
<!-- End Google Analytics 4 -->
@endif

@if($fbPixelId)
<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '{{ $fbPixelId }}');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id={{ $fbPixelId }}&ev=PageView&noscript=1"
/></noscript>
<!-- End Meta Pixel Code -->
@endif

<script>
    // Initial Page View Event
    window.addEventListener('DOMContentLoaded', () => {
        window.WMS_DataLayer.push({
            'event': 'page_view',
            'page_path': window.location.pathname,
            'page_title': document.title
        });
    });
</script>
