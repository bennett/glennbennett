/**
 * Google Drive Picker for OnSong Backup Import
 *
 * Requires global config vars set in the view:
 *   GDRIVE_CLIENT_ID, GDRIVE_API_KEY, GDRIVE_APP_ID, CSRF_TOKEN_NAME, CSRF_TOKEN_HASH
 *
 * Uses the shared showStatus(type, message) function defined in upload.php
 */
(function() {
    var tokenClient;
    var accessToken = null;
    var pickerInited = false;
    var gisInited = false;

    // Called when Google Picker API is loaded
    window.onPickerApiLoad = function() {
        pickerInited = true;
    };

    // Called when Google Identity Services is loaded
    window.onGisLoad = function() {
        tokenClient = google.accounts.oauth2.initTokenClient({
            client_id: GDRIVE_CLIENT_ID,
            scope: 'https://www.googleapis.com/auth/drive.readonly',
            callback: function(response) {
                if (response.error) {
                    showStatus('error', 'Authentication failed: ' + response.error);
                    return;
                }
                accessToken = response.access_token;
                createPicker();
            }
        });
        gisInited = true;
    };

    // Open the picker
    window.openGoogleDrivePicker = function() {
        if (!pickerInited || !gisInited) {
            showStatus('error', 'Google APIs are still loading. Please try again.');
            return;
        }

        if (accessToken) {
            createPicker();
        } else {
            tokenClient.requestAccessToken({ prompt: 'consent' });
        }
    };

    function createPicker() {
        var picker = new google.picker.PickerBuilder()
            .enableFeature(google.picker.Feature.NAV_HIDDEN)
            .setDeveloperKey(GDRIVE_API_KEY)
            .setAppId(GDRIVE_APP_ID)
            .setOAuthToken(accessToken)
            .addView(
                new google.picker.DocsView()
                    .setIncludeFolders(true)
                    .setQuery('.backup')
            )
            .setTitle('Select an OnSong .backup file')
            .setCallback(pickerCallback)
            .build();
        picker.setVisible(true);
    }

    function pickerCallback(data) {
        if (data.action === google.picker.Action.PICKED) {
            var file = data.docs[0];
            importFromDrive(file.id, file.name);
        }
    }

    function importFromDrive(fileId, fileName) {
        showStatus('info', 'Downloading "' + fileName + '" from Google Drive...');

        var postData = {};
        postData['file_id'] = fileId;
        postData['file_name'] = fileName;
        postData['access_token'] = accessToken;
        postData[CSRF_TOKEN_NAME] = CSRF_TOKEN_HASH;

        $.ajax({
            url: '/upload/googleDriveImport',
            type: 'POST',
            data: postData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    showStatus('success', 'File downloaded! Redirecting...');
                    window.location = '/onsong/preflight/?file=' + encodeURIComponent(response.file_name);
                } else {
                    showStatus('error', response.message || 'Import failed.');
                }
            },
            error: function(xhr) {
                var msg = 'Import failed.';
                try {
                    var resp = JSON.parse(xhr.responseText);
                    if (resp.message) msg = resp.message;
                } catch(e) {}
                showStatus('error', msg);
            }
        });
    }
})();
