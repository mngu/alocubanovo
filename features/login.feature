Feature: Login

    Scenario: Login with right login/password
        Given I am on "/login"
        When I fill in "_username" with "admin"
        And I fill in "_password" with "adminpass"
        And I press "Connexion"
        Then I should see "Bienvenue"

    Scenario: Login with wrong login/password
        Given I am on "/login"
        When I fill in "_username" with "a"
        And I fill in "_password" with "adminpass"
        And I press "Connexion"
        Then I should see "Bad credentials"