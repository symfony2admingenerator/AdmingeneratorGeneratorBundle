# Templating - Tips and Tricks
---------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/documentation.md#7-cookbook

### 1. Adding log in/log out button in navbar

If you want to automatically add a "Log in" / "Log out" / "Exit impersonation mode" button in your navbar, you have to specify route names under `admingenerator_generator`:
```yaml
admingenerator_generator:
    login_path: MyLogin_path
    logout_path: MyLogout_path
    exit_path: MyExit_path
```

