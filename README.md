# Selective Cache extension for Magento2

This extension adds a **Refresh Invalidated Cache** button. Upon clicking the button, the extension cleans all Cache Types which are marked as *Invalidated*.

## Usage

Navigate to the System > Tools > Cache Management page and you should see a **Refresh Invalidated Cache** button.

![Cache Management Page](https://raw.githubusercontent.com/mcspronko/selective-cache/master/docs/cache-management-button.png)

The cache records which are marked as *Invalidated* are cleaned when a user hits **Refresh Invalidated Cache** button.

![Cache Management Success](https://raw.githubusercontent.com/mcspronko/selective-cache/master/docs/cache-management-success.png)

## Installation

### Composer

```bash
composer config repositories.pronko git git@github.com:mcspronko/selective-cache.git
composer require pronko/selective-cache
```

### Modman

```bash
modman clone git@github.com:mcspronko/selective-cache.git
```

## Authors

* [Max Pronko](https://www.maxpronko.com)

## Contributors

* Peter Herrmann
* Harshvardhan Malpani
* Serhii Koval
* Antonis Galanis
* Arshad Syed
* Andresa Martins
