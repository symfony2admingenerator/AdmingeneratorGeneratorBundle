# Actions builder
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/documentation.md#4-generator

### 1. Usage

Actions builder is used to generate controller STUBs for your object and batch actions. 
Actions builder generates `ActionsController` with your configured actions code.
There are two actions that handle everything else:

* `objectAction` route for this action is `Vendor_BundleName_GeneratorPrefix_object`, 
for example: `Acme_DemoBundle_User_object` which takes two arguments `pk` 
(object primary key) and `action` (object action name)

* `batchAction` route for this action is `Vendor_BundleName_GeneratorPrefix_batch`, 
for example: `Acme_DemoBundle_User_batch`, this action requires *POST* method and
two posted variables: `action` (batch action name) and `selected` (an array of selected
object primary keys) 

Additionally all batch actions are by default CSRF protected. Object actions may 
be CSRF protected, if you add `csrfProtected: true` in action config.

Depending on the value of `action` parameter diffrent action will be called.
E.g. `/{pk}/delete` will call delete object action.

> **Note:** edit and show action names are reserved. Delete action is preconfigured.

### 2. Support

Actions builder is avaliable for **Doctrine ORM**, **Doctrine ODM** and **Propel**.

### 3. Custom object action example

#### Object actions configuration

```yaml
# ...
params:
    # add "global" config shared between all builders
    # for object actions
    object_actions:      
        impersonate:
            label:    Login as
            icon:     icon-user
            route:    Homepage
            params:
                _switch_user:   "{{ User.username }}"
        lock:
            label:    Lock account
            icon:     icon-lock
            route:    Acme_SecurityBundle_User_object
            csrfProtected: true
            params:
                pk:       "{{ User.id }}"
                action:   lock
            options:
                # this is the title for intermediate page
                # if JS is avaliable then intermediate page will not be used
                title:  "Are you sure you want to lock this account?"

builders:
    list:
        params:
            # if action config is set to null (~)
            # then "global" shared config will be used
            object_actions:
                impersonate: ~
                lock: ~
                delete: ~
            # delete action is pre-configured, so it does not need a config

    actions:
        params:
            object_actions:
                lock: ~
            # we're generateing a STUB for lock action
```

Let's have a quick look what's going on in this config. First of all, we're 
configureing `object_actions` under global `params`, so we don't have to 
configure them twice (for list and actions builder).

**Impersonate** action leads to `Homepage` route, which takes no parameters. 
Becouse of that `_switch_user` parameter will be appended as a GET parameter
(e.g. `/?_switch_user=cedric`) which is exacly what we want. [Read more][impersonate-user]
about impersonateing a user in Symfony2 book, Security chapter. 

[impersonate-user]: http://symfony.com/doc/current/book/security.html#impersonating-a-user

**Lock** action is a custom action, for which we will generate a STUB and customize 
it in our ActionsController. So, first we configure `lock` action to use our `object_actions`
route.

> **Note:** the route for object and batch actions uses `generator prefix`, not ~model name~!
You may have multiple generators for one model (e.g. for `User` model you may generate 
`BasicUser-generator.yml`, `AdvancedUser-generator.yml` and `AdminUser-generator.yml`).
In such case remember that the route for BasicUser would be `Acme_SecurityBundle_BasicUser_object` 
or `Acme_SecurityBundle_BasicUser_batch` for batch actions.

This route requires two parameters: `pk`, in this case `{{ User.id }}` and `action` which 
is our action's name `lock`.

Next, we're setting `csrfProtected` to `true` to enable the built-in actions CSRF protection.

Last, we're configureing the intermediate page title. Intermediate page is only used if 
for some reason Javascript fails.

#### List builder

In list builder configuration we're adding actions we want to display. If action config 
is set to null (~) then global config will be used. Thats to this little fix we can have 
diffrent object actions in List and Actions builders, but share their configuration.

So, in list we want to display:

* `impersonate` -> custom object action leading to `Homepage` route
* `lock` -> custom object action useing actions stub generator
* `delete` -> pre-configured default object action

#### Actions builder 

In actions builder configuration we're adding actions we want to generate STUBs for. 
That is only `lock` action in this example.

#### Code your custom actions

Now, all we have to do is go to our bundle's directory and code what our `lock` action 
actually does. In our example we can do that by editing 
`Acme/SecurityBundle/Controller/UserController/ActionsController.php`. 

```php

    /**
     * This function is for you to customize what action actually does
     */
    public function executeObjectLock($User)
    {
        // In this example I use Doctrine ORM
        $em = $this->getDoctrine()->getManager();
        // Lock user
        $User->setLocked(true);
        // Save changes to database
        $em->persist($User);
        $em->flush();
    }

```

For each custom object action there are four methods generated: 

* `attemptObject{{ ActionName }}` - handles common object actions behaviour like 
checking CSRF protection token or credentials
* `executeObject{{ ActionName }}` - holds action logic
* `successObject{{ ActionName }}` - called if action was successfull
* `errorObject{{ ActionName }}` - called if action errored

> **Note:** The only method you **have to** overwrite is `executeObject{{ ActionName }}`

### 4. Custom batch action example

#### Batch actions configuration

```yaml
# ...
params:
    # add "global" config shared between all builders
    # for object actions
    batch_actions:
        lock:
            label:    Lock account
            icon:     icon-lock
            route:    Acme_SecurityBundle_User_batch

builders:
    list:
        params:
            # if action config is set to null (~)
            # then "global" shared config will be used
            batch_actions:
                lock: ~
                delete: ~
            # delete action is pre-configured, so it does not need a config

    actions:
        params:
            batch_actions:
                lock: ~
            # we're generateing a STUB for lock action
```

Let's have a quick look what's going on in this config. Similar to object actions 
configuration, first we're configureing "global" batch actions. Batch actions 
configuration is a bit shorter, becouse all batch actions are always CSRF protected, 
and there is no intermediate page, so we don't have to specify a title for it.

Also, all required parameters (action name, selected objects, csrf token) are rendered 
as part of form on List view, so they need no further configuration.

#### List builder

In list builder configuration we're adding actions we want to display. If action config 
is set to null (~) then global config will be used. Thats to this little fix we can have 
diffrent batch actions in List and Actions builders, but share their configuration.

So, in list we want to display:

* `lock` -> custom batch action useing actions stub generator
* `delete` -> pre-configured default batch action

#### Actions builder 

In actions builder configuration we're adding actions we want to generate STUBs for. 
That is only `lock` action in this example.


#### Code your custom actions

Now, all we have to do is go to our bundle's directory and code what our `lock` action 
actually does. In our example we can do that by editing 
`Acme/SecurityBundle/Controller/UserController/ActionsController.php`. 

```php

    /**
     * This function is for you to customize what action actually does
     */
    public function executeBatchLock($selected)
    {
        // In this example I use Doctrine ORM
        $em = $this->getDoctrine()->getManager();
        // Lock users
        $em->createQuery('UPDATE Acme\SecurityBundle\Entity\User u SET u.locked = :locked WHERE u.id IN (:selected)')
           ->setParameter('locked', true)
           ->setParameter('selected', $selected)
           ->getResult();
    }

```

Similarly to object actions, for each custom batch action there are four methods generated: 

* `attemptBatch{{ ActionName }}` - handles common object actions behaviour like 
checking CSRF protection token or credentials
* `executeBatch{{ ActionName }}` - holds action logic
* `successBatch{{ ActionName }}` - called if action was successfull
* `errorBatch{{ ActionName }}` - called if action errored

> **Note:** The only method you **have to** overwrite is `executeBatch{{ ActionName }}`