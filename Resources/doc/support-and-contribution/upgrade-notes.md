# Upgrade notes
----------------------------------------------------

[go back to Table of contents][back-to-index]

[back-to-index]: https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/blob/master/Resources/doc/documentation.md#2-support-and-contribution

This file lists B/C breaking PRs in reverse chronological order. Each PR contains 
description explaining nature of changes and upgrade notes to help you upgrade your 
project.

## PR [#508][pr508] Custom object actions stub generator

[pr508]: [https://github.com/symfony2admingenerator/AdmingeneratorGeneratorBundle/pull/508]

#### Description:

This PR introduces a new builder called `actions` which is used to generate 
stubs for custom actions.

#### B/C breaks:

This PR completely removes DeleteBuilder. Delete object and batch actions 
code has been moved to ActionsBuilder.

#### Upgrade notes: