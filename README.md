# Hello! This is Rockmelon.app

Hello - my name is Eric Bae and this is my entry for 2021 Larajam with the theme of "Personal Productivity". This README will explain what Rockmelon is and how you can install it on your machine for a test-drive.

If you don't want to install it on your machine, you can see the app in action at https://www.rockmelon.app.

# What is Rockmelon?

As I'm sure you do too, I have a few websites that I visit on a daily basis - some of them are ProductHunt, HackerNews, Reddit etc.

These sites usually list many links which I often click. For example, ProductHunt lists the today's most popular products. More often than not, I click on every one of them to check them out. Daily, ProductHunt will have anywhere between 20-40 products. That's 20 to 40 clicks - talk about inefficiency.

I created Rockmelon because I wanted to combine many links into one single link and by clicking on that link, it will open all the links. Imagine opening all of today's ProductHunt products at once. Or how about clicking the top 10 stories on HN with a single click?

I measured that it takes me around 3 seconds to find the next item on ProductHunt, click and wait for the site to load. If there are 30 products, that's 90 seconds & 30 clicks I spend on just clicking links. What an unproductive way of spending my time.

Rockmelon now lets me spend 1 second click and start browsing immediately. By the time I'm ready to move to the next, it's already loaded on my next tab. If I can use Rockmelon on all of my sites, newsletters, link-heavy emails, I wonder how much more time I will save!

# Installing Rockmelon

You don't need to install if you don't want to. You can test-drive it at https://www.rockmelon.app.

If you want to install
* Clone the repo
* Fill the details in the .env file (database details - we use Postgres)
* Run composer install
* Run npm install
* Run php artisan migrate to create all the DB tables
* Then go to http://rockmelon.test (that's the local URL, but you can set it to whatever you want in .env file - make sure you update the /etc/hosts file so your local machine responds to the URL)

# Notes
* I apologize - not everything might be working exactly the way I expect them to.
* Please let me know hello@rockmelon.app if you run into any issues.
* Thanks Larajam! It was super fun & stressful - lol.