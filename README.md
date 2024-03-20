# Xytriza's Uploading Service
The official source of Xytriza's Uploading Service.

## Official Server information
The official server resources are low as this does not use much proccessing power
We use 1vCPU and 1GB of ram
Tested with php 8.3 with mariadb 10.06 and nginx

## Custom Hosting Paths
The /delete/* and /files/* are done using nginx, here is the nginx config we use.
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

## Private Use
If you would like to setup the service for private use, you can do so by following the instructions below.

1. Clone the repository using `git clone https://github.com/XytrizaReal/uploading-service.git && cd uploading-service`
2. Upload the files to your server
3. Install the required packages using `composer install` (`data` folder)
4. Create a new database and import the `database.sql` file
5. Edit the `config.php` file and fill in the required information
6. On your google cloud console, create a new storage bucket and go to `Permissions` tab and click `Grant Access` and put the principal as `allUsers` and the role as `Storage Legacy Object Reader`
7. Done! You can now use the service for private use.

## Public Use
We do not recommend using this service for public use, as it is highly recommended to use the official service instead. However, if you would like to use the service for public use, you can do so by following the private use instructions.

## Bugs and Issues
If you find any bugs or issues, please report them to the GitHub issues page. We will try to fix them as soon as possible.

## Severe Exploits and Vulnerabilities
If you find any severe exploits or vulnerabilities, please email us at cryfxreal@gmail.com. We will try to fix them as soon as possible.

## Other Information
We do not recommend using this service on your own server, as it is highly recommended to use the official service instead until we release a stable version of the service.
If you still would like to, we recommend raising the upload filesize to atleast 5GB.

## Credits
- Xytriza - Developer and Owner of the service

## License
This service is licensed under the Apache 2.0 License. You can view the license [here](https://github.com/Xytrizareal/uploading-service/blob/master/LICENSE.txt).

## Contact
If you would like to contact us, you can do so by emailing us at cryfxreal@gmail.com or joining the official [Discord server](https://upload.xytriza.com/discord).
