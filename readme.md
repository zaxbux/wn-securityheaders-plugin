# Security Headers

This October CMS plugin allows you to manage a variety of security HTTP headers for your application.

See your score at [securityheaders.com](https://securityheaders.com/)

## CSP Nonce
This plugin generates a cryptographic nonce (128 bits, base64 encoded) for each HTTP response. To use the CSP nonce, enable the `'nonce'` source for a CSP directive and include the `CSPNonce` component on the layout or page you want to apply the nonce. A page variable named `csp_conce` will contain the base64 encoded nonce.

```
<script nonce="{{ csp_nonce }}">
	// ...
</script>
```

## In Case Things Break
Enabling the Content Security Policy header or the Strict Transport Security (HSTS) header could break your site if they're not setup properly. In each case, there is a command to disable the headers.

To disable the CSP from the console:
```
artisan securityheaders:disable_csp
```

To disable HSTS from the console:
```
artisan securityheaders:disable_hsts
```

## Headers by Controller

Different headers are applied to different routes, based on the controller used. The **System** and **Backend** controllers have limited security headers to avoid breaking functionality. For exmaple, the backend would require adding the `unsafe-inline` directive, effectivley making a CSP policy useless, so that header is not added.

### System

The `System\Classes\SystemController` controller handles asset combining. These are the headers that may be sent:

 * Strict-Transport-Security
 * X-Frame-Options
 * X-Content-Type-Options
 * X-XSS-Protection

### Backend

The `Backend\Classes\BackendController` controller handles the backend CMS. These are the headers that may be sent:

 * Strict-Transport-Security
 * X-Frame-Options
 * X-Content-Type-Options
 * X-XSS-Protection

### CMS

The `Cms\Classes\CmsController` controller handles the frontend. These are the headers that may be sent:

 * Strict-Transport-Security
 * Referrer-Policy
 * Content-Security-Policy
 * X-Frame-Options
 * X-Content-Type-Options
 * X-XSS-Protection
 * Feature-Policy

---

## Obselete Headers

These headers are available for configuration in case legacy browsers need to be supported, but they are not recomended if you only support current browser verions.

### X-Frame-Options

The `X-Frame-Options` header has been obsoleted [by the `frame-ancestors` directive]((https://www.w3.org/TR/CSP2/#frame-ancestors-and-frame-options)) from CSP Level 2 for supporting browsers.

### X-XSS-Protection
This header is non-standard and support has been removed (or will never be present) in a majority of browsers. You can achieve better protection using a **Content Security Policy**. Currently supported in IE 11 and Safari ([caniuse.com](https://caniuse.com/#feat=mdn-http_headers_x-xss-protection)).

* [Never implemented in Firefox](https://bugzilla.mozilla.org/show_bug.cgi?id=528661)
* [Removed in Chrome 78](https://groups.google.com/a/chromium.org/forum/#!msg/blink-dev/TuYw-EZhO9g/blGViehIAwAJ)
* [Removed from Edge](https://blogs.windows.com/windowsexperience/2018/07/25/announcing-windows-10-insider-preview-build-17723-and-build-18204/)