# Xytriza's Uploading Service

Welcome to the official source of Xytriza's Uploading Service!

## Official Server Information

Our server runs on minimal resources since the service is designed to be somewhat lightweight:
- **CPU**: 1 vCPU
- **RAM**: 1 GB
- **Environment**: Tested with PHP 8.3.4, MariaDB 10.6, and Apache.

## Private Use Guide

To set up this service for private use, simply follow these steps:

1. Clone the repository using `git clone https://github.com/XytrizaReal/uploading-service.git && cd uploading-service`.
2. Upload files to a webserver.
3. Install the necessary packages: `composer install` within the `vendor` folder.
4. Create a new database and import the `database.sql` file.
5. Update the `config.php` file with your details.
6. In your Google Cloud Console, create a new storage bucket. Navigate to the `Permissions` tab, click `Grant Access`, set the principal to `allUsers`, and the role to `Storage Legacy Object Reader`.
7. Make a IAM Service Account and upload the JSON to `packages` with filename `auth.json`.
8. Raise the file uploading limit for your Apache config to atleast 5GB.

## Warning

If you do not use Apache, **you WILL have security issues with IAM Service Account json being accessed by anybody**. We highly recommend using Apache.

## Public Use Advisory

We generally advise against using this service for public purposes and suggest opting for the official service instead. Should you choose to proceed, follow the instructions listed under Private Use.

## Reporting Bugs and Issues

Encountered any bugs or issues? Report them on our GitHub issues page [here]([https://github.com/Xytrizareal/uploading-service/issues](https://github.com/Xytrizareal/uploading-service/issues/new?assignees=&labels=bug&projects=&template=bug_report.md&title=)).

## Feature Requests

Want something changed or a feature added? Create a feature request [here](https://github.com/Xytrizareal/uploading-service/issues/new?assignees=&labels=&projects=&template=feature_request.md&title=).

## Severe Exploits and Vulnerabilities

For reporting critical exploits or vulnerabilities, please email us directly at `cryfxreal@gmail.com` and we will try to fix it soon.

## Additional Notes

While you can host this service yourself, we strongly recommend using the official service until a stable release is available. If you proceed, consider increasing the upload file size limit to at least 5GB.

## Credits

- **Xytriza**: Developer and Owner
- **Contributors**: Cvolton and Megasa1nt for their unofficial contributions to `mainLib.php` (Lines 109 - 142), `captcha.php`, and `ip_in_range.php`, with modifications by Xytriza.

## License

This service is under the Apache 2.0 License. View the license [here](https://github.com/Xytrizareal/uploading-service/blob/main/LICENSE.txt).

## Contact Us

Questions or suggestions? Email us at cryfxreal@gmail.com or join our official [Discord server](https://upload.xytriza.com/discord).
