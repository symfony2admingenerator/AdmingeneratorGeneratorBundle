---
layout: base
title: Contribute
---

# How To Contribute ? #

You just have to _fork_ the [Admingenerator project on github](https://github.com/cedriclombardot/AdmingeneratorGeneratorBundle) and
provide Pull Requests or submit issues.

## Submit an issue ##

The ticketing system hosted on Github:

* AdmingeneratorGeneratorBundle: [https://github.com/cedriclombardot/AdmingeneratorGeneratorBundle/issues](https://github.com/cedriclombardot/AdmingeneratorGeneratorBundle/issues)
* AdmingeneratorIpsum: [https://github.com/cedriclombardot/AdmingeneratorIpsum/issues](https://github.com/cedriclombardot/AdmingeneratorIpsum/issues)

## Make a Pull Request ##

The best way to submit a patch is to make a Pull Request on Github. First, you should create a new branch from the `master`.
Assuming you are in your local project:

{% highlight bash %}
> git checkout -b master fix-my-patch
{% endhighlight %}

Now you can write your patch in this branch. Don't forget to provide unit tests with your fix to prove both the bug and the patch.
It will ease the process to accept or refuse a Pull Request.

When you're done, you have to rebase your branch to provide a clean and safe Pull Request.

{% highlight bash %}
> git checkout master
> git pull --ff-only upstream master
> git checkout fix-my-patch
> git rebase master
{% endhighlight %}

In this example, the `upstream` remote is the official repository.

Once done, you can submit the Pull Request by pushing your branch to your fork:

{% highlight bash %}
> git push origin fix-my-patch
{% endhighlight %}

Go to the www.github.com and press the _Pull Request_ button. Add a short description to this Pull Request and submit it.

## Running Unit Tests ##

We use [PHPUnit](http://www.phpunit.de) to test the build and runtime frameworks.

You can find the unit test classes and support files in the `Tests` directory.

### Install PHPUnit ###

In order to run the tests, you must install PHPUnit:

{% highlight bash %}
> pear channel-discover pear.phpunit.de
> pear install phpunit/PHPUnit
{% endhighlight %}

## Running Functionnal Tests ##

We are start building behat tests, you can run on the AdmingeneratorIpsum project with your fork as vendor

{% highlight bash %}
app/console -e=test behat --init @AdmingeneratorGeneratorBundle
{% endhighlight %}
