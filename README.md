Burning Ears
============

Creates a workflow for notifying any users you mention by name in a blog post, via Twitter, that they were mentioned.

Configuration
=============

Set your consumer key, consumer secret, access token, and access token secret in your wp-config.php file.  Set them as follows:

```
define( 'CONSUMER_KEY'       , 'XXX' );
define( 'CONSUMER_SECRET'    , 'XXX' );
define( 'ACCESS_TOKEN'       , 'XXX' );
define( 'ACCESS_TOKEN_SECRET', 'XXX' );
```

Don't have this info yet?  [This tutorial](https://themepacific.com/how-to-generate-api-key-consumer-token-access-key-for-twitter-oauth/994/) should prove helpful. Much thanks to [@jtsternberg](https://twitter.com/Jtsternberg) for his [sweet little OAuth Twitter library](github.com/jtsternberg/TwitterWP).

How It All Works
================

1. Once you set these keys, you'll notice that posts now have an extra button to notify possible names in your post content that they've been mentioned.

1. Upon clicking this button, you'll enter a workflow that will do the following:
    - Check your content for names.  If you've created a list of names with specific elements, like H3 tags, we can look those up from those tags.  Alternatively, we can try and smartly parse the content.  Right now, we check the content for two capitalized strings, separated by a space.
    - Next, we'll use the Twitter API to search for these names among an amalgamated group of people you follow, as well as people that follow you.  Future iterations may allow for a more fuzzy matching system, returning people outside this sphere, perhaps a drop-down of the top 10 results, etc.  Not for 1.0.
    - Then, now that we've matched everyone up, we allow you to craft a tweet to send to them.  Worth remembering that links you use might be shortened, usernames are different lengths, etc.  Craft your tweet with this in mind so as to not hit your 140-character limit.
    - Finally, having crafted your tweet and confirmed usernames, you can send the tweet.  BRAND ENGAGMENT: UNLOCKED!

Contributions
=============

Totally welcome.  This was all built in a morning/afternoon at the behest and bribery of Chris Lema, so YMMV.
