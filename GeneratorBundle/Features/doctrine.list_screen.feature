Feature: On a movie screen list
  The yaml parameter list.max_per_page change the numbers of elements in page

  #@javascript
  Scenario:
    Given I set the yaml "base.yml"
    Given I am on "/admin-demo"
    Then the response status code should be 200
    Then I should see "Here is a beautifull title no ???"
    Then I should see "New" in the "ul.actions" element
    When I follow "New"
    Then I should be on "/admin-demo/new"