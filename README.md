# jivochat_to_crm
Create lead customer from Jivochat to zoho crm when new chat accepted

## Install
Upload folder `jivochat_to_crm` to wordpress plugins folder, and active plugin

## Requirements
- Jivo: go to admin https://admin.jivosite.com -> settings -> Integration Settings for Developers 
and point the Webhooks endpoint URL field to `http://[wordpresssite.domain]/index.php?jivochat-to-crm-endpoint=true`
<br/>Example Webhooks endpoint URL field: http://demo.pwawordpress.com/index.php?jivochat-to-crm-endpoint=true
- Zoho: get the token. See https://www.zoho.com/crm/help/api/using-authentication-token.html#Generate_Auth_Token
- Change the token that your own in `jivochat-to-crm.php` file at line 64( '$token="e683ab93b394e4b721e55ae1cd3eb3ce";')
