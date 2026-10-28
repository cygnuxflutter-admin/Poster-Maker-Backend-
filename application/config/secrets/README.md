# Firebase Service Account Credentials

Place your Firebase Service Account JSON file in this directory with the filename:
`firebase_service_account.json`

### Where to get this file:
1. Open the [Firebase Console](https://console.firebase.google.com/project/poster---text-art/settings/serviceaccounts/adminsdk).
2. Select the **poster---text-art** project.
3. Click **Generate new private key**.
4. Rename the downloaded file to `firebase_service_account.json` and place it here.

### Security:
- This directory is protected by `.htaccess` with `Require all denied`.
- `firebase_service_account.json` is listed in `.gitignore` and must NEVER be committed to version control.
