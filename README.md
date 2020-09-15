Tell OVH how to reach home!
---

Updates DNS records with OVH api to your WAN ip!

## Requirements

- PHP 7+
- An OVH account with a domain

## Installation

- Create [API Credentials](https://api.ovh.com/createToken/index.cgi?GET=/me)
- Clone or download this repository
- Install dependencies with composer
```
composer install
```
- Copy `config.example.php` to `config.php` and insert the credentials from the OVH API


## Usage

```
php main.php
```

