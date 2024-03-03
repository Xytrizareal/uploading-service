# Xytriza's Uploading Service
The official source of Xytriza's Uploading Service.

## Private Use
If you would like to setup the service for private use, you can do so by following the instructions below.

1. Clone the repository using `git clone https://github.com/XytrizaReal/uploading-service.git && cd uploading-service`
2. Upload the files to your server
3. Rename the htdocs folder with this command `cd htdocs && mv upload.xytriza.com YOURDOMAIN.com && cd ..`
4. Install the required packages using `cd data && composer install && cd ..`
5. Create a new database and import the `database.sql` file
6. Edit the `config.php` file and fill in the required information
7. On your google cloud console, create a new storage bucket and go to `Permissions` tab and click `Grant Access` and put the principal as `allUsers` and the role as `Storage Legacy Object Reader`
8. Done! You can now use the service for private use.

## Public Use
We do not recommend using this service for public use, as it is highly recommended to use the official service instead. However, if you would like to use the service for public use, you can do so by following the private use instructions.

## Bugs and Issues
If you find any bugs or issues, please report them to the GitHub issues page. We will try to fix them as soon as possible.

## Severe Exploits and Vulnerabilities
If you find any severe exploits or vulnerabilities, please email us at contact@xytriza.com. We will try to fix them as soon as possible.

## Other Information
We do not recommend using this service on your own server, as it is highly recommended to use the official service instead until we release a stable version of the service.

## Credits
- Xytriza - Developer and Owner of the service

## License
This service is licensed under the MIT License. You can view the license [here](https://github.com/Xytrizareal/uploading-service/blob/master/LICENSE.txt).

## Contact
If you would like to contact us, you can do so by emailing us at contact@xytriza.com or joining the official [Discord server](https://upload.xytriza.com/discord).