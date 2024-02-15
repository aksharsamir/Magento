# Etailors MagentoForms

## Table of contents

* [Introduction](#markdown-header-introduction)
* [Installation](#markdown-header-Installation)
* [Configuration](#markdown-header-configuration)
* [Additional Settings](#markdown-header-additional-settings)
    * [Forms](#markdown-header-forms)
    * [General](#markdown-header-general)
    * [Pages](#markdown-header-pages)
    * [Admin mail](#markdown-header-admin-mail)
    * [User mail](#markdown-header-user-mail)
    * [Thank you page](#markdown-header-thank-you-page)
    * [Answers](#markdown-header-answers)
* [Result](#markdown-header-result)

## Introduction

The e-tailors PaasForms is a module that allows you to create and configure forms in Magento.
You can configure:

* Lay-out of the form
* Fields
* Email for admin
* Email for user of the form
* Thank you page

You will be able to see the form submissions as well.

## Installation

```bash
    $ composer require Etailors/MagentoForms
	$ php bin/magento setup:upgrade
	$ php bin/magento setup:di:compile
	$ php bin/magento setup:static-content:deploy -f
	$ php bin/magento cache:flush
```

## Configuration 

The module has no configuration settings.

## Additional Settings

### Forms

In the forms settings you will see an overview of forms you created with the module.

To add a new form, click on the orange "Add new" button in the right corner.

Add/Edit form

### General

**Title**: Name of the form,also showing on the website.

**Key**: Key for the form, you will need the key to insert the form in the page.

**Treat pages as sections**: Depending on the format of the template you can choose if the pages should be treated as sections.

**Template**: Which template.

### Pages

You can create pages (or sections) that will be part of the form.

Inside the page settings you can configure the general information of the page and create fields.

### Admin mail

Here you can configure the email that will be send to the administration when the form is submitted by the customer.

### User mail

Here you can configure the email that will be send to the customer when he/she submitted the form.

### Thank you page

Here you can cofigure the thank-you page and message that will be showed when the customer submitted the form.

### Answers

Here you see the answers/submissions of the form.

To save an form, click on the orange "Save form" button in the right corner.

## Result

When the module is enabled, it is possible to create forms and display them on your website.

When sending the form, the user and you, as admin, will receive a confirmation via email.

These emails can be set up separately.




