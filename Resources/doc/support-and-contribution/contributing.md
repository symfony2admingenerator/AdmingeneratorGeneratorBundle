# Contributing
----------------------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/documentation.md#2-support-and-contribution

## 1. Createing and managing git branches

In your github fork, you need to keep your master branch clean, without any changes, 
like that you can create at any time a branch from your master. Each time, that you 
want commit a bug fix or a feature, you need to create a branch for it, which will 
be the copy of your master branch. 

When you will do a pull request on a branch, you can continue to work on an another 
branch and make another pull request on the other branch. 

Before createing a new branch pull the changes from Symfony2Admingenerator, your master 
needs to be up to date.

### Create the branch on your local machine:

```console
$ git branch <name_of_your_new_branch>
```

### Push the branch on github:

```console
$ git push origin <name_of_your_new_branch>
```

### Switch to your new branch:

```console
$ git checkout <name_of_your_new_branch>
```

When you want to commit something in your branch, be sure to be in your branch.

### Show all branches created:

```console
$ git branch
```

Which will show:

```console
* documentation_update
  feature_sortable
  master
```

Current branch is marked by `*`.

### Add a new remote for you branch:

```console
$ git remote add <name_of_your_remote> <url>
```

### Push changes from your commit into your branch:

```console
$ git push origin <name_of_your_remote>
```

### Delete a branch on your local filesytem:

```console
$ git branch -d <name_of_your_new_branch>
```

### Delete the branch on github:

```console
$ git push origin :<name_of_your_new_branch>
```

The only difference it's the `:` to say delete.

If you want to change default branch, it's so easy with github, in your fork go 
into Admin and in the drop-down list default branch choose what you want.

## 2. Stashing local changes

Sometimes you are in the middle of something, and suddenly you are notified with 
a bug report that needs to be fixed as soon as possible. You are not ready to 
commit your changes, because you your tests don’t pass yet.

You could throw away your current changes to make the patch or checkout 
a clean copy of your project to make the changes.. but wait! You can just stash your 
changes away, and make the patch! Afterward you grab your changes back and continue work.

### Stash your current changes:

```console
$ git stash save <description_of_stashed_changes>
```

### List current stashes:

```console
$ git stash list
```

Which will show:

```console
stash@{0}: WIP on default_save_styles: e7f4318 Add default styles
```

### Apply a stash:

```console
$ git stash apply <stash_name>
```

Stash names look like **stash@{0}** where 0 is stash index number. You may notice 
the stash is still there after you have applied it. You can drop it if you don’t 
need it any more.

### Drop a stash:

```console
$ git stash drop <stash_name>
```

Or, because the stash acts like a stack, you can pop off the last stash you saved:

```console
$ git stash pop
```

### Clear all stashed changes:

```console
$ git stash clear
```
