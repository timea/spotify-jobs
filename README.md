# Spotify Jobs

The base of this project is the [symfony-demo](https://github.com/symfony/symfony-demo) app providing the context to the required view.

## Install

- git clone
- cd spotify-jobs
- php bin/console server:run (the server is running on port 8000)
- you should see this on localhost:8000

![first_screen](/web/images/1.png)

- click browse backend

![second_screen](/web/images/2.png)

- click sign in on the left

![third_screen](/web/images/3.png)

- click Symfony Demo, you will get back to the first screen. But now that you are logged in, click Browse application

![first_screen](/web/images/1.png)

- then you should see this

![fourth_screen](/web/images/4.png)

## Workflow

I like to break down my work to tasks. I used GitHub Issues for this purpose, so if you browse over there, you can see how I divided the tasks. Every task is closed with a specific commit referenced in the commit message, so you can see how I implemented the changes if you wish so.

## Where is what

Most of the code in this project is provided my the demo app. It helped me to put the project in context and understand some basic flows between php and twig.

I did not create a separate brand new bundle, I put the resources where the app was looking for it per default.

### Controller

/src/AppBundle/Controller/CrawlerController.php

Please note, I have created a fallback. If you have cloned the code and trying to take a look at it at a later time when there is no internet, I have put in an object faking the answer from the Spotify call. Line 67.

As the exercise was only about scraping, I decided not to implement saving to the database.

### View

/app/Resources/views/jobs

jobs.html.twig

Responsible for the overall looks, including the modifications to the navbar, language picking and sidenav.

_table.html.twig

Responsible for the table containing the scraped data.


### CSS

/web/css/extra.css

Sections in the file are separated based on where the selectors are used. In the sections the selectors are in alphabetical order.

### JS

/web/js/extra.js

One single function here, not much to see. For real.

# Thank you

Thanks for your time taking a look at my work. Looking forward to hear from you!

Bests,

Timi
