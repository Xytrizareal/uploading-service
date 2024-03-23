function logout() {
    document.cookie = "session=" + "; path=/";
    location.reload();
}

function showNotification(message, type) {
    var notification = document.createElement('div');
    notification.className = 'notification ' + type;
    notification.style.display = 'flex';
    notification.style.flexDirection = 'row';
    notification.style.alignItems = 'center';

    var iconDiv = document.createElement('div');
    var icon = document.createElement('i');
    icon.className = type === 'Success' ? 'fas fa-check' : 'fas fa-times';
    icon.style.color = type === 'Success' ? '#4CAF50' : '#f44336';
    iconDiv.appendChild(icon);
    notification.appendChild(iconDiv);

    var textDiv = document.createElement('div');
    textDiv.id = 'notification-message';
    textDiv.style.display = 'flex';
    textDiv.style.flexDirection = 'column';

    var titleNode = document.createElement('h4');
    titleNode.style.margin = '0';
    titleNode.appendChild(document.createTextNode(type));
    textDiv.appendChild(titleNode);

    var messageNode = document.createElement('div');
    messageNode.style.margin = '0';
    messageNode.appendChild(document.createTextNode(message));
    textDiv.appendChild(messageNode);

    notification.appendChild(textDiv);

    var container = document.getElementById('notification-container');
    container.appendChild(notification);
    notification.style.transform = 'translateY(100%)';
    setTimeout(function() {
        notification.style.transform = 'translateY(0)';
    }, 100);

    setTimeout(function() {
        notification.style.transform = 'translateY(100%)';
        setTimeout(function() {
            container.removeChild(notification);
        }, 500);
    }, 5000);
}

function showSuccess(message) {
    showNotification(message, 'Success');
}

function showError(message) {
    showNotification(message, 'Error');
}

document.addEventListener('DOMContentLoaded', (event) => {
    let element = "";

    element = document.getElementById('dropZone');
    if (element) {
        element.addEventListener('click', function() {
            document.getElementById('fileInput').click();
        });
    }

    element = document.getElementById('fileInput');
    if (element) {
        element.addEventListener('change', function(event) {
            handleFileUpload(event.target.files[0]);
        });
    }

    element = document.getElementById('dropZone');
    if (element) {
            element.addEventListener('dragover', function(event) {
            event.preventDefault();
            event.stopPropagation();
            event.target.style.border = '3px solid #09f';
        });
    }


    element = document.getElementById('dropZone');
    if (element) {
            element.addEventListener('dragleave', function(event) {
            event.preventDefault();
            event.stopPropagation();
            event.target.style.border = '2px dashed #fff';
        });
    }

    element = document.getElementById('dropZone');
    if (element) {
        element.addEventListener('drop', function(event) {
            event.preventDefault();
            event.stopPropagation();
            event.target.style.border = '2px dashed #fff';
            const files = event.dataTransfer.files;
            if (files.length) {
                handleFileUpload(files[0]);
            }
        });
    }

    element = document.getElementById('booleanSetting');
    if (element) {
        element.addEventListener('change', function () {
            let value = this.checked ? true : false;

            let label = document.querySelector("p[id='settingName']").innerText.toLowerCase().replace(/ /g, '-');

            $.ajax({
                url: '/api/dashboard/setPreferences.php',
                type: 'POST',
                data: {[`${label}`]: value},
                success: function(response) {
                    console.log('Full response:', response);
                    if (response.success) {
                        console.log('Settings updated: ', response.response);
                        showSuccess(response.response);
                    } else {
                        console.error('Update failed: ', response.response);
                        showError(response.response);
                    }
                },
                error: function(xhr) {
                    console.error('Update failed: ', xhr);
                    if (xhr.responseJSON && xhr.responseJSON.response) {
                        showError(xhr.responseJSON.response);
                    } else {
                        showError(xhr.responseText);
                    }
                }
            });
        });
    }

    element1 = document.getElementById('passwordChangeForm');
    element2 = document.getElementById('usernameChangeForm');
    element3 = document.getElementById('displaynameChangeForm');
    if (element1 && element2 && element3) {
        $(document).ready(function() {
            $('#passwordChangeForm').on('submit', function(e) {
                e.preventDefault();
                var oldPassword = $('#oldPassword').val();
                var newPassword = $('#newPassword').val();
                var confirmPassword = $('#confirmPassword').val();
                var username = $('#username').val();
                $.ajax({
                    url: '/api/changePassword.php',
                    type: 'POST',
                    data: {
                        username: username,
                        oldpassword: oldPassword,
                        newpassword: newPassword,
                        confirmpassword: confirmPassword
                    },
                    success: function(data) {
                        response = JSON.parse(data);
                        if (response.success) {
                            location.reload();
                        } else {
                            showError(response.response);
                        }
                    },
                    error: function(error) {
                        if (error.responseJSON && error.responseJSON.response) {
                            showError(error.responseJSON.response);
                        } else {
                            showError(error);
                        }
                    }
                });
            });
        
            $('#usernameChangeForm').on('submit', function(e) {
                e.preventDefault();
                var oldUsername = $('#oldUsername').val();
                var newUsername = $('#newUsername').val();
                var password = $('#usernamepassword').val();
                $.ajax({
                    url: '/api/changeUsername.php',
                    type: 'POST',
                    data: {
                        oldusername: oldUsername,
                        newusername: newUsername,
                        password: password
                    },
                    success: function(data) {
                        response = data;
                        if (response.success) {
                            showSuccess(response.response);
                            $('#oldUsername').val(newUsername);
                            $('#username').html('<strong>Username:</strong> ').append(document.createTextNode(newUsername));
                        } else {
                            showError(response.response);
                        }
                    },
                    error: function(jqXHR) {
                        if (jqXHR.responseJSON && jqXHR.responseJSON.response) {
                            showError(jqXHR.responseJSON.response);
                        } else {
                            showError(jqXHR.responseText);
                        }
                    }
                });
            });

            $('#displaynameChangeForm').on('submit', function(e) {
                e.preventDefault();
                var oldUsername = $('#oldDisplayname').val();
                var newUsername = $('#newDisplayname').val();
                var username = $('#username').val();
                var password = $('#displayusernamepassword').val();
                $.ajax({
                    url: '/api/changeDisplayname.php',
                    type: 'POST',
                    data: {
                        olddisplayname: oldUsername,
                        newdisplayname: newUsername,
                        username: username,
                        password: password
                    },
                    success: function(data) {
                        response = data;
                        if (response.success) {
                            showSuccess(response.response);
                            $('#oldDisplayname').val(newUsername);
                            $('#displayname').html('<strong>Username:</strong> ').append(document.createTextNode(newUsername));
                        } else {
                            showError(response.response);
                        }
                    },
                    error: function(jqXHR) {
                        if (jqXHR.responseJSON && jqXHR.responseJSON.response) {
                            showError(jqXHR.responseJSON.response);
                        } else {
                            showError(jqXHR.responseText);
                        }
                    }
                });
            });
        });
    }
});

function deleteFile(deletionKey, imageId) {
    const deleteUrl = 'https://upload.xytriza.com/delete/' + deletionKey;

    $.ajax({
        url: deleteUrl,
        type: 'GET',
        dataType: 'json',
        contentType: 'application/json',
        success: function(responseJson) {
            console.log('Server response:', responseJson);

            const isSuccess = responseJson.success === true || responseJson.success === 'true';

            if (isSuccess) {
                const filesItem = document.querySelector('.files-item[data-id="' + imageId + '"]');
                if (filesItem) {
                    filesItem.remove();
                } else {
                    console.error('File item not found:', deletionKey);
                }
                console.log('File deleted successfully:', deletionKey);
                showSuccess("File deleted Successfuly")
            } else {
                console.error('Error deleting file:', responseJson.response);
                showError("Error deleting file: " + responseJson.response)
            }
        },
        error: function(xhr, status, error) {
            console.error('Error deleting file:', error +  ' (' + status + ')');
            showError('Error deleting file: ' + error +  ' (' + status + ')');
        }
    });
}

function copyToClipboardOld(text, message, type) {
    const tempInput = document.createElement('input');
    tempInput.value = text;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand('copy');
    document.body.removeChild(tempInput);
    console.log('Link copied to clipboard: ', tempInput.value);
    if (message) {
        if (type == 0) {
            showSuccess(message);
        } else if (type == 1) {
            showError(message);
        }
    }
}

async function copyToClipboard(text, message, type) {
    if (!navigator.clipboard) {
        console.error('Clipboard not available');
        showError('Copying not supported on this browser.');
        return;
    }

    try {
        await navigator.clipboard.writeText(text);
        console.log('Link copied to clipboard:', text);

        if (message) {
            type = type.toString();
            if (type === "0" || type === "success") {
                showSuccess(message);
            } else if (type === "1" || type === "error") {
                showError(message);
            } else  {
                console.log('Invalid copy type');
            }
        }
    } catch (err) {
        console.error('Failed to copy content:', err);
        showError('Failed to copy content: ' + err);
    }
}

function downloadFile(fileId, originalName, fileType) {
    $.ajax({
        url: 'https://files.upload.xytriza.com/' + fileId,
        method: 'GET',
        xhrFields: {
            responseType: 'blob'
        },
        success: function (data) {
            var blob = new Blob([data], {type: fileType});
            var url = window.URL.createObjectURL(blob);
            var a = document.createElement('a');
            a.href = url;
            a.download = originalName;
            document.body.appendChild(a);
            a.click();        
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
            showSuccess("File downloaded successfully")
        },
        error: function(xhr, status, error) {
            console.error('Error downloading file:', error +  ' (' + status + ')');
            showError('Error downloading file: ' + error +  ' (' + status + ')');
        }
    });
}

function formatDate(dateStr) {
    const months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    const date = new Date(dateStr);
    const year = date.getFullYear();
    const month = months[date.getMonth()];
    const day = date.getDate();
    return `${month} ${day}, ${year}`;
}

function formatAllDates(dateStrArray) {
    return dateStrArray.map(dateStr => formatDate(dateStr));
}

function logout() {
    document.cookie = "session=" + "; path=/";
    location.reload();
}

function saveTimeSettings() {
    var country = document.getElementById("country").value
    var timezone = document.getElementById("timezone").value
    var dateFormatElements = document.getElementsByName("dateFormat");
    var timeFormatElements = document.getElementsByName("timeFormat");

    var dateFormat = getSelectedRadioValue(dateFormatElements);
    var timeFormat = getSelectedRadioValue(timeFormatElements);

    $.ajax({
        url: 'https://upload.xytriza.com/api/dashboard/setPreferences.php',
        type: 'POST',
        data: {country: country, timezone: timezone, dateformat: dateFormat, timeformat: timeFormat},
        success: function(responseJson) {
            console.log('Server response:', responseJson);

            const isSuccess = responseJson.success === true || responseJson.success === 'true';

            if (isSuccess) {
                showSuccess('Saved your preferences');
            } else {
                showError(responseJson.response);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            showError(jqXHR.responseText);
        }
    });

}

function getSelectedRadioValue(radioElements) {
    for (var i = 0; i < radioElements.length; i++) {
        if (radioElements[i].checked) {
            return radioElements[i].value;
        }
    }
    return null;
}

function logout() {
    document.cookie = "session=" + "; path=/";
    location.reload();
}

async function handleFileUpload(file) {
    if (file.size > 104857600) {
        showError('File size exceeds the 100MB limit.');
        return;
    }

    let formData = new FormData();
    formData.append('file', file);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/api/uploadFile.php', true);

    document.getElementById('progressText').style.display = 'block';

    xhr.upload.onprogress = function(event) {
        if (event.lengthComputable) {
            const percentage = Math.round((event.loaded / event.total) * 100);
            document.getElementById('progressText').innerText = percentage + '%';
        } else {
            console.log('Progress event not computable');
        }
    };

    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success === true) {
                copyToClipboard(response.fileUrl, "", 0);
                showSuccess('File uploaded successfully. Link copied to clipboard');
            } else {
                showError(response.response);
            }
        } else {
            console.error('Error:', xhr.statusText);
            showError(xhr.statusText);
        }
        document.getElementById('progressText').style.display = 'none';
    };
    xhr.send(formData);
}

async function handleUrlUpload() {
    return;
    const url = document.getElementById('urlInput').value = document.getElementById('urlInput').value.replace('https://upload.xytriza.com/files/', 'https://files.upload.xytriza.com/').replace('http://upload.xytriza.com/files/', 'https://files.upload.xytriza.com/');
    const fileName = document.getElementById('fileName').value;
    if (!url) {
        showError('Please enter a valid URL');
        return;
    }

    const formData = new FormData();

    try {
        const response = await fetch(url, { mode: 'cors' });
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const blob = await response.blob();
        const fileType = blob.type;

        const file = new File([blob], fileName, { type: fileType });

        formData.append('file', file);
    } catch (error) {
        showError('Unable to upload file. Make sure the target file has CORS enabled. Error: ' + error);
        console.error(error);
    }

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '/api/uploadFile.php', true);

    document.getElementById('progressText').style.display = 'block';

    xhr.upload.onprogress = function(event) {
        if (event.lengthComputable) {
            const percentage = Math.round((event.loaded / event.total) * 100);
            document.getElementById('progressText').innerText = percentage + '%';
        } else {
            console.log('Progress event not computable');
        }
    };

    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success === true) {
                copyToClipboard(response.fileUrl, ""), 0;
                showSuccess('File uploaded successfully. Link copied to clipboard');
            } else {
                showError(response.response);
            }
        } else {
            console.error('Error:', xhr.statusText);
            showError('An error occurred while uploading the file');
        }
        document.getElementById('progressText').style.display = 'none';
    };
    xhr.send(formData);
}

function downloadConfig() {
    $.ajax({
        url: '/api/dashboard/generateConfig.php',
        method: 'GET',
        success: function(response) {
            var downloadLink = document.createElement('a');
            var url = window.URL.createObjectURL(new Blob([JSON.stringify(response)]));
            downloadLink.href = url;
            downloadLink.download = 'xytrizas-uploading-service.sxcu';
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
            showSuccess('Config file downloaded successfully');
        },
        error: function(jqXHR, textStatus, errorThrown) {
            if (jqXHR.responseJSON && jqXHR.responseJSON.response) {
                showError(jqXHR.responseJSON.response);
            } else {
                showError(jqXHR.responseText);
            }
        }
    });
}

function setCookieAndRedirect() {
    window.location.href = "/api/dashboard/discordCallbackRedirect.php?path=" + btoa(window.location.href);
}

function generateAPIKey() {
    $.ajax({
        url: '/api/dashboard/generateAPIKey.php',
        type: 'GET',
        success: function(response) {
            console.log('Server response:', response);
            if (response.success) {
                showSuccess('API key regenerated successfully');
            } else {
                showError(response.response);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            showError(jqXHR.responseText);
        }
    });
}

function copyAPIKey() {
    $.ajax({
        url: '/api/dashboard/getAPIKey.php',
        type: 'GET',
        success: function(response) {
            console.log('Server response:', response);
            if (response.success) {
                copyToClipboard(response.api_key, 'API Key copied to clipboard', 0);
            } else {
                showError(response.response);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            showError(jqXHR.responseText);
        }
    });
}

function deleteAllFiles() {
    $.ajax({
        url: '/api/deleteAllFiles.php',
        type: 'GET',
        success: function(response) {
            console.log('Server response:', response);
            if (response.success) {
                showSuccess('All files deleted successfully');
            } else {
                showError(response.response);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            showError(jqXHR.responseText);
        }
    });
}

function renameFile(fileId) {
    var fileName = prompt("New file name: ")

    if (!fileName) {
        return;
    }

    $.ajax({
        url: '/api/renameFile.php',
        type: 'POST',
        data: {fileId: fileId, fileName: fileName},
        success: function(response) {
            console.log('Server response:', response);
            if (response.success) {
                var fileItem = document.querySelector('.files-item[data-id="' + fileId + '"]');
                if (fileItem) {
                    var filenameElement = fileItem.querySelector('a > p strong');
                    if (filenameElement) {
                        filenameElement.textContent = fileName;
                    }
                }
                showSuccess('File renamed successfully');
            } else {
                showError(response.response);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            showError(jqXHR.responseText);
        }
    });
}
