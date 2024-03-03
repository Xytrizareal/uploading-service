document.addEventListener("DOMContentLoaded", function() {
    var waitingText = document.getElementById("waiting-text");
    var content = document.getElementById("content");
    if (waitingText && content) {
        window.onload = function() {
            document.getElementById("waiting-text").style.display = "none";
            document.getElementById("content").style.display = "block";
        };
    }
});

function copyToClipboard(text) {
    var replacementText = "... Content too long to copy. Download the file to view the full content.";
    var searchText = "... Preview too long. Download the file to view the full content.";
    if (text.length === 50073 && text.endsWith(searchText)) {
        text = text.replace(searchText, replacementText);
    }
    var textArea = document.createElement("textarea");
    textArea.value = text;
    document.body.appendChild(textArea);
    textArea.select();
    document.execCommand("copy");
    document.body.removeChild(textArea);
    var btn = document.getElementById("copy");
    if (btn.innerText === "Copy") {
        btn.innerText = "Copied";
        setTimeout(function() {
            btn.innerText = "Copy";
        }, 3000);
    }
}

function downloadFile(fileId, originalName, fileType) {
    originalName = atob(originalName);
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