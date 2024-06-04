# Trigger Webhook

Trigger n8n URLs: List all n8n webhook URLS with Name and Trigger Buttons.  

## Built using

- HTML
- Bulma CSS
- Javascript
- Fetch API
- Pagination
- PHP
- PDO and MYSQL  

## Setup

- Download or Clone the Repo
- create `env` file and add the below details

```env
USERNAME=<HTTP Auth username>
PASSWORD=<HTTP Auth Encrypted password>
DBHOST=localhost
DBNAME=xxxxxxxxxx
DBUSER=xxxxxxxxxx
DBPASSWORD=xxxxxxxxxxxxxx
```

- Create HTTP Auth username and password here - <https://www.askapache.com/online-tools/htpasswd-generator/>
- Next Create Database and table - Copy sql queries from `query.sql` file and create table to store webhook name and links
- Done  

## Usage

- Insert Webhook name and link to the database

```sh
INSERT INTO `webhook` (`id`, `name`, `url`) VALUES
(1,'Sample','https://n8n.example.com/webhook/xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
```

- Open website Homepage > Enter HTTP Auth username and Password
- After Successful login you can view the table with list of webhook links with trigger button

## LICENSE

MIT
