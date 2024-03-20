# Xytriza's Uploading Service

Welcome to the official source of Xytriza's Uploading Service!

## Official Server Information

Our server runs on minimal resources since the service is designed to be lightweight:
- **CPU**: 1 vCPU
- **RAM**: 1 GB
- **Environment**: Tested with PHP 8.3, MariaDB 10.06, and Nginx.

## Custom Hosting Paths

We use Nginx for handling specific paths such as `/delete/*` and `/files/*`. Below is the Nginx configuration snippet we employ:
```
server {
  listen 80;
  server_name upload.xytriza.com;

  try_files $uri $uri/ index.php?$args;
  index index.php index.html;
  
  location /files/ {
    try_files $uri $uri/ /api/fetchFile.php?url=$uri&raw=$arg_raw;
  }
  
  location /delete/ {
    try_files $uri $uri/ /api/deleteFile.php?deletionkey=$uri;
  }
}
```

## Private Use Guide

To set up this service for private use, simply follow these steps:

1. Clone the repository: `git clone https://github.com/XytrizaReal/uploading-service.git && cd uploading-service`
2. Upload the files to your server.
3. Install the necessary packages: `composer install` within the `data` folder.
4. Create a new database and import the `database.sql` file.
5. Update the `config.php` file with your details.
6. In your Google Cloud Console, create a new storage bucket. Navigate to the `Permissions` tab, click `Grant Access`, set the principal to `allUsers`, and the role to `Storage Legacy Object Reader`.
7. You're all set for private use!

## Public Use Advisory

We generally advise against using this service for public purposes and suggest opting for the official service instead. Should you choose to proceed, follow the instructions listed under Private Use.

## Reporting Bugs and Issues

Encountered any bugs or issues? Kindly report them on our GitHub issues page. We aim to address them promptly.

## Severe Exploits and Vulnerabilities

For reporting critical exploits or vulnerabilities, please email us directly at cryfxreal@gmail.com for swift action.

## Additional Notes

While you can host this service yourself, we strongly recommend using the official service until a stable release is available. If you proceed, consider increasing the upload file size limit to at least 5GB.

## Credits

- **Xytriza**: Developer and Owner
- **Contributors**: Cvolton and Megasa1nt for their unofficial contributions to `mainLib.php` (Lines 109 - 142), `captcha.php`, and `ip_in_range.php`, with modifications by Xytriza.

## License

This service is under the Apache 2.0 License. View the license [here](https://github.com/Xytrizareal/uploading-service/blob/master/LICENSE.txt).

## Contact Us

Questions or suggestions? Email us at cryfxreal@gmail.com or join our official [Discord server](https://upload.xytriza.com/discord).
