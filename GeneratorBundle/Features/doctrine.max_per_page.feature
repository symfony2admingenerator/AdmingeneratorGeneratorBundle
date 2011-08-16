Feature: On a movie screen list
  The yaml parameter list.max_per_page change the numbers of elements in page

  #@javascript
  Scenario:
    Given the yaml file "doctrine_max_per_page.yml"
    Then the response status code should be 200
    Then I should see "0 article(s)"
    Then I should see "0  news"