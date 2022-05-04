Google Analytics Widget
=======================

## Setting up

1. Setting and create key to access google api via google [devguide](https://developers.google.com/analytics/devguides/config/mgmt/v3/quickstart/service-php).

2. Put downloaded json-key to `/config/extensions/service-account-credentials.json`.

   > **Warning!** This file contents private key - don't add it to git repo even it is private. Add `config/extensions/service-account-credentials.json` to `.gitignore` file.

3. Enable google analytics api access via your key https://console.cloud.google.com/apis/library/analytics.googleapis.com

4. Now you have service account with email like \*\*\*@\*\*\*.iam.gserviceaccount.com. Go to [google analytics](https://analytics.google.com/analytics/web/) > *admin* > *manage access* and add this email to list.

You also can add extension to show analytics in admin panel: https://extensions.boltcms.io/package/bobdenotter-google-analytics-widget.

```bash
composer require bobdenotter/google-analytics-widget
```

On index admin page (/bolt) you can see panel with some diagrams (may be you need to wait some hours while analytics will be refreshed).

### Reference material

 - [Google Developers Console](https://console.developers.google.com/?hl=nl&pli=1)
 - [Hello Analytics API: PHP quickstart for service accounts](https://developers.google.com/analytics/devguides/config/mgmt/v3/quickstart/service-php)
 - [Ben Marshall - Google Analytics API Tutorial](https://benmarshall.me/google-analytics-api-tutorial/)
 - [Google - Dimensions & Metrics Explorer](https://developers.google.com/analytics/devguides/reporting/core/dimsmets)
 - [Sanwe3be - Display Top URLs With Google Analytics API](https://www.sanwebe.com/2013/05/top-viewed-pages-with-google-analytics-api)