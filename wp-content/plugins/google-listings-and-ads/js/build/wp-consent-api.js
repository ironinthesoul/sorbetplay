(()=>{const e={statistics:["analytics_storage"],marketing:["ad_storage","ad_user_data","ad_personalization"]},t=()=>{if("function"==typeof wp_has_consent){void 0===window.wp_consent_type&&(window.wp_consent_type="optin");const t={};for(const[n,o]of Object.entries(e))if(""!==consent_api_get_cookie(window.consent_api.cookie_prefix+"_"+n)){const e=wp_has_consent(n)?"granted":"denied";o.forEach((n=>{t[n]=e}))}Object.keys(t).length>0&&window.gtag("consent","update",t)}};document.addEventListener("wp_listen_for_consent_change",(t=>{const n={},o=e[Object.keys(t.detail)[0]],a="allow"===Object.values(t.detail)[0]?"granted":"denied";void 0!==o&&(o.forEach((e=>{n[e]=a})),Object.keys(n).length>0&&window.gtag("consent","update",n))})),"loading"===document.readyState?document.addEventListener("DOMContentLoaded",t):t()})();