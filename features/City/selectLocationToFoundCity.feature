@SelectLocation
Feature: Select location for new city
In Order to create a city
as registered user
you have to specify the direction


    Background:
        Given following tiles:
           | name | accessable |
           | Grass | yes |
           | Forrest |  |
           | Sea |  |
           | Hill |  |
        And a map "default" with following tiles:
         |   y/x   |   0   |   1   |   2   |   3   |   4   |  5 |  6 | 7 |
         |  0   | Grass | Grass | Grass | Grass | Grass | Grass | Grass | Grass |
         |  1   | Grass | Forrest | Grass | Grass | Grass | Grass | Grass | Grass |
         |  2   | Grass | Grass | Grass | Grass | Grass | Grass | Grass | Grass |
         |  3   | Grass | Sea | Grass | Grass | Grass | Grass | Grass | Grass |
         |  4   | Grass | Grass | Grass | Grass | Grass | Grass | Grass | Grass |
         |  5   | Grass | Grass | Grass | Grass | Grass | Grass | Grass | Grass |
         |  6   | Grass | Grass | Grass | Grass | Grass | Grass | Grass | Grass |
         |  7   | Grass | Grass | Grass | Grass | Grass | Grass | Grass | Grass |
        And following users:
            | username | password | email |
            | BlackScorp | 123456 | test@test.de |
            | Owner1 | 123456 | owner1@test.de |
            | Owner2 | 123456 | owner2@test.de |
        And following cities:
            | name | owner | y | x |
            | City1 | Owner1 | 3 | 3 |


Scenario Outline: Specify direction
    Given I'm logged in as user "BlackScorp"
    And I'am on site "game/start"
    When I select location "<location>"
    Then I should be redirected to "/game/city/list"
    And I should have a city in following area:
        | minX | maxX | minY | maxY |
        | <minX> | <maxX> | <minY> | <maxY> |
    But not at following locations:
        | y | x |
        | 3 | 3 |
        | 1 | 1 |
        | 3 | 1 |


Examples:
    | location | minX | maxX | minY | maxY |
    | north | 0 | 3 | 0 | 3 |
    | east | 3 | 7 | 0 | 3 |
    | south | 3 | 7 | 3 | 7 |
    | west | 0 | 3 | 3 | 7 |
    | any | 0 | 7 | 0 | 7 |

